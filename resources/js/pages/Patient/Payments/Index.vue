<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface PaymentItem {
    id: number
    invCode: string
    doctorName: string
    desc: string
    date: string
    method: string
    status: 'paid' | 'pending' | 'refunded'
    amount: string
    amountRaw: number
    appointment_id: number | null
    searchStr: string
}

interface PaymentStats {
    total_paid: string
    paid_count: number
    total_pending: string
    pending_count: number
    total_refunded: string
    refunded_count: number
}

const props = defineProps<{
    payments: PaymentItem[]
    stats: PaymentStats
}>()

const activeTab = ref('all')
const searchQuery = ref('')
const toastMessage = ref('')
const showToast = ref(false)

const paidCount = computed(() => props.stats.paid_count)
const pendingCount = computed(() => props.stats.pending_count)
const refundedCount = computed(() => props.stats.refunded_count)

const filteredPayments = computed(() => {
    return props.payments.filter((p) => {
        const matchesTab = activeTab.value === 'all' || p.status === activeTab.value
        const q = searchQuery.value.toLowerCase().trim()
        const matchesQuery = !q || p.searchStr.toLowerCase().includes(q)
        return matchesTab && matchesQuery
    })
})

function triggerDownload(type: string, code: string) {
    toastMessage.value = `${type} for ${code} downloaded successfully!`
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 3000)
}

function clearSearch() {
    searchQuery.value = ''
}
</script>

<template>
    <Head title="Payment History" />

    <!-- METRICS OVERVIEW -->
    <div class="metrics-grid">
        <div class="metric-card card-paid">
            <div class="metric-info">
                <label>Total Paid</label>
                <b>{{ props.stats.total_paid }}</b>
                <span>Across {{ paidCount }} consultation{{ paidCount !== 1 ? 's' : '' }}</span>
            </div>
            <div class="metric-icon green-bg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
        </div>

        <div class="metric-card card-pending">
            <div class="metric-info">
                <label>Pending Fees</label>
                <b>{{ props.stats.total_pending }}</b>
                <span>{{ pendingCount }} Unpaid consultation{{ pendingCount !== 1 ? 's' : '' }}</span>
            </div>
            <div class="metric-icon yellow-bg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
        </div>

        <div class="metric-card card-refunded">
            <div class="metric-info">
                <label>Total Refunded</label>
                <b>{{ props.stats.total_refunded }}</b>
                <span>{{ refundedCount }} Cancelled visit{{ refundedCount !== 1 ? 's' : '' }}</span>
            </div>
            <div class="metric-icon gray-bg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- TOOLBAR ROW -->
    <div class="toolbar-row">
        <div class="tab-group">
            <button class="tab-btn" :class="{ active: activeTab === 'all' }" @click="activeTab = 'all'">
                All Invoices <span class="tab-badge">{{ props.payments.length }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'paid' }" @click="activeTab = 'paid'">
                Paid <span class="tab-badge">{{ paidCount }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'pending' }" @click="activeTab = 'pending'">
                Pending <span class="tab-badge">{{ pendingCount }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'refunded' }" @click="activeTab = 'refunded'">
                Refunded <span class="tab-badge">{{ refundedCount }}</span>
            </button>
        </div>

        <div class="search-box">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Search invoice or doctor..." />
            <button v-if="searchQuery" class="clear-btn" title="Clear search" @click="clearSearch">✕</button>
        </div>
    </div>

    <!-- PAYMENTS TABLE -->
    <div class="card-shell">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Invoice Ref</th>
                        <th>Consultation Description</th>
                        <th>Date Issued</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="pay in filteredPayments" :key="pay.id" class="table-row">
                        <td class="inv-code">{{ pay.invCode }}</td>
                        <td>
                            <b class="doc-title">{{ pay.doctorName }}</b>
                            <span class="desc-sub">{{ pay.desc }}</span>
                        </td>
                        <td class="date-cell">{{ pay.date }}</td>
                        <td class="method-cell">{{ pay.method }}</td>
                        <td>
                            <span v-if="pay.status === 'paid'" class="status-tag status-paid">
                                <span class="status-dot"></span> Paid
                            </span>
                            <span v-else-if="pay.status === 'pending'" class="status-tag status-pending">
                                <span class="status-dot"></span> Action Required
                            </span>
                            <span v-else class="status-tag status-refunded">
                                <span class="status-dot"></span> Refunded
                            </span>
                        </td>
                        <td class="amount-val" :class="{ strike: pay.status === 'refunded' }">{{ pay.amount }}</td>
                        <td class="text-right">
                            <div class="action-cell-wrap">
                                <Link v-if="pay.status === 'pending'" :href="`/patient/appointments/${pay.id}/pay`" class="btn-action pay-btn">
                                    <span>Pay {{ pay.amount }} Now</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                                    </svg>
                                </Link>
                                <button v-else-if="pay.status === 'paid'" class="btn-action secondary-btn" @click="triggerDownload('Receipt PDF', pay.invCode)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    <span>Receipt</span>
                                </button>
                                <button v-else class="btn-action secondary-btn" @click="triggerDownload('Credit Note PDF', pay.invCode)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                    <span>Credit Note</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="filteredPayments.length === 0">
                        <td colspan="7" class="empty-state-cell">
                            <div class="empty-state-wrap">
                                <div class="empty-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="28" height="28">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                                    </svg>
                                </div>
                                <h4>No invoices found</h4>
                                <p>We couldn't find any payment record matching your current filter or search criteria.</p>
                                <button class="reset-filter-btn" @click="activeTab = 'all'; clearSearch()">Reset Filters</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <Transition name="toast">
        <div v-if="showToast" class="toast-popup">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <span>{{ toastMessage }}</span>
        </div>
    </Transition>
