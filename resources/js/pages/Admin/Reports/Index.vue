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

    <!-- ELEVATED 3-CARD REPORTS HUB -->
    <div class="reports-grid">
        <!-- CARD 1: APPOINTMENT VOLUME REPORT -->
        <div class="report-card">
            <div class="card-header-row">
                <div class="report-icon-box icon-forest">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10" />
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                    </svg>
                </div>
                <span class="category-pill">OPERATIONAL TRENDS</span>
            </div>

            <div class="report-info">
                <h3>Appointment Volume Report</h3>
                <p>Analyze patient booking trends, department consultation volumes, completion rates, and peak hours.</p>
            </div>

            <div class="card-footer-strip">
                <div class="mini-stat-badge">
                    <span>1,420 YTD Visits</span>
                </div>
                <Link href="/admin/reports/appointments" class="report-btn">Explore Volume Report →</Link>
            </div>
        </div>

        <!-- CARD 2: DOCTOR PERFORMANCE REPORT -->
        <div class="report-card highlight-card">
            <div class="card-header-row">
                <div class="report-icon-box icon-emerald">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <span class="category-pill pill-emerald">PHYSICIAN METRICS</span>
            </div>

            <div class="report-info">
                <h3>Doctor Performance Report</h3>
                <p>Compare physician efficiency, patient satisfaction ratings, no-show ratios, and consultation counts.</p>
            </div>

            <div class="card-footer-strip">
                <div class="mini-stat-badge badge-amber">
                    <span>★ 4.9 Satisfaction</span>
                </div>
                <Link href="/admin/reports/doctors" class="report-btn btn-primary-green">Explore Doctor Report →</Link>
            </div>
        </div>

        <!-- CARD 3: REVENUE FINANCIAL SUMMARY -->
        <div class="report-card">
            <div class="card-header-row">
                <div class="report-icon-box icon-lime">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                        <line x1="1" y1="10" x2="23" y2="10" />
                    </svg>
                </div>
                <span class="category-pill pill-lime">FINANCIAL INSIGHTS</span>
            </div>

            <div class="report-info">
                <h3>Revenue Financial Summary</h3>
                <p>Track monthly consultation fee collections, insurance billing status, and financial growth metrics.</p>
            </div>

            <div class="card-footer-strip">
                <div class="mini-stat-badge">
                    <span>Gross Collections</span>
                </div>
                <Link href="/admin/reports/revenue" class="report-btn">Explore Revenue Report →</Link>
            </div>
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

.report-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; gap: 20px; transition: all 200ms ease; position: relative; }
.report-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lift); border-color: var(--forest); }

.card-header-row { display: flex; align-items: center; justify-content: space-between; }
.report-icon-box { width: 48px; height: 48px; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; }
.report-icon-box svg { width: 22px; height: 22px; }

.icon-forest { background: var(--forest); color: var(--lime); }
.icon-emerald { background: #DCFCE7; color: #15803D; }
.icon-lime { background: var(--lime-soft); color: var(--lime-text); }

.category-pill { font-family: var(--font-mono); font-size: 10.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--forest); background: var(--cream); padding: 4px 10px; border-radius: 999px; border: 1px solid var(--line); }
.pill-emerald { color: #15803D; background: #F0FDF4; border-color: #BBF7D0; }
.pill-lime { color: var(--lime-text); background: var(--lime-soft); border-color: #d8f16c; }

.report-info h3 { font-size: 18px; font-weight: 800; color: var(--forest); margin-bottom: 6px; letter-spacing: -0.01em; }
.report-info p { font-size: 13.5px; color: var(--ink-muted); line-height: 1.5; }

.card-footer-strip { display: flex; flex-direction: column; gap: 12px; margin-top: auto; }
.mini-stat-badge { font-family: var(--font-mono); font-size: 11.5px; font-weight: 700; color: var(--ink-muted); background: var(--cream); padding: 6px 12px; border-radius: var(--radius-md); display: inline-block; width: fit-content; }
.badge-amber { color: #B45309; background: #FEF3C7; }

.report-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; border-radius: 999px; background: var(--cream); border: 1px solid var(--line); font-size: 13.5px; font-weight: 800; color: var(--forest); transition: all 150ms ease; text-decoration: none; width: 100%; }
.report-btn:hover { background: var(--forest); color: #fff; border-color: var(--forest); }
.btn-primary-green { background: var(--forest); color: var(--lime); border-color: var(--forest); }
.btn-primary-green:hover { background: var(--forest-2); color: #fff; }
</style>
