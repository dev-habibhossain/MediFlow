<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

interface AppointmentDetail {
    id: number | string
    code: string
    scheduled_at: string
    status: string
    patient: {
        name: string
        code: string
        phone: string
    }
    doctor: {
        id: number | string
        name: string
        department: string
        license: string
    }
    location: string
    fee: string
    payment_status: string
}

const props = withDefaults(
    defineProps<{
        appointment?: AppointmentDetail
        doctors?: Array<{ id: number | string; name: string }>
    }>(),
    {
        appointment: () => ({
            id: 101,
            code: 'MDF-101',
            scheduled_at: 'August 7, 2026 at 10:00 AM',
            status: 'Confirmed Booking',
            patient: {
                name: 'Habib Hossain',
                code: '#MDF-9021',
                phone: '(555) 340-2199',
            },
            doctor: {
                id: 'jenkins',
                name: 'Dr. Sarah Jenkins',
                department: 'Cardiology Dept',
                license: 'Lic #MD-90412',
            },
            location: 'Suite 302, Harbor Ave Clinic',
            fee: '$120.00 USD',
            payment_status: 'Paid via Stripe (Paid)',
        }),
        doctors: () => [
            { id: 'jenkins', name: 'Dr. Sarah Jenkins (Current)' },
            { id: 'vance', name: 'Dr. Robert Fox' },
            { id: 'watson', name: 'Dr. Alan Grant' },
        ],
    }
)

const selectedDoctor = ref(props.appointment.doctor.id)
const toastMessage = ref('')
const showToast = ref(false)

function handleReassign() {
    toastMessage.value = 'Doctor reassigned successfully!'
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
        router.visit('/admin/appointments')
    }, 1500)
}

function handleCancel() {
    toastMessage.value = 'Appointment cancelled & refunded.'
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
        router.visit('/admin/appointments')
    }, 1500)
}
</script>

<template>
    <Head title="Appointment Detail & Override — Admin Portal" />

    <div class="mb-6">
        <Link href="/admin/appointments" class="back-btn">← Back to Appointments Oversight</Link>
    </div>

    <!-- HEADER BANNER -->
    <div class="appt-header-card mb-6">
        <div class="appt-meta">
            <span class="ref-badge">Booking Reference #{{ appointment.code }}</span>
            <h1>Appointment Oversight & Reassignment</h1>
            <p>Scheduled: <strong>{{ appointment.scheduled_at }}</strong> · In-Person Consultation</p>
            <span class="status-badge">● {{ appointment.status }}</span>
        </div>
    </div>

    <!-- MAIN SPLIT GRID -->
    <div class="detail-grid">
        <!-- LEFT: BOOKING DETAILS -->
        <div class="main-col">
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" /></svg>
                    Consultation & Participant Summary
                </div>

                <div class="info-pairs-grid">
                    <div class="info-box">
                        <label>Patient Name</label>
                        <span>{{ appointment.patient.name }}</span>
                        <small>ID: {{ appointment.patient.code }} · {{ appointment.patient.phone }}</small>
                    </div>

                    <div class="info-box">
                        <label>Assigned Physician</label>
                        <span>{{ appointment.doctor.name }}</span>
                        <small>{{ appointment.doctor.department }} · {{ appointment.doctor.license }}</small>
                    </div>

                    <div class="info-box">
                        <label>Department & Room</label>
                        <span>{{ appointment.doctor.department }}</span>
                        <small>{{ appointment.location }}</small>
                    </div>

                    <div class="info-box">
                        <label>Consultation Fee</label>
                        <span style="font-family: var(--font-mono);">{{ appointment.fee }}</span>
                        <small>{{ appointment.payment_status }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: ADMIN OVERRIDE CONTROLS -->
        <div class="sidebar-col">
            <!-- REASSIGN DOCTOR -->
            <div class="action-card">
                <h4>Reassign Doctor</h4>
                <p>Transfer this consultation to another physician in the department.</p>

                <select v-model="selectedDoctor" class="form-control">
                    <option v-for="doc in doctors" :key="doc.id" :value="doc.id">{{ doc.name }}</option>
                </select>

                <button type="button" class="btn btn-primary" @click="handleReassign">
                    Confirm Reassignment
                </button>
            </div>

            <!-- CANCEL / OVERRIDE -->
            <div class="action-card">
                <h4>Emergency Override</h4>
                <p>Force cancel or reschedule this appointment and notify participant.</p>

                <button type="button" class="btn btn-outline-danger" @click="handleCancel">
                    Cancel & Refund Booking
                </button>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12" /></svg>
        <span>{{ toastMessage }}</span>
    </div>
</template>

<style>
.back-btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 6px 14px; border-radius: 999px; text-decoration: none; transition: all 150ms ease; }
.back-btn:hover { background: var(--card); border-color: var(--forest); }

.appt-header-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px 32px; box-shadow: var(--shadow-card); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; }
.ref-badge { font-family: var(--font-mono); font-size: 13px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 4px 10px; border-radius: var(--radius-sm); display: inline-block; margin-bottom: 4px; }
.appt-meta h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin-bottom: 2px; }
.appt-meta p { font-size: 13.5px; color: var(--ink-muted); font-weight: 500; }
.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 999px; font-size: 12.5px; font-weight: 700; margin-top: 8px; background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

.detail-grid { display: grid; grid-template-columns: 1fr 360px; gap: 28px; align-items: start; }
@media (max-width: 1024px) { .detail-grid { grid-template-columns: 1fr; } }
.main-col { display: flex; flex-direction: column; gap: 24px; }
.sidebar-col { display: flex; flex-direction: column; gap: 24px; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px; box-shadow: var(--shadow-card); }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }
.card-title svg { width: 18px; height: 18px; color: var(--forest); }

.info-pairs-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
@media (max-width: 600px) { .info-pairs-grid { grid-template-columns: 1fr; } }

.info-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 16px; }
.info-box label { font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 4px; }
.info-box span { font-size: 14.5px; font-weight: 700; color: var(--ink); display: block; }
.info-box small { font-size: 12px; color: var(--ink-muted); display: block; margin-top: 2px; }

.action-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; gap: 14px; }
.action-card h4 { font-size: 15px; font-weight: 800; color: var(--forest); }
.action-card p { font-size: 12.5px; color: var(--ink-muted); }

.form-control { width: 100%; height: 42px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 14px; font-size: 13.5px; color: var(--ink); outline: none; margin-bottom: 10px; }
.form-control:focus { border-color: var(--forest); background: var(--card); }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 20px; border-radius: 999px; font-size: 14px; font-weight: 700; transition: all 150ms ease; width: 100%; border: 0; cursor: pointer; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline-danger { background: transparent; color: #DC2626; border: 1.5px solid #FCA5A5; }
.btn-outline-danger:hover { background: #FEF2F2; border-color: #DC2626; }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
