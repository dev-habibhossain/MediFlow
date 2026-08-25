<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps<{
    doctor?: {
        name: string
        specialty: string
        department: string
    }
    greeting?: string
    stats?: {
        appointments_today: number
        total_patients: number
        average_rating: number
        pending_notes: number
        completed_this_month: number
    }
    nextBanner?: {
        id: string
        patient_name: string
        patient_code: string
        reason: string
        status: string
        time_details: string
        action_url: string
    } | null
    hasTodayAppointments?: boolean
    todayAppointments?: Array<{
        id: string
        time: string
        date: string
        patientName: string
        avatarBg: string
        avatarColor: string
        avatarInitials: string
        patientMeta: string
        typeMode: string
        status: string
        statusLabel: string
        isInProgress?: boolean
        isTimePassed?: boolean
        isFinished?: boolean
        actionLabel: string
        actionUrl: string
    }>
    recentActivities?: Array<{
        id: string
        text: string
        time: string
        type?: string
    }>
}>()

const appointmentsList = computed(() => props.todayAppointments ?? [])
const activitiesList = computed(() => props.recentActivities ?? [])
const statsData = computed(() => ({
    appointments_today: props.stats?.appointments_today ?? 0,
    total_patients: props.stats?.total_patients ?? 0,
    average_rating: props.stats?.average_rating ?? 5.0,
    pending_notes: props.stats?.pending_notes ?? 0,
    completed_this_month: props.stats?.completed_this_month ?? 0,
}))
</script>

