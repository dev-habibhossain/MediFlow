<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const showToast = ref(false)

function triggerExport() {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 3000)
}
</script>

<template>
    <Head title="Appointment Volume Report — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <Link href="/admin/reports" class="back-btn">← Back to Reports Hub</Link>
        <button class="btn-export" @click="triggerExport">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="7 10 12 15 17 10" /><line x1="12" y1="15" x2="12" y2="3" />
            </svg>
            Export Report (CSV)
        </button>
    </div>

    <!-- METRICS STRIP -->
    <div class="metrics-grid mb-6">
        <div class="metric-card">
            <div class="metric-meta">
                <span>Total Consultations (YTD)</span>
                <b>1,420</b>
                <small>↑ +14.2% vs last year</small>
            </div>
            <div class="metric-icon" style="background: #DCFCE7; color: #15803D;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12" /></svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-meta">
                <span>In-Person Visits</span>
                <b>1,022 (72%)</b>
                <small>Stable volume</small>
            </div>
            <div class="metric-icon" style="background: #E0F2FE; color: #0369A1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /></svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-meta">
                <span>Telehealth Consultations</span>
                <b>398 (28%)</b>
                <small>↑ +8.5% growth</small>
            </div>
            <div class="metric-icon" style="background: #FEF3C7; color: #B45309;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2" /><line x1="8" y1="21" x2="16" y2="21" /><line x1="12" y1="17" x2="12" y2="21" /></svg>
            </div>
        </div>
    </div>

    <!-- CHART CARD -->
    <div class="card-shell">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="20" x2="18" y2="10" /><line x1="12" y1="20" x2="12" y2="4" /><line x1="6" y1="20" x2="6" y2="14" />
                </svg>
                Monthly Appointment Volume Trend
            </h3>
        </div>

        <div class="chart-box">
            <div class="chart-legend">
                <div class="legend-item"><span class="legend-dot"></span> In-Person Visits</div>
                <div class="legend-item"><span class="legend-dot lime"></span> Telehealth Calls</div>
            </div>

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

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12" /></svg>
        Appointment Volume Report CSV downloaded successfully!
    </div>
</template>

<style>
.back-btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 6px 14px; border-radius: 999px; text-decoration: none; transition: all 150ms ease; }
.back-btn:hover { background: var(--card); border-color: var(--forest); }

.btn-export { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 18px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 13.5px; font-weight: 700; box-shadow: var(--shadow-sm); border: 0; cursor: pointer; transition: background-color 150ms ease; }
.btn-export:hover { background: var(--forest-2); }

.metrics-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
@media (max-width: 900px) { .metrics-grid { grid-template-columns: 1fr; } }

.metric-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 22px; box-shadow: var(--shadow-sm); display: flex; justify-content: space-between; align-items: flex-start; }
.metric-meta span { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-muted); display: block; margin-bottom: 6px; }
.metric-meta b { font-family: var(--font-mono); font-size: 28px; font-weight: 800; color: var(--forest); line-height: 1; display: block; }
.metric-meta small { font-size: 12px; color: #15803D; font-weight: 600; display: block; margin-top: 6px; }
.metric-icon { width: 44px; height: 44px; border-radius: var(--radius-md); background: var(--cream); color: var(--forest); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; padding: 28px; }
.card-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 16px; margin-bottom: 20px; }
.card-header h3 { font-size: 16px; font-weight: 800; color: var(--forest); display: flex; align-items: center; gap: 10px; }

.chart-box { padding: 10px 0; }
.chart-legend { display: flex; gap: 20px; font-size: 12.5px; font-weight: 600; color: var(--ink-muted); margin-bottom: 20px; }
.legend-item { display: flex; align-items: center; gap: 6px; }
.legend-dot { width: 10px; height: 10px; border-radius: 3px; background: var(--forest); }
.legend-dot.lime { background: var(--lime); border: 1px solid #c4dc3c; }

.chart-bars-wrap { display: flex; align-items: flex-end; justify-content: space-between; height: 220px; padding-top: 20px; border-bottom: 1px solid var(--line); gap: 12px; }
.chart-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px; height: 100%; justify-content: flex-end; }
.chart-bar-pillar { width: 100%; max-width: 42px; background: var(--forest); border-radius: 6px 6px 0 0; transition: all 150ms ease; }
.chart-bar-pillar:hover { background: var(--lime); }
.chart-label { font-family: var(--font-mono); font-size: 11.5px; font-weight: 600; color: var(--ink-muted); }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
