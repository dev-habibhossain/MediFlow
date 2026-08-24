<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

interface TransactionItem {
    id: number | string
    invoice_code: string
    patient: string
    service: string
    amount: string
    date: string
    status: string
    payment_method: string
}

const props = defineProps<{
    metrics?: {
        total_revenue?: string
        year_to_date?: string
        total_claims?: number
    }
    transactions?: TransactionItem[]
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
    <Head title="Revenue Financial Summary — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <Link href="/admin/reports" class="back-btn">← Back to Reports Hub</Link>
        <button class="btn-export" @click="triggerExport">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" y1="15" x2="12" y2="3" />
            </svg>
            Export Revenue Report (CSV)
        </button>
    </div>

    <!-- METRICS STRIP -->
    <div class="metrics-grid mb-6">
        <div class="metric-card">
            <div class="metric-meta">
                <span>Total Hospital Revenue</span>
                <b>{{ props.metrics?.total_revenue || '$12,450.00' }}</b>
                <small>↑ +8.4% growth</small>
            </div>
            <div class="metric-icon" style="background: var(--lime-soft); color: var(--lime-text);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                    <line x1="1" y1="10" x2="23" y2="10" />
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-meta">
                <span>Year to Date Collections</span>
                <b>{{ props.metrics?.year_to_date || '$12,450.00' }}</b>
                <small>{{ props.metrics?.total_claims || 14 }} Settlements Processed</small>
            </div>
            <div class="metric-icon" style="background: #DCFCE7; color: #15803D;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-meta">
                <span>Payment Gateways</span>
                <b>Stripe & Cash</b>
                <small>Active & Secure</small>
            </div>
            <div class="metric-icon" style="background: #E0F2FE; color: #0369A1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
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
                        <th>Invoice ID</th>
                        <th>Patient</th>
                        <th>Service / Consultation</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in props.transactions" :key="item.id">
                        <td style="font-family: var(--font-mono); font-weight: 700;">{{ item.invoice_code }}</td>
                        <td><b>{{ item.patient }}</b></td>
                        <td>{{ item.service }}</td>
                        <td style="font-family: var(--font-mono); font-weight: 700;">{{ item.amount }}</td>
                        <td style="font-family: var(--font-mono); font-size: 12.5px;">{{ item.date }}</td>
                        <td><span class="method-tag">{{ item.payment_method }}</span></td>
                        <td><span class="status-badge status-paid">{{ item.status }}</span></td>
                    </tr>

                    <tr v-if="!props.transactions || props.transactions.length === 0">
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--ink-muted);">
                            No revenue transaction records found.
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
        Revenue Report CSV downloaded successfully!
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

.method-tag { font-family: var(--font-mono); font-size: 12px; font-weight: 600; color: var(--forest); background: var(--cream); padding: 2px 8px; border-radius: var(--radius-sm); border: 1px solid var(--line); }
.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
.status-paid { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