<template>
    <Head title="Doctor Dashboard" />

    <!-- TOP GREETING BAR -->
    <div class="greeting-bar">
        <div>
            <h1 class="greeting-title">{{ props.greeting || 'Doctor Command Center' }}</h1>
            <p class="greeting-sub">
                {{ props.doctor?.specialty || 'General Physician' }} · {{ props.doctor?.department || 'Department of Medicine' }}
            </p>
        </div>
        <div class="header-action-group">
            <Link href="/doctor/schedule" class="btn-secondary-head">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                <span>My Schedule</span>
            </Link>
            <Link href="/doctor/appointments" class="btn-primary-head">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                </svg>
                <span>Full Patient Queue</span>
            </Link>
        </div>
    </div>

    <!-- HERO BANNER FOR NEXT CONSULTATION -->
    <div v-if="props.nextBanner" class="welcome-banner">
        <div class="welcome-text">
            <span class="banner-badge">
                <span class="badge-pulse"></span>
                Next Scheduled Patient
            </span>
            <h2>{{ props.nextBanner.patient_name }} <span class="patient-code-tag">{{ props.nextBanner.patient_code }}</span></h2>
            <p class="banner-reason">Reason: <strong>{{ props.nextBanner.reason }}</strong></p>
            <p class="banner-time">{{ props.nextBanner.time_details }}</p>
        </div>
        <Link :href="props.nextBanner.action_url" class="btn-banner-action">
            <span>Start Consultation</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </Link>
    </div>
    <div v-else class="welcome-banner banner-idle">
        <div class="welcome-text">
            <span class="banner-badge badge-idle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                Schedule Status
            </span>
            <h2>No Active Consultation in Queue</h2>
            <p>Your upcoming queue is clear right now. Check your full appointment schedule or update exception days.</p>
        </div>
        <Link href="/doctor/appointments" class="btn-banner-action">
            <span>View All Appointments</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </Link>
    </div>

    <!-- METRICS GRID -->
    <div class="metrics-grid">
        <div class="stat-card">
            <div class="stat-meta">
                <span>Appointments Today</span>
                <b>{{ statsData.appointments_today }}</b>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-meta">
                <span>Total Patients Treated</span>
                <b>{{ statsData.total_patients.toLocaleString() }}</b>
            </div>
            <div class="stat-icon icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-meta">
                <span>Average Rating</span>
                <b>{{ statsData.average_rating }} / 5.0</b>
            </div>
            <div class="stat-icon icon-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
            </div>
        </div>

        <div class="stat-card" :class="{ 'stat-highlight-blue': statsData.pending_notes > 0 }">
            <div class="stat-meta">
                <span>Pending Notes</span>
                <b>{{ statsData.pending_notes }}</b>
            </div>
            <div class="stat-icon icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- MAIN DASHBOARD SPLIT GRID -->
    <div class="dashboard-grid">
        <!-- LEFT: APPOINTMENT QUEUE -->
        <div class="card-shell">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    {{ props.hasTodayAppointments ? "Today's Consultation Queue" : "Upcoming Schedule Queue" }}
                </h3>
                <Link href="/doctor/appointments" class="header-link">View full list →</Link>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Time / Date</th>
                            <th>Patient Name</th>
                            <th>Visit Type</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="appointmentsList.length === 0">
                            <td colspan="5" style="text-align: center; padding: 48px 20px; color: var(--ink-muted);">
                                <div class="empty-state">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="40" height="40">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                                    </svg>
                                    <h4>No Appointments Found</h4>
                                    <p>Your queue is currently empty.</p>
                                </div>
                            </td>
                        </tr>
                        <tr
                            v-for="item in appointmentsList"
                            :key="item.id"
                            class="table-row"
                            :class="{
                                'row-in-progress': item.isInProgress,
                                'row-time-passed': item.isTimePassed,
                                'row-finished': item.isFinished
                            }"
                        >
                            <td>
                                <b class="time-slot">{{ item.time }}</b><br />
                                <span class="time-sub">{{ item.date }}</span>
                            </td>
                            <td>
                                <div class="patient-cell">
                                    <div class="patient-avatar" :style="{ background: item.avatarBg, color: item.avatarColor }">
                                        {{ item.avatarInitials }}
                                    </div>
                                    <div class="patient-meta">
                                        <b>{{ item.patientName }}</b>
                                        <span>{{ item.patientMeta }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="type-cell">{{ item.typeMode }}</td>
                            <td>
                                <span
                                    class="badge"
                                    :class="{
                                        'badge-confirmed': item.status === 'confirmed',
                                        'badge-in-progress': item.isInProgress,
                                        'badge-pending': item.status === 'pending',
                                        'badge-completed': item.status === 'completed',
                                        'badge-cancelled': item.status === 'cancelled' || item.status === 'no_show',
                                        'badge-passed': item.isTimePassed
                                    }"
                                >
                                    <span class="badge-dot"></span> {{ item.statusLabel }}
                                </span>
                            </td>
                            <td class="text-right">
                                <Link
                                    :href="item.actionUrl"
                                    class="btn-table-action"
                                    :class="{
                                        primary: item.status === 'confirmed',
                                        in_progress_btn: item.isInProgress,
                                        muted_btn: item.isFinished
                                    }"
                                >
                                    {{ item.actionLabel }}
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RIGHT SIDEBAR: QUICK ACTIONS & RECENT ACTIVITY -->
        <div class="side-col">
            <!-- QUICK ACTIONS CARD -->
            <div class="card-shell">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/>
                        </svg>
                        Quick Tools
                    </h3>
                </div>
                <div class="quick-action-list">
                    <Link href="/doctor/schedule" class="action-item">
                        <div class="action-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>Edit Schedule & Hours</h4>
                            <p>Manage daily working slots</p>
                        </div>
                    </Link>

                    <Link href="/doctor/schedule/exceptions" class="action-item">
                        <div class="action-icon tile-amber">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>Schedule Leave / Exceptions</h4>
                            <p>Block out vacation days</p>
                        </div>
                    </Link>

                    <Link href="/doctor/performance" class="action-item">
                        <div class="action-icon tile-blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>View Performance Analytics</h4>
                            <p>Check ratings & completion rate</p>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- RECENT CLINICAL ACTIVITY CARD -->
            <div class="card-shell">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                        Recent Clinical Activity
                    </h3>
                </div>
                <div class="feed-list">
                    <div v-if="activitiesList.length === 0" class="empty-feed">
                        <p>No recent activity recorded yet.</p>
                    </div>
                    <div v-for="act in activitiesList" :key="act.id" class="feed-item">
                        <div
                            class="feed-dot"
                            :class="{
                                'dot-blue': act.type === 'record',
                                'dot-amber': act.type === 'prescription',
                                'dot-green': act.type === 'appointment'
                            }"
                        ></div>
                        <div class="feed-content">
                            <p>{{ act.text }}</p>
                            <span>{{ act.time }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* TOP GREETING BAR */
.greeting-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 16px;
}
.greeting-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--forest);
    margin: 0 0 4px 0;
    letter-spacing: -0.02em;
}
.greeting-sub {
    font-size: 13.5px;
    color: var(--ink-muted);
    margin: 0;
}

