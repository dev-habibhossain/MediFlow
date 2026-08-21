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
        }, 2500)
    }
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
        <div style="font-size: 13.5px; font-weight: 700; color: var(--forest);">
            Showing all processed payment records
        </div>

        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input v-model="searchQuery" type="text" class="search-input" placeholder="Search invoice or patient..." />
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
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="tx in filteredTransactions" :key="tx.id">
                        <td style="font-family: var(--font-mono); font-weight: 700;">{{ tx.id }}</td>
                        <td><b>{{ tx.patient_name }}</b></td>
                        <td>{{ tx.service_details }}</td>
                        <td style="font-family: var(--font-mono); font-weight: 700;">{{ tx.amount }}</td>
                        <td>
                            <span class="status-badge" :class="tx.status.includes('Paid') ? 'status-success' : 'status-refunded'">
                                ● {{ tx.status }}
                            </span>
                        </td>
                        <td>
                            <button class="btn-action-icon" title="View Transaction Detail" @click="openDetail(tx)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <tr v-if="filteredTransactions.length === 0">
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--ink-muted);">
                            No transaction records found matching your search.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TRANSACTION DETAIL MODAL -->
    <div v-if="showModal && selectedTx" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Transaction Detail ({{ selectedTx.id }})</h3>
                <button class="modal-close" @click="closeModal">✕</button>
            </div>

            <div class="modal-body">
                <div class="detail-row">
                    <span class="detail-label">Invoice Number</span>
                    <span class="detail-value">{{ selectedTx.id }}</span>
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
                    <span class="detail-value" style="font-size: 16px; color: #15803D;">{{ selectedTx.amount }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Timestamp</span>
                    <span class="detail-value">{{ selectedTx.timestamp }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Status</span>
                    <span class="detail-value" :style="selectedTx.status === 'Refunded' ? 'color: #B45309;' : ''">{{ selectedTx.status }}</span>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-refund" @click="triggerRefund">Issue Full Refund</button>
                <button class="btn-action-icon" style="width: auto; padding: 0 16px; border-radius: 999px; height: 40px; font-weight: 700; font-size: 13.5px;" @click="closeModal">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12" /></svg>
        <span>{{ toastMsg }}</span>
    </div>
</template>

<style>
.toolbar-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--line); padding-bottom: 16px; margin-bottom: 24px; }

.search-box { position: relative; width: 280px; }
.search-box svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); }
.search-input { width: 100%; height: 40px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); padding: 0 16px 0 40px; font-size: 13.5px; color: var(--ink); outline: none; transition: border-color 150ms ease; }
.search-input:focus { border-color: var(--forest); }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 16px 24px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
.status-success { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-refunded { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }

.btn-action-icon { width: 34px; height: 34px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--cream); display: inline-flex; align-items: center; justify-content: center; color: var(--forest); transition: all 150ms ease; cursor: pointer; }
.btn-action-icon:hover { border-color: var(--forest); background: var(--forest); color: #fff; }

.modal-overlay { position: fixed; inset: 0; background: rgba(22,24,15,0.5); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 100; padding: 20px; }
.modal-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); width: 100%; max-width: 600px; box-shadow: var(--shadow-lift); overflow: hidden; animation: scaleUp 200ms ease-out; }
@keyframes scaleUp { from { transform: scale(0.96); opacity: 0; } to { transform: scale(1); opacity: 1; } }

.modal-header { padding: 20px 24px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; background: var(--cream); }
.modal-header h3 { font-size: 17px; font-weight: 800; color: var(--forest); }
.modal-close { width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--line); background: var(--card); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--ink-muted); transition: all 150ms ease; }
.modal-close:hover { color: var(--ink); border-color: var(--forest); }

.modal-body { padding: 24px; display: flex; flex-direction: column; gap: 16px; }
.detail-row { display: flex; justify-content: space-between; align-items: center; padding-bottom: 12px; border-bottom: 1px solid var(--line); font-size: 13.5px; }
.detail-row:last-child { border-bottom: none; padding-bottom: 0; }
.detail-label { font-weight: 600; color: var(--ink-muted); }
.detail-value { font-weight: 700; color: var(--forest); font-family: var(--font-mono); }

.modal-footer { padding: 16px 24px; border-top: 1px solid var(--line); background: var(--cream); display: flex; justify-content: space-between; align-items: center; }
.btn-refund { height: 40px; padding: 0 20px; border-radius: 999px; background: #FEF2F2; border: 1px solid #FCA5A5; color: #DC2626; font-size: 13.5px; font-weight: 700; cursor: pointer; transition: all 150ms ease; }
.btn-refund:hover { background: #DC2626; color: #fff; border-color: #DC2626; }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
