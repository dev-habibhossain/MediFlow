<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

const appointments = [
    {
        id: '101',
        time: '10:00 AM',
        patientName: 'Habib Hossain',
        avatarBg: 'var(--lime)',
        avatarColor: 'var(--lime-text)',
        avatarInitials: 'HH',
        patientMeta: '28 Yrs · Male · #MDF-9021',
        typeMode: 'In-Person Visit',
        status: 'confirmed',
        statusLabel: 'Confirmed',
        actionLabel: 'Manage',
        actionUrl: '/doctor/appointments/MDF-9021',
    },
    {
        id: '102',
        time: '11:30 AM',
        patientName: 'Tanjila Ahmed',
        avatarBg: '#E0F2FE',
        avatarColor: '#0369A1',
        avatarInitials: 'TA',
        patientMeta: '34 Yrs · Female · #MDF-8812',
        typeMode: 'Telehealth Call',
        status: 'waiting',
        statusLabel: 'Waiting Room',
        actionLabel: 'Join Call',
        actionUrl: '/doctor/appointments/MDF-9022',
    },
    {
        id: '103',
        time: '02:00 PM',
        patientName: 'Karim Alam',
        avatarBg: '#FEF3C7',
        avatarColor: '#B45309',
        avatarInitials: 'KA',
        patientMeta: '52 Yrs · Male · #MDF-7419',
        typeMode: 'In-Person Visit',
        status: 'confirmed',
        statusLabel: 'Confirmed',
        actionLabel: 'View',
        actionUrl: '/doctor/appointments/MDF-8810',
    },
]
</script>

<template>
    <Head title="Doctor Dashboard" />

    <!-- HERO BANNER -->
    <div class="welcome-banner">
        <div class="welcome-text">
            <span class="banner-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                Next Scheduled Consultation
            </span>
            <h2>Habib Hossain (Cardiology Follow-Up)</h2>
            <p>Today at 10:00 AM · In-Person Visit · Room 302, Harbor Ave Clinic</p>
        </div>
        <Link href="/doctor/appointments/MDF-9021" class="btn-banner-action">
            <span>Start Consultation</span>
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
                <b>6</b>
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
                <b>1,240</b>
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
                <b>4.9 / 5</b>
            </div>
            <div class="stat-icon icon-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-meta">
                <span>Pending Medical Notes</span>
                <b>2</b>
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
        <!-- LEFT: TODAY'S APPOINTMENT QUEUE -->
        <div class="card-shell">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    Today's Schedule Queue
                </h3>
                <Link href="/doctor/appointments" class="header-link">View full schedule →</Link>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Time Slot</th>
                            <th>Patient Name</th>
                            <th>Type & Mode</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in appointments" :key="item.id" class="table-row">
                            <td><b class="time-slot">{{ item.time }}</b></td>
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
                                <span class="badge" :class="item.status === 'confirmed' ? 'badge-confirmed' : 'badge-in-progress'">
                                    <span class="badge-dot"></span> {{ item.statusLabel }}
                                </span>
                            </td>
                            <td class="text-right">
                                <Link :href="item.actionUrl" class="btn-table-action primary">
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
                        Quick Actions
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
                            <h4>Edit Availability</h4>
                            <p>Manage working slots & hours</p>
                        </div>
                    </Link>

                    <Link href="/doctor/schedule/exceptions" class="action-item">
                        <div class="action-icon tile-amber">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 6L6 18M6 6l12 12"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>Schedule Leave Day</h4>
                            <p>Block out vacation / exception days</p>
                        </div>
                    </Link>

                    <Link href="/doctor/performance" class="action-item">
                        <div class="action-icon tile-blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>View Performance</h4>
                            <p>Check ratings & completion rate</p>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- RECENT PATIENT ACTIVITY CARD -->
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
                    <div class="feed-item">
                        <div class="feed-dot"></div>
                        <div class="feed-content">
                            <p>Prescription #RX-401 issued for Habib Hossain (Amlodipine 5mg).</p>
                            <span>2 hours ago</span>
                        </div>
                    </div>
                    <div class="feed-item">
                        <div class="feed-dot dot-blue"></div>
                        <div class="feed-content">
                            <p>Medical Record #REC-301 finalized for Habib Hossain.</p>
                            <span>Yesterday</span>
                        </div>
                    </div>
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
.welcome-text { max-width: 540px; z-index: 1; }
.welcome-text h2 { font-size: 22px; font-weight: 800; margin-bottom: 6px; letter-spacing: -0.01em; }
.welcome-text p { font-size: 14px; opacity: 0.85; line-height: 1.5; }
.banner-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(221,241,92,0.15);
    border: 1px solid rgba(221,241,92,0.3);
    color: var(--lime);
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 999px;
    margin-bottom: 12px;
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

.time-slot { font-family: var(--font-mono); font-size: 13px; color: var(--forest); font-weight: 700; }
.type-cell { color: var(--ink-muted); font-size: 13px; }

.patient-cell { display: flex; align-items: center; gap: 12px; }
.patient-avatar { width: 38px; height: 38px; border-radius: 50%; font-weight: 800; font-size: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.patient-meta b { display: block; font-size: 14px; font-weight: 700; color: var(--ink); }
.patient-meta span { display: block; font-size: 12px; color: var(--ink-muted); }

.badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
.badge-dot { width: 6px; height: 6px; border-radius: 50%; }
.badge-confirmed { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.badge-confirmed .badge-dot { background: #16A34A; }
.badge-in-progress { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.badge-in-progress .badge-dot { background: #D97706; }

.text-right { text-align: right; }

.btn-table-action { height: 34px; padding: 0 16px; border-radius: 999px; border: 1px solid var(--forest); font-size: 12.5px; font-weight: 700; transition: all 150ms ease; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; cursor: pointer; }
.btn-table-action.primary { background: var(--forest); color: #fff; }
.btn-table-action.primary:hover { background: var(--forest-2); transform: translateY(-1px); box-shadow: var(--shadow-sm); }

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
.feed-item { display: flex; gap: 12px; align-items: flex-start; }
.feed-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--forest); margin-top: 5px; flex-shrink: 0; }
.feed-dot.dot-blue { background: #0369A1; }
.feed-content p { font-size: 13px; color: var(--ink); line-height: 1.4; margin: 0 0 2px 0; }
.feed-content span { font-size: 11px; color: var(--ink-muted); font-family: var(--font-mono); }
</style>
