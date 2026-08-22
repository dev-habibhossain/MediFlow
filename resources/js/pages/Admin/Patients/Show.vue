<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

const props = withDefaults(
    defineProps<{
        patient?: {
            id: number
            code: string
            name: string
            initials: string
            email: string
            phone: string
            dob: string
            age: number
            gender: string
            blood_group: string
            allergies: string
            registered_at: string
            status: string
            total_spent: string
            invoices_count: number
            appointments: Array<{
                id: string
                date: string
                doctor: string
                department: string
                status: string
            }>
        }
    }>(),
    {
        patient: () => ({
            id: 9021,
            code: 'MDF-9021',
            name: 'Habib Hossain',
            initials: 'HH',
            email: 'habib@example.com',
            phone: '(555) 340-2199',
            dob: 'April 12, 1998',
            age: 28,
            gender: 'Male',
            blood_group: 'O+',
            allergies: 'Penicillin (Mild)',
            registered_at: 'Nov 14, 2024',
            status: 'active',
            total_spent: '$1,680.00',
            invoices_count: 14,
            appointments: [
                {
                    id: 'MDF-101',
                    date: 'Aug 7, 2026 · 10:00 AM',
                    doctor: 'Dr. Sarah Jenkins',
                    department: 'Cardiology',
                    status: 'Confirmed Visit',
                },
                {
                    id: 'MDF-881',
                    date: 'July 14, 2026 · 02:30 PM',
                    doctor: 'Dr. Sarah Jenkins',
                    department: 'Cardiology',
                    status: 'Completed',
                },
                {
                    id: 'MDF-720',
                    date: 'May 10, 2026 · 11:00 AM',
                    doctor: 'Dr. Emily Watson',
                    department: 'Pediatrics',
                    status: 'Completed',
                },
            ],
        }),
    }
)
</script>

<template>
    <Head :title="`Patient ${props.patient.name} — Admin Portal`" />

    <!-- BACK BUTTON -->
    <div class="mb-6">
        <Link href="/admin/patients" class="back-btn">← Back to Patients Registry</Link>
    </div>

    <!-- PATIENT HEADER BANNER -->
    <div class="patient-header-card mb-6">
        <div class="patient-info-left">
            <div class="patient-avatar-lg">{{ props.patient.initials }}</div>
            <div class="patient-meta-lg">
                <h1>{{ props.patient.name }}</h1>
                <p>Patient Account ID: <strong>#{{ props.patient.code }}</strong> · Registered {{ props.patient.registered_at }}</p>
                <span class="status-badge status-active">● Active Account</span>
            </div>
        </div>

        <div>
            <Link v-if="props.patient.user_id" :href="`/admin/doctors/create?user_id=${props.patient.user_id}`" class="btn-promote-doc">
                + Promote to Doctor
            </Link>
        </div>
    </div>

    <!-- MAIN SPLIT GRID -->
    <div class="detail-grid">
        <!-- LEFT COLUMN -->
        <div class="main-col">
            <!-- ACCOUNT DETAILS BOX -->
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" />
                    </svg>
                    Account Demographic Information
                </div>

                <div class="info-pairs-grid">
                    <div class="info-box">
                        <label>Email Address</label>
                        <span>{{ props.patient.email }}</span>
                        <small>Verified Account</small>
                    </div>

                    <div class="info-box">
                        <label>Phone Number</label>
                        <span>{{ props.patient.phone }}</span>
                        <small>Primary Contact</small>
                    </div>

                    <div class="info-box">
                        <label>Date of Birth & Gender</label>
                        <span>{{ props.patient.dob }} ({{ props.patient.age }} Yrs)</span>
                        <small>{{ props.patient.gender }}</small>
                    </div>

                    <div class="info-box">
                        <label>Blood Group & Allergies</label>
                        <span style="color: #15803D;">{{ props.patient.blood_group }} Positive</span>
                        <small>{{ props.patient.allergies }}</small>
                    </div>
                </div>
            </div>

            <!-- APPOINTMENT SUMMARY TABLE -->
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                    Appointment History Summary
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Ref ID</th>
                                <th>Date & Time</th>
                                <th>Assigned Doctor</th>
                                <th>Department</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="app in props.patient.appointments" :key="app.id">
                                <td style="font-family: var(--font-mono); font-weight: 700;">#{{ app.id }}</td>
                                <td>{{ app.date }}</td>
                                <td>{{ app.doctor }}</td>
                                <td>{{ app.department }}</td>
                                <td>
                                    <span class="badge-status" :class="app.status === 'Confirmed Visit' ? 'badge-confirmed' : 'badge-completed'">
                                        {{ app.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="sidebar-col">
            <div class="action-card">
                <h4>Administrative Actions</h4>
                <p>Manage patient account security and portal access privileges.</p>

                <button type="button" class="btn btn-secondary-action">
                    Send Password Reset Link
                </button>

                <button type="button" class="btn btn-outline-danger">
                    Deactivate Account
                </button>
            </div>

            <div class="action-card">
                <h4>Billing Summary</h4>
                <p>Total consultation payments processed:</p>
                <div class="font-mono text-2xl font-extrabold text-[var(--forest)]">
                    {{ props.patient.total_spent }}
                </div>
                <span class="text-xs text-[var(--ink-muted)]">{{ props.patient.invoices_count }} Invoices Settled</span>
            </div>
        </div>
    </div>
</template>

<style>
.back-btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 6px 14px; border-radius: 999px; transition: all 150ms ease; text-decoration: none; }
.back-btn:hover { background: var(--card); border-color: var(--forest); }

.patient-header-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px 32px; box-shadow: var(--shadow-card); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; }
.patient-info-left { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
.patient-avatar-lg { width: 72px; height: 72px; border-radius: 50%; background: var(--lime); color: var(--lime-text); font-weight: 800; font-size: 24px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-sm); }
.patient-meta-lg h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin-bottom: 2px; }
.patient-meta-lg p { font-size: 13.5px; color: var(--ink-muted); font-weight: 500; }