</template>

<style scoped>
.metrics-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 28px; }
@media (max-width: 900px) { .metrics-grid { grid-template-columns: 1fr; } }

.metric-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 22px; box-shadow: var(--shadow-sm); display: flex; align-items: center; justify-content: space-between; transition: all 200ms ease; position: relative; overflow: hidden; }
.metric-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lift); }

.card-paid::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: #16A34A; }
.card-pending::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: #D97706; }
.card-refunded::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--ink-muted); }

.metric-info label { font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-muted); display: block; margin-bottom: 4px; }
.metric-info b { font-family: var(--font-mono); font-size: 26px; font-weight: 800; color: var(--forest); display: block; line-height: 1.1; }
.metric-info span { font-size: 12px; color: var(--ink-muted); display: block; margin-top: 4px; }

.metric-icon { width: 48px; height: 48px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.metric-icon.green-bg { background: #DCFCE7; color: #15803D; }
.metric-icon.yellow-bg { background: #FEF3C7; color: #B45309; }
.metric-icon.gray-bg { background: var(--cream-alt); color: var(--ink); }
.metric-icon svg { width: 24px; height: 24px; }

.toolbar-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--line); padding-bottom: 18px; margin-bottom: 28px; }

.tab-group { display: flex; background: var(--card); border: 1px solid var(--line); border-radius: 999px; padding: 4px; gap: 4px; box-shadow: var(--shadow-sm); }
.tab-btn { padding: 8px 18px; border-radius: 999px; font-size: 13px; font-weight: 600; color: var(--ink-muted); transition: all 150ms ease; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; border: none; background: transparent; }
.tab-btn:hover { color: var(--ink); }
.tab-btn.active { background: var(--forest); color: #fff; box-shadow: 0 2px 6px rgba(22, 48, 31, 0.2); }
.tab-badge { font-family: var(--font-mono); font-size: 11px; padding: 2px 7px; border-radius: 999px; background: rgba(22,24,15,0.06); color: inherit; font-weight: 700; }
.tab-btn.active .tab-badge { background: rgba(255,255,255,0.22); color: #fff; }

.search-box { position: relative; width: 290px; }
.search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); pointer-events: none; }
.search-box input { width: 100%; height: 42px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); padding: 0 36px 0 40px; font-size: 13.5px; color: var(--ink); transition: all 150ms ease; }
.search-box input:focus { outline: none; border-color: var(--forest); box-shadow: 0 0 0 3px rgba(22, 48, 31, 0.08); }
.clear-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; border-radius: 50%; background: var(--cream-alt); border: none; font-size: 11px; color: var(--ink-muted); cursor: pointer; display: flex; align-items: center; justify-content: center; }
.clear-btn:hover { background: var(--line); color: var(--ink); }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }

