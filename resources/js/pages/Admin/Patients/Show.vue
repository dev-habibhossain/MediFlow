<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

const props = withDefaults(
    defineProps<{
        patient?: {
            id: number
            code: string
            name: string
            initials: string
            avatar_url?: string | null
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
            avatar_url: null,
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
            <div class="patient-avatar-lg">
                <img v-if="props.patient.avatar_url" :src="props.patient.avatar_url" :alt="props.patient.name" class="avatar-fit-lg" />
                <span v-else>{{ props.patient.initials }}</span>
            </div>
            <div class="patient-meta-lg">
                <h1>{{ props.patient.name }}</h1>
                <p>Patient Account ID: <strong>#{{ props.patient.code }}</strong> · Registered {{ props.patient.registered_at }}</p>
                <span class="status-badge" :class="props.patient.status === 'active' ? 'status-active' : 'status-inactive'">
                    ● {{ props.patient.status === 'active' ? 'Active Account' : 'Inactive Account' }}
                </span>
            </div>
        </div>
        <div class="stats-pills">
            <div class="pill-stat">
                <span>Total Spent</span>
                <b>{{ props.patient.total_spent }}</b>
            </div>
            <div class="pill-stat">
                <span>Appointments</span>
                <b>{{ props.patient.appointments.length }} Visits</b>
            </div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="patient-grid">
        <!-- LEFT COLUMN: PERSONAL DETAILS -->
        <div class="col-details">
            <div class="card-shell mb-6">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" />
                    </svg>
                    Demographics & Health Info
                </div>

                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">Full Name:</span>
                        <span class="info-val"><b>{{ props.patient.name }}</b></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email Address:</span>
                        <span class="info-val">{{ props.patient.email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone Number:</span>
                        <span class="info-val font-mono"><b>{{ props.patient.phone }}</b></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date of Birth:</span>
                        <span class="info-val">{{ props.patient.dob }} ({{ props.patient.age }} Yrs)</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Gender:</span>
                        <span class="info-val">{{ props.patient.gender }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Blood Group:</span>
                        <span class="info-val"><b class="blood-tag">{{ props.patient.blood_group }}</b></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Known Allergies:</span>
                        <span class="info-val text-amber-700 font-semibold">{{ props.patient.allergies }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: APPOINTMENT HISTORY -->
        <div class="col-history">
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    Consultation & Appointment History
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Appointment ID</th>
                                <th>Schedule Date</th>
                                <th>Attending Doctor</th>
                                <th>Department</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="app in props.patient.appointments" :key="app.id">
                                <td style="font-family: var(--font-mono); font-weight: 700;">#{{ app.id }}</td>
                                <td style="font-size: 12.5px;">{{ app.date }}</td>
                                <td><b>{{ app.doctor }}</b></td>
                                <td><span class="dept-badge">{{ app.department }}</span></td>
                                <td><span class="status-badge status-active">{{ app.status }}</span></td>
                            </tr>

                            <tr v-if="props.patient.appointments.length === 0">
                                <td colspan="5" style="text-align: center; padding: 24px; color: var(--ink-muted);">
                                    No appointment history recorded.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.back-btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 6px 14px; border-radius: 999px; transition: all 150ms ease; text-decoration: none; }
.back-btn:hover { background: var(--card); border-color: var(--forest); }

.patient-header-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px 32px; box-shadow: var(--shadow-card); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; }
.patient-info-left { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
.patient-avatar-lg { width: 72px; height: 72px; border-radius: 50%; background: var(--lime); color: var(--lime-text); font-weight: 800; font-size: 24px; font-family: var(--font-mono); display: flex; align-items: center; justify-content: center; overflow: hidden; border: 2px solid var(--line); flex-shrink: 0; }
.avatar-fit-lg { width: 100%; height: 100%; object-fit: cover; }
.patient-meta-lg h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin-bottom: 2px; }
.patient-meta-lg p { font-size: 13.5px; color: var(--ink-muted); font-weight: 500; }

.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; margin-top: 6px; }
.status-active { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-inactive { background: #F3F4F6; color: #6B7280; border: 1px solid #E5E7EB; }

.stats-pills { display: flex; gap: 16px; }
.pill-stat { background: var(--cream); border: 1px solid var(--line); padding: 12px 20px; border-radius: var(--radius-lg); text-align: center; }
.pill-stat span { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-muted); display: block; }
.pill-stat b { font-family: var(--font-mono); font-size: 18px; font-weight: 800; color: var(--forest); display: block; margin-top: 2px; }

.patient-grid { display: grid; grid-template-columns: 380px 1fr; gap: 24px; align-items: start; }
@media (max-width: 1024px) { .patient-grid { grid-template-columns: 1fr; } }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px; box-shadow: var(--shadow-card); }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }
.card-title svg { width: 18px; height: 18px; color: var(--forest); }

.info-list { display: flex; flex-direction: column; gap: 14px; }
.info-row { display: flex; justify-content: space-between; align-items: center; font-size: 13.5px; border-bottom: 1px dashed var(--line); padding-bottom: 10px; }
.info-row:last-child { border-bottom: 0; padding-bottom: 0; }
.info-label { color: var(--ink-muted); font-weight: 600; }
.info-val { color: var(--ink); }

.blood-tag { background: #FEE2E2; color: #DC2626; padding: 2px 8px; border-radius: 4px; font-family: var(--font-mono); font-size: 12px; }
.dept-badge { font-size: 12px; font-weight: 700; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 2px 8px; border-radius: var(--radius-sm); }

.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 12px 16px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 14px 16px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }
</style>
