<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const selectedTab = ref('all')
const searchQuery = ref('')

const appointments = [
    {
        id: 'MDF-9021',
        patient: 'Habib Hossain',
        avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=150',
        time: '09:30 AM',
        type: 'In-Person (Room 302)',
        reason: 'Hypertension Follow-up & ECG',
        status: 'in-progress',
        statusLabel: 'In Progress',
        historyUrl: '/doctor/patients/MDF-9021/history',
        actionUrl: '/doctor/appointments/MDF-9021',
        actionLabel: 'Open Consult',
    },
    {
        id: 'MDF-9022',
        patient: 'Tanjila Ahmed',
        avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=150',
        time: '10:15 AM',
        type: 'Telehealth (Virtual)',
        reason: 'Chest Tightness Consultation',
        status: 'checked-in',
        statusLabel: 'Checked-In',
        historyUrl: '/doctor/patients/MDF-9022/history',
        actionUrl: '/doctor/appointments/MDF-9022',
        actionLabel: 'Start Telehealth',
    },
    {
        id: 'MDF-8810',
        patient: 'Robert Chen',
        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=150',
        time: '11:00 AM',
        type: 'In-Person (Room 302)',
        reason: 'Routine Cardiac Screening',
        status: 'confirmed',
        statusLabel: 'Confirmed',
        historyUrl: '/doctor/patients/MDF-8810/history',
        actionUrl: '/doctor/appointments/MDF-8810',
        actionLabel: 'View Details',
    },
    {
        id: 'MDF-8742',
        patient: 'Sophia Martinez',
        avatar: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&q=80&w=150',
        time: '08:45 AM',
        type: 'In-Person (Room 302)',
        reason: 'Post-op Lipid Panel Review',
        status: 'completed',
        statusLabel: 'Completed',
        historyUrl: '/doctor/patients/MDF-8742/history',
        actionUrl: '/doctor/appointments/MDF-8742',
        actionLabel: 'View Summary',
    },
]
</script>

<template>
    <Head title="Doctor Dashboard" />

    <!-- METRICS OVERVIEW STRIP -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-info">
                <label>Today's Appointments</label>
                <b>8 Patients</b>
                <span>2 In Waiting Room</span>
            </div>
            <div class="metric-icon bg-forest-soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <label>Completed Visits</label>
                <b>5 Patients</b>
                <span class="text-success">↑ 100% On-time Rate</span>
            </div>
            <div class="metric-icon bg-lime-soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <label>Queue Status</label>
                <b>3 Pending</b>
                <span>Avg Wait: 14m</span>
            </div>
            <div class="metric-icon bg-cream-soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <label>Satisfaction Score</label>
                <b>4.9 / 5</b>
                <span>28 Reviews This Week</span>
            </div>
            <div class="metric-icon bg-amber-soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- MAIN DASHBOARD CONTENT GRID -->
    <div class="dashboard-main-grid">
        <!-- LEFT: PATIENT QUEUE & SCHEDULE -->
        <div class="table-card">
            <div class="card-header-bar">
                <div class="header-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    </svg>
                    Today's Patient Schedule
                </div>

                <div class="tab-filters">
                    <button class="tab-btn" :class="{ active: selectedTab === 'all' }" @click="selectedTab = 'all'">All (8)</button>
                    <button class="tab-btn" :class="{ active: selectedTab === 'waiting' }" @click="selectedTab = 'waiting'">Waiting (2)</button>
                    <button class="tab-btn" :class="{ active: selectedTab === 'completed' }" @click="selectedTab = 'completed'">Completed (5)</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Patient Info</th>
                            <th>Time & Mode</th>
                            <th>Reason / Symptoms</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="app in appointments" :key="app.id">
                            <td>
                                <div class="patient-cell">
                                    <img :src="app.avatar" :alt="app.patient" class="patient-avatar" />
                                    <div>
                                        <b>{{ app.patient }}</b>
                                        <span>ID: {{ app.id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="time-cell">
                                    <b>{{ app.time }}</b>
                                    <span>{{ app.type }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="reason-text">{{ app.reason }}</span>
                            </td>
                            <td>
                                <span class="status-pill-badge" :class="app.status">
                                    {{ app.statusLabel }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btn-group">
                                    <Link :href="app.historyUrl" class="btn-sm btn-outline">History</Link>
                                    <Link :href="app.actionUrl" class="btn-sm btn-primary">{{ app.actionLabel }}</Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RIGHT: SIDE PANEL WITH QUICK ACTIONS & FEED -->
        <div class="side-panel">
            <!-- QUICK ACTIONS CARD -->
            <div class="panel-card">
                <div class="panel-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                    </svg>
                    Quick Clinical Actions
                </div>

                <div class="quick-actions-list">
                    <Link href="/doctor/appointments/MDF-9021/prescriptions/create" class="action-tile">
                        <div class="tile-icon bg-lime-soft">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </div>
                        <div>
                            <b>Issue Digital Prescription</b>
                            <span>Write new medication order</span>
                        </div>
                    </Link>

                    <Link href="/doctor/appointments/MDF-9021/records/create" class="action-tile">
                        <div class="tile-icon bg-forest-soft">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </div>
                        <div>
                            <b>New Medical Record Entry</b>
                            <span>Log SOAP note & diagnosis</span>
                        </div>
                    </Link>

                    <Link href="/doctor/schedule/exceptions" class="action-tile">
                        <div class="tile-icon bg-cream-soft">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><line x1="9" y1="14" x2="15" y2="14"/>
                            </svg>
                        </div>
                        <div>
                            <b>Schedule Exception / Leave</b>
                            <span>Block hours or request time off</span>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- ACTIVITY FEED CARD -->
            <div class="panel-card">
                <div class="panel-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Recent Clinical Activity
                </div>

                <div class="activity-feed">
                    <div class="feed-item">
                        <div class="feed-dot bg-green"></div>
                        <div class="feed-content">
                            <p>Prescription issued for <strong>Robert Chen</strong> (Lisinopril 10mg)</p>
                            <small>32 minutes ago</small>
                        </div>
                    </div>

                    <div class="feed-item">
                        <div class="feed-dot bg-blue"></div>
                        <div class="feed-content">
                            <p>Medical record updated for <strong>Sophia Martinez</strong> (#REC-301)</p>
                            <small>1 hour ago</small>
                        </div>
                    </div>

                    <div class="feed-item">
                        <div class="feed-dot bg-amber"></div>
                        <div class="feed-content">
                            <p>New telehealth visit confirmed with <strong>Tanjila Ahmed</strong></p>
                            <small>2 hours ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}
@media (max-width: 1100px) { .metrics-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 540px) { .metrics-grid { grid-template-columns: 1fr; } }

.metric-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 20px;
    box-shadow: var(--shadow-sm);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.metric-info label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--ink-muted);
    display: block;
    margin-bottom: 4px;
}

.metric-info b {
    font-family: var(--font-mono);
    font-size: 26px;
    font-weight: 800;
    color: var(--forest);
    line-height: 1;
    display: block;
}

.metric-info span {
    font-size: 12px;
    color: var(--ink-muted);
    font-weight: 600;
    display: inline-block;
    margin-top: 6px;
}

.metric-info span.text-success { color: #15803D; }

.metric-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.metric-icon svg { width: 22px; height: 22px; }
.bg-forest-soft { background: #E2E8F0; color: var(--forest); }
.bg-lime-soft { background: var(--lime-soft); color: var(--lime-text); }
.bg-cream-soft { background: var(--cream); color: var(--forest); }
.bg-amber-soft { background: #FEF3C7; color: #B45309; }

.dashboard-main-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    align-items: start;
}
@media (max-width: 1024px) { .dashboard-main-grid { grid-template-columns: 1fr; } }

.table-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-card);
    overflow: hidden;
}

.card-header-bar {
    padding: 20px 24px;
    border-bottom: 1px solid var(--line);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.header-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--forest);
    display: flex;
    align-items: center;
    gap: 10px;
}

.tab-filters {
    display: flex;
    gap: 6px;
    background: var(--cream);
    padding: 4px;
    border-radius: 999px;
    border: 1px solid var(--line);
}

.tab-btn {
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--ink-muted);
    transition: all 150ms ease;
}

