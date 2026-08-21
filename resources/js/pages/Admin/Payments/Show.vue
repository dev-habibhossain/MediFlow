<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const status = ref<'paid' | 'refunded'>('paid')
const showToast = ref(false)

function triggerRefund() {
    if (confirm('Are you sure you want to issue a full refund for invoice #INV-89201?')) {
        status.value = 'refunded'
        showToast.value = true
        setTimeout(() => {
            showToast.value = false
        }, 3000)
    }
}
</script>

<template>
    <Head title="Transaction Detail (#INV-89201) - MediFlow Admin" />

    <AdminLayout>
        <div class="payment-detail-container">
            <div class="top-nav-bar">
                <Link href="/admin/payments" class="back-btn"> ← Back to Transactions List </Link>
            </div>

            <!-- TOAST NOTICE -->
            <div v-if="showToast" class="toast-notice">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                <span>Full refund processed successfully via Stripe!</span>
            </div>

            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                        <line x1="1" y1="10" x2="23" y2="10" />
                    </svg>
                    <span>Transaction Detail — #INV-89201</span>
                </div>

                <div class="detail-list">
                    <div class="detail-row">
                        <span class="detail-label">Invoice Number</span>
                        <span class="detail-value">#INV-89201</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Patient Name</span>
                        <span class="detail-value">Habib Hossain</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Service / Consultation</span>
                        <span class="detail-value">Cardiology Consultation (#MDF-101)</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Amount Collected</span>
                        <span class="detail-value amount-success">$120.00</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Gateway Reference</span>
                        <span class="detail-value ref-mono">pi_3Nq2V9LkdJ9021sz9X</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Timestamp</span>
                        <span class="detail-value">Aug 7, 2026 · 02:10 AM</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Current Status</span>
                        <div>
                            <span v-if="status === 'paid'" class="status-badge status-success">● Paid (Stripe)</span>
                            <span v-else class="status-badge status-refunded">● Refunded</span>
                        </div>
                    </div>
                </div>

                <div class="action-row">
                    <span class="action-hint">Need to reverse this charge? Issue a full refund directly through Stripe.</span>
                    <button v-if="status === 'paid'" type="button" class="btn-refund" @click="triggerRefund">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <polyline points="1 4 1 10 7 10" />
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
                        </svg>
                        Issue Full Refund
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.payment-detail-container {
    max-width: 960px;
    margin: 0 auto;
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.top-nav-bar {
    display: flex;
    align-items: center;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--forest);
    background: var(--cream);
    border: 1px solid var(--line);
    padding: 6px 14px;
    border-radius: 999px;
    text-decoration: none;
    transition: all 150ms ease;
}

.back-btn:hover {
    background: var(--card);
    border-color: var(--forest);
}

.card-shell {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 32px;
    box-shadow: var(--shadow-card);
}

.card-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--forest);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid var(--line);
    padding-bottom: 12px;
}

.detail-list {
    display: flex;
    flex-direction: column;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid var(--line);
    font-size: 14px;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: var(--ink-muted);
}

.detail-value {
    font-weight: 700;
    color: var(--forest);
    font-family: var(--font-mono);
}

.amount-success {
    font-size: 16px;
    color: #15803D;
}

.ref-mono {
    font-size: 12px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.status-success {
    background: #DCFCE7;
    color: #15803D;
    border: 1px solid #BBF7D0;
}

.status-refunded {
    background: #FEF3C7;
    color: #B45309;
    border: 1px solid #FDE68A;
}

.action-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--line);
}

.action-hint {
    font-size: 13px;
    color: var(--ink-muted);
}

.btn-refund {
    height: 44px;
    padding: 0 24px;
    border-radius: 999px;
    background: #FEF2F2;
    border: 1px solid #FCA5A5;
    color: #DC2626;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 150ms ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-refund:hover {
    background: #DC2626;
    color: #fff;
    border-color: #DC2626;
}

.toast-notice {
    background: #DCFCE7;
    border: 1px solid #BBF7D0;
    color: #15803D;
    padding: 12px 16px;
    border-radius: var(--radius-md);
    font-weight: 700;
    font-size: 13.5px;
    display: flex;
    align-items: center;
    gap: 10px;
}
</style>
