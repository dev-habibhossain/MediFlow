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
    <Head title="Doctor Performance Report — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <Link href="/admin/reports" class="back-btn">← Back to Reports Hub</Link>
        <button class="btn-export" @click="triggerExport">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="7 10 12 15 17 10" /><line x1="12" y1="15" x2="12" y2="3" />
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
                    <tr>
                        <td>
                            <div class="doctor-cell">
                                <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=150" alt="Dr. Sarah Jenkins" class="doctor-avatar" />
                                <div class="doctor-meta">
                                    <b>Dr. Sarah Jenkins</b>
                                    <span>MD-90412</span>
                                </div>
                            </div>
                        </td>
                        <td><b>Cardiology</b></td>
                        <td style="font-family: var(--font-mono); font-weight: 700;">148 Visits</td>
                        <td style="font-family: var(--font-mono); color: #15803D; font-weight: 700;">98.6%</td>
                        <td><span class="rating-tag">★ 4.9 <small style="color: var(--ink-muted); font-weight: normal;">(142)</small></span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="doctor-cell">
                                <img src="https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&q=80&w=150" alt="Dr. Marcus Vance" class="doctor-avatar" />
                                <div class="doctor-meta">
                                    <b>Dr. Marcus Vance</b>
                                    <span>MD-88210</span>
                                </div>
                            </div>
                        </td>
                        <td><b>Neurology</b></td>
                        <td style="font-family: var(--font-mono); font-weight: 700;">92 Visits</td>
                        <td style="font-family: var(--font-mono); color: #15803D; font-weight: 700;">96.8%</td>
                        <td><span class="rating-tag">★ 4.8 <small style="color: var(--ink-muted); font-weight: normal;">(88)</small></span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="doctor-cell">
                                <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&q=80&w=150" alt="Dr. Emily Watson" class="doctor-avatar" />
                                <div class="doctor-meta">
                                    <b>Dr. Emily Watson</b>
                                    <span>MD-77102</span>
                                </div>
                            </div>
                        </td>
                        <td><b>Pediatrics</b></td>
                        <td style="font-family: var(--font-mono); font-weight: 700;">210 Visits</td>
                        <td style="font-family: var(--font-mono); color: #15803D; font-weight: 700;">99.1%</td>
                        <td><span class="rating-tag">★ 4.9 <small style="color: var(--ink-muted); font-weight: normal;">(204)</small></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12" /></svg>
        Doctor Performance Report CSV downloaded successfully!
    </div>
</template>

<style>
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
.doctor-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background: var(--cream-alt); flex-shrink: 0; }
.doctor-meta b { display: block; font-size: 14px; font-weight: 700; color: var(--forest); }
.doctor-meta span { display: block; font-size: 11.5px; color: var(--ink-muted); font-family: var(--font-mono); }
.rating-tag { font-weight: 700; color: #D97706; display: inline-flex; align-items: center; gap: 4px; font-size: 13px; }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