.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 999px; font-size: 12.5px; font-weight: 700; margin-top: 8px; }
.status-active { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

.detail-grid { display: grid; grid-template-columns: 1fr 340px; gap: 28px; align-items: start; }
@media (max-width: 1024px) { .detail-grid { grid-template-columns: 1fr; } }

.main-col { display: flex; flex-direction: column; gap: 24px; }
.sidebar-col { display: flex; flex-direction: column; gap: 24px; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px; box-shadow: var(--shadow-card); }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }
.card-title svg { width: 18px; height: 18px; color: var(--forest); }

.info-pairs-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
@media (max-width: 600px) { .info-pairs-grid { grid-template-columns: 1fr; } }

.info-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 16px; }
.info-box label { font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 4px; }
.info-box span { font-size: 14.5px; font-weight: 700; color: var(--ink); display: block; }
.info-box small { font-size: 12px; color: var(--ink-muted); display: block; margin-top: 2px; }

.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 12px 16px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 14px 16px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.badge-status { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
.badge-completed { background: var(--cream-alt); color: var(--ink-muted); border: 1px solid var(--line); }
.badge-confirmed { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

.action-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; gap: 14px; }
.action-card h4 { font-size: 15px; font-weight: 800; color: var(--forest); }
.action-card p { font-size: 12.5px; color: var(--ink-muted); }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 20px; border-radius: 999px; font-size: 14px; font-weight: 700; transition: all 150ms ease; width: 100%; cursor: pointer; text-decoration: none; border: 0; }
.btn-promote-doc { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 20px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 13.5px; font-weight: 700; text-decoration: none; transition: background-color 150ms ease; }
.btn-promote-doc:hover { background: var(--forest-2); }
.btn-secondary-action { background: var(--cream); color: var(--forest); border: 1px solid var(--line); }
.btn-secondary-action:hover { background: var(--card); border-color: var(--forest); }
.btn-outline-danger { background: transparent; color: #DC2626; border: 1.5px solid #FCA5A5; }
.btn-outline-danger:hover { background: #FEF2F2; border-color: #DC2626; }
</style>
