<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const isCancelModalOpen = ref(false)

function openCancelModal() {
    isCancelModalOpen.value = true
}

function closeCancelModal() {
    isCancelModalOpen.value = false
}

function confirmCancellation() {
    alert('Your appointment #MDF-101 has been cancelled successfully.')
    router.visit('/patient/appointments')
}
</script>

<template>
    <Head title="Appointment #MDF-101 Detail" />

    <!-- TOP NAV HEADER -->
    <div class="top-nav-row">
        <Link href="/patient/appointments" class="back-btn">← Back to My Appointments</Link>
    </div>

    <!-- APPOINTMENT TITLE HEADER BAR -->
    <div class="detail-header-card">
        <div>
            <div class="header-info-group">
                <span class="ref-badge">#MDF-101</span>
                <span class="badge badge-confirmed">Confirmed Visit</span>
            </div>
            <h1>Cardiology Consultation</h1>
        </div>

        <button class="btn btn-outline btn-print" @click="window.print()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
            </svg>
            Print Receipt & Pass
        </button>
    </div>

    <!-- MAIN DETAIL GRID -->
    <div class="detail-grid">
        <!-- LEFT COLUMN: FULL APPOINTMENT SPECIFICATIONS -->
        <div class="main-details-col">
            <!-- PHYSICIAN CARD -->
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    </svg>
                    Doctor Profile
                </div>
                <div class="doc-detail-row">
                    <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=200" alt="Dr. Sarah Jenkins" class="doc-detail-avatar" />
                    <div class="doc-detail-meta">
                        <h3>Dr. Sarah Jenkins</h3>
                        <p>Senior Cardiologist (12+ Yrs Exp)</p>
                        <span class="dept-tag">Cardiology Department</span>
                    </div>
                </div>
            </div>

            <!-- TIME & LOCATION SPECIFICATIONS -->
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    Schedule & Location Details
                </div>

                <div class="info-pairs-grid">
                    <div class="info-box">
                        <label>Date & Time</label>
                        <span>Friday, Aug 7, 2026</span>
                        <small>10:00 AM – 10:30 AM EST</small>
                    </div>

                    <div class="info-box">
                        <label>Consultation Mode</label>
                        <span>In-Person Visit</span>
                        <small>Building B, Room 302</small>
                    </div>

                    <div class="info-box">
                        <label>Clinic Address</label>
                        <span>MediFlow Hospital</span>
                        <small>120 Harbor Ave, Suite 300</small>
                    </div>

                    <div class="info-box">
                        <label>Patient Contact</label>
                        <span>Habib Hossain</span>
                        <small>habib@example.com · (555) 340-2199</small>
                    </div>
                </div>
            </div>

            <!-- PATIENT NOTES & PREPARATION -->
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    Reason for Visit & Instructions
                </div>

                <div class="reason-block">
                    <span class="sub-label">Stated Symptoms / Reason</span>
                    <p class="reason-text">Routine follow-up consultation regarding recent blood pressure fluctuations and post-exercise chest tightness.</p>
                </div>

                <div class="prep-box">
                    <h4>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Patient Pre-Visit Checklist
                    </h4>
                    <p>Please arrive 15 minutes prior to your scheduled time slot. Bring your current medication list and recent ECG reports if available.</p>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR: ACTIONS & PAYMENT STATUS -->
        <div class="sidebar-actions-col">
            <div class="action-card">
                <h4>Appointment Actions</h4>
                <p class="action-sub font-muted">Need to adjust your consultation schedule?</p>

                <Link href="/patient/appointments/101/reschedule" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Reschedule Slot
                </Link>

                <button class="btn btn-danger" @click="openCancelModal">
                    Cancel Appointment
                </button>

                <!-- PAYMENT SUMMARY TILE -->
                <div class="payment-summary-box">
                    <div class="pay-row">
                        <span class="pay-label">Consultation Fee</span>
                        <span class="pay-val">$120.00</span>
                    </div>
                    <div class="pay-row">
                        <span class="pay-label">Payment Method</span>
                        <span class="pay-val">Pay at Clinic</span>
                    </div>
                    <div class="pay-row total">
                        <span>Total Due</span>
                        <b>$120.00</b>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CANCEL APPOINTMENT MODAL OVERLAY -->
    <div v-if="isCancelModalOpen" class="modal-overlay" @click.self="closeCancelModal">
        <div class="modal-card">
            <div class="modal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </div>
            <h3>Cancel Appointment?</h3>
            <p>Are you sure you want to cancel your cardiology appointment with <strong>Dr. Sarah Jenkins</strong> on Aug 7, 2026?</p>

            <div class="modal-actions">
                <button class="btn btn-outline" @click="closeCancelModal">Keep Appointment</button>
                <button class="btn btn-danger-solid" @click="confirmCancellation">Yes, Cancel Visit</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.top-nav-row { margin-bottom: 20px; }
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
.back-btn:hover { background: var(--card); border-color: var(--forest); }

