<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface TransactionItem {
    id: string
    patient_name: string
    service_details: string
    amount: string
    timestamp: string
    status: string
}

const props = withDefaults(
    defineProps<{
        transactions?: TransactionItem[]
    }>(),
    {
        transactions: () => [
            {
                id: '#INV-89201',
                patient_name: 'Habib Hossain',
                service_details: 'Cardiology Consultation (#MDF-101)',
                amount: '$120.00',
                timestamp: 'Aug 7, 2026 · 02:10 AM',
                status: 'Paid (Stripe)',
            },
            {
                id: '#INV-89198',
                patient_name: 'Tanjila Ahmed',
                service_details: 'Neurology Consultation (#MDF-102)',
                amount: '$140.00',
                timestamp: 'Aug 7, 2026 · 01:15 AM',
                status: 'Paid (Stripe)',
            },
            {
                id: '#INV-88402',
                patient_name: 'Robert Chen',
                service_details: 'Orthopedics Checkup (#MDF-103)',
                amount: '$110.00',
                timestamp: 'Aug 6, 2026 · 04:30 PM',
                status: 'Paid (Stripe)',
            },
            {
                id: '#INV-88110',
                patient_name: 'Sophia Martinez',
                service_details: 'Pediatric Visit (#MDF-104)',
                amount: '$95.00',
                timestamp: 'Aug 5, 2026 · 11:20 AM',
                status: 'Refunded',
            },
        ],
    }
)

const searchQuery = ref<string>('')
const selectedTx = ref<TransactionItem | null>(null)
const showModal = ref(false)
const showToast = ref(false)
const toastMsg = ref('')

const filteredTransactions = computed(() => {
    const q = searchQuery.value.toLowerCase().trim()
    if (!q) return props.transactions
    return props.transactions.filter(
        (tx) =>
            tx.id.toLowerCase().includes(q) ||
            tx.patient_name.toLowerCase().includes(q) ||
            tx.service_details.toLowerCase().includes(q)
    )
})

function openDetail(tx: TransactionItem) {
    selectedTx.value = { ...tx }
    showModal.value = true
}

function closeModal() {
    showModal.value = false
    selectedTx.value = null
}

function triggerRefund() {
    if (selectedTx.value && confirm('Are you sure you want to issue a full refund for this transaction?')) {
        selectedTx.value.status = 'Refunded'
        toastMsg.value = 'Full refund processed successfully via Stripe.'
        showToast.value = true
        closeModal()
        setTimeout(() => {
            showToast.value = false
        }, 3000)
    }
}

function clearSearch() {
    searchQuery.value = ''
}
</script>

