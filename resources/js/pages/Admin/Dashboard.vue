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
                <small>↑ +2 added this month</small>
            </div>
            <div class="stat-icon" style="background: #DCFCE7; color: #15803D;">
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
                <small>↑ +124 new registrations</small>
            </div>
            <div class="stat-icon" style="background: #E0F2FE; color: #0369A1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-meta">
                <span>Appointments Today</span>
                <b>42</b>
                <small>36 Completed · 6 In Progress</small>
            </div>
            <div class="stat-icon" style="background: #FEF3C7; color: #B45309;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-meta">
                <span>Monthly Revenue</span>
                <b>$42,800</b>
                <small>↑ +8.4% growth</small>
            </div>
            <div class="stat-icon" style="background: var(--lime-soft); color: var(--lime-text);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- MAIN DASHBOARD SPLIT GRID -->
    <div class="dashboard-grid">
        <!-- LEFT: APPOINTMENT TREND CHART & SYSTEM LOGS -->
        <div style="display: flex; flex-direction: column; gap: 28px;">
            <!-- CHART CARD -->
            <div class="card-shell">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                        Monthly Appointment Volume
                    </h3>
                    <Link href="/admin/reports">Detailed volume report →</Link>
                </div>

                <div class="chart-box">
                    <div class="chart-bars-wrap">
                        <div class="chart-col"><div class="chart-bar-pillar" style="height: 55%;"></div><span class="chart-label">Mar</span></div>
                        <div class="chart-col"><div class="chart-bar-pillar" style="height: 70%;"></div><span class="chart-label">Apr</span></div>
                        <div class="chart-col"><div class="chart-bar-pillar" style="height: 82%;"></div><span class="chart-label">May</span></div>
                        <div class="chart-col"><div class="chart-bar-pillar" style="height: 78%;"></div><span class="chart-label">Jun</span></div>
                        <div class="chart-col"><div class="chart-bar-pillar" style="height: 92%;"></div><span class="chart-label">Jul</span></div>
                        <div class="chart-col"><div class="chart-bar-pillar" style="height: 96%; background: var(--lime);"></div><span class="chart-label">Aug</span></div>
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
                    <Link href="/admin/activity-logs">All logs</Link>
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
                        <div class="activity-dot" style="background: #0369A1;"></div>
                        <div class="activity-content">
                            <p>Payment #INV-89201 processed for consultation #MDF-102.</p>
                            <span>1 hour ago · Stripe Webhook</span>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-dot" style="background: #B45309;"></div>
                        <div class="activity-content">
                            <p>Holiday Schedule exception added for Independence Day.</p>
                            <span>Yesterday · Admin Console</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR: QUICK ACTIONS & CONTROLS -->
        <div style="display: flex; flex-direction: column; gap: 28px;">
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
                        <div class="action-icon">
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
                        <div class="action-icon" style="background: #E0F2FE; color: #0369A1;">
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
                        <div class="action-icon" style="background: #FEF3C7; color: #B45309;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                        </div>
                        <div class="action-info">
                            <h4>Broadcast Announcement</h4>
                            <p>Send system-wide notification</p>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- SYSTEM HEALTH & CONFIG SUMMARY -->
            <div class="card-shell" style="padding: 20px 24px;">
                <b style="font-size: 14px; color: var(--forest); display: block; margin-bottom: 8px;">System Configuration</b>
                <div style="font-size: 12.5px; color: var(--ink-muted); display: flex; flex-direction: column; gap: 6px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Hospital Name:</span> <strong>MediFlow Central</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Slot Duration:</span> <strong>30 Minutes</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Payment Gateway:</span> <strong style="color: #15803D;">Stripe Active</strong>
                    </div>
                </div>
                <Link href="/admin/settings/general" style="display: inline-block; font-size: 12.5px; font-weight: 700; color: var(--forest); text-decoration: underline; margin-top: 12px;">
                    Manage Hospital Settings →
                </Link>
            </div>
        </div>
    </div>
</template>

<style>
.metrics-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
@media (max-width: 1100px) { .metrics-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 540px) { .metrics-grid { grid-template-columns: 1fr; } }

.stat-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 20px; box-shadow: var(--shadow-sm); display: flex; justify-content: space-between; align-items: flex-start; }
.stat-meta span { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 4px; }
.stat-meta b { font-family: var(--font-mono); font-size: 26px; font-weight: 800; color: var(--forest); line-height: 1; display: block; }
.stat-meta small { font-size: 12px; color: #15803D; font-weight: 600; display: block; margin-top: 4px; }

.stat-icon { width: 44px; height: 44px; border-radius: var(--radius-md); background: var(--cream); color: var(--forest); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-icon svg { width: 20px; height: 20px; }

.dashboard-grid { display: grid; grid-template-columns: 1fr 340px; gap: 28px; align-items: start; }
@media (max-width: 1100px) { .dashboard-grid { grid-template-columns: 1fr; } }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.card-header { padding: 20px 24px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; }
.card-header h3 { font-size: 16px; font-weight: 800; color: var(--forest); display: flex; align-items: center; gap: 8px; margin: 0; }
.card-header h3 svg { width: 18px; height: 18px; color: var(--forest); }
.card-header a { font-size: 13px; font-weight: 700; color: var(--forest); text-decoration: underline; }

.chart-box { padding: 24px; }
.chart-bars-wrap { display: flex; align-items: flex-end; justify-content: space-between; height: 200px; padding-top: 20px; border-bottom: 1px solid var(--line); gap: 12px; }
.chart-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px; height: 100%; justify-content: flex-end; }
.chart-bar-pillar { width: 100%; max-width: 42px; background: var(--forest); border-radius: 6px 6px 0 0; transition: background 150ms ease; }
.chart-bar-pillar:hover { background: var(--lime); }
.chart-label { font-family: var(--font-mono); font-size: 11.5px; font-weight: 600; color: var(--ink-muted); }

.activity-list { padding: 16px 24px; display: flex; flex-direction: column; gap: 14px; }
.activity-item { display: flex; gap: 12px; align-items: flex-start; }
.activity-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--forest); margin-top: 6px; flex-shrink: 0; }
.activity-content p { font-size: 13px; color: var(--ink); line-height: 1.4; margin: 0 0 2px 0; }
.activity-content span { font-size: 11.5px; color: var(--ink-muted); font-family: var(--font-mono); }

.quick-action-list { padding: 16px 24px; display: flex; flex-direction: column; gap: 10px; }
.action-item { display: flex; align-items: center; gap: 14px; padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); transition: all 150ms ease; text-decoration: none; }
.action-item:hover { border-color: var(--forest); background: var(--card); box-shadow: var(--shadow-sm); transform: translateY(-1px); }
.action-icon { width: 38px; height: 38px; border-radius: var(--radius-sm); background: var(--lime-soft); color: var(--lime-text); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.action-icon svg { width: 18px; height: 18px; }
.action-info h4 { font-size: 14px; font-weight: 700; color: var(--ink); margin: 0; }
.action-info p { font-size: 12px; color: var(--ink-muted); margin: 0; }
</style>