.header-action-group {
    display: flex;
    align-items: center;
    gap: 10px;
}
.btn-secondary-head {
    height: 40px;
    padding: 0 16px;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--card);
    color: var(--ink);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 150ms ease;
}
.btn-secondary-head:hover { background: var(--cream); border-color: var(--forest); }

.btn-primary-head {
    height: 40px;
    padding: 0 18px;
    border-radius: var(--radius-md);
    background: var(--forest);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 150ms ease;
}
.btn-primary-head:hover { background: var(--forest-2); }

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
    margin-bottom: 24px;
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
.welcome-banner.banner-idle::after { background: #38BDF8; opacity: 0.12; }

.welcome-text { max-width: 580px; z-index: 1; }
.welcome-text h2 { font-size: 22px; font-weight: 800; margin-bottom: 6px; letter-spacing: -0.01em; }
.patient-code-tag { font-family: var(--font-mono); font-size: 14px; opacity: 0.8; font-weight: 600; }
.banner-reason { font-size: 13.5px; opacity: 0.9; margin: 4px 0; }
.banner-time { font-size: 13px; opacity: 0.75; margin: 0; font-family: var(--font-mono); }
.banner-text p { font-size: 14px; opacity: 0.85; line-height: 1.5; }

.banner-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(221,241,92,0.18);
    border: 1px solid rgba(221,241,92,0.35);
    color: var(--lime);
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 999px;
    margin-bottom: 12px;
}
.banner-badge.badge-idle {
    background: rgba(255,255,255,0.12);
    border-color: rgba(255,255,255,0.25);
    color: #fff;
}

.badge-pulse {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--lime);
    box-shadow: 0 0 0 0 rgba(221, 241, 92, 0.7);
    animation: pulse 1.6s infinite;
}
@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(221, 241, 92, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(221, 241, 92, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(221, 241, 92, 0); }
}

.btn-banner-action {
    background: var(--lime);
    color: var(--lime-text);
    height: 42px;
    padding: 0 20px;
    font-size: 13.5px;
    font-weight: 700;
    border-radius: 999px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
    z-index: 1;
    box-shadow: 0 4px 14px rgba(221, 241, 92, 0.4);
    transition: all 180ms ease;
    flex-shrink: 0;
}
.btn-banner-action:hover { background: #d2e85a; transform: translateY(-2px); box-shadow: 0 6px 18px rgba(221, 241, 92, 0.55); color: var(--forest); }

/* METRICS GRID */
.metrics-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 28px; }
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
    transition: all 200ms ease;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lift); }