.detail-header-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 28px 32px;
    box-shadow: var(--shadow-card);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 24px;
}
.header-info-group { display: flex; align-items: center; gap: 16px; }
.ref-badge { font-family: var(--font-mono); font-size: 13px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 4px 10px; border-radius: var(--radius-sm); }
.detail-header-card h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin: 4px 0 0 0; }

.badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 999px; font-size: 13px; font-weight: 700; }
.badge-confirmed { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

.detail-grid { display: grid; grid-template-columns: 1fr 360px; gap: 24px; align-items: start; }
@media (max-width: 1024px) { .detail-grid { grid-template-columns: 1fr; } }

.main-details-col { display: flex; flex-direction: column; gap: 24px; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); padding: 28px; }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }
.card-title svg { width: 18px; height: 18px; color: var(--forest); }

.doc-detail-row { display: flex; gap: 20px; align-items: center; }
.doc-detail-avatar { width: 80px; height: 80px; border-radius: var(--radius-lg); object-fit: cover; background: var(--cream-alt); }
.doc-detail-meta h3 { font-size: 18px; font-weight: 800; color: var(--forest); margin: 0 0 2px 0; }
.doc-detail-meta p { font-size: 14px; color: var(--ink-muted); font-weight: 500; margin: 0; }
.dept-tag { display: inline-block; font-size: 12px; font-weight: 600; background: var(--lime-soft); color: var(--lime-text); padding: 3px 10px; border-radius: 999px; margin-top: 6px; }

.info-pairs-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
@media (max-width: 600px) { .info-pairs-grid { grid-template-columns: 1fr; } }

.info-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 16px; }
.info-box label { font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 4px; }
.info-box span { font-size: 14.5px; font-weight: 700; color: var(--ink); display: block; }
.info-box small { font-size: 12px; color: var(--ink-muted); display: block; margin-top: 2px; }

.reason-block { margin-bottom: 20px; }
.sub-label { font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--ink-muted); display: block; margin-bottom: 4px; }
.reason-text { font-size: 14.5px; color: var(--ink); font-weight: 500; margin: 0; }

.prep-box { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: var(--radius-md); padding: 18px; color: #1E40AF; }
.prep-box h4 { font-size: 14px; font-weight: 700; margin: 0 0 6px 0; display: flex; align-items: center; gap: 8px; }
.prep-box p { font-size: 13.5px; line-height: 1.5; color: #1E3A8A; margin: 0; }

.sidebar-actions-col { display: flex; flex-direction: column; gap: 24px; }
.action-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; gap: 12px; }
.action-card h4 { font-size: 15px; font-weight: 800; color: var(--forest); margin: 0 0 4px 0; }
.action-sub { font-size: 12.5px; color: var(--ink-muted); margin: 0 0 12px 0; }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 24px; border-radius: 999px; font-size: 14.5px; font-weight: 600; text-decoration: none; transition: all 150ms ease; width: 100%; cursor: pointer; }
.btn-print { width: auto; height: 40px; font-size: 13px; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }
.btn-danger { background: #FEE2E2; color: #B91C1C; border: 1px solid #FCA5A5; }
.btn-danger:hover { background: #FCA5A5; color: #7F1D1D; }
.btn-danger-solid { background: #DC2626; color: #fff; border: none; }
.btn-danger-solid:hover { background: #B91C1C; }

.payment-summary-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 18px; margin-top: 8px; }
.pay-row { display: flex; justify-content: space-between; font-size: 13.5px; margin-bottom: 8px; }
.pay-label { color: var(--ink-muted); }
.pay-val { font-weight: 600; }
.pay-row.total { border-top: 1px solid var(--line); padding-top: 10px; margin-top: 10px; font-weight: 800; font-size: 15px; }
.pay-row.total b { font-family: var(--font-mono); color: var(--forest); }

/* MODAL OVERLAY */
.modal-overlay { position: fixed; inset: 0; background: rgba(22,24,15,0.6); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 100; padding: 20px; }
.modal-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 32px; max-width: 440px; width: 100%; text-align: center; box-shadow: var(--shadow-lift); }
.modal-icon { width: 48px; height: 48px; border-radius: 50%; background: #FEE2E2; color: #B91C1C; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
.modal-card h3 { font-size: 20px; font-weight: 800; color: var(--forest); margin: 0 0 8px 0; }
.modal-card p { font-size: 14px; color: var(--ink-muted); line-height: 1.5; margin: 0 0 20px 0; }
.modal-actions { display: flex; gap: 12px; }
</style>
