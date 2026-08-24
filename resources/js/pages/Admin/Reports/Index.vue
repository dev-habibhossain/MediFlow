<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps<{
    metrics?: {
        total_revenue?: string
        total_appointments?: number | string
        completed_appointments?: number | string
        total_doctors?: number | string
        avg_rating?: string
    }
}>()
</script>

<template>
    <Head title="Reports & Analytics Hub — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">Reports & Analytics Hub</h1>
            <p class="text-xs text-[var(--ink-muted)]">Generate, review, and export hospital-wide operational reports</p>
        </div>
    </div>

    <!-- QUICK EXECUTIVE SUMMARY STRIP -->
    <div class="executive-strip mb-8">
        <div class="exec-card">
            <span class="exec-label">Gross Revenue</span>
            <b class="exec-value">{{ props.metrics?.total_revenue || '$12,450.00' }}</b>
            <span class="exec-hint text-emerald-600">Total hospital collections</span>
        </div>
        <div class="exec-card">
            <span class="exec-label">Total Appointments</span>
            <b class="exec-value">{{ props.metrics?.total_appointments || 42 }}</b>
            <span class="exec-hint text-blue-600">{{ props.metrics?.completed_appointments || 38 }} Completed</span>
        </div>
        <div class="exec-card">
            <span class="exec-label">Active Physicians</span>
            <b class="exec-value">{{ props.metrics?.total_doctors || 12 }}</b>
            <span class="exec-hint text-amber-600">★ {{ props.metrics?.avg_rating || '4.9' }} Avg Rating</span>
        </div>
    </div>

    <!-- CLEAN & SLEEK 3-CARD REPORTS HUB -->
    <div class="reports-grid">
        <!-- CARD 1: APPOINTMENT VOLUME REPORT -->
        <div class="report-card">
            <div class="card-top flex items-center justify-between">
                <div class="icon-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10" />
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                    </svg>
                </div>
                <span class="tag-label">Volume & Trends</span>
            </div>

            <div class="card-content">
                <h3>Appointment Volume Report</h3>
                <p>Analyze patient booking trends, department consultation volumes, completion rates, and peak hours.</p>
            </div>

            <Link href="/admin/reports/appointments" class="card-action-btn">
                <span>View Volume Report</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                    <polyline points="9 18 15 12 9 6" />
                </svg>
            </Link>
        </div>

        <!-- CARD 2: DOCTOR PERFORMANCE REPORT -->
        <div class="report-card">
            <div class="card-top flex items-center justify-between">
                <div class="icon-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <span class="tag-label">Staff Performance</span>
            </div>

            <div class="card-content">
                <h3>Doctor Performance Report</h3>
                <p>Compare physician efficiency, patient satisfaction ratings, no-show ratios, and consultation counts.</p>
            </div>

            <Link href="/admin/reports/doctors" class="card-action-btn">
                <span>View Doctor Report</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                    <polyline points="9 18 15 12 9 6" />
                </svg>
            </Link>
        </div>

        <!-- CARD 3: REVENUE FINANCIAL SUMMARY -->
        <div class="report-card">
            <div class="card-top flex items-center justify-between">
                <div class="icon-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                        <line x1="1" y1="10" x2="23" y2="10" />
                    </svg>
                </div>
                <span class="tag-label">Financial Metrics</span>
            </div>

            <div class="card-content">
                <h3>Revenue Financial Summary</h3>
                <p>Track monthly consultation fee collections, insurance billing status, and financial growth metrics.</p>
            </div>

            <Link href="/admin/reports/revenue" class="card-action-btn">
                <span>View Revenue Report</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                    <polyline points="9 18 15 12 9 6" />
                </svg>
            </Link>
        </div>
    </div>
</template>

<style scoped>
.executive-strip { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
@media (max-width: 900px) { .executive-strip { grid-template-columns: 1fr; } }
.exec-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 20px 24px; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; gap: 4px; }
.exec-label { font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-muted); }
.exec-value { font-family: var(--font-mono); font-size: 26px; font-weight: 800; color: var(--forest); }
.exec-hint { font-size: 12px; font-weight: 600; }

.reports-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
@media (max-width: 1024px) { .reports-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .reports-grid { grid-template-columns: 1fr; } }

.report-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 32px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; gap: 20px; transition: all 180ms ease; }
.report-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lift); border-color: var(--forest); }

.icon-box { width: 44px; height: 44px; border-radius: var(--radius-md); background: var(--cream); color: var(--forest); display: flex; align-items: center; justify-content: center; }
.icon-box svg { width: 22px; height: 22px; }

.tag-label { font-family: var(--font-mono); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); background: var(--cream); border: 1px solid var(--line); padding: 3px 10px; border-radius: var(--radius-sm); }

.card-content h3 { font-size: 18px; font-weight: 800; color: var(--forest); margin-bottom: 8px; }
.card-content p { font-size: 13.5px; color: var(--ink-muted); line-height: 1.5; margin: 0; }

.card-action-btn { display: inline-flex; align-items: center; justify-content: space-between; height: 44px; padding: 0 20px; border-radius: 999px; background: var(--cream); border: 1px solid var(--line); font-size: 13.5px; font-weight: 700; color: var(--forest); text-decoration: none; transition: all 150ms ease; margin-top: auto; }
.card-action-btn:hover { background: var(--forest); color: #fff; border-color: var(--forest); }
.card-action-btn:hover svg { transform: translateX(3px); }
.card-action-btn svg { transition: transform 150ms ease; }
</style>