.stat-meta span { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 4px; }
.stat-meta b { font-family: var(--font-mono); font-size: 26px; font-weight: 800; color: var(--forest); line-height: 1; display: block; }
.stat-icon { width: 44px; height: 44px; border-radius: var(--radius-md); background: var(--cream); color: var(--forest); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-icon svg { width: 22px; height: 22px; }
.stat-icon.icon-green { background: #DCFCE7; color: #15803D; }
.stat-icon.icon-amber { background: #FEF3C7; color: #B45309; }
.stat-icon.icon-blue { background: #E0F2FE; color: #0369A1; }

/* MAIN DASHBOARD SPLIT GRID */
.dashboard-grid { display: grid; grid-template-columns: 1fr 340px; gap: 28px; align-items: start; }
@media (max-width: 1100px) { .dashboard-grid { grid-template-columns: 1fr; } }

.side-col { display: flex; flex-direction: column; gap: 28px; }

/* CONTENT CARD */
.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.card-header { padding: 20px 24px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; background: var(--cream); }
.card-header h3 { font-size: 15.5px; font-weight: 800; color: var(--forest); display: flex; align-items: center; gap: 8px; margin: 0; }
.card-header h3 svg { width: 18px; height: 18px; color: var(--forest); }
.header-link { font-size: 13px; font-weight: 700; color: var(--forest); text-decoration: none; transition: opacity 150ms ease; }
.header-link:hover { opacity: 0.8; text-decoration: underline; }

/* DATA TABLE */
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 12px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 16px 24px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }
.table-row { transition: background 150ms ease; }
.table-row:hover { background: rgba(248, 246, 239, 0.6); }
.data-table tr:last-child td { border-bottom: none; }

.empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; }
.empty-state svg { opacity: 0.4; margin-bottom: 8px; }
.empty-state h4 { font-size: 15px; font-weight: 700; color: var(--forest); margin: 0 0 4px 0; }
.empty-state p { font-size: 13px; color: var(--ink-muted); margin: 0; }

.row-in-progress { background: #FEFCE8; }
.row-time-passed { background: #F8FAFC; opacity: 0.88; }
.row-finished { background: #FAF9F6; opacity: 0.8; }

.time-slot { font-family: var(--font-mono); font-size: 13px; color: var(--forest); font-weight: 700; }
.time-sub { font-family: var(--font-mono); font-size: 11.5px; color: var(--ink-muted); }
.type-cell { color: var(--ink-muted); font-size: 13px; }

.patient-cell { display: flex; align-items: center; gap: 12px; }
.patient-avatar { width: 38px; height: 38px; border-radius: 50%; font-weight: 800; font-size: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.patient-meta b { display: block; font-size: 14px; font-weight: 700; color: var(--ink); }
.patient-meta span { display: block; font-size: 12px; color: var(--ink-muted); }

.badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
.badge-dot { width: 6px; height: 6px; border-radius: 50%; }
.badge-confirmed { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.badge-confirmed .badge-dot { background: #16A34A; }
.badge-in-progress { background: #FEF3C7; color: #B45309; border: 1px solid #FCD34D; }
.badge-in-progress .badge-dot { background: #D97706; }
.badge-pending { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.badge-pending .badge-dot { background: #D97706; }
.badge-completed { background: var(--cream-alt); color: var(--ink-muted); border: 1px solid var(--line); }
.badge-completed .badge-dot { background: var(--ink-muted); }
.badge-cancelled { background: #FEE2E2; color: #B91C1C; border: 1px solid #FCA5A5; }
.badge-cancelled .badge-dot { background: #DC2626; }
.badge-passed { background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0; }
.badge-passed .badge-dot { background: #94A3B8; }

.text-right { text-align: right; }

.btn-table-action { height: 34px; padding: 0 16px; border-radius: 999px; border: 1px solid var(--forest); font-size: 12.5px; font-weight: 700; transition: all 150ms ease; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; cursor: pointer; }
.btn-table-action.primary { background: var(--forest); color: #fff; }
.btn-table-action.primary:hover { background: var(--forest-2); transform: translateY(-1px); box-shadow: var(--shadow-sm); }
.btn-table-action.in_progress_btn { background: #D97706; color: #fff; border-color: #D97706; }
.btn-table-action.in_progress_btn:hover { background: #B45309; }
.btn-table-action.muted_btn { background: var(--cream-alt); color: var(--ink-muted); border-color: var(--line); }

/* QUICK ACTIONS LIST */
.quick-action-list { padding: 16px 24px; display: flex; flex-direction: column; gap: 10px; }
.action-item { display: flex; align-items: center; gap: 14px; padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); transition: all 180ms ease; text-decoration: none; }
.action-item:hover { border-color: var(--forest); background: var(--card); box-shadow: var(--shadow-sm); transform: translateY(-1px); }
.action-icon { width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--lime-soft); color: var(--lime-text); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.action-icon.tile-amber { background: #FEF3C7; color: #B45309; }
.action-icon.tile-blue { background: #E0F2FE; color: #0369A1; }
.action-icon svg { width: 18px; height: 18px; }
.action-info h4 { font-size: 13.5px; font-weight: 700; color: var(--ink); margin: 0; }
.action-info p { font-size: 12px; color: var(--ink-muted); margin: 2px 0 0 0; }

/* RECENT ACTIVITY FEED */
.feed-list { padding: 18px 24px; display: flex; flex-direction: column; gap: 16px; }
.empty-feed { text-align: center; color: var(--ink-muted); font-size: 13px; padding: 12px 0; }
.feed-item { display: flex; gap: 12px; align-items: flex-start; }
.feed-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--forest); margin-top: 5px; flex-shrink: 0; }
.feed-dot.dot-blue { background: #0369A1; }
.feed-dot.dot-amber { background: #B45309; }
.feed-dot.dot-green { background: #15803D; }
.feed-content p { font-size: 13px; color: var(--ink); line-height: 1.4; margin: 0 0 2px 0; }
.feed-content span { font-size: 11px; color: var(--ink-muted); font-family: var(--font-mono); }
</style>
