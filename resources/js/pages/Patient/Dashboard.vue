<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

interface NextAppointment {
    id: number
    appointment_code: string
    doctor_name: string
    doctor_avatar: string
    specialty: string
    date_formatted: string
    time_formatted: string
    type: string
    status: string
    reason?: string
}

interface UpcomingAppointment {
    id: number
    appointment_code: string
    doctor_name: string
    doctor_avatar: string
    specialty: string
    date_formatted: string
    time_formatted: string
    type: string
    status: string
}

interface NotificationFeedItem {
    id: string
    text: string
    time: string
    bg_class?: string
    url?: string
}

interface PatientStats {
    upcoming_visits: number
    active_prescriptions: number
    medical_records: number
    completed_visits: number
}

interface PatientInfo {
    id: number
    code: string
    name: string
    email: string
}

const props = defineProps<{
    patientInfo?: PatientInfo
    stats: PatientStats
    nextAppointment?: NextAppointment | null
    upcomingAppointments: UpcomingAppointment[]
    recentNotifications: NotificationFeedItem[]
}>()

function getStatusBadgeClass(status: string) {
    switch (status.toLowerCase()) {
        case 'confirmed':
            return 'badge-confirmed'
        case 'completed':
            return 'badge-completed'
        case 'in progress':
            return 'badge-in-progress'
        case 'cancelled':
            return 'badge-cancelled'
        default:
            return 'badge-pending'
    }
}
</script>

<template>
    <Head title="Patient Dashboard - MediFlow" />

    <!-- HERO WELCOME BANNER -->
    <div class="welcome-banner">
        <template v-if="nextAppointment">
            <div class="welcome-text">
                <span class="banner-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Next Scheduled Consultation
                </span>
                <h2>{{ nextAppointment.doctor_name }} ({{ nextAppointment.specialty }})</h2>
                <p>{{ nextAppointment.date_formatted }} at {{ nextAppointment.time_formatted }} · {{ nextAppointment.type }}</p>
            </div>
            <Link :href="`/patient/appointments/${nextAppointment.id}`" class="btn-banner-action">View Details</Link>
        </template>
        <template v-else>
            <div class="welcome-text">
                <span class="banner-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12">
                        <path d="M12 21s-7-4.35-9.5-8.5C.6 8.9 2.3 5 6 5c2 0 3.3 1.1 4 2 .7-.9 2-2 4-2 3.7 0 5.4 3.9 3.5 7.5C19 16.65 12 21 12 21z"/>
                    </svg>
                    Ready to schedule?
                </span>
                <h2>No upcoming consultations</h2>
                <p>Book an appointment with top specialists across Cardiology, Pediatrics, Neurology, and more.</p>
            </div>
            <Link href="/appointments/book" class="btn-banner-action">Book Appointment →</Link>
        </template>
    </div>

    <!-- METRICS GRID -->
    <div class="metrics-grid">
        <div class="stat-card">
            <div class="stat-meta">
                <span>Upcoming Visits</span>
                <b>{{ stats?.upcoming_visits ?? 0 }}</b>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-meta">
                <span>Active Prescriptions</span>
                <b>{{ stats?.active_prescriptions ?? 0 }}</b>
            </div>
            <div class="stat-icon icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.5 20.4l-6.9-6.9c-.8-.8-.8-2 0-2.8l11.3-11.3c.8-.8 2-.8 2.8 0l6.9 6.9c.8.8.8 2 0 2.8l-11.3 11.3c-.8.8-2 .8-2.8 0z"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-meta">
                <span>Medical Records</span>
                <b>{{ stats?.medical_records ?? 0 }}</b>
            </div>
            <div class="stat-icon icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-meta">
                <span>Completed Visits</span>
                <b>{{ stats?.completed_visits ?? 0 }}</b>
            </div>
            <div class="stat-icon icon-muted">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- MAIN DASHBOARD GRID -->
    <div class="dashboard-grid">
        <!-- LEFT: UPCOMING APPOINTMENTS TABLE -->
        <div class="card-shell">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    Upcoming Appointments
                </h3>
                <Link href="/patient/appointments" class="header-link">View all</Link>
            </div>

            <div v-if="upcomingAppointments && upcomingAppointments.length > 0" class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Doctor & Specialty</th>
                            <th>Date & Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="app in upcomingAppointments" :key="app.id">
                            <td>
                                <div class="doc-cell">
                                    <img :src="app.doctor_avatar" :alt="app.doctor_name" />
                                    <div class="doc-meta">
                                        <b>{{ app.doctor_name }}</b>
                                        <span>{{ app.specialty }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <b class="date-text">{{ app.date_formatted }}</b>
                                <span class="time-sub">{{ app.time_formatted }}</span>
                            </td>
                            <td>{{ app.type }}</td>
                            <td>
                                <span class="badge" :class="getStatusBadgeClass(app.status)">
                                    {{ app.status }}
                                </span>
                            </td>
                            <td>
                                <div class="action-menu">
                                    <Link :href="`/patient/appointments/${app.id}`" class="btn-table-action">View</Link>
                                    <Link :href="`/patient/appointments/${app.id}/reschedule`" class="btn-table-action text-muted">Reschedule</Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else class="empty-state-wrap">
                <div class="empty-icon-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                </div>
                <h4>No upcoming appointments scheduled</h4>
                <p>Need medical advice or a routine checkup? Schedule a consultation today.</p>
                <Link href="/appointments/book" class="btn-table-action primary-btn">
                    + Book New Appointment
                </Link>
            </div>
        </div>

        <!-- RIGHT SIDEBAR: QUICK ACTIONS & NOTIFICATIONS SUMMARY -->
        <div class="side-column">
            <!-- QUICK ACTIONS CARD -->
            <div class="card-shell">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/>
                        </svg>
                        Quick Actions
                    </h3>
                </div>
                <div class="quick-action-list">
                    <Link href="/appointments/book" class="action-item">
                        <div class="action-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>Book Consultation</h4>
                            <p>Choose doctor & time slot</p>
                        </div>
                    </Link>

                    <Link href="/patient/prescriptions" class="action-item">
                        <div class="action-icon icon-blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>My Prescriptions</h4>
                            <p>Download digital Rx PDFs</p>
                        </div>
                    </Link>

                    <Link href="/patient/medical-records" class="action-item">
                        <div class="action-icon icon-green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>Medical History</h4>
                            <p>View past diagnoses & vitals</p>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- NOTIFICATIONS SUMMARY CARD -->
            <div class="card-shell">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        Notifications & Activity
                    </h3>
                    <Link href="/patient/notifications" class="header-link">View all</Link>
                </div>
                <div class="feed-list">
                    <Link
                        v-for="item in recentNotifications"
                        :key="item.id"
                        :href="item.url || '/patient/notifications'"
                        class="feed-item"
                    >
                        <div class="feed-dot" :class="item.bg_class || 'bg-green'"></div>
                        <div class="feed-content">
                            <p>{{ item.text }}</p>
                            <span>{{ item.time }}</span>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* HERO BANNER */
.welcome-banner {
    background: var(--forest);
    color: #fff;
    border-radius: var(--radius-xl);
    padding: 28px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-card);
}
.welcome-banner::after {
    content: "";
    position: absolute;
    right: -40px;
    top: -40px;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: var(--lime);
    opacity: 0.15;
    filter: blur(20px);
}
.welcome-text { max-width: 520px; z-index: 1; }
.welcome-text h2 { font-size: 22px; font-weight: 800; margin-bottom: 6px; letter-spacing: -0.01em; }
.welcome-text p { font-size: 14px; opacity: 0.85; line-height: 1.5; margin: 0; }