<template>
    <Head title="Transactions & Financial Hub — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">Transactions & Financial Registry</h1>
            <p class="text-xs text-[var(--ink-muted)]">Monitor patient consultation payments, Stripe invoices, and refund actions</p>
        </div>
    </div>

    <!-- TOOLBAR & FILTERS -->
    <div class="toolbar-row">
        <div class="summary-badge">
            <span class="pulse-dot"></span>
            <span>Showing {{ filteredTransactions.length }} processed payment records</span>
        </div>

        <div class="search-box">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input v-model="searchQuery" type="text" class="search-input" placeholder="Search invoice, patient, or service..." />
            <button v-if="searchQuery" class="clear-btn" title="Clear search" @click="clearSearch">✕</button>
        </div>
    </div>

    <!-- DATA TABLE CARD -->
    <div class="card-shell">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Patient Name</th>
                        <th>Service Details</th>
                        <th>Timestamp</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="tx in filteredTransactions" :key="tx.id" class="table-row">
                        <td class="inv-code">{{ tx.id }}</td>
                        <td><b class="patient-name">{{ tx.patient_name }}</b></td>
                        <td class="service-text">{{ tx.service_details }}</td>
                        <td class="time-text">{{ tx.timestamp }}</td>
                        <td class="amount-val">{{ tx.amount }}</td>
                        <td>
                            <span class="status-badge" :class="tx.status.includes('Paid') ? 'status-success' : 'status-refunded'">
                                <span class="badge-dot"></span> {{ tx.status }}
                            </span>
                        </td>
                        <td class="text-right">
                            <button class="btn-action-icon" title="View Transaction Detail" @click="openDetail(tx)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" />
                                </svg>
                                <span>Details</span>
                            </button>
                        </td>
                    </tr>

                    <tr v-if="filteredTransactions.length === 0">
                        <td colspan="7" class="empty-cell">
                            <div class="empty-wrap">
                                <div class="empty-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="26" height="26">
                                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                    </svg>
                                </div>
                                <h4>No transactions found</h4>
                                <p>No payment records match your search filter.</p>
                                <button class="reset-btn" @click="clearSearch">Reset Search</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TRANSACTION DETAIL MODAL -->
    <Transition name="modal">
        <div v-if="showModal && selectedTx" class="modal-overlay" @click.self="closeModal">
            <div class="modal-card">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h3>Transaction Detail</h3>
                        <span class="inv-pill">{{ selectedTx.id }}</span>
                    </div>
                    <button class="modal-close" title="Close" @click="closeModal">✕</button>
                </div>

                <div class="modal-body">
                    <div class="detail-row">
                        <span class="detail-label">Invoice Number</span>
                        <span class="detail-value font-mono">{{ selectedTx.id }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Patient Name</span>
                        <span class="detail-value">{{ selectedTx.patient_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Service / Consultation</span>
                        <span class="detail-value">{{ selectedTx.service_details }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Transaction Amount</span>
                        <span class="detail-value amount-highlight">{{ selectedTx.amount }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Timestamp</span>
                        <span class="detail-value text-muted">{{ selectedTx.timestamp }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Status</span>
                        <span class="detail-value" :class="selectedTx.status === 'Refunded' ? 'text-amber' : 'text-green'">{{ selectedTx.status }}</span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button v-if="selectedTx.status !== 'Refunded'" class="btn-refund" @click="triggerRefund">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/>
                        </svg>
                        Issue Full Refund
                    </button>
                    <div v-else class="refunded-notice">
                        ✓ Refund Processed
                    </div>
                    <button class="btn-close-modal" @click="closeModal">Close</button>
                </div>
            </div>
        </div>
    </Transition>

    <!-- TOAST NOTICE -->
    <Transition name="toast">
        <div v-if="showToast" class="toast-notice">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
            <span>{{ toastMsg }}</span>
        </div>
    </Transition>
</template>

<style scoped>
.toolbar-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--line); padding-bottom: 18px; margin-bottom: 24px; }

.summary-badge { display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: var(--forest); background: var(--card); border: 1px solid var(--line); padding: 6px 14px; border-radius: 999px; box-shadow: var(--shadow-sm); }
.pulse-dot { width: 7px; height: 7px; border-radius: 50%; background: #16A34A; box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.2); }

.search-box { position: relative; width: 300px; }
.search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); pointer-events: none; }
.search-input { width: 100%; height: 42px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); padding: 0 36px 0 40px; font-size: 13.5px; color: var(--ink); outline: none; transition: all 150ms ease; }
.search-input:focus { border-color: var(--forest); box-shadow: 0 0 0 3px rgba(22, 48, 31, 0.08); }
.clear-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; border-radius: 50%; background: var(--cream-alt); border: none; font-size: 11px; color: var(--ink-muted); cursor: pointer; display: flex; align-items: center; justify-content: center; }
.clear-btn:hover { background: var(--line); color: var(--ink); }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-muted); border-bottom: 1px solid var(--line); white-space: nowrap; }
.data-table td { padding: 16px 24px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }
.table-row { transition: background 150ms ease; }
.table-row:hover { background: rgba(248, 246, 239, 0.6); }

.inv-code { font-family: var(--font-mono); font-weight: 700; color: var(--forest); white-space: nowrap; }
.patient-name { color: var(--ink); font-weight: 700; }
.service-text { color: var(--ink-muted); font-size: 13px; }
.time-text { color: var(--ink-muted); font-size: 12.5px; white-space: nowrap; }
.amount-val { font-family: var(--font-mono); font-weight: 800; font-size: 14.5px; color: var(--forest); white-space: nowrap; }

