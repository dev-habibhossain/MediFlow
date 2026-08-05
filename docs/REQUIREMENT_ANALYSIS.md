# Requirement Analysis Document
## Hospital Appointment Management System (HAMS)

| | |
|---|---|
| **Document Type** | Software Requirements Specification (SRS) |
| **Project Name** | Hospital Appointment Management System |
| **Version** | 1.0 |
| **Status** | Draft for Development |
| **Stack** | Laravel 12, PHP 8+, MySQL, Inertia.js, Vue.js, TypeScript, Tailwind CSS, Shadcn UI |

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Goals](#goals)
3. [Business Objectives](#business-objectives)
4. [Problems Being Solved](#problems-being-solved)
5. [Target Users](#target-users)
6. [User Roles](#user-roles)
7. [Functional Requirements](#functional-requirements)
8. [Non-Functional Requirements](#non-functional-requirements)
9. [System Constraints](#system-constraints)
10. [Future Enhancements](#future-enhancements)
11. [Risks](#risks)
12. [Assumptions](#assumptions)
13. [Success Metrics](#success-metrics)
14. [Project Scope](#project-scope)

---

## Project Overview

The **Hospital Appointment Management System (HAMS)** is a multi-tenant-ready, production-grade SaaS web application designed to digitize and streamline the entire lifecycle of hospital appointment management — from patient discovery of doctors and departments, to booking, consultation, medical record-keeping, prescription issuance, and administrative oversight.

Unlike a static hospital marketing website, HAMS is a **transactional, role-based platform** with real business logic: scheduling conflicts, availability windows, appointment states, payment processing, notification pipelines, and audit-grade medical record handling. It is built to demonstrate enterprise-level engineering practices — clean architecture, authorization boundaries, data integrity, and scalable module design — suitable for a professional software engineering portfolio.

The system serves four distinct actors (Guest, Patient, Doctor, Administrator), each with tightly scoped permissions enforced via **Spatie Laravel Permission**, and delivers a modern, fast, SPA-like experience using **Inertia.js + Vue 3 + TypeScript** on top of a Laravel 12 backend, styled with **Tailwind CSS** and **Shadcn UI** components.

---

## Goals

1. Provide a **seamless appointment booking experience** for patients across multiple departments and doctors.
2. Give doctors a **centralized workspace** to manage schedules, appointments, patient histories, and prescriptions.
3. Equip administrators with **full operational control** — user management, department/doctor onboarding, scheduling policy, reporting, and system configuration.
4. Build a **secure, auditable, and compliant** data model suitable for sensitive medical information.
5. Demonstrate **production-quality software architecture**: modular design, role-based access control, validation-first development, and scalable database design.
6. Deliver a **responsive, accessible, and performant** UI across desktop, tablet, and mobile devices.
7. Lay the groundwork for **future monetization** (Stripe) and **media handling** (Cloudinary) without re-architecting the core system.

---

## Business Objectives

| Objective | Description |
|---|---|
| **Reduce administrative overhead** | Automate scheduling, reminders, and record-keeping to reduce manual front-desk work. |
| **Minimize appointment no-shows** | Automated notifications and reminders to reduce missed appointments. |
| **Improve patient satisfaction** | Self-service booking, transparent doctor availability, and appointment history access. |
| **Enable data-driven decisions** | Dashboards and analytics for administrators to track hospital performance (appointments/day, department load, doctor utilization, revenue). |
| **Support future monetization** | Consultation fee collection via Stripe (future phase) to simulate a real SaaS revenue model. |
| **Ensure regulatory-style data handling** | Treat medical records and prescriptions with strict access control and audit logging, mirroring real healthcare compliance expectations (e.g., HIPAA-inspired principles, not full legal compliance). |

---

## Problems Being Solved

1. **Manual, error-prone scheduling** — Traditional phone/walk-in booking leads to double-booking, long wait times, and lost records.
2. **Lack of visibility into doctor availability** — Patients cannot see real-time open slots without calling the hospital.
3. **Fragmented medical history** — Paper-based or siloed records make it hard for doctors to access a patient's history during consultation.
4. **No centralized reporting** — Hospital administrators lack real-time insight into appointment volume, department performance, and doctor workload.
5. **Poor communication** — Patients are not reliably notified of confirmations, reschedules, or cancellations.
6. **Inconsistent access control** — Without proper RBAC, sensitive medical data can be exposed to unauthorized users.
7. **Non-scalable legacy systems** — Many hospital systems are not built to scale or integrate with modern payment/notification infrastructure.

---

## Target Users

| User Segment | Description |
|---|---|
| **Patients** | Individuals seeking to book, manage, and track medical appointments online without visiting or calling the hospital. |
| **Doctors** | Medical professionals who need an efficient way to manage their schedules, view patient histories, and issue prescriptions. |
| **Hospital Administrators / Staff** | Non-clinical staff responsible for operational management — onboarding doctors, managing departments, overseeing appointments, and generating reports. |
| **Prospective Patients (Guests)** | Visitors researching the hospital's departments, doctors, and services before registering. |
| **Portfolio Reviewers / Recruiters** *(secondary audience)* | Technical evaluators assessing the architectural quality, code standards, and completeness of the system. |

---

## User Roles

### 1. Guest (Unauthenticated Visitor)

**Description:** Any unauthenticated visitor browsing the public-facing side of the platform.

**Capabilities:**
- View the public landing page, hospital information, and marketing content.
- Browse the list of departments and their descriptions.
- Browse doctor profiles (name, specialization, department, qualifications, public availability summary).
- Search and filter doctors by specialization, department, or availability.
- View general FAQs, contact information, and hospital policies.
- Register as a new Patient.
- Log in to an existing account.

**Restrictions:**
- Cannot book appointments.
- Cannot view any patient-specific, doctor-specific, or administrative data.
- Cannot access dashboards of any kind.
- Redirected to registration/login when attempting a restricted action (e.g., "Book Appointment").

---

### 2. Patient

**Description:** A registered end-user who books and manages their own medical appointments.

**Capabilities:**
- Register, verify email, log in/out, and manage account credentials.
- Complete and update a personal profile (demographics, contact info, emergency contact, avatar).
- Search/filter doctors and departments.
- View doctor availability and book appointments within valid time slots.
- View, reschedule, or cancel their own upcoming appointments (subject to business rules).
- View history of past appointments.
- View their own medical records and prescriptions (read-only).
- Receive notifications (email/in-app) for booking confirmation, reminders, rescheduling, and cancellations.
- Make payments for consultations (future phase — Stripe).
- Rate/review a completed appointment (optional module).

**Restrictions:**
- Cannot view or modify other patients' data.
- Cannot view doctor schedules beyond public availability.
- Cannot access administrative or doctor dashboards.
- Cannot edit medical records or prescriptions (doctor/admin-only, write access).

---

### 3. Doctor

**Description:** A verified medical professional who delivers consultations and manages their own clinical workflow.

**Capabilities:**
- Log in to a dedicated doctor dashboard.
- Manage personal profile (bio, specialization, qualifications, consultation fee, profile photo).
- Define and manage weekly availability/schedule (working days, time slots, breaks, leave/blackout dates).
- View, accept, or decline appointment requests (if applicable to the booking model).
- View a list of upcoming and past appointments with assigned patients.
- Access the medical history of patients they are/were assigned to (not all patients hospital-wide).
- Create, update medical records and issue prescriptions for patients they've consulted.
- Mark appointments as "Completed," "No-show," or "Cancelled" with notes.
- Receive notifications for new bookings, cancellations, and reminders.
- View personal performance metrics (appointments completed, ratings, patient count) on their dashboard.

**Restrictions:**
- Cannot view or manage other doctors' schedules or patients.
- Cannot access hospital-wide administrative settings, user management, or system reports.
- Cannot alter appointment records outside their own consultations.
- Medical record edits are scoped strictly to patients they have an active/past appointment relationship with.

---

### 4. Administrator

**Description:** The super-user responsible for the operational and technical management of the entire platform.

**Capabilities:**
- Full user management: create, edit, suspend, or delete Patient, Doctor, and Staff accounts.
- Assign and manage roles/permissions (via Spatie Permission).
- Manage departments (create, edit, deactivate, reorder).
- Onboard and manage doctor profiles, including specialization, department assignment, and credentials verification.
- Oversee all appointments hospital-wide: view, reassign, cancel, or resolve conflicts.
- Configure global scheduling rules (slot duration, buffer time, max daily bookings per doctor, holidays).
- Access hospital-wide analytics and reports (appointments, revenue, department load, doctor performance, patient growth).
- Manage system-wide settings (branding, notification templates, business hours, payment configuration).
- View and manage payment transactions (future phase).
- Access audit logs for sensitive actions (record access, role changes, deletions).
- Manage content on public pages (departments, doctor listings, announcements).

**Restrictions:**
- Should not directly alter clinical content (e.g., prescription contents) without an explicit override permission and audit trail, to preserve clinical integrity.
- All destructive actions (delete user, delete department) require confirmation and are soft-deleted, not hard-deleted, where medical data is involved.

---

## Functional Requirements

Requirements are organized by module. Each module specifies **Purpose**, **Features**, **User Permissions**, **Business Rules**, **Validation Rules**, **Edge Cases**, and **Dependencies**.

---

### 1. Authentication Module

**Purpose:** Securely manage identity, registration, login, and session lifecycle for all authenticated roles.

**Features:**
- Registration (Patient self-registration; Doctor/Admin created by Administrator).
- Login/logout with session and "remember me" support.
- Email verification (Laravel Breeze).
- Password reset via secure emailed token.
- Two-factor consideration (future enhancement).
- Role-based redirect after login (Patient → patient dashboard, Doctor → doctor dashboard, Admin → admin dashboard).
- Account lockout after repeated failed login attempts (rate limiting).

**User Permissions:**
- Guest: register, login, request password reset.
- Patient/Doctor/Admin: logout, change password, manage active sessions.
- Admin: force-reset any user's password, deactivate accounts.

**Business Rules:**
- Only Patients can self-register; Doctor and Admin accounts are provisioned by an Administrator.
- Email must be verified before a Patient can book an appointment.
- Each email address maps to exactly one account across the system.

**Validation Rules:**
- Email: required, valid format, unique.
- Password: required, minimum 8 characters, must include letters and numbers.
- Name: required, string, max 255 characters.
- Password confirmation must match on registration/reset.

**Edge Cases:**
- Duplicate registration attempts with an already-verified email.
- Password reset token expiration or reuse.
- Login attempt on a deactivated/suspended account.
- Race condition: two simultaneous registrations with the same email.

**Dependencies:** Laravel Breeze, Spatie Permission (role assignment on registration).

---

### 2. Appointment Module

**Purpose:** Core transactional module enabling patients to book, manage, and track appointments, and doctors/admins to fulfill them.

**Features:**
- Doctor + department + date/time slot selection.
- Real-time slot availability check against doctor schedule and existing bookings.
- Appointment states: `Pending`, `Confirmed`, `Completed`, `Cancelled`, `No-show`, `Rescheduled`.
- Patient-initiated cancellation/reschedule (within policy window).
- Doctor/Admin-initiated cancellation, reassignment, or status update.
- Appointment reason/notes field (symptom description) captured at booking.
- Automatic conflict prevention (no double-booking of a single slot).
- Appointment history view (past and upcoming) per role.

**User Permissions:**
- Patient: create own appointment, view/cancel/reschedule own appointment (pre-consultation only).
- Doctor: view assigned appointments, update status, add consultation notes.
- Admin: full CRUD across all appointments, override/reassign.

**Business Rules:**
- An appointment slot becomes unavailable the moment it is successfully booked.
- Cancellations are only allowed up to a configurable cutoff (e.g., 2 hours before appointment time).
- A patient cannot book two appointments with the same doctor at overlapping times.
- Completed appointments are immutable except for adding clinical notes/prescriptions by the assigned doctor.
- No-show appointments are auto-flagged if not marked complete within a grace period after the scheduled time.

**Validation Rules:**
- Appointment date/time: required, must be in the future, must fall within the doctor's defined availability.
- Doctor and department: required, must reference active/valid records.
- Reason for visit: required, max 1000 characters.
- Status transitions must follow an allowed state machine (e.g., `Pending → Confirmed → Completed`, not `Completed → Pending`).

**Edge Cases:**
- Two patients attempting to book the same slot simultaneously (handled via DB-level locking/unique constraints).
- Doctor going on leave after appointments are already booked (requires reassignment/notification flow).
- Timezone mismatches between patient and hospital.
- Patient attempts to reschedule into an already-booked slot.

**Dependencies:** Doctor Management, Schedules Module, Notifications Module, Patient Management.

---

### 3. Doctor Management Module

**Purpose:** Manage doctor profiles, credentials, specialization, and departmental association.

**Features:**
- Admin-driven onboarding of doctor accounts.
- Doctor profile: name, photo, bio, specialization, qualifications, years of experience, consultation fee, department.
- Doctor status: `Active`, `On Leave`, `Suspended`, `Inactive`.
- Public doctor directory listing with search/filter.
- Doctor self-service profile editing (non-critical fields only).

**User Permissions:**
- Guest/Patient: view public doctor profiles.
- Doctor: edit own bio, photo, availability, consultation fee (if allowed by policy).
- Admin: full CRUD, status management, department reassignment.

**Business Rules:**
- A doctor must belong to exactly one primary department (with optional secondary departments).
- A doctor cannot be deleted if they have existing appointment history — must be soft-deleted/deactivated instead.
- Doctor status changes to `On Leave`/`Suspended` should automatically block new bookings but not cancel existing confirmed ones without admin action.

**Validation Rules:**
- Name, specialization, department: required.
- Consultation fee: numeric, non-negative.
- Profile photo: image file, max size enforced (e.g., 2MB), allowed formats (jpg, png, webp).

**Edge Cases:**
- Doctor deactivated mid-day with pending appointments.
- Doctor reassigned to a new department with existing recurring schedules.
- Duplicate doctor profiles for the same license/email.

**Dependencies:** Departments Module, Schedules Module, File Uploads Module, Authentication Module.

---

### 4. Patient Management Module

**Purpose:** Maintain accurate patient demographic and account data for administrative and clinical use.

**Features:**
- Patient profile: name, DOB, gender, contact info, address, emergency contact, blood group, allergies (optional).
- Admin view of all patients with search/filter.
- Patient self-service profile management.
- Soft-delete/deactivation of patient accounts.

**User Permissions:**
- Patient: view/edit own profile.
- Doctor: view profile (read-only) of patients they are consulting.
- Admin: full CRUD, deactivate/reactivate accounts.

**Business Rules:**
- A patient's core medical identifiers (e.g., patient ID) are immutable once created.
- Sensitive fields (allergies, blood group) are visible only to the patient, assigned doctors, and admins.

**Validation Rules:**
- DOB: required, must result in a valid, non-future age.
- Phone number: required, valid format.
- Emergency contact: optional but recommended, valid phone format if provided.

**Edge Cases:**
- Patient attempts to delete their own account with active upcoming appointments.
- Merge/duplicate patient profile detection (same name + DOB + phone).

**Dependencies:** Authentication Module, Appointment Module, Medical Records Module.

---

### 5. Departments Module

**Purpose:** Organize doctors and services into logical hospital departments (e.g., Cardiology, Dermatology, Pediatrics).

**Features:**
- CRUD for departments (name, description, icon/image, active status).
- Department listing on public pages with associated doctor count.
- Department-based doctor filtering.

**User Permissions:**
- Guest/Patient: view active departments.
- Admin: full CRUD.

**Business Rules:**
- A department cannot be deleted if it has associated doctors — must be deactivated instead.
- Department names must be unique.

**Validation Rules:**
- Name: required, unique, max 100 characters.
- Description: optional, max 1000 characters.

**Edge Cases:**
- Deactivating a department that has doctors with future appointments.
- Renaming a department referenced in historical reports.

**Dependencies:** Doctor Management Module.

---

### 6. Schedules Module

**Purpose:** Define and enforce doctor availability windows that govern what slots patients can book.

**Features:**
- Weekly recurring availability templates per doctor (days + time ranges).
- One-off overrides: leave days, holidays, extended hours.
- Configurable slot duration and buffer time (admin-level global default, doctor-level override).
- Auto-generation of bookable time slots based on schedule + existing appointments.

**User Permissions:**
- Doctor: manage own schedule and leave requests.
- Admin: manage/override any doctor's schedule, set hospital-wide holidays.
- Patient/Guest: view resulting available slots only (not raw schedule config).

**Business Rules:**
- Slots are dynamically computed — never statically stored as "empty" slots — to avoid stale data.
- Overlapping schedule entries for the same doctor are not allowed.
- Leave requests block new bookings but require admin review if existing appointments fall within the leave period.

**Validation Rules:**
- Start time must be before end time.
- Slot duration must be a positive integer (in minutes), within a sane range (e.g., 5–120 minutes).

**Edge Cases:**
- Doctor changes schedule after slots are already booked under the old schedule.
- Daylight saving/timezone edge cases if the platform supports multiple timezones.
- Public holiday overlapping with a doctor-specific leave day.

**Dependencies:** Doctor Management Module, Appointment Module.

---

### 7. Payments Module *(Future Phase — Stripe Integration)*

**Purpose:** Handle consultation fee collection and payment history securely.

**Features:**
- Stripe Checkout/Payment Intent integration for consultation fees.
- Payment status tracking: `Pending`, `Paid`, `Failed`, `Refunded`.
- Invoice/receipt generation.
- Admin view of all transactions; patient view of own payment history.

**User Permissions:**
- Patient: initiate payment, view own payment history/receipts.
- Admin: view all transactions, issue refunds, reconcile reports.
- Doctor: view whether their appointment is paid (read-only), not raw payment details.

**Business Rules:**
- An appointment may be configured as requiring pre-payment or pay-at-consultation, per hospital policy.
- Refunds are only permitted for appointments cancelled within policy or cancelled by the hospital.
- No card data is stored directly — Stripe tokenization is mandatory (PCI-DSS alignment).

**Validation Rules:**
- Payment amount must match the doctor's configured consultation fee at time of booking.
- Currency must be consistent across the platform (single-currency for v1).

**Edge Cases:**
- Payment succeeds but appointment booking fails (requires reconciliation/rollback logic).
- Webhook delivery failure/delay from Stripe.
- Duplicate payment attempts on slow network retries.

**Dependencies:** Appointment Module, Notifications Module, Stripe API (external).

---

### 8. Notifications Module

**Purpose:** Keep users informed of relevant events across the appointment lifecycle.

**Features:**
- In-app notifications (bell icon, notification center).
- Email notifications (booking confirmation, reminders, cancellations, reschedules).
- Configurable reminder timing (e.g., 24 hours and 1 hour before appointment).
- Admin broadcast announcements (e.g., holiday notices).

**User Permissions:**
- All authenticated roles: receive and view their own notifications, mark as read.
- Admin: send system-wide or targeted announcements.

**Business Rules:**
- Critical notifications (cancellation, reschedule) are sent immediately; reminders are sent via scheduled jobs.
- Notification preferences (email on/off) are user-configurable within reasonable limits (critical alerts cannot be fully disabled).

**Validation Rules:**
- Notification content must reference a valid, existing entity (appointment, user).

**Edge Cases:**
- User has no verified email — notification silently logged, not sent.
- Notification job failure/retry handling (queue failures).
- High-volume broadcast to all patients (must be queued/batched, not synchronous).

**Dependencies:** Laravel Queues, Mail configuration, Appointment Module.

---

### 9. Reports Module

**Purpose:** Provide administrators with actionable operational insight.

**Features:**
- Appointment volume reports (daily/weekly/monthly, filterable by department/doctor).
- Doctor performance reports (completed vs. no-show vs. cancelled).
- Revenue reports (future, tied to Payments Module).
- Patient growth/retention reports.
- Exportable reports (CSV/PDF).

**User Permissions:**
- Admin only.
- Doctor: limited personal performance report (own stats only).

**Business Rules:**
- Reports reflect near-real-time data (cached with a defined refresh interval for performance).
- Historical report data must remain accurate even if underlying records (e.g., department renamed) later change — use denormalized/snapshot values where relevant.

**Validation Rules:**
- Date range filters: start date must be before end date; range capped to a reasonable maximum (e.g., 2 years) to protect performance.

**Edge Cases:**
- Report requested for a date range with no data.
- Very large export requests (must be queued/background-processed, not synchronous).

**Dependencies:** Appointment Module, Payments Module, Doctor Management Module.

---

### 10. Profile Management Module

**Purpose:** Allow each authenticated user to manage their personal identity and preferences.

**Features:**
- Update name, email (with re-verification), phone, avatar.
- Change password.
- Manage notification preferences.
- View account activity (last login, active sessions) — stretch goal.

**User Permissions:**
- All authenticated roles: manage own profile.
- Admin: manage any user's profile as an override.

**Business Rules:**
- Changing email requires re-verification before the new email becomes active for login.
- Avatar uploads follow the same constraints as the File Uploads Module.

**Validation Rules:**
- Standard field validation consistent with Authentication Module rules.

**Edge Cases:**
- User changes email to one already used by another (soft-deleted) account.
- Concurrent profile edits from multiple sessions.

**Dependencies:** Authentication Module, File Uploads Module.

---

### 11. Settings Module

**Purpose:** Centralize hospital-wide configuration controlled by administrators.

**Features:**
- General settings: hospital name, logo, contact info, business hours.
- Scheduling defaults: default slot duration, buffer time, cancellation cutoff window.
- Notification templates (email subject/body customization).
- Role and permission management (via Spatie).
- Holiday calendar management.

**User Permissions:**
- Admin only.

**Business Rules:**
- Changes to global scheduling defaults do not retroactively alter already-booked appointments.
- Only a "Super Admin" tier (if introduced) can modify role/permission definitions themselves — regular admins manage users within existing roles.

**Validation Rules:**
- All settings fields validated per type (numeric ranges, valid time formats, required branding assets).

**Edge Cases:**
- Invalid settings value causing system-wide scheduling errors (must be validated before save, not after).

**Dependencies:** Spatie Permission, all modules referencing global config.

---

### 12. Search Module

**Purpose:** Enable fast discovery of doctors, departments, and (for admins) appointments/patients.

**Features:**
- Public search: doctors by name/specialization; departments by name.
- Admin search: patients, appointments, transactions by multiple identifiers.
- Debounced, server-side search with pagination.

**User Permissions:**
- Guest/Patient: public search only.
- Admin: full-entity search.

**Business Rules:**
- Search results respect role-based visibility (e.g., a Patient's search never surfaces another patient's data).

**Validation Rules:**
- Search query: sanitized input, minimum length before triggering a query (e.g., 2 characters) to avoid excessive load.

**Edge Cases:**
- Empty search query.
- Special characters/SQL-injection attempts (must be parameter-bound, never raw-concatenated).

**Dependencies:** Doctor Management, Departments, Patient Management, Appointment Module.

---

### 13. Filtering Module

**Purpose:** Allow users to narrow large data sets (doctors, appointments, reports) via structured filters.

**Features:**
- Filter doctors by department, specialization, availability, rating.
- Filter appointments by status, date range, doctor, department.
- Combine filters with search and sorting.
- Persist filter state in the URL (query parameters) for shareable/bookmarkable views.

**User Permissions:**
- Role-scoped per module (a Patient filters only their own appointments; Admin filters all).

**Business Rules:**
- Filters must always be applied server-side (not client-side only) to prevent exposure of unauthorized data via API responses.

**Validation Rules:**
- Filter values must be validated against allowed enums (e.g., status must be one of the defined appointment states).

**Edge Cases:**
- Conflicting filter combinations returning zero results (must display a clear empty state).
- Invalid/tampered query parameters.

**Dependencies:** Appointment Module, Doctor Management, Reports Module.

---

### 14. Dashboard Module

**Purpose:** Provide each role with a tailored, at-a-glance operational view upon login.

**Features:**
- **Patient Dashboard:** upcoming appointments, quick rebook, recent prescriptions, notifications.
- **Doctor Dashboard:** today's schedule, pending appointments, quick access to patient history, performance snapshot.
- **Admin Dashboard:** system-wide KPIs (total appointments today, active doctors, new patients, revenue snapshot), quick links to management modules, recent activity feed.

**User Permissions:**
- Each role sees only their own dashboard variant; strictly enforced at the route/controller level, not just UI-hidden.

**Business Rules:**
- Dashboard data must be scoped server-side per authenticated user — no client-side filtering of a larger payload.

**Validation Rules:** N/A (read-focused module; relies on underlying module validation).

**Edge Cases:**
- New user with no data yet (empty states required for every widget).
- Dashboard load performance with large historical datasets (requires pagination/aggregation, not full table scans).

**Dependencies:** Appointment Module, Reports Module, Notifications Module.

---

### 15. Analytics Module

**Purpose:** Deliver deeper, visual, trend-based insight beyond static reports (primarily for Admin, with a lightweight variant for Doctors).

**Features:**
- Charts: appointment trends over time, department load distribution, doctor utilization, patient acquisition trend.
- Configurable date ranges and comparison periods (e.g., this month vs. last month).
- Drill-down from chart to underlying data table.

**User Permissions:**
- Admin: full analytics suite.
- Doctor: personal analytics only (own appointment trends, patient retention).

**Business Rules:**
- Analytics aggregates should be computed via optimized queries/materialized aggregation tables for large datasets, not on-the-fly full scans in production.

**Validation Rules:**
- Date range and comparison parameters validated the same as the Reports Module.

**Edge Cases:**
- Sparse data periods producing misleading chart scales (must handle gracefully, e.g., fixed axis minimums).

**Dependencies:** Reports Module, Appointment Module, Payments Module.

---

### 16. File Uploads Module

**Purpose:** Handle secure upload and storage of user-generated media (profile photos, medical documents).

**Features:**
- Avatar/profile photo upload (Patient, Doctor).
- Medical document attachment upload (lab results, scans) tied to a Medical Record.
- Future integration with Cloudinary for optimized storage/delivery.

**User Permissions:**
- Patient/Doctor: upload own avatar.
- Doctor: upload documents attached to a patient's medical record for consultations they own.
- Admin: manage/remove any uploaded file if flagged inappropriate or erroneous.

**Business Rules:**
- File type and size are strictly validated server-side, never trusted from client-side checks alone.
- Medical documents are private by default — never publicly accessible via guessable URLs.

**Validation Rules:**
- Images: jpg/png/webp only, max 2–5MB.
- Documents: pdf only (for medical attachments), max 10MB.

**Edge Cases:**
- Malicious file disguised with a valid extension (must validate MIME type server-side, not just extension).
- Upload interrupted mid-transfer (must not leave orphaned/partial records).

**Dependencies:** Cloudinary (future), Medical Records Module, Profile Management Module.

---

### 17. Medical Records Module

**Purpose:** Maintain a structured, secure, and auditable clinical history for each patient.

**Features:**
- Record creation tied to a specific completed/in-progress appointment.
- Fields: diagnosis, symptoms, vitals, doctor notes, attached documents.
- Chronological patient medical history view.
- Read-only access for patients to their own records.

**User Permissions:**
- Doctor: create/edit records only for patients they have an active or completed appointment relationship with.
- Patient: read-only access to own records.
- Admin: read access for oversight/audit; edit access restricted or logged if used (to preserve clinical integrity).

**Business Rules:**
- A medical record is always linked to an appointment — no orphaned/free-floating records.
- Once an appointment is marked `Completed`, records tied to it can be amended but changes should be logged (audit trail) rather than silently overwritten.

**Validation Rules:**
- Diagnosis/notes: required if the record is being finalized; max length constraints applied.
- Attached files: subject to File Uploads Module rules.

**Edge Cases:**
- Doctor attempts to edit a record for a patient outside their consultation history (must be blocked at the authorization layer).
- Record created for a `Cancelled` appointment (should be disallowed).

**Dependencies:** Appointment Module, File Uploads Module, Patient Management, Doctor Management.

---

### 18. Prescriptions Module

**Purpose:** Enable doctors to issue and track digital prescriptions tied to a consultation.

**Features:**
- Create prescription: medication name, dosage, frequency, duration, special instructions.
- Multiple medications per prescription.
- Printable/downloadable prescription (PDF).
- Patient view of active and historical prescriptions.

**User Permissions:**
- Doctor: create prescriptions only for their own completed/active appointments.
- Patient: view/download own prescriptions (read-only).
- Admin: read access for audit/support purposes.

**Business Rules:**
- A prescription must be linked to a specific appointment and, by extension, a medical record.
- Prescriptions are immutable once issued — corrections require issuing a new, versioned prescription rather than editing history (preserves clinical audit integrity).

**Validation Rules:**
- Medication name: required.
- Dosage/frequency/duration: required, structured (e.g., numeric + unit).

**Edge Cases:**
- Doctor attempts to issue a prescription for an appointment that was cancelled.
- Prescription requested for download after the doctor account has been deactivated (data must remain accessible to the patient).

**Dependencies:** Medical Records Module, Appointment Module, File Uploads Module (PDF generation/storage).

---

## Non-Functional Requirements

### Performance
- Page transitions via Inertia.js should complete in under 300ms on standard broadband for cached/optimized routes.
- Database queries for list views (appointments, doctors) must be paginated and indexed; avoid N+1 query patterns (enforced via eager loading).
- Heavy operations (report exports, bulk notifications) must be offloaded to Laravel Queues, not processed synchronously in the request cycle.

### Security
- All authentication flows follow Laravel Breeze best practices (hashed passwords, CSRF protection, signed URLs for verification/reset).
- Role and permission enforcement via Spatie Permission at both route middleware and policy/gate level (defense in depth).
- All medical and personal data access is authorization-checked server-side; UI-level hiding is never treated as a security boundary.
- Protection against common OWASP risks: SQL injection (via Eloquent/parameter binding), XSS (via output escaping/Vue's default escaping), CSRF (via Laravel tokens), mass assignment (via model `$fillable`/Form Requests).
- Rate limiting on authentication and search endpoints.
- File uploads validated by MIME type and size server-side.

### Accessibility
- UI components (Shadcn UI) used with proper semantic HTML and ARIA attributes.
- Full keyboard navigability for critical flows (booking, login, dashboard navigation).
- Sufficient color contrast per WCAG 2.1 AA guidelines across Tailwind theme tokens.
- Form fields include associated labels and error messaging accessible to screen readers.

### Scalability
- Database schema designed with normalization and appropriate indexing to support growth in appointment/patient volume.
- Stateless application design (sessions/queues externally backed) to support horizontal scaling.
- Modular codebase (feature-based organization) to allow independent scaling of complexity per module.

### Maintainability
- Consistent use of Laravel conventions: Form Requests for validation, Policies for authorization, Resource classes for API/Inertia data shaping.
- TypeScript on the frontend to reduce runtime type errors and improve refactor safety.
- Clear separation of concerns: Controllers thin, business logic in Services/Actions, models focused on relationships and scopes.
- Documented codebase with README, this requirements document, and inline docblocks for complex logic.

### Responsiveness
- Fully responsive UI across mobile, tablet, and desktop breakpoints using Tailwind's responsive utilities.
- Critical flows (booking, dashboard) tested and optimized for mobile-first usage, given patients often book via phone.

### SEO (Public Pages)
- Server-rendered (Inertia SSR or static meta injection) titles, meta descriptions, and Open Graph tags for public pages (landing, departments, doctor profiles).
- Semantic HTML structure (proper heading hierarchy) on public pages.
- Clean, human-readable URLs (e.g., `/doctors/dr-jane-doe`, `/departments/cardiology`).
- Sitemap and robots.txt for public-facing routes only; authenticated routes excluded from indexing.

### Reliability
- Critical operations (booking, payment) wrapped in database transactions to prevent partial writes.
- Idempotent handling of payment webhooks (future phase) to avoid duplicate processing.
- Graceful error handling with user-friendly messages; no raw stack traces exposed in production.

### Backup
- Scheduled automated database backups (daily), with a defined retention policy.
- Backup restoration process documented and periodically tested (as part of operational readiness, even for a portfolio project).

### Logging
- Application-level logging (Laravel's logging stack) for errors and critical business events (appointment created/cancelled, payment processed, role changed).
- Audit logging for sensitive actions: medical record access/edits, role/permission changes, account deactivations.
- Logs structured and centralized to support future observability tooling integration.

---

## System Constraints

- Must be built on **Laravel 12** and **PHP 8+**; no legacy PHP version support required.
- Frontend must use **Inertia.js with Vue 3 and TypeScript** — no separate detached SPA/API-only architecture for v1.
- UI components restricted to **Tailwind CSS + Shadcn UI** conventions for design consistency.
- **MySQL** is the sole supported database engine for v1 (no multi-database abstraction required).
- Role and permission logic must be implemented through **Spatie Laravel Permission** rather than a custom-built ACL.
- Payment and media handling (**Stripe**, **Cloudinary**) are explicitly deferred to a future phase and must not block v1 delivery.
- Single-currency, single-language (English) support assumed for v1.
- Single-hospital tenancy for v1 — true multi-tenant (multi-hospital) architecture is a future consideration, not a v1 constraint.

---

## Future Enhancements

- **Stripe payment integration** for consultation fees, deposits, and refund handling.
- **Cloudinary integration** for optimized image/document storage and delivery.
- **Multi-hospital / multi-tenant support** (SaaS-proper, with tenant-scoped data isolation).
- **Telemedicine/video consultation** integration.
- **SMS notifications** in addition to email/in-app.
- **Two-factor authentication (2FA)** for enhanced account security.
- **Patient rating and review system** for doctors.
- **AI-assisted symptom triage** to help patients select the correct department/doctor.
- **Multi-language (i18n) support** for broader accessibility.
- **Insurance claim integration** for payment processing.
- **Mobile application** (native or PWA) built on the same backend API surface.
- **Waitlist system** for fully booked doctors.

---

## Risks

| Risk | Impact | Likelihood | Mitigation |
|---|---|---|---|
| Double-booking due to race conditions | High | Medium | Database-level unique constraints + transactional locking on slot booking. |
| Sensitive medical data exposure via authorization gaps | High | Medium | Policy-based authorization enforced server-side on every record access, tested explicitly. |
| Scope creep given the breadth of modules | Medium | High | Phase-based delivery plan (core booking first, then payments/analytics). |
| Notification delivery failures (email provider issues) | Medium | Medium | Queue with retry/backoff; logging of failed jobs for manual follow-up. |
| Performance degradation as appointment/report data grows | Medium | Medium | Indexing strategy, pagination, and query profiling from the start. |
| Incomplete future-phase dependencies (Stripe/Cloudinary) blocking core flows | Low | Low | Architected as pluggable modules; core booking flow functions independently of payment/media features. |

---

## Assumptions

- The platform operates for a **single hospital entity** in v1, not a network of hospitals.
- All users interact in a **single timezone** (hospital's local timezone) for v1 simplicity.
- Payments and file storage integrations (Stripe, Cloudinary) are **not required for the initial production release** but the architecture accommodates them without major rework.
- Doctor onboarding is **admin-mediated**, not self-service registration, reflecting real-world hospital credential verification needs.
- The system is a **portfolio-grade production simulation** — built to professional standards but not currently pursuing formal medical compliance certification (e.g., HIPAA certification itself), only compliance-inspired best practices.
- English is the only supported language for v1.

---

## Success Metrics

| Metric | Target / Indicator |
|---|---|
| Appointment booking completion rate | High ratio of started vs. completed bookings (low drop-off) |
| Time-to-book | Patient can complete a booking in under 2 minutes end-to-end |
| No-show rate | Reduced through reminder notifications (trackable via appointment status data) |
| System uptime | High availability suitable for production demonstration (target 99%+ for portfolio SLA simulation) |
| Page load performance | Core pages load within acceptable performance budgets (e.g., <2s on standard connections) |
| Role-based access integrity | Zero unauthorized cross-role data access incidents during testing/audit |
| Code quality / maintainability | Consistent adherence to Laravel and Vue/TypeScript best practices, verifiable via clean architecture and test coverage |
| Test coverage | Meaningful automated test coverage on critical modules (Authentication, Appointments, Authorization) |

---

## Project Scope

### In Scope

- Guest-facing public pages: landing page, department listings, doctor directory, search/filter.
- Full authentication system (registration, login, verification, password reset) via Laravel Breeze.
- Role-based access control across Guest, Patient, Doctor, Administrator via Spatie Permission.
- Complete appointment booking lifecycle: search, book, confirm, reschedule, cancel, complete.
- Doctor schedule/availability management.
- Department management.
- Patient and Doctor profile management.
- Medical records and prescriptions tied to appointments.
- Notification system (email + in-app) for core appointment events.
- Admin dashboard with reports and analytics for appointments, doctors, and departments.
- Search and filtering across doctors, departments, and (admin-side) appointments/patients.
- File uploads for avatars and medical documents (local/standard storage for v1; Cloudinary-ready architecture).
- System settings/configuration managed by Administrators.
- Responsive, accessible UI built with Tailwind CSS and Shadcn UI.
- Basic SEO optimization for public-facing pages.

### Out of Scope (v1)

- Live Stripe payment processing (architected for, but not activated, in v1 — flagged as future phase).
- Cloudinary media pipeline (v1 uses standard file storage; Cloudinary is a planned migration).
- Multi-hospital / multi-tenant SaaS data isolation.
- Telemedicine / video consultation functionality.
- SMS notifications.
- Native mobile applications.
- Multi-language (i18n) support.
- Insurance and third-party billing integrations.
- AI-based triage or recommendation systems.
- Formal legal/regulatory medical compliance certification (e.g., official HIPAA certification).

---

**End of Document**
