<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
    <Head title="Reports & Analytics Hub — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">Reports & Analytics Hub</h1>
            <p class="text-xs text-[var(--ink-muted)]">Generate, review, and export hospital-wide operational reports</p>
        </div>
    </div>

    <!-- REPORTS GRID -->
    <div class="reports-grid">
        <!-- CARD 1 -->
        <div class="report-card">
            <div class="report-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="20" x2="18" y2="10" /><line x1="12" y1="20" x2="12" y2="4" /><line x1="6" y1="20" x2="6" y2="14" />
                </svg>
            </div>
            <div class="report-info">
                <h3>Appointment Volume Report</h3>
                <p>Analyze patient booking trends, department consultation volumes, completion rates, and peak hours.</p>
            </div>
            <Link href="/admin/reports/appointments" class="report-btn">View Volume Report →</Link>
        </div>

        <!-- CARD 2 -->
        <div class="report-card">
            <div class="report-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </div>
            <div class="report-info">
                <h3>Doctor Performance Report</h3>
                <p>Compare physician efficiency, patient satisfaction ratings, no-show ratios, and consultation counts.</p>
            </div>
            <Link href="/admin/reports/doctors" class="report-btn">View Doctor Report →</Link>
        </div>

        <!-- CARD 3 -->
        <div class="report-card">
            <div class="report-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2" /><line x1="1" y1="10" x2="23" y2="10" />
                </svg>
            </div>
            <div class="report-info">
                <h3>Revenue Financial Summary</h3>
                <p>Track monthly consultation fee collections, insurance billing status, and financial growth metrics.</p>
            </div>
            <Link href="/admin/reports/revenue" class="report-btn">View Revenue Report →</Link>
        </div>
    </div>
</template>

<style>
.reports-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
@media (max-width: 1024px) { .reports-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .reports-grid { grid-template-columns: 1fr; } }

.report-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; gap: 16px; transition: transform 150ms ease, box-shadow 150ms ease; }
.report-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lift); }

.report-icon-box { width: 50px; height: 50px; border-radius: var(--radius-md); background: var(--cream); color: var(--forest); display: flex; align-items: center; justify-content: center; }
.report-icon-box svg { width: 24px; height: 24px; }

.report-info h3 { font-size: 17px; font-weight: 800; color: var(--forest); margin-bottom: 4px; }
.report-info p { font-size: 13px; color: var(--ink-muted); line-height: 1.45; }

.report-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 40px; border-radius: 999px; background: var(--cream); border: 1px solid var(--line); font-size: 13.5px; font-weight: 700; color: var(--forest); transition: all 150ms ease; margin-top: auto; text-decoration: none; }
.report-btn:hover { background: var(--forest); color: #fff; border-color: var(--forest); }
</style>