.status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; white-space: nowrap; }
.badge-dot { width: 6px; height: 6px; border-radius: 50%; }
.status-success { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-success .badge-dot { background: #16A34A; }
.status-refunded { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.status-refunded .badge-dot { background: #D97706; }

.text-right { text-align: right; }

.btn-action-icon { height: 34px; padding: 0 14px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 600; color: var(--ink); transition: all 150ms ease; cursor: pointer; }
.btn-action-icon:hover { border-color: var(--forest); background: var(--forest); color: #fff; transform: translateY(-1px); }

/* MODAL STYLES */
.modal-overlay { position: fixed; inset: 0; background: rgba(22,24,15,0.55); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 100; padding: 20px; }
.modal-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); width: 100%; max-width: 540px; box-shadow: var(--shadow-lift); overflow: hidden; }

.modal-header { padding: 20px 24px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; background: var(--cream); }
.modal-title-group { display: flex; align-items: center; gap: 10px; }
.modal-header h3 { font-size: 17px; font-weight: 800; color: var(--forest); margin: 0; }
.inv-pill { font-family: var(--font-mono); font-size: 12px; font-weight: 700; background: var(--lime-soft); color: var(--lime-text); padding: 2px 8px; border-radius: 6px; }

.modal-close { width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--line); background: var(--card); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--ink-muted); transition: all 150ms ease; }
.modal-close:hover { color: var(--ink); border-color: var(--forest); }

.modal-body { padding: 24px; display: flex; flex-direction: column; gap: 14px; }
.detail-row { display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid var(--line); font-size: 13.5px; }
.detail-row:last-child { border-bottom: none; padding-bottom: 0; }
.detail-label { font-weight: 600; color: var(--ink-muted); }
.detail-value { font-weight: 700; color: var(--ink); }
.font-mono { font-family: var(--font-mono); color: var(--forest); }
.amount-highlight { font-family: var(--font-mono); font-size: 16px; color: #15803D; font-weight: 800; }
.text-muted { color: var(--ink-muted); font-size: 12.5px; }
.text-green { color: #15803D; }
.text-amber { color: #B45309; }

.modal-footer { padding: 16px 24px; border-top: 1px solid var(--line); background: var(--cream); display: flex; justify-content: space-between; align-items: center; }
.btn-refund { height: 40px; padding: 0 18px; border-radius: 999px; background: #FEF2F2; border: 1px solid #FCA5A5; color: #DC2626; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 150ms ease; display: inline-flex; align-items: center; gap: 6px; }
.btn-refund:hover { background: #DC2626; color: #fff; border-color: #DC2626; transform: translateY(-1px); }
.refunded-notice { font-size: 13px; font-weight: 700; color: #B45309; background: #FEF3C7; padding: 6px 14px; border-radius: 999px; border: 1px solid #FDE68A; }

.btn-close-modal { height: 40px; padding: 0 20px; border-radius: 999px; background: var(--card); border: 1px solid var(--line); color: var(--ink); font-weight: 700; font-size: 13.5px; cursor: pointer; transition: all 150ms ease; }
.btn-close-modal:hover { border-color: var(--forest); background: var(--forest); color: #fff; }

/* EMPTY STATE */
.empty-cell { padding: 48px 24px !important; text-align: center; }
.empty-wrap { display: flex; flex-direction: column; align-items: center; max-width: 340px; margin: 0 auto; }
.empty-icon { width: 52px; height: 52px; border-radius: 50%; background: var(--cream); color: var(--ink-muted); display: flex; align-items: center; justify-content: center; margin-bottom: 12px; border: 1px solid var(--line); }
.empty-wrap h4 { font-size: 15px; font-weight: 800; color: var(--forest); margin: 0 0 4px 0; }
.empty-wrap p { font-size: 13px; color: var(--ink-muted); margin: 0 0 14px 0; }
.reset-btn { padding: 8px 16px; border-radius: 999px; background: var(--forest); color: #fff; border: none; font-size: 12.5px; font-weight: 700; cursor: pointer; }

/* TOAST NOTICE */
.toast-notice { position: fixed; bottom: 28px; right: 28px; background: var(--forest); color: #fff; padding: 12px 20px; border-radius: var(--radius-md); font-size: 13.5px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; border: 1px solid rgba(255,255,255,0.1); }
.toast-icon { width: 24px; height: 24px; border-radius: 50%; background: var(--lime); color: var(--lime-text); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

.modal-enter-active, .modal-leave-active { transition: all 200ms ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; transform: scale(0.96); }

.toast-enter-active, .toast-leave-active { transition: all 250ms ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(12px) scale(0.95); }
</style>
