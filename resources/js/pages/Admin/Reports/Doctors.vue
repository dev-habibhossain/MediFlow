<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

interface DoctorReportItem {
    id: number | string
    name: string
    avatar_url?: string
    license: string
    department: string
    consultations: string
    completion_rate: string
    rating: string
    review_count: number
}

const props = defineProps<{
    doctors?: DoctorReportItem[]
}>()

const showToast = ref(false)

function triggerExport() {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 3000)
}

function getInitials(name?: string) {
    if (!name) return 'DR'
    return name
        .replace(/^Dr\.\s*/i, '')
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2)
}
</script>

<template>
    <Head title="Doctor Performance Report — Admin Portal" />

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

    <!-- DATA TABLE CARD -->
    <div class="card-shell">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Physician Details</th>
                        <th>Department</th>
                        <th>Total Consultations</th>
                        <th>Completion Rate</th>
                        <th>Satisfaction Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="doc in props.doctors" :key="doc.id">
                        <td>
                            <div class="doctor-cell">
                                <div class="avatar-circle">
                                    <img v-if="doc.avatar_url" :src="doc.avatar_url" :alt="doc.name" class="doctor-avatar-fit" />
                                    <span v-else class="avatar-fallback">{{ getInitials(doc.name) }}</span>
                                </div>
                                <div class="doctor-meta">
                                    <b>{{ doc.name }}</b>
                                    <span>{{ doc.license }}</span>
                                </div>
                            </div>
                        </td>
                        <td><b>{{ doc.department }}</b></td>
                        <td style="font-family: var(--font-mono); font-weight: 700;">{{ doc.consultations }}</td>
                        <td style="font-family: var(--font-mono); color: #15803D; font-weight: 700;">{{ doc.completion_rate }}</td>
                        <td>
                            <span class="rating-tag">★ {{ doc.rating }} <small style="color: var(--ink-muted); font-weight: normal;">({{ doc.review_count }})</small></span>
                        </td>
                    </tr>

                    <tr v-if="!props.doctors || props.doctors.length === 0">
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--ink-muted);">
                            No physician performance records found.
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
        Doctor Performance Report CSV downloaded successfully!
    </div>
</template>

<style scoped>
.back-btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 6px 14px; border-radius: 999px; text-decoration: none; transition: all 150ms ease; }
.back-btn:hover { background: var(--card); border-color: var(--forest); }

.btn-export { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 18px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 13.5px; font-weight: 700; box-shadow: var(--shadow-sm); border: 0; cursor: pointer; transition: background-color 150ms ease; }
.btn-export:hover { background: var(--forest-2); }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 16px 24px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.doctor-cell { display: flex; align-items: center; gap: 12px; }
.avatar-circle { width: 40px; height: 40px; border-radius: 50%; background: var(--forest); color: var(--lime); font-weight: 800; font-size: 13px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; border: 1px solid var(--line); }
.doctor-avatar-fit { width: 100%; height: 100%; object-fit: cover; }
.avatar-fallback { font-family: var(--font-mono); }
.doctor-meta b { display: block; font-size: 14px; font-weight: 700; color: var(--forest); }
.doctor-meta span { display: block; font-size: 11.5px; color: var(--ink-muted); font-family: var(--font-mono); }
.rating-tag { font-weight: 700; color: #D97706; display: inline-flex; align-items: center; gap: 4px; font-size: 13px; }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
