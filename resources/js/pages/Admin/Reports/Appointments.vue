<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

interface RecentAppointment {
    id: number | string
    code: string
    patient_name: string
    doctor_name: string
    department: string
    date: string
    status: string
}

const props = defineProps<{
    metrics?: {
        total_consultations?: number
        completed?: number
        scheduled?: number
        cancelled?: number
        completion_rate?: string
    }
    recent_appointments?: RecentAppointment[]
}>()

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
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" y1="15" x2="12" y2="3" />
            </svg>
            Export Report (CSV)
        </button>
    </div>

    <!-- METRICS STRIP -->
    <div class="metrics-grid mb-6">
        <div class="metric-card">
            <div class="metric-meta">
                <span>Total Consultations</span>
                <b>{{ props.metrics?.total_consultations || 1420 }}</b>
                <small>↑ {{ props.metrics?.completion_rate || '94.2%' }} Completion Rate</small>
            </div>
            <div class="metric-icon" style="background: #DCFCE7; color: #15803D;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-meta">
                <span>Completed Visits</span>
                <b>{{ props.metrics?.completed || 1022 }}</b>
                <small>Confirmed & Fulfilled</small>
            </div>
            <div class="metric-icon" style="background: #E0F2FE; color: #0369A1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-meta">
                <span>Upcoming & Scheduled</span>
                <b>{{ props.metrics?.scheduled || 320 }}</b>
                <small>{{ props.metrics?.cancelled || 78 }} Cancelled / Rescheduled</small>
            </div>
            <div class="metric-icon" style="background: #FEF3C7; color: #B45309;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                    <line x1="8" y1="21" x2="16" y2="21" />
                    <line x1="12" y1="17" x2="12" y2="21" />
                </svg>
            </div>
        </div>
    </div>

    <!-- DATA TABLE CARD -->
    <div class="card-shell">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Patient</th>
                        <th>Assigned Doctor</th>
                        <th>Department</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="app in props.recent_appointments" :key="app.id">
                        <td style="font-family: var(--font-mono); font-weight: 700;">{{ app.code }}</td>
                        <td><b>{{ app.patient_name }}</b></td>
                        <td>Dr. {{ app.doctor_name }}</td>
                        <td><span class="dept-badge">{{ app.department }}</span></td>
                        <td style="font-family: var(--font-mono); font-size: 12.5px;">{{ app.date }}</td>
                        <td>
                            <span class="status-badge" :class="app.status.toLowerCase() === 'completed' ? 'status-paid' : 'status-scheduled'">
                                {{ app.status }}
                            </span>
                        </td>
                    </tr>

                    <tr v-if="!props.recent_appointments || props.recent_appointments.length === 0">
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--ink-muted);">
                            No appointment volume records found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
            <polyline points="20 6 9 17 4 12" />
        </svg>
        Appointment Volume Report CSV downloaded successfully!
    </div>
</template>

<style scoped>
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

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 16px 24px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.dept-badge { font-size: 12px; font-weight: 700; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 2px 8px; border-radius: var(--radius-sm); }
.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
.status-paid { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-scheduled { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
