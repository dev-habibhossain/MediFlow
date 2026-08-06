# Sitemap & Page Inventory
## Hospital Appointment Management System (HAMS)

This document lists **every page** the application needs, grouped by access area, with its **route path** and **purpose**. Use this as the master checklist for building static HTML/CSS pages first, before wiring them into the Laravel + Inertia project.

**Total Pages: 79**

| Area | Page Count |
|---|---|
| Public / Guest | 13 |
| Authentication | 6 |
| Booking Flow | 3 |
| Patient Dashboard | 15 |
| Doctor Dashboard | 12 |
| Admin Dashboard | 27 |
| System / Utility | 3 |

---

## 1. Public / Guest Pages (13)

No login required. This is the first section to build statically — it's what visitors and recruiters see first.

| # | Page Name | Path | Purpose |
|---|---|---|---|
| 1 | Homepage | `/` | Hero, featured departments, top doctors, trust stats, CTA |
| 2 | Departments Listing | `/departments` | Grid of all active departments |
| 3 | Department Detail | `/departments/{slug}` | One department + its doctors |
| 4 | Doctors Directory | `/doctors` | Searchable/filterable list of all doctors |
| 5 | Doctor Profile | `/doctors/{id}` | Doctor bio, qualifications, fee, availability preview, reviews |
| 6 | About Us | `/about` | Hospital story, mission, values |
| 7 | Contact Us | `/contact` | Contact form + hospital info/map |
| 8 | FAQ | `/faq` | Common questions |
| 9 | Privacy Policy | `/privacy-policy` | Legal |
| 10 | Terms of Service | `/terms-of-service` | Legal |
| 11 | 404 Not Found | `/404` | Broken/invalid link |
| 12 | 403 Forbidden | `/403` | Unauthorized access attempt |
| 13 | 500 Server Error | `/500` | Unexpected server failure |

---

## 2. Authentication Pages (6)

| # | Page Name | Path | Purpose |
|---|---|---|---|
| 14 | Login | `/login` | Existing user sign-in |
| 15 | Register | `/register` | New Patient sign-up |
| 16 | Forgot Password | `/forgot-password` | Request reset link |
| 17 | Reset Password | `/reset-password/{token}` | Set new password |
| 18 | Check Your Email | `/verify-email` | "We sent a verification link" screen |
| 19 | Email Verified | `/email/verified` | Confirmation landing after clicking the email link |

---

## 3. Booking Flow (3)

Patient-only, but distinct enough from the dashboard to design as its own focused flow (minimal nav, progress indicator).

| # | Page Name | Path | Purpose |
|---|---|---|---|
| 20 | Book Appointment — Select Slot | `/appointments/book/{doctor}` | Calendar + time slot picker |
| 21 | Book Appointment — Review & Confirm | `/appointments/book/{doctor}/confirm` | Summary before submitting |
| 22 | Booking Confirmed | `/appointments/{id}/confirmation` | Success screen with appointment code |

---

## 4. Patient Dashboard Pages (15)

Prefix: `/patient/*` (or `/dashboard/*` — pick one convention and use it consistently; examples below use `/patient/*`)

| # | Page Name | Path | Purpose |
|---|---|---|---|
| 23 | Patient Dashboard Home | `/patient/dashboard` | Upcoming appointments, quick actions, notifications summary |
| 24 | My Appointments | `/patient/appointments` | Upcoming/Past tabs, filterable list |
| 25 | Appointment Detail | `/patient/appointments/{id}` | Full detail + actions (cancel/reschedule) |
| 26 | Reschedule Appointment | `/patient/appointments/{id}/reschedule` | Pick a new slot |
| 27 | Medical History | `/patient/medical-records` | Chronological list of past records |
| 28 | Medical Record Detail | `/patient/medical-records/{id}` | Diagnosis, notes, vitals (read-only) |
| 29 | Prescriptions List | `/patient/prescriptions` | All prescriptions received |
| 30 | Prescription Detail | `/patient/prescriptions/{id}` | Medications, instructions, download PDF |
| 31 | Leave a Review | `/patient/appointments/{id}/review` | Rate a completed appointment |
| 32 | Notifications | `/patient/notifications` | Full notification history |
| 33 | Profile Settings — Personal Info | `/patient/settings/profile` | Edit name, DOB, contact, address |
| 34 | Profile Settings — Security | `/patient/settings/security` | Change password |
| 35 | Profile Settings — Notifications | `/patient/settings/notifications` | Notification preferences |
| 36 | Payment History *(Phase 3)* | `/patient/payments` | Past payments/receipts |
| 37 | Payment Checkout *(Phase 3)* | `/patient/appointments/{id}/pay` | Stripe payment for a consultation fee |

---

## 5. Doctor Dashboard Pages (12)

Prefix: `/doctor/*`

| # | Page Name | Path | Purpose |
|---|---|---|---|
| 38 | Doctor Dashboard Home | `/doctor/dashboard` | Today's schedule, stats widgets |
| 39 | My Appointments | `/doctor/appointments` | Full schedule, filterable by date/status |
| 40 | Appointment Detail | `/doctor/appointments/{id}` | Patient info, reason, status update actions |
| 41 | Patient History (scoped) | `/doctor/patients/{id}` | History of a specific patient they've treated |
| 42 | Create Medical Record | `/doctor/appointments/{id}/medical-record/create` | Diagnosis, symptoms, vitals, notes form |
| 43 | Edit/Amend Medical Record | `/doctor/appointments/{id}/medical-record/edit` | Amend an existing record |
| 44 | Issue Prescription | `/doctor/appointments/{id}/prescriptions/create` | Add medication line items |
| 45 | Correct Prescription (Supersede) | `/doctor/prescriptions/{id}/supersede` | Issue a corrected version |
| 46 | My Schedule (Availability Editor) | `/doctor/schedule` | Weekly recurring template editor |
| 47 | Schedule Exceptions | `/doctor/schedule/exceptions` | Leave days / extra hours list + add form |
| 48 | My Performance | `/doctor/performance` | Charts: completion rate, no-show rate, ratings |
| 49 | Profile Settings | `/doctor/settings/profile` | Bio, qualifications, avatar |

