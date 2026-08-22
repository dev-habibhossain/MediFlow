<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
    <Head title="Admin Dashboard — MediFlow" />

    <!-- METRICS GRID -->
    <div class="metrics-grid">
        <div class="stat-card">
            <div class="stat-meta">
                <span>Total Doctors</span>
                <b>28</b>
                <small class="trend-up">↑ +2 added this month</small>
            </div>
            <div class="stat-icon icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-meta">
                <span>Active Patients</span>
                <b>1,840</b>
                <small class="trend-up">↑ +124 new registrations</small>
            </div>
            <div class="stat-icon icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-meta">
                <span>Appointments Today</span>
                <b>42</b>
                <small class="sub-detail">36 Completed · 6 In Progress</small>
            </div>
            <div class="stat-icon icon-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-meta">
                <span>Monthly Revenue</span>
                <b>$42,800</b>
                <small class="trend-up">↑ +8.4% growth</small>
            </div>
            <div class="stat-icon icon-lime">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- MAIN DASHBOARD SPLIT GRID -->
    <div class="dashboard-grid">
        <!-- LEFT: APPOINTMENT TREND CHART & SYSTEM LOGS -->
        <div class="left-col">
            <!-- CHART CARD -->
            <div class="card-shell">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                        Monthly Appointment Volume
                    </h3>
                    <Link href="/admin/reports" class="header-link">Detailed volume report →</Link>
                </div>

                <div class="chart-box">
                    <div class="chart-bars-wrap">
                        <div class="chart-col"><div class="chart-bar-pillar" style="height: 55%;"></div><span class="chart-label">Mar</span></div>
                        <div class="chart-col"><div class="chart-bar-pillar" style="height: 70%;"></div><span class="chart-label">Apr</span></div>
                        <div class="chart-col"><div class="chart-bar-pillar" style="height: 82%;"></div><span class="chart-label">May</span></div>
                        <div class="chart-col"><div class="chart-bar-pillar" style="height: 78%;"></div><span class="chart-label">Jun</span></div>
                        <div class="chart-col"><div class="chart-bar-pillar" style="height: 92%;"></div><span class="chart-label">Jul</span></div>
                        <div class="chart-col"><div class="chart-bar-pillar chart-highlight" style="height: 96%;"></div><span class="chart-label">Aug</span></div>
                    </div>
                </div>
            </div>

            <!-- SYSTEM ACTIVITY LOG -->
            <div class="card-shell">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        </svg>
                        System Audit Feed
                    </h3>
                    <Link href="/admin/activity-logs" class="header-link">All logs →</Link>
                </div>

                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-dot"></div>
                        <div class="activity-content">
                            <p>Dr. Marcus Vance onboarded to Neurology Department.</p>
                            <span>10 minutes ago · Admin User #1</span>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-dot dot-blue"></div>
                        <div class="activity-content">
                            <p>Payment #INV-89201 processed for consultation #MDF-102.</p>
                            <span>1 hour ago · Stripe Webhook</span>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-dot dot-amber"></div>
                        <div class="activity-content">
                            <p>Holiday Schedule exception added for Independence Day.</p>
                            <span>Yesterday · Admin Console</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR: QUICK ACTIONS & CONTROLS -->
        <div class="right-col">
            <!-- QUICK ACTIONS -->
            <div class="card-shell">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/>
                        </svg>
                        Management Actions
                    </h3>
                </div>

                <div class="quick-action-list">
                    <Link href="/admin/doctors/create" class="action-item">
                        <div class="action-icon icon-lime">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>Onboard New Doctor</h4>
                            <p>Register new physician account</p>
                        </div>
                    </Link>

                    <Link href="/admin/departments/create" class="action-item">
                        <div class="action-icon icon-blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 21h18M3 7v14M21 7v14M6 3h12a2 2 0 0 1 2 2v2H4V5a2 2 0 0 1 2-2z"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>Add Department</h4>
                            <p>Create medical specialty unit</p>
                        </div>
                    </Link>

                    <Link href="/admin/announcements" class="action-item">
                        <div class="action-icon icon-amber">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>Broadcast Announcement</h4>
                            <p>Send system-wide notification</p>
                        </div>
                    </Link>

                    <Link href="/admin/settings" class="action-item">
                        <div class="action-icon icon-forest">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>System Settings Control</h4>
                            <p>Configure hospital parameters</p>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- SYSTEM HEALTH & CONFIG SUMMARY -->
            <div class="card-shell config-summary-card">
                <b class="config-title">System Configuration</b>
                <div class="config-list">
                    <div class="config-row">
                        <span>Hospital Name:</span> <strong>MediFlow Central</strong>
                    </div>
                    <div class="config-row">
                        <span>Slot Duration:</span> <strong>30 Minutes</strong>
                    </div>
                    <div class="config-row">
                        <span>Payment Gateway:</span> <strong class="text-green">Stripe Active</strong>
                    </div>
                </div>
                <Link href="/admin/settings" class="config-link">
                    Manage Hospital Settings →
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
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
.stat-meta small.trend-up { font-size: 12px; color: #15803D; font-weight: 600; display: block; margin-top: 4px; }
.stat-meta small.sub-detail { font-size: 12px; color: var(--ink-muted); font-weight: 600; display: block; margin-top: 4px; }

.stat-icon { width: 44px; height: 44px; border-radius: var(--radius-md); background: var(--cream); color: var(--forest); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-icon svg { width: 22px; height: 22px; }
.stat-icon.icon-green { background: #DCFCE7; color: #15803D; }
.stat-icon.icon-blue { background: #E0F2FE; color: #0369A1; }
.stat-icon.icon-amber { background: #FEF3C7; color: #B45309; }
.stat-icon.icon-lime { background: var(--lime-soft); color: var(--lime-text); }

.dashboard-grid { display: grid; grid-template-columns: 1fr 340px; gap: 28px; align-items: start; }
@media (max-width: 1100px) { .dashboard-grid { grid-template-columns: 1fr; } }

.left-col, .right-col { display: flex; flex-direction: column; gap: 28px; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.card-header { padding: 20px 24px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; background: var(--cream); }
.card-header h3 { font-size: 15.5px; font-weight: 800; color: var(--forest); display: flex; align-items: center; gap: 8px; margin: 0; }
.card-header h3 svg { width: 18px; height: 18px; color: var(--forest); }
.header-link { font-size: 13px; font-weight: 700; color: var(--forest); text-decoration: none; transition: opacity 150ms ease; }
.header-link:hover { opacity: 0.8; text-decoration: underline; }

.chart-box { padding: 24px; }
.chart-bars-wrap { display: flex; align-items: flex-end; justify-content: space-between; height: 200px; padding-top: 20px; border-bottom: 1px solid var(--line); gap: 12px; }
.chart-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px; height: 100%; justify-content: flex-end; }
.chart-bar-pillar { width: 100%; max-width: 42px; background: var(--forest); border-radius: 6px 6px 0 0; transition: all 180ms ease; }
.chart-bar-pillar:hover { background: #1e4029; transform: scaleY(1.02); }
.chart-bar-pillar.chart-highlight { background: var(--lime); }
.chart-bar-pillar.chart-highlight:hover { background: #d2e85a; }
.chart-label { font-family: var(--font-mono); font-size: 11.5px; font-weight: 600; color: var(--ink-muted); }

.activity-list { padding: 18px 24px; display: flex; flex-direction: column; gap: 14px; }
.activity-item { display: flex; gap: 12px; align-items: flex-start; }
.activity-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--forest); margin-top: 5px; flex-shrink: 0; }
.activity-dot.dot-blue { background: #0369A1; }
.activity-dot.dot-amber { background: #B45309; }
.activity-content p { font-size: 13px; color: var(--ink); line-height: 1.4; margin: 0 0 2px 0; }
.activity-content span { font-size: 11px; color: var(--ink-muted); font-family: var(--font-mono); }

.quick-action-list { padding: 16px 24px; display: flex; flex-direction: column; gap: 10px; }
.action-item { display: flex; align-items: center; gap: 14px; padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); transition: all 180ms ease; text-decoration: none; }
.action-item:hover { border-color: var(--forest); background: var(--card); box-shadow: var(--shadow-sm); transform: translateY(-1px); }
.action-icon { width: 40px; height: 40px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.action-icon svg { width: 18px; height: 18px; }
.action-icon.icon-lime { background: var(--lime-soft); color: var(--lime-text); }
.action-icon.icon-blue { background: #E0F2FE; color: #0369A1; }
.action-icon.icon-amber { background: #FEF3C7; color: #B45309; }
.action-icon.icon-forest { background: var(--forest); color: var(--lime); }
.action-info h4 { font-size: 13.5px; font-weight: 700; color: var(--ink); margin: 0; }
.action-info p { font-size: 12px; color: var(--ink-muted); margin: 2px 0 0 0; }

.config-summary-card { padding: 20px 24px; }
.config-title { font-size: 14px; color: var(--forest); display: block; margin-bottom: 10px; font-weight: 800; }
.config-list { font-size: 12.5px; color: var(--ink-muted); display: flex; flex-direction: column; gap: 8px; }
.config-row { display: flex; justify-content: space-between; align-items: center; }
.text-green { color: #15803D; font-weight: 700; }
.config-link { display: inline-block; font-size: 12.5px; font-weight: 700; color: var(--forest); text-decoration: none; margin-top: 14px; transition: opacity 150ms ease; }
.config-link:hover { text-decoration: underline; opacity: 0.85; }
</style>
