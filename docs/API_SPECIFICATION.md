# API Specification
## Hospital Appointment Management System (HAMS)

| | |
|---|---|
| **Document Type** | API Specification |
| **Project Name** | Hospital Appointment Management System |
| **Version** | 1.0 |
| **Backend Framework** | Laravel 12 (Inertia.js server-driven routes + JSON API endpoints) |
| **Related Documents** | REQUIREMENT_ANALYSIS.md, DATABASE_DESIGN.md, PRD.md |

---

## Table of Contents

1. [Overview](#overview)
2. [Base URL & Versioning](#base-url--versioning)
3. [Authentication & Authorization](#authentication--authorization)
4. [Request & Response Conventions](#request--response-conventions)
5. [Error Handling](#error-handling)
6. [Rate Limiting](#rate-limiting)
7. [Pagination, Filtering & Sorting](#pagination-filtering--sorting)
8. [Endpoints — Authentication Module](#endpoints--authentication-module)
9. [Endpoints — Profile Module](#endpoints--profile-module)
10. [Endpoints — Departments Module](#endpoints--departments-module)
11. [Endpoints — Doctors Module](#endpoints--doctors-module)
12. [Endpoints — Doctor Schedules Module](#endpoints--doctor-schedules-module)
13. [Endpoints — Patients Module (Admin)](#endpoints--patients-module-admin)
14. [Endpoints — Appointments Module](#endpoints--appointments-module)
15. [Endpoints — Medical Records Module](#endpoints--medical-records-module)
16. [Endpoints — Prescriptions Module](#endpoints--prescriptions-module)
17. [Endpoints — Payments Module (Phase 3)](#endpoints--payments-module-phase-3)
18. [Endpoints — Notifications Module](#endpoints--notifications-module)
19. [Endpoints — Reviews Module](#endpoints--reviews-module)
20. [Endpoints — Reports & Analytics Module (Admin)](#endpoints--reports--analytics-module-admin)
21. [Endpoints — Settings Module (Admin)](#endpoints--settings-module-admin)
22. [Endpoints — Roles & Permissions Module (Admin)](#endpoints--roles--permissions-module-admin)
23. [Endpoints — Search Module](#endpoints--search-module)
24. [Webhooks](#webhooks)
25. [Status Code Reference](#status-code-reference)
26. [Core Data Models](#core-data-models)
27. [Security Considerations](#security-considerations)
28. [Versioning & Deprecation Policy](#versioning--deprecation-policy)

---

## Overview

HAMS is built on **Laravel 12 with Inertia.js**, meaning most user-facing navigation is served through **Inertia responses** (server-rendered page props, not raw JSON) rather than a client fully decoupled from the backend. However, the application also exposes a set of **JSON API-style endpoints** for actions that are: (a) invoked via AJAX/fetch from within Inertia pages (e.g., live slot availability, search-as-you-type, notification polling), (b) intended for future consumption by a native mobile app or third-party integration, and (c) required for webhook receivers (Stripe).

This document specifies **all endpoints as if consumed as a JSON API**, since this is the most precise and framework-agnostic way to define request/response contracts. In the actual Laravel implementation:

- Endpoints that render a full page (e.g., `GET /doctors`) are Inertia routes returning a rendered page with props matching the "Response Body" shown here.
- Endpoints that perform an action and redirect (e.g., `POST /appointments`) follow Laravel's standard Inertia form-submission pattern (redirect + flash message) but are documented here with the JSON shape of the underlying resource for clarity and future API-consumer reference.
- Endpoints explicitly marked **`[JSON]`** are pure JSON endpoints (e.g., `/api/*` prefixed), used for dynamic in-page interactions and are not full-page Inertia visits.

All endpoints are grouped by module, consistent with **REQUIREMENT_ANALYSIS.md** and **DATABASE_DESIGN.md**.

---

## Base URL & Versioning

| Environment | Base URL |
|---|---|
| Local development | `http://localhost:8000` |
| Staging | `https://staging.hams.app` |
| Production | `https://api.hams.app` (JSON endpoints) / `https://hams.app` (Inertia web routes) |

- Web (Inertia) routes are unprefixed (e.g., `/appointments`).
- Pure JSON endpoints intended for dynamic interactions and future external consumption are prefixed `/api/v1/` (e.g., `/api/v1/doctors/{doctor}/availability`).
- Versioning strategy: **URI versioning** (`/api/v1/...`). A new major version (`/api/v2/...`) is introduced only for breaking changes; additive, backward-compatible changes ship within the existing version.

---

## Authentication & Authorization

- **Web routes (Inertia):** session-based authentication via Laravel's default cookie/session guard, protected by the `auth` and `verified` middleware where applicable.
- **JSON API routes:** authenticated via **Laravel Sanctum** (session-based for first-party SPA usage; token-based for future mobile/third-party consumption).
- **Authorization:** enforced via **Spatie Laravel Permission** role/permission checks combined with Laravel **Policies** at the model level — every endpoint below specifies the minimum role required, and object-level checks (e.g., "only the assigned doctor") are enforced in addition to role checks, not instead of them.

### Role Access Legend

| Symbol | Meaning |
|---|---|
| 🌐 | Public — no authentication required |
| 👤 | Patient |
| 🩺 | Doctor |
| 🛡️ | Administrator |
| 🔒 | Any authenticated user (role-agnostic, object-level scoping applies) |

### Standard Auth Headers (JSON endpoints)

```
Authorization: Bearer {token}      // for token-based (future mobile) consumption
X-XSRF-TOKEN: {csrf_token}         // for session-based SPA/browser consumption
Accept: application/json
Content-Type: application/json
```

---

## Request & Response Conventions

- All request/response bodies use **JSON**, `snake_case` keys, matching Laravel/Eloquent conventions.
- All timestamps are returned in **ISO 8601 UTC** (e.g., `2026-08-12T09:30:00Z`); presentation-layer timezone conversion happens client-side.
- All monetary values are returned as strings with two decimal places (e.g., `"150.00"`) to avoid floating-point precision issues, alongside an ISO currency code where relevant.
- Every resource response includes `id`, `created_at`, and `updated_at` at minimum.
- List endpoints return data wrapped in a consistent envelope (see [Pagination](#pagination-filtering--sorting)).
- Single-resource endpoints return the resource wrapped in a `data` key:

```json
{
  "data": {
    "id": 42,
    "appointment_code": "APT-000123",
    "status": "confirmed"
  }
}
```

---

## Error Handling

All error responses follow a consistent shape:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "appointment_date": ["The appointment date must be a date after today."],
    "doctor_id": ["The selected doctor is invalid or inactive."]
  }
}
```

- `message` — a human-readable summary suitable for a generic fallback display.
- `errors` — present only on validation errors (HTTP 422); an object of field names to arrays of error messages, matching Laravel's default Form Request validation error format.
- Errors that are not field-specific (e.g., a booking conflict detected at submission time) return a `message`-only body with an appropriate status code (e.g., `409 Conflict`) and no `errors` object.
- Authorization failures return `403` with `{"message": "This action is unauthorized."}` and never leak whether a resource exists if the requester has no visibility into it at all (returns `404` instead of `403` where existence itself is sensitive, e.g., another patient's appointment).

---

## Rate Limiting

| Endpoint Group | Limit |
|---|---|
| Authentication (`/login`, `/register`, `/forgot-password`) | 5 requests / minute / IP |
| Search & availability endpoints | 60 requests / minute / user |
| General authenticated API | 120 requests / minute / user |
| Webhook receivers | Not rate-limited by application (validated by signature instead) |

Rate-limited responses return `429 Too Many Requests` with a `Retry-After` header.

---

## Pagination, Filtering & Sorting

List endpoints share a consistent query interface:

| Query Param | Description |
|---|---|
| `page` | Page number (default `1`) |
| `per_page` | Items per page (default `15`, max `100`) |
| `sort` | Field to sort by, prefixed with `-` for descending (e.g., `sort=-created_at`) |
| `filter[field]` | Field-scoped filters (e.g., `filter[status]=confirmed`) |
| `search` | Free-text search, scoped per endpoint's supported fields |

**Standard paginated response envelope:**

```json
{
  "data": [ { "id": 1, "...": "..." }, { "id": 2, "...": "..." } ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 47,
    "last_page": 4
  },
  "links": {
    "first": "/api/v1/appointments?page=1",
    "last": "/api/v1/appointments?page=4",
    "prev": null,
    "next": "/api/v1/appointments?page=2"
  }
}
```

---

## Endpoints — Authentication Module

### `POST /register`
🌐 Register a new Patient account.

**Request Body**
```json
{
  "name": "Rafiq Ahmed",
  "email": "rafiq@example.com",
  "password": "SecurePass123",
  "password_confirmation": "SecurePass123"
}
```

**Response `201 Created`**
```json
{
  "data": {
    "id": 101,
    "name": "Rafiq Ahmed",
    "email": "rafiq@example.com",
    "role": "patient",
    "email_verified_at": null
  },
  "message": "Registration successful. Please verify your email."
}
```

**Errors:** `422` (validation — duplicate email, weak password, mismatched confirmation).
**Business Rules:** Role is always `patient` for self-registration; Doctor/Admin accounts cannot be created via this endpoint.

---

### `POST /login`
🌐 Authenticate an existing user and establish a session.

**Request Body**
```json
{ "email": "rafiq@example.com", "password": "SecurePass123", "remember": true }
```

**Response `200 OK`**
```json
{ "data": { "id": 101, "name": "Rafiq Ahmed", "role": "patient" }, "redirect": "/dashboard" }
```

**Errors:** `401` (invalid credentials), `403` (account deactivated), `429` (rate-limited after repeated failures).

---

### `POST /logout`
🔒 Terminate the current session.

**Response `204 No Content`**

---

### `POST /email/verification-notification`
🔒 Resend the email verification link.

**Response `200 OK`** — `{"message": "Verification link sent."}`
**Errors:** `429` (throttled to prevent spam resends).

---

### `GET /email/verify/{id}/{hash}`
🔒 Verify email via signed URL (Laravel Breeze convention).

**Response:** Redirects to dashboard with a success flash message. **Errors:** `403` (invalid/expired signature).

---

### `POST /forgot-password`
🌐 Request a password reset link.

**Request Body:** `{ "email": "rafiq@example.com" }`
**Response `200 OK`:** `{"message": "Password reset link sent if the account exists."}` — deliberately non-revealing of account existence.

---

### `POST /reset-password`
🌐 Reset password using a valid token.

**Request Body**
```json
{
  "token": "abc123...",
  "email": "rafiq@example.com",
  "password": "NewSecurePass123",
  "password_confirmation": "NewSecurePass123"
}
```

**Response `200 OK`:** `{"message": "Password has been reset."}`
**Errors:** `422` (invalid/expired token, validation failures).

---

## Endpoints — Profile Module

### `GET /profile`
🔒 Retrieve the authenticated user's profile (merges `users` with role-specific extension: `patients` or `doctors`).

**Response `200 OK`**
```json
{
  "data": {
    "id": 101,
    "name": "Rafiq Ahmed",
    "email": "rafiq@example.com",
    "phone": "+8801xxxxxxxxx",
    "avatar_path": "/storage/avatars/101.jpg",
    "role": "patient",
    "patient_profile": {
      "date_of_birth": "1985-03-12",
      "gender": "male",
      "blood_group": "O+",
      "patient_code": "PT-00101"
    }
  }
}
```

### `PUT /profile`
🔒 Update the authenticated user's core profile fields.

**Request Body:** `{ "name": "...", "phone": "...", "address": "..." }`
**Response `200 OK`:** Updated resource, shape as above.
**Business Rules:** Changing `email` triggers re-verification; the new email is not active until verified.

### `PUT /profile/password`
🔒 Change password.

**Request Body:** `{ "current_password": "...", "password": "...", "password_confirmation": "..." }`
**Errors:** `422` (current password mismatch, weak new password).

### `POST /profile/avatar`
🔒 Upload/replace avatar image. **`[JSON]`** `multipart/form-data`.

**Request Body:** `avatar` (file, jpg/png/webp, max 2MB).
**Response `200 OK`:** `{ "data": { "avatar_path": "/storage/avatars/101.jpg" } }`
**Errors:** `422` (invalid file type/size).

---

## Endpoints — Departments Module

### `GET /departments`
🌐 List active departments (public directory).

**Query Params:** `search`, `sort`, `per_page`.
**Response `200 OK`:** Paginated list of department resources.
```json
{ "data": [ { "id": 3, "name": "Cardiology", "slug": "cardiology", "description": "...", "doctor_count": 8 } ] }
```

### `GET /departments/{slug}`
🌐 Retrieve a single department with its associated doctors.

**Response `200 OK`:** Department resource including a nested paginated `doctors` collection.
**Errors:** `404` (not found or inactive).

### `POST /departments`
🛡️ Create a new department.

**Request Body:** `{ "name": "Neurology", "description": "...", "icon_path": null }`
**Response `201 Created`:** New department resource.
**Errors:** `422` (duplicate name), `403` (non-admin).

### `PUT /departments/{id}`
🛡️ Update a department.

**Request Body:** Partial or full department fields.
**Response `200 OK`:** Updated resource.

### `DELETE /departments/{id}`
🛡️ Deactivate (soft-delete) a department.

**Response `200 OK`:** `{"message": "Department deactivated."}`
**Errors:** `409 Conflict` — returned instead of allowing a hard delete when active doctors are still assigned; message instructs the admin to reassign doctors first.

---

## Endpoints — Doctors Module

### `GET /doctors`
🌐 List/search doctors (public directory).

**Query Params:** `filter[department_id]`, `filter[specialization]`, `search`, `sort`, `per_page`.
**Response `200 OK`**
```json
{
  "data": [
    {
      "id": 12,
      "name": "Dr. Nadia Islam",
      "specialization": "Cardiologist",
      "department": { "id": 3, "name": "Cardiology" },
      "consultation_fee": "150.00",
      "years_of_experience": 12,
      "avatar_path": "/storage/avatars/doc12.jpg",
      "average_rating": 4.8,
      "status": "active"
    }
  ],
  "meta": { "current_page": 1, "per_page": 12, "total": 34, "last_page": 3 }
}
```

### `GET /doctors/{id}`
🌐 Retrieve a single doctor's public profile.

**Response `200 OK`:** Doctor resource including `bio`, `qualifications`, `department`, secondary `departments[]`, `average_rating`, and a short `upcoming_availability_preview` array (next 5 open slots).
**Errors:** `404`.

### `POST /doctors`
🛡️ Onboard a new doctor (creates linked `users` + `doctors` rows).

**Request Body**
```json
{
  "name": "Dr. Imran Kabir",
  "email": "imran.kabir@hams.app",
  "department_id": 3,
  "specialization": "Interventional Cardiology",
  "license_number": "MED-2024-0091",
  "consultation_fee": "180.00",
  "qualifications": "MBBS, MD (Cardiology)"
}
```

**Response `201 Created`:** New doctor resource; triggers an account-setup email to the doctor.
**Errors:** `422` (duplicate license number/email), `403` (non-admin).

### `PUT /doctors/{id}`
🛡️ / 🩺 (own profile only, restricted fields) Update a doctor profile.

**Request Body:** Full/partial doctor fields. Doctors may update `bio`, `qualifications`, `avatar_path`; only Admins may update `department_id`, `status`, `license_number`.
**Response `200 OK`:** Updated resource.
**Errors:** `403` (doctor attempting to edit a restricted field).

### `PATCH /doctors/{id}/status`
🛡️ Change a doctor's status (`active`, `on_leave`, `suspended`, `inactive`).

**Request Body:** `{ "status": "on_leave", "reason": "Medical leave through Aug 20" }`
**Response `200 OK`:** Updated resource.
**Business Rules:** Setting `status != active` blocks new bookings immediately but does not auto-cancel existing confirmed appointments; those are flagged for Admin review.

### `DELETE /doctors/{id}`
🛡️ Deactivate (soft-delete) a doctor.

**Response `200 OK`**
**Errors:** `409 Conflict` if the doctor has future confirmed appointments not yet reassigned.

---

## Endpoints — Doctor Schedules Module

### `GET /doctors/{id}/schedule`
🔒 Retrieve a doctor's recurring weekly schedule and active exceptions.

**Response `200 OK`**
```json
{
  "data": {
    "weekly_template": [
      { "day_of_week": 1, "start_time": "09:00", "end_time": "17:00", "slot_duration_minutes": 20 }
    ],
    "exceptions": [
      { "exception_date": "2026-08-20", "type": "unavailable", "reason": "Conference" }
    ]
  }
}
```

### `PUT /doctors/{id}/schedule`
🩺 (own) / 🛡️ Replace the weekly availability template.

**Request Body:** Array of `{ day_of_week, start_time, end_time, slot_duration_minutes }`.
**Response `200 OK`:** Updated schedule.
**Errors:** `422` (overlapping ranges, `end_time <= start_time`).

### `POST /doctors/{id}/schedule/exceptions`
🩺 (own) / 🛡️ Add a leave/extra-hours exception.

**Request Body:** `{ "exception_date": "2026-08-20", "type": "unavailable", "reason": "Conference" }`
**Response `201 Created`:** New exception resource.
**Business Rules:** If existing confirmed appointments fall on `exception_date`, response includes `"affected_appointments": [...]` for the caller to act on (notify/reassign).

### `DELETE /doctors/{id}/schedule/exceptions/{exceptionId}`
🩺 (own) / 🛡️ Remove a schedule exception.

**Response `200 OK`**

### `GET /doctors/{id}/availability` `[JSON]`
🌐 Compute real-time bookable slots for a given date range (used by the booking UI).

**Query Params:** `from` (date, required), `to` (date, required, max 14-day range).
**Response `200 OK`**
```json
{
  "data": {
    "doctor_id": 12,
    "slots": [
      { "date": "2026-08-12", "times": ["09:00", "09:20", "09:40", "10:20"] },
      { "date": "2026-08-13", "times": [] }
    ]
  }
}
```
**Notes:** Slots already booked, within an `unavailable` exception, or outside the recurring template are excluded — never returned as "available then rejected," to avoid a misleading UX.

---

## Endpoints — Patients Module (Admin)

### `GET /admin/patients`
🛡️ List/search all patients.

**Query Params:** `search`, `filter[is_active]`, `sort`, `per_page`.
**Response `200 OK`:** Paginated patient resources (name, email, patient_code, appointment_count, is_active).

### `GET /admin/patients/{id}`
🛡️ Retrieve full patient detail, including appointment/medical summary counts (not full clinical detail, which remains behind the Medical Records endpoints and their own authorization).

**Response `200 OK`**

### `PUT /admin/patients/{id}`
🛡️ Update a patient's account-level fields (e.g., contact info corrections, `is_active` status).

**Request Body:** `{ "is_active": false }`
**Response `200 OK`**

---

## Endpoints — Appointments Module

### `GET /appointments`
🔒 List appointments scoped to the authenticated user's role:
- 👤 Patient → only their own appointments.
- 🩺 Doctor → only appointments assigned to them.
- 🛡️ Admin → all appointments hospital-wide.

**Query Params:** `filter[status]`, `filter[doctor_id]` (Admin only), `filter[department_id]` (Admin only), `filter[date_from]`, `filter[date_to]`, `sort`, `per_page`.

**Response `200 OK`**
```json
{
  "data": [
    {
      "id": 501,
      "appointment_code": "APT-000501",
      "status": "confirmed",
      "appointment_date": "2026-08-12",
      "start_time": "09:00",
      "end_time": "09:20",
      "reason": "Follow-up consultation",
      "consultation_fee_snapshot": "150.00",
      "doctor": { "id": 12, "name": "Dr. Nadia Islam", "specialization": "Cardiologist" },
      "patient": { "id": 101, "name": "Rafiq Ahmed", "patient_code": "PT-00101" },
      "department": { "id": 3, "name": "Cardiology" }
    }
  ],
  "meta": { "current_page": 1, "per_page": 15, "total": 6, "last_page": 1 }
}
```
**Note:** The `patient` object is included in Doctor/Admin views; Patient-facing responses omit redundant self-reference and instead expand `doctor` fully.

### `POST /appointments`
👤 Book a new appointment.

**Request Body**
```json
{
  "doctor_id": 12,
  "appointment_date": "2026-08-12",
  "start_time": "09:00",
  "reason": "Persistent chest discomfort over the past week."
}
```

**Response `201 Created`:** New appointment resource (status `pending` or `confirmed` depending on hospital policy).
**Errors:**
- `422` — validation (missing reason, invalid date/time format).
- `409 Conflict` — `{"message": "This slot is no longer available. Please choose another time."}` when the slot was booked by another patient in the intervening moment.
- `403` — unverified email attempting to book.

**Business Rules:** `department_id` and `consultation_fee_snapshot` are derived server-side from the doctor's current record at booking time and stored on the appointment — never trusted from client input.

### `GET /appointments/{id}`
🔒 Retrieve a single appointment (object-level scoped to participant or Admin).

**Response `200 OK`:** Full appointment resource, including nested `medical_record` and `prescriptions` summary if present and if the requester is authorized to view them.
**Errors:** `404` returned (not `403`) if the requester has no relationship to the appointment, to avoid confirming its existence.

### `PATCH /appointments/{id}/status`
🩺 (assigned doctor) / 🛡️ Update appointment status.

**Request Body:** `{ "status": "completed", "notes": "Consultation completed on time." }`
**Response `200 OK`:** Updated resource.
**Business Rules:** Status transitions follow a strict state machine (`pending → confirmed → completed`, `confirmed → cancelled`, `confirmed → no_show`, etc.); invalid transitions return `422` with a clear message (e.g., "Cannot mark a cancelled appointment as completed.").

### `POST /appointments/{id}/cancel`
👤 (own, within policy window) / 🩺 / 🛡️ Cancel an appointment.

**Request Body:** `{ "cancellation_reason": "Schedule conflict." }`
**Response `200 OK`:** Updated resource with `status: "cancelled"`, `cancelled_at`, `cancelled_by`.
**Errors:** `403` — `{"message": "Cancellation window has passed. Please contact the hospital directly."}` when outside policy.

### `POST /appointments/{id}/reschedule`
👤 (own, within policy window) / 🛡️ Reschedule to a new slot.

**Request Body:** `{ "appointment_date": "2026-08-14", "start_time": "10:00" }`
**Response `200 OK`:** The original appointment is marked `rescheduled`; a **new** appointment resource is created and returned, linked via `rescheduled_from_id`.
**Errors:** `409 Conflict` (new slot unavailable), `403` (outside policy window).

---

## Endpoints — Medical Records Module

### `GET /appointments/{id}/medical-record`
🔒 (patient owner / assigned doctor / admin) Retrieve the medical record for an appointment.

**Response `200 OK`**
```json
{
  "data": {
    "id": 301,
    "appointment_id": 501,
    "diagnosis": "Mild hypertension, recommend lifestyle modification.",
    "symptoms": "Occasional chest tightness, elevated BP readings.",
    "vitals": { "blood_pressure": "138/89", "pulse": 76, "temperature_c": 36.8, "weight_kg": 82 },
    "doctor_notes": "Follow up in 4 weeks; monitor BP daily.",
    "version": 1,
    "created_at": "2026-08-12T09:25:00Z"
  }
}
```
**Errors:** `404` (no record yet, or requester unauthorized — existence not confirmed to unauthorized requesters).

### `POST /appointments/{id}/medical-record`
🩺 (assigned doctor only) Create a medical record for a completed appointment.

**Request Body:** `{ "diagnosis": "...", "symptoms": "...", "vitals": {...}, "doctor_notes": "..." }`
**Response `201 Created`:** New medical record resource.
**Errors:** `422` (missing required `diagnosis`), `409` (appointment not yet `completed`, or a record already exists — use the amend endpoint instead).

### `PUT /appointments/{id}/medical-record`
🩺 (assigned doctor only) Amend an existing medical record.

**Request Body:** Updated fields.
**Response `200 OK`:** Updated resource with incremented `version`; the amendment is also written to the audit log.

---

## Endpoints — Prescriptions Module

### `GET /prescriptions`
🔒 List prescriptions scoped to the authenticated user (Patient: own; Doctor: issued by them; Admin: all).

**Query Params:** `filter[patient_id]` (Doctor/Admin only), `filter[appointment_id]`, `sort`, `per_page`.
**Response `200 OK`:** Paginated prescription resources with nested `items[]`.

### `GET /prescriptions/{id}`
🔒 Retrieve a single prescription, including medication line items.

**Response `200 OK`**
```json
{
  "data": {
    "id": 88,
    "prescription_code": "RX-000088",
    "appointment_id": 501,
    "issued_at": "2026-08-12T09:30:00Z",
    "special_instructions": "Take medication with food.",
    "items": [
      { "medication_name": "Amlodipine", "dosage": "5mg", "frequency": "Once daily", "duration": "30 days" }
    ],
    "supersedes_id": null
  }
}
```

### `POST /appointments/{id}/prescriptions`
🩺 (assigned doctor only) Issue a new prescription for an appointment.

**Request Body**
```json
{
  "special_instructions": "Take medication with food.",
  "items": [
    { "medication_name": "Amlodipine", "dosage": "5mg", "frequency": "Once daily", "duration": "30 days" }
  ]
}
```

**Response `201 Created`:** New prescription resource.
**Errors:** `422` (empty `items` array, missing dosage/frequency), `409` (no medical record exists yet for this appointment — must be created first).
**Business Rules:** Prescriptions are immutable; there is no `PUT`/`PATCH` endpoint. Corrections use the supersede endpoint below.

### `POST /prescriptions/{id}/supersede`
🩺 (original issuing doctor only) Issue a corrected prescription that supersedes a previous one.

**Request Body:** Same shape as creation.
**Response `201 Created`:** New prescription resource with `supersedes_id` set to the original; the original remains retrievable but is flagged `is_superseded: true` in list views.

### `GET /prescriptions/{id}/download`
🔒 Download a PDF rendition of the prescription.

**Response `200 OK`:** Binary PDF stream (`Content-Type: application/pdf`).

---

## Endpoints — Payments Module (Phase 3)

### `POST /appointments/{id}/payment-intent`
👤 Create a Stripe Payment Intent for an appointment's consultation fee.

**Response `201 Created`**
```json
{ "data": { "payment_id": 55, "client_secret": "pi_xxx_secret_xxx", "amount": "150.00", "currency": "USD" } }
```
**Business Rules:** `amount` is always derived from `appointments.consultation_fee_snapshot`, never client-supplied.

### `GET /payments/{id}`
🔒 Retrieve payment status for an appointment.

**Response `200 OK`:** `{ "data": { "id": 55, "status": "paid", "amount": "150.00", "paid_at": "2026-08-10T14:02:00Z" } }`

### `GET /admin/payments`
🛡️ List/search all payment transactions.

**Query Params:** `filter[status]`, `filter[date_from]`, `filter[date_to]`, `sort`, `per_page`.

### `POST /admin/payments/{id}/refund`
🛡️ Issue a refund for a payment.

**Request Body:** `{ "reason": "Appointment cancelled by hospital." }`
**Response `200 OK`:** Updated payment resource; a corresponding `transactions` ledger row is created.
**Errors:** `409` (payment not in a refundable state).

---

## Endpoints — Notifications Module

### `GET /notifications`
🔒 List the authenticated user's notifications.

**Query Params:** `filter[read]` (`true`/`false`), `per_page`.
**Response `200 OK`:** Paginated notifications with `data` (payload), `read_at`, `created_at`.

### `PATCH /notifications/{id}/read`
🔒 Mark a single notification as read.

**Response `200 OK`**

### `POST /notifications/mark-all-read`
🔒 Mark all notifications as read.

**Response `200 OK`:** `{"message": "All notifications marked as read."}`

### `POST /admin/announcements`
🛡️ Broadcast a system-wide or targeted announcement.

**Request Body:** `{ "audience": "all_patients", "title": "Holiday Schedule Notice", "message": "..." }`
**Response `202 Accepted`:** `{"message": "Announcement queued for delivery."}` — dispatched asynchronously via queue, not synchronously to avoid blocking the request on large audiences.

---

## Endpoints — Reviews Module

### `GET /doctors/{id}/reviews`
🌐 List visible reviews for a doctor.

**Query Params:** `sort`, `per_page`.
**Response `200 OK`:** Paginated review resources (`rating`, `comment`, `patient_display_name` — first name + last initial only, for privacy).

### `POST /appointments/{id}/review`
👤 (own, appointment must be `completed`) Submit a review.

**Request Body:** `{ "rating": 5, "comment": "Excellent, thorough consultation." }`
**Response `201 Created`:** New review resource.
**Errors:** `409` (appointment not completed, or a review already exists for it — one review per appointment).

### `PATCH /admin/reviews/{id}/visibility`
🛡️ Hide/unhide a review without deleting it.

**Request Body:** `{ "is_visible": false }`
**Response `200 OK`**

---

## Endpoints — Reports & Analytics Module (Admin)

### `GET /admin/reports/appointments`
🛡️ Aggregate appointment volume report.

**Query Params:** `date_from`, `date_to` (required, max 2-year range), `group_by` (`day`|`week`|`month`), `filter[department_id]`, `filter[doctor_id]`.
**Response `200 OK`**
```json
{ "data": { "series": [ { "period": "2026-08-01", "total": 42, "completed": 38, "cancelled": 3, "no_show": 1 } ] } }
```

### `GET /admin/reports/doctors/{id}/performance`
🛡️ / 🩺 (own only) Doctor-level performance report.

**Response `200 OK`:** Completion rate, no-show rate, average rating, patient count over the requested range.

### `GET /admin/reports/revenue` *(Phase 3)*
🛡️ Revenue report tied to `payments`/`transactions`.

**Query Params:** `date_from`, `date_to`, `group_by`.

### `GET /admin/reports/export`
🛡️ Export a report as CSV or PDF.

**Query Params:** `report` (e.g., `appointments`), `format` (`csv`|`pdf`), plus the relevant report's filters.
**Response `202 Accepted`:** `{"message": "Export queued.", "export_id": "exp_9182"}` — large exports processed asynchronously.

### `GET /admin/exports/{export_id}`
🛡️ Poll export status / retrieve download link once ready.

**Response `200 OK`:** `{ "data": { "status": "ready", "download_url": "/storage/exports/exp_9182.csv" } }`

---

## Endpoints — Settings Module (Admin)

### `GET /admin/settings`
🛡️ Retrieve all hospital-wide configuration key-value pairs, grouped by category.

**Response `200 OK`**
```json
{
  "data": {
    "general": { "hospital_name": "HAMS General Hospital", "contact_email": "info@hams.app" },
    "scheduling": { "default_slot_duration_minutes": 20, "cancellation_cutoff_hours": 2 },
    "notifications": { "reminder_hours_before": [24, 1] }
  }
}
```

### `PUT /admin/settings`
🛡️ Update one or more settings.

**Request Body:** `{ "scheduling": { "cancellation_cutoff_hours": 4 } }`
**Response `200 OK`:** Updated settings object.
**Business Rules:** Changes are non-retroactive — do not alter already-computed appointment rows.

---

## Endpoints — Roles & Permissions Module (Admin)

### `GET /admin/users`
🛡️ List all users across roles, with filters.

**Query Params:** `filter[role]`, `filter[is_active]`, `search`, `sort`, `per_page`.

### `PATCH /admin/users/{id}/role`
🛡️ Assign or change a user's role.

**Request Body:** `{ "role": "doctor" }`
**Response `200 OK`**
**Business Rules:** This action is itself written to `activity_logs` given its sensitivity.

### `PATCH /admin/users/{id}/status`
🛡️ Activate/deactivate a user account.

**Request Body:** `{ "is_active": false, "reason": "Requested by user." }`
**Response `200 OK`**

### `GET /admin/activity-logs`
🛡️ Retrieve the audit trail.

**Query Params:** `filter[causer_id]`, `filter[subject_type]`, `filter[action]`, `date_from`, `date_to`, `sort`, `per_page`.
**Response `200 OK`:** Paginated activity log entries.

---

## Endpoints — Search Module

### `GET /search/doctors` `[JSON]`
🌐 Debounced, type-ahead doctor search for the public directory and booking flow.

**Query Params:** `q` (min 2 chars), `department_id` (optional).
**Response `200 OK`:** Lightweight doctor result list (`id`, `name`, `specialization`, `avatar_path`) — deliberately minimal payload for fast autocomplete rendering.

### `GET /admin/search` `[JSON]`
🛡️ Unified admin search across patients, doctors, and appointments.

**Query Params:** `q` (min 2 chars), `scope` (`patients`|`doctors`|`appointments`|`all`).
**Response `200 OK`:** Grouped results per scope.

---

## Webhooks

### `POST /webhooks/stripe` *(Phase 3)*
🌐 (signature-verified, not user-authenticated) Receive asynchronous payment events from Stripe.

**Headers Required:** `Stripe-Signature` — verified against the webhook signing secret before any processing occurs.

**Handled Event Types:**

| Event | Action |
|---|---|
| `payment_intent.succeeded` | Marks the corresponding `payments` row `paid`, sets `paid_at`, writes a `transactions` ledger entry. |
| `payment_intent.payment_failed` | Marks `payments` row `failed`; notifies the patient to retry. |
| `charge.refunded` | Marks `payments` row `refunded`; writes a `transactions` ledger entry (`type: refund`). |

**Response `200 OK`:** `{"received": true}` — must respond quickly; all processing beyond signature verification and record lookup is deferred to a queued job to avoid Stripe's webhook timeout.
**Errors:** `400` (invalid signature) — request rejected before any data is touched.
**Idempotency:** Each event's `gateway_reference` is checked against `transactions` before processing to prevent duplicate handling on Stripe's at-least-once delivery retries.

---

## Status Code Reference

| Code | Meaning | Typical Use in HAMS |
|---|---|---|
| `200 OK` | Successful GET/PUT/PATCH/DELETE (with body) | Standard successful response |
| `201 Created` | Successful POST creating a resource | New appointment, doctor, prescription, etc. |
| `202 Accepted` | Request accepted for async processing | Report exports, bulk announcements |
| `204 No Content` | Successful action with no response body | Logout |
| `400 Bad Request` | Malformed request | Invalid webhook signature |
| `401 Unauthorized` | Missing/invalid authentication | Expired session, bad credentials |
| `403 Forbidden` | Authenticated but not permitted | Wrong role, outside cancellation window, restricted field edit |
| `404 Not Found` | Resource doesn't exist, or hidden from an unauthorized requester | Any object-scoped resource |
| `409 Conflict` | Valid request but conflicts with current state | Double-booking, refund on non-refundable payment |
| `422 Unprocessable Entity` | Validation failure | Invalid form input, business-rule validation |
| `429 Too Many Requests` | Rate limit exceeded | Login attempts, search spam |
| `500 Internal Server Error` | Unexpected server failure | Logged and alerted; generic message returned to client |

---

## Core Data Models

Full column-level definitions live in **DATABASE_DESIGN.md**; the following is a quick-reference summary of the primary API-facing resource shapes.

| Resource | Key Fields |
|---|---|
| **User** | `id`, `name`, `email`, `phone`, `avatar_path`, `role`, `is_active` |
| **Patient (extension)** | `date_of_birth`, `gender`, `blood_group`, `patient_code`, `allergies` |
| **Doctor (extension)** | `department`, `specialization`, `consultation_fee`, `status`, `license_number` |
| **Department** | `id`, `name`, `slug`, `description`, `is_active` |
| **Appointment** | `id`, `appointment_code`, `status`, `appointment_date`, `start_time`, `end_time`, `reason`, `consultation_fee_snapshot`, `doctor`, `patient`, `department` |
| **MedicalRecord** | `id`, `appointment_id`, `diagnosis`, `symptoms`, `vitals`, `doctor_notes`, `version` |
| **Prescription** | `id`, `prescription_code`, `appointment_id`, `items[]`, `supersedes_id`, `issued_at` |
| **Payment** | `id`, `appointment_id`, `amount`, `currency`, `status`, `paid_at` |
| **Notification** | `id`, `type`, `data`, `read_at`, `created_at` |
| **Review** | `id`, `appointment_id`, `rating`, `comment`, `is_visible` |

---

## Security Considerations

- **Object-level authorization on every endpoint**, not just role-level — a Patient authenticated as `patient` role must still be blocked from accessing another patient's `appointment_id` even though both are "Patients."
- **`404` over `403` for existence-sensitive resources** (as noted in Error Handling) — a Patient probing another patient's appointment ID should not learn that the ID is valid.
- **Server-derived, never client-trusted, sensitive fields** — `consultation_fee_snapshot`, `department_id` on appointments, and payment `amount` are always computed/copied server-side at the moment of the relevant action, never accepted as raw client input.
- **Signed, expiring URLs** for sensitive file downloads (medical documents, prescription PDFs) rather than permanent public paths.
- **Webhook signature verification** is mandatory and occurs before any database write — an unverified Stripe payload is rejected outright.
- **Audit logging** wraps all sensitive mutation endpoints (`role` changes, `status` changes on users/doctors, medical record amendments, refunds) as described in DATABASE_DESIGN.md's `activity_logs` table.
- **Consistent validation layering** — Laravel Form Requests provide the primary, user-facing validation; database constraints (from DATABASE_DESIGN.md) provide a secondary enforcement layer against any bypass.

---

## Versioning & Deprecation Policy

- The current and only supported version at launch is **`v1`**.
- Backward-compatible changes (new optional fields, new endpoints) ship within `v1` without a version bump.
- Breaking changes (removed fields, altered semantics, changed status codes) require a new version (`v2`) with `v1` maintained in parallel for a defined deprecation window (minimum 90 days), communicated via a `Deprecation` response header and this document's changelog.
- Inertia web routes are internal to the application and are **not** subject to this external versioning policy — only the `/api/v1/` JSON surface is treated as a versioned public contract.

---

**End of Document**