---

## 6. Admin Dashboard Pages (27)

Prefix: `/admin/*`

### Overview
| # | Page Name | Path | Purpose |
|---|---|---|---|
| 50 | Admin Dashboard Home | `/admin/dashboard` | System-wide KPIs, activity feed, trend chart |

### Doctor Management
| # | Page Name | Path | Purpose |
|---|---|---|---|
| 51 | Doctors List | `/admin/doctors` | All doctors, searchable/filterable |
| 52 | Add Doctor | `/admin/doctors/create` | Onboarding form |
| 53 | Doctor Detail / Edit | `/admin/doctors/{id}` | View + edit profile, change status |
| 54 | Doctor's Schedule (Admin View) | `/admin/doctors/{id}/schedule` | Admin-side schedule override |

### Patient Management
| # | Page Name | Path | Purpose |
|---|---|---|---|
| 55 | Patients List | `/admin/patients` | All patients, searchable |
| 56 | Patient Detail | `/admin/patients/{id}` | Account info + appointment summary (not full clinical detail) |

### Department Management
| # | Page Name | Path | Purpose |
|---|---|---|---|
| 57 | Departments List | `/admin/departments` | All departments |
| 58 | Add Department | `/admin/departments/create` | New department form |
| 59 | Department Detail / Edit | `/admin/departments/{id}` | Edit + view assigned doctors |

### Appointment Oversight
| # | Page Name | Path | Purpose |
|---|---|---|---|
| 60 | All Appointments | `/admin/appointments` | Hospital-wide filterable table |
| 61 | Appointment Detail (Admin) | `/admin/appointments/{id}` | Full detail + reassign/override actions |

### Reports & Analytics
| # | Page Name | Path | Purpose |
|---|---|---|---|
| 62 | Reports Overview | `/admin/reports` | Report type selector |
| 63 | Appointment Volume Report | `/admin/reports/appointments` | Charts + export |
| 64 | Doctor Performance Report | `/admin/reports/doctors` | Per-doctor comparison |
| 65 | Revenue Report *(Phase 3)* | `/admin/reports/revenue` | Financial summary |
| 66 | Export Status | `/admin/exports/{id}` | Download-when-ready screen |

### User & Access Management
| # | Page Name | Path | Purpose |
|---|---|---|---|
| 67 | All Users | `/admin/users` | Every account across roles |
| 68 | User Detail | `/admin/users/{id}` | Role change, deactivate, reset password |
| 69 | Roles & Permissions | `/admin/roles` | Manage Spatie roles/permissions |
| 70 | Activity Logs | `/admin/activity-logs` | Audit trail, filterable |

### Reviews & Announcements
| # | Page Name | Path | Purpose |
|---|---|---|---|
| 71 | Reviews Moderation | `/admin/reviews` | Show/hide submitted reviews |
| 72 | Announcements | `/admin/announcements` | Send broadcast notifications |

### Payments *(Phase 3)*
| # | Page Name | Path | Purpose |
|---|---|---|---|
| 73 | Transactions List | `/admin/payments` | All payments, filterable |
| 74 | Transaction Detail | `/admin/payments/{id}` | Detail + refund action |

### Settings
| # | Page Name | Path | Purpose |
|---|---|---|---|
| 75 | Settings — General | `/admin/settings/general` | Hospital name, logo, contact info |
| 76 | Settings — Scheduling | `/admin/settings/scheduling` | Slot duration, cancellation policy |
| 77 | Settings — Notification Templates | `/admin/settings/notifications` | Email/SMS template editor |
| 78 | Settings — Holidays | `/admin/settings/holidays` | Hospital-wide holiday calendar |

---

## 7. System / Utility Pages (3)

| # | Page Name | Path | Purpose |
|---|---|---|---|
| 79 | Search Results | `/search?q=` | Standalone results page for header search bar |
| — | Maintenance Mode | `/maintenance` | Shown during deployments (Laravel default, still worth a custom static version) |
| — | Notification Bell Dropdown | *(component, not a page)* | Quick-view panel from the top nav — no separate route needed |

---

## Recommended Static Build Order

Since you're building static pages first, build in this order — it front-loads the pages that establish your design system, then reuses those patterns everywhere else:

1. **Design system foundation:** Homepage, Login, Register (establishes typography, colors, buttons, forms, nav/footer shell)
2. **Public pages:** Departments Listing, Doctor Directory, Doctor Profile, About, Contact, FAQ
3. **Dashboard shell:** One dashboard layout (sidebar + top nav) built once, reused across Patient/Doctor/Admin
4. **Patient flow:** Patient Dashboard → Booking (3 pages) → My Appointments → Appointment Detail → Medical History → Prescriptions → Settings
5. **Doctor flow:** Doctor Dashboard → My Appointments → Appointment Detail → Medical Record form → Prescription form → My Schedule
6. **Admin flow:** Admin Dashboard → Doctors CRUD → Departments CRUD → Appointments table → Reports → Users → Settings
7. **Error/utility pages:** 404, 403, 500 (quick, reuse the public layout)

This order means every later page is just reusing components (cards, tables, forms, modals) you already built in step 1–3 — you won't be inventing new UI patterns past the first dashboard page.

---

**End of Document**