.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-muted); border-bottom: 1px solid var(--line); white-space: nowrap; }
.data-table td { padding: 18px 24px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }
.table-row { transition: background 150ms ease; }
.table-row:hover { background: rgba(248, 246, 239, 0.6); }
.data-table tr:last-child td { border-bottom: none; }

.inv-code { font-family: var(--font-mono); font-weight: 700; color: var(--forest); font-size: 13.5px; white-space: nowrap; }

.doc-title { display: block; font-size: 14px; color: var(--ink); font-weight: 700; }
.desc-sub { font-size: 12px; color: var(--ink-muted); display: block; margin-top: 1px; }

.date-cell, .method-cell { color: var(--ink); font-size: 13.5px; white-space: nowrap; }

.status-tag { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; white-space: nowrap; }
.status-tag .status-dot { width: 6px; height: 6px; border-radius: 50%; }
.status-paid { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-paid .status-dot { background: #16A34A; }
.status-pending { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.status-pending .status-dot { background: #D97706; }
.status-refunded { background: var(--cream-alt); color: var(--ink-muted); border: 1px solid var(--line); }
.status-refunded .status-dot { background: var(--ink-muted); }

.amount-val { font-family: var(--font-mono); font-weight: 800; font-size: 15px; color: var(--forest); white-space: nowrap; }
.amount-val.strike { color: var(--ink-muted); text-decoration: line-through; }

.text-right { text-align: right; }
.action-cell-wrap { display: flex; justify-content: flex-end; align-items: center; }

/* PROFESSIONAL BUTTON DESIGN */
.btn-action { height: 38px; padding: 0 16px; border-radius: 999px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all 180ms ease; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; border: 1px solid transparent; box-sizing: border-box; }

.pay-btn { background: var(--lime); color: var(--lime-text); border-color: #c4dc3c; box-shadow: 0 2px 8px rgba(221, 241, 92, 0.45); }
.pay-btn:hover { background: #d2e85a; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(221, 241, 92, 0.6); color: var(--forest); }
.pay-btn:active { transform: translateY(0); }

.secondary-btn { background: var(--card); border-color: var(--line); color: var(--ink); }
.secondary-btn:hover { border-color: var(--forest); background: var(--forest); color: #ffffff; transform: translateY(-1px); box-shadow: var(--shadow-sm); }
.secondary-btn:hover svg { stroke: #ffffff; }

/* EMPTY STATE */
.empty-state-cell { padding: 48px 24px !important; text-align: center; }
.empty-state-wrap { display: flex; flex-direction: column; align-items: center; max-width: 360px; margin: 0 auto; }
.empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--cream); color: var(--ink-muted); display: flex; align-items: center; justify-content: center; margin-bottom: 14px; border: 1px solid var(--line); }
.empty-state-wrap h4 { font-size: 16px; font-weight: 800; color: var(--forest); margin: 0 0 6px 0; }
.empty-state-wrap p { font-size: 13px; color: var(--ink-muted); margin: 0 0 16px 0; line-height: 1.4; }
.reset-filter-btn { padding: 8px 18px; border-radius: 999px; background: var(--forest); color: #fff; border: none; font-size: 12.5px; font-weight: 700; cursor: pointer; transition: all 150ms ease; }
.reset-filter-btn:hover { background: var(--forest-2); }

/* TOAST POPUP */
.toast-popup { position: fixed; bottom: 28px; right: 28px; background: var(--forest); color: #ffffff; padding: 12px 20px; border-radius: var(--radius-md); font-size: 13.5px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; border: 1px solid rgba(255,255,255,0.1); }
.toast-icon { width: 24px; height: 24px; border-radius: 50%; background: var(--lime); color: var(--lime-text); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

.toast-enter-active, .toast-leave-active { transition: all 250ms ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(12px) scale(0.95); }
</style>