.tab-btn.active {
    background: var(--card);
    color: var(--forest);
    box-shadow: var(--shadow-sm);
}

.table-responsive { width: 100%; overflow-x: auto; }

.data-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}

.data-table th {
    background: var(--cream);
    padding: 12px 20px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--ink-muted);
    border-bottom: 1px solid var(--line);
}

.data-table td {
    padding: 16px 20px;
    border-bottom: 1px solid var(--line);
    font-size: 13.5px;
    vertical-align: middle;
}
.data-table tr:last-child td { border-bottom: none; }

.patient-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.patient-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.patient-cell b { display: block; font-weight: 700; color: var(--forest); }
.patient-cell span { display: block; font-size: 11.5px; color: var(--ink-muted); font-family: var(--font-mono); }

.time-cell b { display: block; font-family: var(--font-mono); font-size: 13px; color: var(--forest); }
.time-cell span { display: block; font-size: 11.5px; color: var(--ink-muted); }

.reason-text { font-size: 13px; color: var(--ink); line-height: 1.3; }

.status-pill-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
}
.status-pill-badge.in-progress { background: var(--lime-soft); color: var(--lime-text); border: 1px solid #d2e85a; }
.status-pill-badge.checked-in { background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; }
.status-pill-badge.confirmed { background: var(--cream-alt); color: var(--ink); border: 1px solid var(--line); }
.status-pill-badge.completed { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

.action-btn-group { display: flex; gap: 8px; }

.btn-sm {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all 150ms ease;
}
.btn-outline { background: transparent; color: var(--forest); border: 1px solid var(--line); }
.btn-outline:hover { background: var(--cream); border-color: var(--forest); }
.btn-primary { background: var(--forest); color: white; border: 1px solid var(--forest); }
.btn-primary:hover { background: var(--forest-2); }

.side-panel { display: flex; flex-direction: column; gap: 20px; }

.panel-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 20px;
    box-shadow: var(--shadow-card);
}

.panel-header {
    font-size: 14.5px;
    font-weight: 800;
    color: var(--forest);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    border-bottom: 1px solid var(--line);
    padding-bottom: 10px;
}

.quick-actions-list { display: flex; flex-direction: column; gap: 10px; }

.action-tile {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: var(--radius-md);
    background: var(--cream);
    border: 1px solid var(--line);
    text-decoration: none;
    transition: all 150ms ease;
}
.action-tile:hover {
    background: var(--card);
    border-color: var(--forest);
    transform: translateY(-1px);
}

.tile-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.action-tile b { display: block; font-size: 13px; font-weight: 700; color: var(--forest); }
.action-tile span { display: block; font-size: 11.5px; color: var(--ink-muted); }

.activity-feed { display: flex; flex-direction: column; gap: 14px; }
.feed-item { display: flex; gap: 10px; align-items: flex-start; }
.feed-dot { width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
.feed-dot.bg-green { background: #16A34A; }
.feed-dot.bg-blue { background: #0284C7; }
.feed-dot.bg-amber { background: #D97706; }

.feed-content p { font-size: 12.5px; color: var(--ink); margin: 0 0 2px 0; line-height: 1.35; }
.feed-content small { font-size: 11px; color: var(--ink-muted); font-family: var(--font-mono); }
</style>
