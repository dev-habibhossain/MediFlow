# Database Design Document
## Hospital Appointment Management System (HAMS)

| | |
|---|---|
| **Document Type** | Database Architecture & Schema Design |
| **Project Name** | Hospital Appointment Management System |
| **Version** | 1.0 |
| **Database Engine** | MySQL 8.x |
| **ORM / Framework** | Laravel 12 (Eloquent) |
| **Related Document** | REQUIREMENT_ANALYSIS.md |

---

## Table of Contents

1. [Database Overview](#database-overview)
2. [Design Principles](#design-principles)
3. [Complete Entity List](#complete-entity-list)
4. [ER Diagram (Mermaid)](#er-diagram-mermaid)
5. [Relationship Explanation](#relationship-explanation)
6. [Query Optimization Suggestions](#query-optimization-suggestions)
7. [Database Security](#database-security)
8. [Backup Strategy](#backup-strategy)
9. [Future Database Expansion](#future-database-expansion)

---

## Database Overview

The HAMS database is designed as a **normalized, relational MySQL schema** that models the full lifecycle of a hospital appointment ecosystem: identity and access management, department/doctor structuring, scheduling, appointment transactions, clinical documentation (medical records and prescriptions), payments, notifications, and audit logging.

The schema is built to Laravel 12 / Eloquent conventions so it maps cleanly onto Eloquent models, relationships, and the Spatie Permission package, while remaining engine-agnostic in principle (InnoDB, foreign-key-constrained, UTF8MB4).

Core design pillars:

- **Single source of truth** — `users` is the authentication root; `patients` and `doctors` are role-specific profile extensions linked 1:1 to `users`, avoiding duplicated identity data.
- **Transactional integrity** — appointment booking, payment capture, and status transitions are designed to be wrapped in database transactions with constraint-level protection against race conditions (e.g., double-booking).
- **Auditable clinical data** — medical records and prescriptions are treated as append-mostly, versioned, and soft-deleted rather than hard-deleted, preserving clinical history integrity.
- **Scalable read patterns** — indexing strategy anticipates the most frequent queries (doctor availability lookups, patient appointment history, admin reporting) rather than being added reactively.

---

## Design Principles

### Normalization Strategy

- The schema targets **Third Normal Form (3NF)** for all core operational tables (`users`, `patients`, `doctors`, `appointments`, `departments`, `medical_records`, `prescriptions`) to eliminate redundancy and update anomalies.
- **Controlled denormalization** is applied selectively in reporting-adjacent contexts (e.g., storing `consultation_fee` on the `appointments` row at booking time) to preserve historical accuracy even if a doctor's live fee later changes — this is a deliberate business-driven denormalization, not a normalization failure.
- Lookup-style, low-cardinality values (appointment status, payment status, gender) are implemented as constrained `ENUM` or short `VARCHAR` columns validated at the application layer via Laravel enums/Form Requests, rather than being over-normalized into tiny reference tables that add join overhead without meaningful benefit at this scale.

### Indexes

- Every foreign key column is indexed by default (MySQL implicitly indexes FKs, but composite indexes are added explicitly where multi-column filtering is common).
- Composite indexes are placed on high-frequency filter/sort combinations, e.g. `(doctor_id, appointment_date, start_time)` on `appointments` for availability checks.
- Unique indexes enforce business-critical uniqueness (e.g., one user per email, one schedule slot per doctor per day/time).
- Full-text or `LIKE`-friendly indexes are considered for doctor name/specialization search (see Query Optimization Suggestions).

### Constraints

- Foreign key constraints are enforced at the database level (`InnoDB`, referential integrity ON), not just in application code — this protects data integrity even against direct DB access or bugs in application logic.
- `NOT NULL` is enforced on all columns that are business-required, with explicit defaults only where a sensible default exists (e.g., `status`, `is_active`).
- `CHECK`-equivalent validation (MySQL 8 supports `CHECK` constraints) is used sparingly for hard invariants (e.g., `end_time > start_time` on schedules), with the primary validation layer still living in Laravel Form Requests for user-facing error messaging.

### Naming Convention

Following Laravel/Eloquent conventions throughout:

- **Tables:** plural, `snake_case` (e.g., `appointments`, `doctor_schedules`).
- **Pivot tables:** singular model names in alphabetical order, `snake_case` (e.g., `department_doctor`).
- **Primary keys:** `id` (unsigned big integer, auto-increment).
- **Foreign keys:** `{singular_table}_id` (e.g., `doctor_id`, `patient_id`, `department_id`).
- **Booleans:** prefixed `is_`/`has_` (e.g., `is_active`, `has_verified_email` handled via `email_verified_at`).
- **Timestamps:** `created_at`, `updated_at` (Eloquent-managed); soft deletes via `deleted_at`.
- **Enums stored as strings:** lower_snake_case values (e.g., `pending`, `confirmed`, `no_show`).

### Relationships

- **One-to-One:** `users` ↔ `patients`, `users` ↔ `doctors`.
- **One-to-Many:** `departments` → `doctors`, `doctors` → `appointments`, `patients` → `appointments`, `appointments` → `medical_records`.
- **Many-to-Many:** `doctors` ↔ `departments` (secondary department affiliation) via `department_doctor`; `roles` ↔ `permissions` via Spatie's pivot tables.
- **Polymorphic:** `activity_logs` (audit trail across multiple entity types), `attachments` (files attached to medical records, prescriptions, or user avatars).

### Cascade Rules

Cascade behavior is deliberately **conservative** given the sensitivity of medical data:

| Relationship | On Parent Delete |
|---|---|
| `users` → `patients`/`doctors` | `CASCADE` (profile is meaningless without the account) |
| `departments` → `doctors` | `RESTRICT` (must reassign/deactivate doctors first) |
| `doctors` → `appointments` | `RESTRICT` (historical appointments must never be silently deleted) |
| `patients` → `appointments` | `RESTRICT` |
| `appointments` → `medical_records` | `RESTRICT` (clinical history must persist) |
| `appointments` → `prescriptions` | `RESTRICT` |
| `medical_records` → `attachments` (polymorphic) | `CASCADE` (files are meaningless without the parent record) |
| `appointments` → `payments` | `RESTRICT` |

In practice, hard deletes on core clinical/operational entities are **discouraged entirely** in favor of soft deletes and status flags; `RESTRICT` acts as a safety net against accidental cascading data loss.

### Soft Deletes

Soft deletes (`deleted_at` + Eloquent `SoftDeletes` trait) are applied to tables where historical integrity or recoverability matters:

- `users`, `patients`, `doctors`, `departments`, `appointments`, `medical_records`, `prescriptions`, `payments`.

Tables that are purely transactional/log-like or trivially re-creatable use **hard deletes** or are simply immutable/append-only:

- `notifications`, `activity_logs` (append-only, never deleted by users — pruned only via retention policy), `doctor_schedules` (schedule rows may be hard-deleted/replaced since they are configuration, not historical fact — but any schedule change is itself recorded in `activity_logs`).

### Timestamps

- All tables include `created_at` and `updated_at` managed automatically by Eloquent.
- Domain-specific timestamps are added where they carry distinct business meaning beyond record creation/modification, e.g. `appointments.confirmed_at`, `appointments.cancelled_at`, `appointments.completed_at`, `payments.paid_at`, `users.email_verified_at`.
- Timezone handling: all timestamps are stored in **UTC** at the database level; conversion to the hospital's configured local timezone happens at the application/presentation layer.

---

## Complete Entity List

### 1. `users`

**Purpose:** Root authentication and identity table for all system actors (Patient, Doctor, Administrator). Role assignment is managed via Spatie Permission's pivot tables, not a `role` column, to support flexible multi-role scenarios.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `name` | `VARCHAR(255)` | No | — | |
| `email` | `VARCHAR(255)` | No | — | Unique |
| `email_verified_at` | `TIMESTAMP` | Yes | `NULL` | |
| `password` | `VARCHAR(255)` | No | — | Hashed (bcrypt/argon2) |
| `phone` | `VARCHAR(20)` | Yes | `NULL` | |
| `avatar_path` | `VARCHAR(255)` | Yes | `NULL` | Local path or Cloudinary URL (future) |
| `is_active` | `BOOLEAN` | No | `true` | Deactivation flag (admin-controlled) |
| `remember_token` | `VARCHAR(100)` | Yes | `NULL` | Laravel standard |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |
| `deleted_at` | `TIMESTAMP` | Yes | `NULL` | Soft delete |

**Indexes:** `UNIQUE(email)`, index on `is_active`, index on `deleted_at`.
**Foreign Keys:** None (root entity).
**Unique Constraints:** `email`.
**Validation Notes:** Email format + uniqueness enforced at DB and app layer; password never stored in plain text.
**Business Rules:** One `users` row per human actor regardless of role; role determined via `model_has_roles` (Spatie). Deactivated users (`is_active = false`) are blocked at login regardless of credentials validity.

---

### 2. `roles` / `permissions` / `model_has_roles` / `model_has_permissions` / `role_has_permissions`

**Purpose:** Standard Spatie Laravel Permission package tables providing role-based access control (RBAC). Not custom-designed — included here for schema completeness.

| Table | Key Columns |
|---|---|
| `roles` | `id`, `name` (e.g., `patient`, `doctor`, `admin`), `guard_name`, timestamps |
| `permissions` | `id`, `name` (e.g., `manage-departments`, `view-reports`), `guard_name`, timestamps |
| `model_has_roles` | `role_id`, `model_type`, `model_id` (polymorphic, links `users` to `roles`) |
| `model_has_permissions` | `permission_id`, `model_type`, `model_id` |
| `role_has_permissions` | `permission_id`, `role_id` |

**Business Rules:** Every `users` row must have exactly one primary role (`patient`, `doctor`, `admin`) assigned at creation; the application enforces single-role simplicity for v1 even though Spatie technically supports multi-role.

---

### 3. `patients`

**Purpose:** Role-specific profile extension for users with the `patient` role, holding demographic and medical-context data.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `user_id` | `BIGINT UNSIGNED` (FK) | No | — | 1:1 with `users` |
| `date_of_birth` | `DATE` | No | — | |
| `gender` | `ENUM('male','female','other')` | Yes | `NULL` | |
| `blood_group` | `VARCHAR(5)` | Yes | `NULL` | e.g., `O+`, `AB-` |
| `address` | `VARCHAR(500)` | Yes | `NULL` | |
| `emergency_contact_name` | `VARCHAR(255)` | Yes | `NULL` | |
| `emergency_contact_phone` | `VARCHAR(20)` | Yes | `NULL` | |
| `allergies` | `TEXT` | Yes | `NULL` | Free-text; structured allergy table is a future enhancement |
| `patient_code` | `VARCHAR(20)` | No | — | Immutable, system-generated (e.g., `PT-00001`) |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |
| `deleted_at` | `TIMESTAMP` | Yes | `NULL` | Soft delete |

**Indexes:** `UNIQUE(user_id)`, `UNIQUE(patient_code)`.
**Foreign Keys:** `user_id` → `users.id` (`CASCADE` on delete).
**Unique Constraints:** `user_id`, `patient_code`.
**Validation Notes:** `date_of_birth` must resolve to a non-negative, realistic age (app-layer validation).
**Business Rules:** `patient_code` is generated once and never changes; used in reporting and patient-facing references instead of the raw internal `id`.

---

### 4. `doctors`

**Purpose:** Role-specific profile extension for users with the `doctor` role, holding clinical/professional metadata.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `user_id` | `BIGINT UNSIGNED` (FK) | No | — | 1:1 with `users` |
| `department_id` | `BIGINT UNSIGNED` (FK) | No | — | Primary department |
| `specialization` | `VARCHAR(255)` | No | — | |
| `qualifications` | `VARCHAR(500)` | Yes | `NULL` | |
| `bio` | `TEXT` | Yes | `NULL` | |
| `years_of_experience` | `TINYINT UNSIGNED` | Yes | `0` | |
| `consultation_fee` | `DECIMAL(10,2)` | No | `0.00` | Live/current fee |
| `license_number` | `VARCHAR(100)` | No | — | Unique, professional credential |
| `status` | `ENUM('active','on_leave','suspended','inactive')` | No | `active` | |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |
| `deleted_at` | `TIMESTAMP` | Yes | `NULL` | Soft delete |

**Indexes:** `UNIQUE(user_id)`, `UNIQUE(license_number)`, index on `department_id`, index on `status`, composite index `(department_id, status)`.
**Foreign Keys:** `user_id` → `users.id` (`CASCADE`); `department_id` → `departments.id` (`RESTRICT`).
**Unique Constraints:** `user_id`, `license_number`.
**Validation Notes:** `consultation_fee` must be `>= 0`.
**Business Rules:** A doctor with `status != active` cannot receive new bookings but retains existing confirmed appointments unless explicitly reassigned by an admin.

---

### 5. `departments`

**Purpose:** Organizational grouping of doctors and services (e.g., Cardiology, Pediatrics).

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `name` | `VARCHAR(100)` | No | — | Unique |
| `slug` | `VARCHAR(120)` | No | — | Unique, URL-friendly |
| `description` | `TEXT` | Yes | `NULL` | |
| `icon_path` | `VARCHAR(255)` | Yes | `NULL` | |
| `is_active` | `BOOLEAN` | No | `true` | |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |
| `deleted_at` | `TIMESTAMP` | Yes | `NULL` | Soft delete |

**Indexes:** `UNIQUE(name)`, `UNIQUE(slug)`, index on `is_active`.
**Foreign Keys:** None.
**Unique Constraints:** `name`, `slug`.
**Validation Notes:** `slug` auto-generated from `name`, must remain unique even after renames (append suffix if collision).
**Business Rules:** Cannot be hard-deleted while `doctors.department_id` references it (`RESTRICT`); deactivation (`is_active = false`) is the standard removal path.

---

### 6. `department_doctor` (Pivot)

**Purpose:** Many-to-many association for doctors with **secondary** department affiliations (beyond their primary `doctors.department_id`).

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `doctor_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `department_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |

**Indexes:** `UNIQUE(doctor_id, department_id)`.
**Foreign Keys:** `doctor_id` → `doctors.id` (`CASCADE`); `department_id` → `departments.id` (`CASCADE`).
**Unique Constraints:** Composite `(doctor_id, department_id)`.
**Business Rules:** A department listed here must not duplicate the doctor's primary `department_id`.

---

### 7. `doctor_schedules`

**Purpose:** Defines a doctor's recurring weekly availability template, from which bookable slots are computed dynamically.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `doctor_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `day_of_week` | `TINYINT UNSIGNED` | No | — | `0` (Sun) – `6` (Sat) |
| `start_time` | `TIME` | No | — | |
| `end_time` | `TIME` | No | — | Must be `> start_time` |
| `slot_duration_minutes` | `SMALLINT UNSIGNED` | No | `15` | Overrides global default if set |
| `is_active` | `BOOLEAN` | No | `true` | |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |

**Indexes:** Composite `(doctor_id, day_of_week)`, index on `is_active`.
**Foreign Keys:** `doctor_id` → `doctors.id` (`CASCADE`).
**Unique Constraints:** None enforced at DB level (overlap prevention is an application-layer business rule, validated on save); a composite uniqueness on `(doctor_id, day_of_week, start_time)` may be added as a secondary safeguard.
**Validation Notes:** `end_time > start_time`; no overlapping ranges for the same `doctor_id` + `day_of_week`.
**Business Rules:** Represents a *template*, not concrete dates — actual bookable slots are computed at request time by combining this with `appointments` and `doctor_schedule_exceptions`.

---

### 8. `doctor_schedule_exceptions`

**Purpose:** One-off overrides to the recurring schedule — leave days, holidays, extended hours for a specific date.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `doctor_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `exception_date` | `DATE` | No | — | |
| `type` | `ENUM('unavailable','extra_hours')` | No | `unavailable` | |
| `start_time` | `TIME` | Yes | `NULL` | Required if `type = extra_hours` |
| `end_time` | `TIME` | Yes | `NULL` | Required if `type = extra_hours` |
| `reason` | `VARCHAR(255)` | Yes | `NULL` | |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |

**Indexes:** Composite `(doctor_id, exception_date)`.
**Foreign Keys:** `doctor_id` → `doctors.id` (`CASCADE`).
**Unique Constraints:** Composite `(doctor_id, exception_date, type)` recommended to prevent duplicate entries.
**Validation Notes:** `exception_date` should generally be in the future when created (past-dated exceptions allowed only for administrative backfill).
**Business Rules:** If an `unavailable` exception is added for a date with existing confirmed appointments, the system must flag those appointments for admin review/reassignment rather than silently cancelling them.

---

### 9. `appointments`

**Purpose:** The core transactional entity representing a booked consultation between a patient and a doctor.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `appointment_code` | `VARCHAR(20)` | No | — | Unique, system-generated (e.g., `APT-000123`) |
| `patient_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `doctor_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `department_id` | `BIGINT UNSIGNED` (FK) | No | — | Denormalized snapshot at booking time |
| `appointment_date` | `DATE` | No | — | |
| `start_time` | `TIME` | No | — | |
| `end_time` | `TIME` | No | — | |
| `reason` | `TEXT` | No | — | Patient-provided symptom/reason |
| `status` | `ENUM('pending','confirmed','completed','cancelled','no_show','rescheduled')` | No | `pending` | |
| `consultation_fee_snapshot` | `DECIMAL(10,2)` | No | — | Fee at time of booking (historical accuracy) |
| `cancelled_by` | `BIGINT UNSIGNED` (FK, nullable) | Yes | `NULL` | References `users.id` |
| `cancellation_reason` | `VARCHAR(500)` | Yes | `NULL` | |
| `confirmed_at` | `TIMESTAMP` | Yes | `NULL` | |
| `completed_at` | `TIMESTAMP` | Yes | `NULL` | |
| `cancelled_at` | `TIMESTAMP` | Yes | `NULL` | |
| `rescheduled_from_id` | `BIGINT UNSIGNED` (FK, nullable) | Yes | `NULL` | Self-referencing, links to the original appointment |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |
| `deleted_at` | `TIMESTAMP` | Yes | `NULL` | Soft delete |

**Indexes:** `UNIQUE(appointment_code)`, composite `(doctor_id, appointment_date, start_time)`, composite `(patient_id, appointment_date)`, index on `status`, index on `department_id`.
**Foreign Keys:** `patient_id` → `patients.id` (`RESTRICT`); `doctor_id` → `doctors.id` (`RESTRICT`); `department_id` → `departments.id` (`RESTRICT`); `cancelled_by` → `users.id` (`SET NULL`); `rescheduled_from_id` → `appointments.id` (`SET NULL`).
**Unique Constraints:** `appointment_code`; composite `UNIQUE(doctor_id, appointment_date, start_time)` to guarantee no double-booking at the database level.
**Validation Notes:** `appointment_date` must be today or future at creation time; `start_time`/`end_time` must fall within the doctor's computed availability; `end_time > start_time`.
**Business Rules:** The composite unique constraint on `(doctor_id, appointment_date, start_time)` is the **last line of defense** against race-condition double-booking, complementing application-level locking. Status transitions follow a strict state machine enforced in a Service/Action layer, not directly editable via mass update.

---

### 10. `medical_records`

**Purpose:** Structured clinical documentation tied to a specific appointment.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `appointment_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `patient_id` | `BIGINT UNSIGNED` (FK) | No | — | Denormalized for direct query access |
| `doctor_id` | `BIGINT UNSIGNED` (FK) | No | — | Denormalized for direct query access |
| `diagnosis` | `TEXT` | No | — | |
| `symptoms` | `TEXT` | Yes | `NULL` | |
| `vitals` | `JSON` | Yes | `NULL` | Structured (BP, pulse, temp, weight) |
| `doctor_notes` | `TEXT` | Yes | `NULL` | |
| `version` | `SMALLINT UNSIGNED` | No | `1` | Incremented on amendment |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |
| `deleted_at` | `TIMESTAMP` | Yes | `NULL` | Soft delete |

**Indexes:** `UNIQUE(appointment_id)`, index on `patient_id`, index on `doctor_id`.
**Foreign Keys:** `appointment_id` → `appointments.id` (`RESTRICT`); `patient_id` → `patients.id` (`RESTRICT`); `doctor_id` → `doctors.id` (`RESTRICT`).
**Unique Constraints:** `appointment_id` (one record per appointment).
**Validation Notes:** `diagnosis` required before an appointment can transition to `completed`.
**Business Rules:** Amendments after finalization increment `version` and are logged in `activity_logs`; the record itself is never silently overwritten without a trace.

---

### 11. `prescriptions`

**Purpose:** Header entity for a prescription issued during/after a consultation; individual medications live in `prescription_items`.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `prescription_code` | `VARCHAR(20)` | No | — | Unique (e.g., `RX-000045`) |
| `appointment_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `medical_record_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `patient_id` | `BIGINT UNSIGNED` (FK) | No | — | Denormalized |
| `doctor_id` | `BIGINT UNSIGNED` (FK) | No | — | Denormalized |
| `special_instructions` | `TEXT` | Yes | `NULL` | |
| `issued_at` | `TIMESTAMP` | No | `CURRENT_TIMESTAMP` | |
| `supersedes_id` | `BIGINT UNSIGNED` (FK, nullable) | Yes | `NULL` | Self-referencing for corrections |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |
| `deleted_at` | `TIMESTAMP` | Yes | `NULL` | Soft delete |

**Indexes:** `UNIQUE(prescription_code)`, index on `patient_id`, index on `appointment_id`.
**Foreign Keys:** `appointment_id` → `appointments.id` (`RESTRICT`); `medical_record_id` → `medical_records.id` (`RESTRICT`); `patient_id` → `patients.id` (`RESTRICT`); `doctor_id` → `doctors.id` (`RESTRICT`); `supersedes_id` → `prescriptions.id` (`SET NULL`).
**Unique Constraints:** `prescription_code`.
**Business Rules:** Prescriptions are **immutable once issued**; a correction is issued as a new row referencing `supersedes_id`, never an in-place edit.

---

### 12. `prescription_items`

**Purpose:** Individual medication line items belonging to a prescription (one prescription, many medications).

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `prescription_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `medication_name` | `VARCHAR(255)` | No | — | |
| `dosage` | `VARCHAR(100)` | No | — | e.g., `500mg` |
| `frequency` | `VARCHAR(100)` | No | — | e.g., `Twice daily` |
| `duration` | `VARCHAR(100)` | No | — | e.g., `7 days` |
| `notes` | `VARCHAR(500)` | Yes | `NULL` | |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |

**Indexes:** Index on `prescription_id`.
**Foreign Keys:** `prescription_id` → `prescriptions.id` (`CASCADE`).
**Unique Constraints:** None.
**Business Rules:** Cascades with the parent prescription only in the sense of soft-delete propagation handled at the application layer (parent prescriptions are not hard-deleted).

---

### 13. `payments` *(Future Phase — Stripe)*

**Purpose:** Tracks payment intent/status per appointment.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `appointment_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `patient_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `amount` | `DECIMAL(10,2)` | No | — | |
| `currency` | `VARCHAR(3)` | No | `USD` | ISO 4217 |
| `status` | `ENUM('pending','paid','failed','refunded')` | No | `pending` | |
| `stripe_payment_intent_id` | `VARCHAR(255)` | Yes | `NULL` | External reference, unique when present |
| `paid_at` | `TIMESTAMP` | Yes | `NULL` | |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |
| `deleted_at` | `TIMESTAMP` | Yes | `NULL` | Soft delete |

**Indexes:** `UNIQUE(appointment_id)`, `UNIQUE(stripe_payment_intent_id)`, index on `status`.
**Foreign Keys:** `appointment_id` → `appointments.id` (`RESTRICT`); `patient_id` → `patients.id` (`RESTRICT`).
**Unique Constraints:** `appointment_id`, `stripe_payment_intent_id`.
**Business Rules:** No raw card data ever stored; only Stripe-tokenized references. `amount` must equal `appointments.consultation_fee_snapshot` at capture time.

---

### 14. `transactions` *(Future Phase — Stripe)*

**Purpose:** Immutable ledger of every financial event (charge, refund) tied to a `payments` row — supports auditing and reconciliation independent of the mutable `payments.status`.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `payment_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `type` | `ENUM('charge','refund')` | No | — | |
| `amount` | `DECIMAL(10,2)` | No | — | |
| `gateway_reference` | `VARCHAR(255)` | No | — | Stripe charge/refund ID |
| `raw_response` | `JSON` | Yes | `NULL` | Stored gateway payload for audit |
| `created_at` | `TIMESTAMP` | No | `CURRENT_TIMESTAMP` | Append-only, no `updated_at` needed |

**Indexes:** Index on `payment_id`, index on `gateway_reference`.
**Foreign Keys:** `payment_id` → `payments.id` (`RESTRICT`).
**Unique Constraints:** `UNIQUE(gateway_reference)`.
**Business Rules:** Append-only ledger; rows are never updated or deleted, only inserted — this is the authoritative audit trail for financial reconciliation.

---

### 15. `notifications`

**Purpose:** In-app and email notification records per user (compatible with Laravel's built-in notifications table structure, extended for HAMS needs).

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `CHAR(36)` (UUID, PK) | No | — | Laravel default notifications convention |
| `type` | `VARCHAR(255)` | No | — | Notification class name |
| `notifiable_type` | `VARCHAR(255)` | No | — | Polymorphic (typically `User`) |
| `notifiable_id` | `BIGINT UNSIGNED` | No | — | Polymorphic |
| `data` | `JSON` | No | — | Message payload |
| `read_at` | `TIMESTAMP` | Yes | `NULL` | |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |

**Indexes:** Composite `(notifiable_type, notifiable_id)`, index on `read_at`.
**Foreign Keys:** None (polymorphic, not FK-constrained by Laravel convention).
**Business Rules:** Reminder notifications generated via scheduled queue jobs; critical notifications (cancellation) generated synchronously on the triggering event.

---

### 16. `reviews`

**Purpose:** Patient feedback/rating for a completed appointment.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `appointment_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `patient_id` | `BIGINT UNSIGNED` (FK) | No | — | |
| `doctor_id` | `BIGINT UNSIGNED` (FK) | No | — | Denormalized |
| `rating` | `TINYINT UNSIGNED` | No | — | 1–5 |
| `comment` | `TEXT` | Yes | `NULL` | |
| `is_visible` | `BOOLEAN` | No | `true` | Admin can hide inappropriate reviews |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |

**Indexes:** `UNIQUE(appointment_id)`, index on `doctor_id`.
**Foreign Keys:** `appointment_id` → `appointments.id` (`CASCADE`); `patient_id` → `patients.id` (`CASCADE`); `doctor_id` → `doctors.id` (`CASCADE`).
**Unique Constraints:** `appointment_id` (one review per appointment).
**Validation Notes:** `rating` between 1 and 5 inclusive; only allowed when `appointments.status = completed`.
**Business Rules:** Reviews may be hidden (`is_visible = false`) by an admin without deleting the underlying data, preserving rating-average integrity while moderating display.

---

### 17. `attachments`

**Purpose:** Polymorphic file storage record for avatars, medical documents, and prescription PDFs.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `attachable_type` | `VARCHAR(255)` | No | — | Polymorphic (`MedicalRecord`, `User`, `Prescription`) |
| `attachable_id` | `BIGINT UNSIGNED` | No | — | Polymorphic |
| `file_path` | `VARCHAR(500)` | No | — | Local path or Cloudinary URL (future) |
| `file_name` | `VARCHAR(255)` | No | — | Original filename |
| `mime_type` | `VARCHAR(100)` | No | — | Server-validated |
| `file_size_kb` | `INT UNSIGNED` | No | — | |
| `uploaded_by` | `BIGINT UNSIGNED` (FK) | No | — | References `users.id` |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |
| `deleted_at` | `TIMESTAMP` | Yes | `NULL` | Soft delete |

**Indexes:** Composite `(attachable_type, attachable_id)`, index on `uploaded_by`.
**Foreign Keys:** `uploaded_by` → `users.id` (`RESTRICT`).
**Validation Notes:** `mime_type` restricted per context (images for avatars, PDF for medical documents) at the application layer.
**Business Rules:** Files are never publicly accessible via predictable URLs; access is brokered through signed/authorized application routes.

---

### 18. `activity_logs`

**Purpose:** Polymorphic audit trail capturing sensitive or notable actions across the system (record access/edits, role changes, deletions, status transitions).

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `causer_id` | `BIGINT UNSIGNED` (FK, nullable) | Yes | `NULL` | References `users.id`; null for system/queue actions |
| `subject_type` | `VARCHAR(255)` | No | — | Polymorphic (e.g., `Appointment`, `MedicalRecord`) |
| `subject_id` | `BIGINT UNSIGNED` | No | — | Polymorphic |
| `action` | `VARCHAR(100)` | No | — | e.g., `created`, `status_changed`, `deleted` |
| `description` | `VARCHAR(500)` | Yes | `NULL` | Human-readable summary |
| `properties` | `JSON` | Yes | `NULL` | Before/after values, metadata |
| `ip_address` | `VARCHAR(45)` | Yes | `NULL` | IPv4/IPv6 |
| `created_at` | `TIMESTAMP` | No | `CURRENT_TIMESTAMP` | Append-only, no `updated_at` |

**Indexes:** Composite `(subject_type, subject_id)`, index on `causer_id`, index on `created_at`.
**Foreign Keys:** `causer_id` → `users.id` (`SET NULL`).
**Business Rules:** Append-only; never updated or deleted by application logic. Pruning only occurs via a defined data-retention policy job, not ad hoc deletion.

---

### 19. `settings`

**Purpose:** Key-value store for hospital-wide configuration managed by Administrators.

| Column | Type | Nullable | Default | Notes |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK, AI) | No | — | |
| `key` | `VARCHAR(150)` | No | — | Unique (e.g., `default_slot_duration`) |
| `value` | `TEXT` | Yes | `NULL` | Stored as string/JSON, cast at app layer |
| `type` | `ENUM('string','integer','boolean','json')` | No | `string` | Guides app-layer casting |
| `updated_by` | `BIGINT UNSIGNED` (FK, nullable) | Yes | `NULL` | References `users.id` |
| `created_at` / `updated_at` | `TIMESTAMP` | Yes | `NULL` | |

**Indexes:** `UNIQUE(key)`.
**Foreign Keys:** `updated_by` → `users.id` (`SET NULL`).
**Unique Constraints:** `key`.
**Business Rules:** Changes to scheduling-related settings (e.g., default slot duration) are non-retroactive — do not alter already-computed/booked appointment rows.

---

### 20. Supporting Laravel Standard Tables

Included for completeness; managed largely by framework defaults rather than custom design:

| Table | Purpose |
|---|---|
| `password_reset_tokens` | Laravel Breeze password reset flow (`email`, `token`, `created_at`). |
| `sessions` | Laravel session storage (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`). |
| `jobs` / `failed_jobs` | Laravel queue infrastructure for notification dispatch, report generation, and exports. |
| `cache` | Laravel database cache driver (if used instead of Redis). |

---

## ER Diagram (Mermaid)

```mermaid
erDiagram
    USERS ||--o| PATIENTS : "has profile"
    USERS ||--o| DOCTORS : "has profile"
    USERS ||--o{ MODEL_HAS_ROLES : "assigned"
    ROLES ||--o{ MODEL_HAS_ROLES : "grants"
    ROLES ||--o{ ROLE_HAS_PERMISSIONS : "includes"
    PERMISSIONS ||--o{ ROLE_HAS_PERMISSIONS : "granted via"

    DEPARTMENTS ||--o{ DOCTORS : "primary department"
    DEPARTMENTS ||--o{ DEPARTMENT_DOCTOR : "secondary"
    DOCTORS ||--o{ DEPARTMENT_DOCTOR : "secondary"

    DOCTORS ||--o{ DOCTOR_SCHEDULES : "defines availability"
    DOCTORS ||--o{ DOCTOR_SCHEDULE_EXCEPTIONS : "has exceptions"

    PATIENTS ||--o{ APPOINTMENTS : "books"
    DOCTORS ||--o{ APPOINTMENTS : "fulfills"
    DEPARTMENTS ||--o{ APPOINTMENTS : "categorizes"
    APPOINTMENTS ||--o| APPOINTMENTS : "rescheduled_from"

    APPOINTMENTS ||--o| MEDICAL_RECORDS : "documents"
    PATIENTS ||--o{ MEDICAL_RECORDS : "owns"
    DOCTORS ||--o{ MEDICAL_RECORDS : "authors"

    MEDICAL_RECORDS ||--o{ PRESCRIPTIONS : "supports"
    APPOINTMENTS ||--o{ PRESCRIPTIONS : "issued during"
    PRESCRIPTIONS ||--o{ PRESCRIPTION_ITEMS : "contains"
    PRESCRIPTIONS ||--o| PRESCRIPTIONS : "supersedes"

    APPOINTMENTS ||--o| PAYMENTS : "billed via"
    PAYMENTS ||--o{ TRANSACTIONS : "ledgered by"

    APPOINTMENTS ||--o| REVIEWS : "reviewed via"
    PATIENTS ||--o{ REVIEWS : "writes"
    DOCTORS ||--o{ REVIEWS : "receives"

    USERS ||--o{ NOTIFICATIONS : "receives"
    USERS ||--o{ ATTACHMENTS : "uploads"
    USERS ||--o{ ACTIVITY_LOGS : "causes"
    MEDICAL_RECORDS ||--o{ ATTACHMENTS : "attached files"

    USERS {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        boolean is_active
    }
    PATIENTS {
        bigint id PK
        bigint user_id FK
        date date_of_birth
        string blood_group
        string patient_code UK
    }
    DOCTORS {
        bigint id PK
        bigint user_id FK
        bigint department_id FK
        string specialization
        decimal consultation_fee
        string status
    }
    DEPARTMENTS {
        bigint id PK
        string name UK
        string slug UK
        boolean is_active
    }
    DOCTOR_SCHEDULES {
        bigint id PK
        bigint doctor_id FK
        tinyint day_of_week
        time start_time
        time end_time
    }
    APPOINTMENTS {
        bigint id PK
        string appointment_code UK
        bigint patient_id FK
        bigint doctor_id FK
        bigint department_id FK
        date appointment_date
        time start_time
        string status
        decimal consultation_fee_snapshot
    }
    MEDICAL_RECORDS {
        bigint id PK
        bigint appointment_id FK UK
        bigint patient_id FK
        bigint doctor_id FK
        text diagnosis
        smallint version
    }
    PRESCRIPTIONS {
        bigint id PK
        string prescription_code UK
        bigint appointment_id FK
        bigint medical_record_id FK
        bigint supersedes_id FK
    }
    PRESCRIPTION_ITEMS {
        bigint id PK
        bigint prescription_id FK
        string medication_name
        string dosage
    }
    PAYMENTS {
        bigint id PK
        bigint appointment_id FK UK
        decimal amount
        string status
    }
    TRANSACTIONS {
        bigint id PK
        bigint payment_id FK
        string type
        decimal amount
    }
    REVIEWS {
        bigint id PK
        bigint appointment_id FK UK
        tinyint rating
    }
    ATTACHMENTS {
        bigint id PK
        string attachable_type
        bigint attachable_id
        string file_path
    }
    ACTIVITY_LOGS {
        bigint id PK
        bigint causer_id FK
        string subject_type
        bigint subject_id
        string action
    }
```

---

## Relationship Explanation

- **`users` → `patients` / `doctors` (1:1):** `users` is the sole authentication root; role-specific data is split into extension tables to keep the auth table lean and to cleanly separate "who can log in" from "what kind of profile they have." A `user_id` foreign key with a unique constraint enforces the 1:1 cardinality.
- **`departments` → `doctors` (1:Many, primary):** Every doctor has exactly one primary department, modeled as a direct foreign key rather than a pivot, since a "home department" is a first-class attribute of a doctor, not an optional association.
- **`doctors` ↔ `departments` (Many:Many, secondary):** The `department_doctor` pivot allows a doctor (e.g., a general physician) to be discoverable under more than one department without changing their primary affiliation.
- **`doctors` → `doctor_schedules` / `doctor_schedule_exceptions` (1:Many):** Availability is modeled as a *template* (recurring weekly pattern) plus *exceptions* (specific date overrides), rather than pre-generating thousands of empty slot rows — this keeps the schedule table small and avoids stale-data problems when a doctor's pattern changes.
- **`patients` / `doctors` → `appointments` (1:Many):** The `appointments` table is the central transactional hub; both participants are foreign keys, and `department_id` is denormalized onto the row so historical reporting remains accurate even if a doctor is later reassigned to a different department.
- **`appointments` → `medical_records` (1:1):** Each appointment can produce at most one medical record, enforced via a unique foreign key — clinical documentation is always traceable back to the specific consultation that produced it.
- **`medical_records` / `appointments` → `prescriptions` (1:Many):** A single consultation can result in multiple prescriptions over time (e.g., an initial prescription plus a corrected/superseding one), which is why `prescriptions` links to both the `appointment_id` (context) and, via `supersedes_id`, to its own prior version (versioning chain).
- **`prescriptions` → `prescription_items` (1:Many):** Medications are normalized into their own table rather than a comma-separated field, allowing structured querying (e.g., "find all patients prescribed Medication X").
- **`appointments` → `payments` → `transactions` (1:1:Many):** `payments` reflects the *current state* of billing for an appointment, while `transactions` is an **immutable ledger** of every gateway event (charge, refund) — separating mutable state from an authoritative audit trail is standard financial-system practice.
- **`appointments` → `reviews` (1:1):** A review is scoped to a specific completed appointment, ensuring rating data is always tied to a verifiable consultation rather than being freely postable.
- **Polymorphic relationships (`attachments`, `activity_logs`, `notifications`):** These entities need to attach to multiple unrelated parent types (a medical record, a user avatar, a prescription document; or an appointment, a role change, a payment event). Polymorphic association avoids creating a separate join table per parent type and keeps the schema extensible as new auditable/attachable entities are added.

---

## Query Optimization Suggestions

- **Doctor availability lookups** (`doctor_id`, `appointment_date`, `status`) are the single most frequent query pattern (every booking page load computes free slots). The composite index `(doctor_id, appointment_date, start_time)` on `appointments`, combined with `(doctor_id, day_of_week)` on `doctor_schedules`, ensures both lookups hit indexed paths rather than full scans.
- **Eager loading discipline:** Eloquent relationships (`appointments->doctor->user`, `appointments->patient->user`) must always be eager-loaded (`with()`) in list views to avoid N+1 query patterns, especially on the Admin dashboard and Reports module which aggregate across many appointments.
- **Denormalized snapshots reduce joins:** `consultation_fee_snapshot` on `appointments` and `patient_id`/`doctor_id` duplicated onto `medical_records`, `prescriptions`, and `reviews` avoid extra joins back through `appointments` for common "all records for this patient" or "all prescriptions by this doctor" queries.
- **Search on doctor name/specialization:** For the public doctor directory, a MySQL `FULLTEXT` index on `doctors.specialization` (joined with `users.name`) is recommended once the doctor count grows beyond a few hundred rows; for v1 scale, a standard `LIKE '%term%'` with a supporting index is acceptable but should be monitored.
- **Reporting queries:** Admin analytics (appointment volume by department/month, doctor performance) should use aggregation queries (`GROUP BY` with indexed date columns) and, at scale, be backed by a scheduled job that populates lightweight **summary/aggregate tables** (e.g., `daily_appointment_stats`) rather than aggregating raw transactional data on every dashboard load.
- **Pagination everywhere:** All list endpoints (appointments, patients, doctors, activity logs) must use cursor or offset pagination (Eloquent's `paginate()`/`cursorPaginate()`) — never return unbounded result sets.
- **Avoid `SELECT *` in hot paths:** Explicitly select only needed columns in high-frequency queries (e.g., slot-availability checks don't need `medical_records` joins at all).
- **Connection/query caching:** Frequently-read, rarely-changed data (`departments`, `settings`, active `doctors` list) are good candidates for application-level caching (Redis/array cache) with explicit invalidation on write, reducing repetitive DB round-trips.

---

## Database Security

- **Least-privilege DB credentials:** The application's MySQL user should have only the privileges it needs (`SELECT, INSERT, UPDATE, DELETE` on application schema) — no `DROP`, `ALTER`, or `GRANT` privileges in production connection credentials; schema migrations run under a separate, more privileged deployment-time credential.
- **Encrypted connections:** Production database connections use TLS/SSL between the application server and MySQL.
- **Encryption at rest:** Sensitive columns (e.g., could extend to `patients.allergies`, `medical_records.diagnosis`) are candidates for Laravel's built-in encrypted casts (`encrypted` cast) in addition to disk-level encryption on the database volume, given their clinical sensitivity.
- **No secrets in the schema:** No API keys, Stripe secrets, or credentials are ever stored in application tables — these live in environment configuration (`.env`), never in the database.
- **Application-layer authorization is mandatory, not optional:** Every query touching `medical_records`, `prescriptions`, `patients`, and `appointments` must pass through Laravel Policies scoping results to the authenticated user's role and ownership — the database schema enables data isolation (via foreign keys) but does not itself enforce row-level security; this is handled in the application layer consistently across all controllers/services.
- **Audit logging as a security control:** `activity_logs` captures who accessed/modified sensitive records and when, supporting after-the-fact security review, not just debugging.
- **Soft deletes as a safeguard:** Because clinical and financial tables are soft-deleted rather than hard-deleted, accidental or malicious data loss is recoverable, and deleted records remain available for compliance/audit review.
- **Input validation defense-in-depth:** While Laravel Form Requests are the primary validation layer, database-level constraints (`NOT NULL`, `UNIQUE`, foreign keys, `ENUM`) act as a second line of defense against invalid data reaching storage even if application validation is bypassed by a bug.
- **Prepared statements by default:** All queries are executed through Eloquent/Query Builder, which parameter-binds by default, eliminating raw SQL injection risk; any raw query (if ever needed) must use parameter binding explicitly and undergo code review.

---

## Backup Strategy

- **Automated daily full backups** of the MySQL database (e.g., via `mysqldump` or a managed database provider's snapshot feature), retained on a rolling window (e.g., 30 days) with weekly backups retained longer (e.g., 6–12 months) for historical/compliance recoverability.
- **Point-in-time recovery (PITR):** Binary logging (`binlog`) enabled in production to allow restoration to a specific point in time between full backups, minimizing data loss window in an incident.
- **Off-site/redundant storage:** Backups stored in a location separate from the primary database server (e.g., cloud object storage) to protect against server-level failure.
- **Backup encryption:** Backup files encrypted at rest, given the presence of medical and personal data.
- **Restoration testing:** Backup restoration is periodically tested (not just taken and assumed valid) — a backup that has never been restored is not a verified backup.
- **Pre-deployment safety backups:** A backup/snapshot is taken immediately before running production migrations, allowing fast rollback if a migration causes unexpected data issues.

---

## Future Database Expansion

- **Multi-tenancy:** Introduce a `hospitals`/`tenants` table with a `tenant_id` foreign key propagated across core tables (`users`, `departments`, `doctors`, `appointments`, etc.) to support multiple hospitals on a shared schema, or migrate to a database-per-tenant model for stronger isolation at larger scale.
- **Structured allergy/condition tracking:** Replace the free-text `patients.allergies` field with normalized `allergies` and `patient_allergies` (pivot) tables to support structured clinical decision support.
- **Telemedicine support:** New `video_sessions` table linked 1:1 to `appointments`, storing session provider metadata (room ID, join URLs, recording reference).
- **Insurance integration:** `insurance_providers` and `patient_insurance_policies` tables, with `payments` extended to reference a policy for claim-based billing.
- **Waitlist system:** A `waitlist_entries` table linking `patients` to a desired `doctor_id`/`department_id` and date range, with automated matching against newly opened slots (e.g., from cancellations).
- **Multi-language content:** Translation tables (e.g., `department_translations`, `doctor_translations`) keyed by locale for i18n support on public-facing content.
- **Notification preference granularity:** A dedicated `notification_preferences` table per user, replacing simple on/off flags with channel- and event-type-specific opt-in/opt-out settings.
- **Aggregate/reporting tables:** Introduction of pre-computed summary tables (`daily_appointment_stats`, `doctor_monthly_performance`) refreshed via scheduled jobs to keep the Analytics module performant as transactional data volume grows into the millions of rows.

---

**End of Document**