.banner-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(221,241,92,0.15);
    border: 1px solid rgba(221,241,92,0.3);
    color: var(--lime);
    font-size: 12.5px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 999px;
    margin-bottom: 12px;
}

.btn-banner-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 40px;
    padding: 0 20px;
    border-radius: 999px;
    background: var(--lime);
    color: var(--lime-text);
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none;
    z-index: 1;
    transition: transform 150ms ease;
    white-space: nowrap;
}
.btn-banner-action:hover { transform: translateY(-1px); }

/* METRICS GRID */
.metrics-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
@media (max-width: 1100px) { .metrics-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 540px) { .metrics-grid { grid-template-columns: 1fr; } }

.stat-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 20px;
    box-shadow: var(--shadow-sm);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.stat-meta span { font-size: 12.5px; font-weight: 600; color: var(--ink-muted); display: block; margin-bottom: 6px; }
.stat-meta b { font-family: var(--font-mono); font-size: 26px; font-weight: 800; color: var(--forest); line-height: 1; display: block; }

.stat-icon {
    width: 42px;
    height: 42px;
    border-radius: var(--radius-md);
    background: var(--cream);
    color: var(--forest);
    display: flex;
    align-items: center;
    justify-content: center;
}
.stat-icon svg { width: 20px; height: 20px; }
.stat-icon.icon-green { background: #DCFCE7; color: #15803D; }
.stat-icon.icon-blue { background: #E0F2FE; color: #0369A1; }
.stat-icon.icon-muted { background: var(--cream-alt); color: var(--ink); }

/* MAIN DASHBOARD SPLIT GRID */
.dashboard-grid { display: grid; grid-template-columns: 1fr 340px; gap: 28px; align-items: start; }
@media (max-width: 1100px) { .dashboard-grid { grid-template-columns: 1fr; } }

.side-column { display: flex; flex-direction: column; gap: 28px; }

/* CONTENT SHADCN-STYLE CARD */
.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.card-header { padding: 20px 24px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; }
.card-header h3 { font-size: 16px; font-weight: 800; color: var(--forest); display: flex; align-items: center; gap: 8px; margin: 0; }
.card-header h3 svg { width: 18px; height: 18px; color: var(--forest); }
.header-link { font-size: 13px; font-weight: 700; color: var(--forest); text-decoration: underline; }

/* TABLE */
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 12px 24px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 16px 24px; border-bottom: 1px solid var(--line); font-size: 14px; vertical-align: middle; }
.data-table tr:last-child td { border-bottom: none; }

.doc-cell { display: flex; align-items: center; gap: 12px; }
.doc-cell img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background: var(--cream-alt); }
.doc-meta b { display: block; font-size: 14px; font-weight: 700; color: var(--ink); }
.doc-meta span { display: block; font-size: 12px; color: var(--ink-muted); }

.date-text { font-size: 13.5px; display: block; }
.time-sub { font-size: 12px; color: var(--ink-muted); display: block; }

.badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
.badge-confirmed { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.badge-pending { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.badge-completed { background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; }
.badge-in-progress { background: #FCE7F3; color: #BE185D; border: 1px solid #FBCFE8; }
.badge-cancelled { background: #FEE2E2; color: #B91C1C; border: 1px solid #FCA5A5; }

.action-menu { display: flex; gap: 8px; }
.btn-table-action { height: 32px; padding: 0 12px; border-radius: var(--radius-sm); border: 1px solid var(--line); font-size: 12.5px; font-weight: 600; color: var(--ink); text-decoration: none; transition: all 150ms ease; display: inline-flex; align-items: center; justify-content: center; }
.btn-table-action:hover { border-color: var(--forest); background: var(--forest); color: #fff; }
.btn-table-action.text-muted { color: var(--ink-muted); }
.btn-table-action.primary-btn { background: var(--forest); color: #fff; border-color: var(--forest); }
.btn-table-action.primary-btn:hover { background: var(--forest-2); }

.empty-state-wrap { padding: 40px 24px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; }
.empty-icon-box { width: 48px; height: 48px; border-radius: 50%; background: var(--cream); color: var(--ink-muted); display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
.empty-icon-box svg { width: 24px; height: 24px; }
.empty-state-wrap h4 { font-size: 15px; font-weight: 700; color: var(--forest); margin: 0 0 6px 0; }
.empty-state-wrap p { font-size: 13px; color: var(--ink-muted); margin: 0 0 16px 0; max-width: 360px; }

/* QUICK ACTIONS LIST */
.quick-action-list { padding: 16px 24px; display: flex; flex-direction: column; gap: 10px; }
.action-item { display: flex; align-items: center; gap: 14px; padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); text-decoration: none; transition: all 150ms ease; }
.action-item:hover { border-color: var(--forest); background: var(--card); box-shadow: var(--shadow-sm); transform: translateY(-1px); }
.action-icon { width: 38px; height: 38px; border-radius: var(--radius-sm); background: var(--lime-soft); color: var(--lime-text); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.action-icon svg { width: 18px; height: 18px; }
.action-icon.icon-blue { background: #E0F2FE; color: #0369A1; }
.action-icon.icon-green { background: #DCFCE7; color: #15803D; }

.action-info h4 { font-size: 14px; font-weight: 700; color: var(--ink); margin: 0 0 2px 0; }
.action-info p { font-size: 12px; color: var(--ink-muted); margin: 0; }

/* NOTIFICATION FEED */
.feed-list { padding: 16px 24px; display: flex; flex-direction: column; gap: 12px; }
.feed-item { display: flex; gap: 12px; align-items: flex-start; text-decoration: none; padding: 6px 8px; border-radius: var(--radius-sm); transition: background 150ms ease; }
.feed-item:hover { background: var(--cream); }
.feed-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--forest); margin-top: 6px; flex-shrink: 0; }
.feed-dot.bg-green { background: #16A34A; }
.feed-dot.bg-amber { background: #B45309; }
.feed-content p { font-size: 12.5px; color: var(--ink); line-height: 1.4; margin: 0 0 2px 0; }
.feed-content span { font-size: 11px; color: var(--ink-muted); font-family: var(--font-mono); }
</style>
