<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

interface DoctorUser {
    id: number
    name: string
    email: string
    avatar_path: string | null
    phone?: string
}

interface Department {
    id: number
    name: string
    slug: string
}

interface Doctor {
    id: number
    specialization: string
    consultation_fee: number | string
    license_number: string
    user: DoctorUser
    department: Department
}

const props = defineProps<{
    doctor: Doctor
}>()

const page = usePage()

const bookingMode = ref('In-Person Visit')
const bookingDate = ref('Friday, Aug 7')
const bookingTime = ref('10:00 AM')

const patientName = ref('Habib Hossain')
const patientEmail = ref('habib@example.com')
const patientPhone = ref('(555) 340-2199')
const patientReason = ref('Routine cardiology checkup')

const acceptTerms = ref(true)
const showSuccessModal = ref(false)

onMounted(() => {
    const user = page.props.auth?.user as { name?: string; email?: string; phone?: string } | undefined
    if (user) {
        if (user.name) patientName.value = user.name
        if (user.email) patientEmail.value = user.email
        if (user.phone) patientPhone.value = user.phone
    }

    const savedMode = localStorage.getItem('booking_mode') || sessionStorage.getItem('booking_mode')
    const savedDate = localStorage.getItem('booking_date') || sessionStorage.getItem('booking_date')
    const savedTime = localStorage.getItem('booking_time') || sessionStorage.getItem('booking_time')
    const savedName = localStorage.getItem('patient_name') || sessionStorage.getItem('patient_name')
    const savedEmail = localStorage.getItem('patient_email') || sessionStorage.getItem('patient_email')
    const savedPhone = localStorage.getItem('patient_phone') || sessionStorage.getItem('patient_phone')
    const savedReason = localStorage.getItem('patient_reason') || sessionStorage.getItem('patient_reason')

    if (savedMode) bookingMode.value = savedMode
    if (savedDate) bookingDate.value = savedDate
    if (savedTime) bookingTime.value = savedTime
    if (savedName) patientName.value = savedName
    if (savedEmail) patientEmail.value = savedEmail
    if (savedPhone) patientPhone.value = savedPhone
    if (savedReason) patientReason.value = savedReason
})

function confirmAppointment() {
    if (!acceptTerms.value) {
        alert('Please accept the terms and policies before confirming.')
        return
    }

    sessionStorage.setItem('patient_name', patientName.value)
    sessionStorage.setItem('patient_email', patientEmail.value)
    sessionStorage.setItem('patient_phone', patientPhone.value)
    sessionStorage.setItem('patient_reason', patientReason.value)

    showSuccessModal.value = true
}

function goToSuccess() {
    router.get(`/appointments/book/${props.doctor.license_number}/success`)
}
</script>

<template>
    <PublicLayout title="Book Appointment — Review & Confirm">
        <Head title="Book Appointment — Review & Confirm — MediFlow" />

        <div class="wrap py-8">
            <!-- WIZARD STEP HEADER -->
            <div class="wizard-bar">
                <div class="wizard-steps">
                    <div class="step-item completed">
                        <span class="step-num">✓</span>
                        <span class="step-text">Select Date & Time</span>
                    </div>
                    <div class="step-divider"></div>
                    <div class="step-item completed">
                        <span class="step-num">✓</span>
                        <span class="step-text">Patient Information</span>
                    </div>
                    <div class="step-divider"></div>
                    <div class="step-item active">
                        <span class="step-num">3</span>
                        <span class="step-text">Confirmation</span>
                    </div>
                </div>
            </div>

            <!-- MAIN REVIEW GRID -->
            <div class="confirm-grid mt-6">
                <!-- LEFT REVIEW DETAILS -->
                <div class="review-panel">
                    <div class="section-head">
                        <h2>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-[#16301F]">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                            </svg>
                            Doctor & Department
                        </h2>
                        <Link :href="`/appointments/book/${doctor.license_number}`" class="edit-link">Edit</Link>
                    </div>

                    <div class="doc-review-card">
                        <img
                            :src="doctor.user.avatar_path || 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=200'"
                            :alt="doctor.user.name"
                        />
                        <div class="doc-review-info">
                            <h3>{{ doctor.user.name }}</h3>
                            <p>{{ doctor.specialization }}</p>
                            <span class="dept-badge">{{ doctor.department.name }} Department</span>
                        </div>
                    </div>

                    <div class="section-head">
                        <h2>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-[#16301F]">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <path d="M16 2v4M8 2v4M3 10h18" />
                            </svg>
                            Schedule & Mode
                        </h2>
                        <Link :href="`/appointments/book/${doctor.license_number}`" class="edit-link">Edit</Link>
                    </div>

                    <div class="info-block-grid">
                        <div class="info-item">
                            <label>Date & Time</label>
                            <span>{{ bookingDate }} at {{ bookingTime }}</span>
                        </div>
                        <div class="info-item">
                            <label>Consultation Mode</label>
                            <span>{{ bookingMode }}</span>
                        </div>
                    </div>

                    <div class="section-head">
                        <h2>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-[#16301F]">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            Patient Details
                        </h2>
                        <Link :href="`/appointments/book/${doctor.license_number}`" class="edit-link">Edit</Link>
                    </div>

                    <div class="info-block-grid mb-6">
                        <div class="info-item">
                            <label>Patient Name</label>
                            <input v-model="patientName" class="input-field" type="text" />
                        </div>
                        <div class="info-item">
                            <label>Phone Number</label>
                            <input v-model="patientPhone" class="input-field" type="text" />
                        </div>
                        <div class="info-item">
                            <label>Email Address</label>
                            <input v-model="patientEmail" class="input-field" type="email" />
                        </div>
                        <div class="info-item">
                            <label>Visit Reason</label>
                            <input v-model="patientReason" class="input-field" type="text" />
                        </div>
                    </div>

                    <!-- CANCELLATION POLICY & AGREEMENT -->
                    <div class="policy-box">
                        <label class="flex gap-3 items-start cursor-pointer">
                            <input v-model="acceptTerms" type="checkbox" class="w-4 h-4 accent-[#16301F] mt-1 shrink-0" />
                            <p class="text-xs text-[#62655A] leading-relaxed">
                                I confirm that the details provided are accurate. I accept the
                                <Link href="/terms-of-service" target="_blank" class="text-[#16301F] font-bold underline">Terms of Service</Link>
                                and understand that I can cancel or reschedule free of charge up to 2 hours before the visit.
                            </p>
                        </label>
                    </div>
                </div>

                <!-- RIGHT BILLING & SUBMIT CARD -->
                <div class="billing-card">
                    <h3>Payment Summary</h3>

                    <div class="price-row">
                        <span class="text-[#62655A]">Consultation Fee</span>
                        <span class="font-semibold">${{ typeof doctor.consultation_fee === 'number' ? doctor.consultation_fee.toFixed(2) : doctor.consultation_fee }}</span>
                    </div>
                    <div class="price-row">
                        <span class="text-[#62655A]">Hospital Booking Fee</span>
                        <span class="font-semibold text-[#15803D]">FREE</span>
                    </div>

                    <div class="price-row total">
                        <span>Total Amount</span>
                        <b>${{ typeof doctor.consultation_fee === 'number' ? doctor.consultation_fee.toFixed(2) : doctor.consultation_fee }}</b>
                    </div>

                    <div class="pay-badge mt-5">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 shrink-0">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        Pay at Clinic / On Consultation
                    </div>

                    <button class="btn btn-primary w-full" @click="confirmAppointment">
                        Confirm Appointment
                    </button>
                </div>
            </div>

            <!-- SUCCESS MODAL OVERLAY -->
            <div v-if="showSuccessModal" class="modal-overlay" @click.self="showSuccessModal = false">
                <div class="modal-card">
                    <div class="modal-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <h2>Appointment Confirmed!</h2>
                    <p class="text-[#62655A] text-sm leading-relaxed mb-6">
                        Your visit with <strong>{{ doctor.user.name }}</strong> has been booked successfully. A confirmation email and calendar invite have been sent to your inbox.
                    </p>

                    <div class="modal-code-badge">
                        Booking Reference: #MDF-89240
                    </div>

                    <button @click="goToSuccess" class="btn btn-primary w-full">
                        View Confirmation Details
                    </button>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; width: 100%; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }

/* WIZARD STEP HEADER */
.wizard-bar { padding: 24px 0 16px; border-bottom: 1px solid #E7E3D3; }
.wizard-steps { display: flex; align-items: center; justify-content: center; gap: 32px; }
@media (max-width: 600px) { .wizard-steps { gap: 12px; } }

.step-item { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; color: #62655A; }
.step-num { width: 30px; height: 30px; border-radius: 50%; background: #F0EEE3; border: 1px solid #E7E3D3; display: flex; align-items: center; justify-content: center; font-family: 'JetBrains Mono', monospace; font-size: 13px; }
.step-item.active { color: #16301F; font-weight: 700; }
.step-item.active .step-num { background: #16301F; color: #fff; border-color: #16301F; }
.step-item.completed .step-num { background: #DDF15C; color: #3B4A12; border-color: #c4dc3c; }
.step-divider { width: 40px; height: 1px; background: #E7E3D3; }
@media (max-width: 600px) { .step-divider { width: 16px; } .step-text { display: none; } }

/* REVIEW GRID LAYOUT */
.confirm-grid { display: grid; grid-template-columns: 1fr 380px; gap: 32px; align-items: start; }
@media (max-width: 960px) { .confirm-grid { grid-template-columns: 1fr; } }

/* LEFT REVIEW CARD */
.review-panel { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 32px; padding: 36px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
@media (max-width: 600px) { .review-panel { padding: 24px; } }

.section-head { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #E7E3D3; padding-bottom: 14px; margin-bottom: 20px; }
.section-head h2 { font-size: 18px; font-weight: 800; color: #16301F; display: flex; align-items: center; gap: 10px; }
.edit-link { font-size: 13px; font-weight: 700; color: #16301F; text-decoration: underline; }

/* DOCTOR SUMMARY BANNER */
.doc-review-card { background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 20px; display: flex; gap: 20px; align-items: center; margin-bottom: 32px; }
.doc-review-card img { width: 72px; height: 72px; border-radius: 16px; object-fit: cover; background: #F0EEE3; }
.doc-review-info h3 { font-size: 18px; font-weight: 800; color: #16301F; margin-bottom: 2px; }
.doc-review-info p { font-size: 13.5px; color: #62655A; font-weight: 500; }
.doc-review-info .dept-badge { display: inline-block; font-size: 12px; font-weight: 600; background: #EEF7C4; color: #3B4A12; padding: 3px 10px; border-radius: 999px; margin-top: 6px; }

/* SUMMARY DATA BLOCKS */
.info-block-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
@media (max-width: 600px) { .info-block-grid { grid-template-columns: 1fr; } }

.info-item { background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 16px; padding: 16px 20px; }
.info-item label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #62655A; display: block; margin-bottom: 4px; }
.info-item span { font-size: 15px; font-weight: 700; color: #16180F; }
.input-field { width: 100%; background: transparent; border: none; font-size: 15px; font-weight: 700; color: #16180F; outline: none; border-bottom: 1px solid #E7E3D3; padding-bottom: 2px; }

/* TERMS CHECKBOX */
.policy-box { background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 16px; padding: 20px; }

/* RIGHT BILLING SUMMARY CARD */
.billing-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 32px; padding: 28px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); position: sticky; top: 100px; }
.billing-card h3 { font-size: 18px; font-weight: 800; color: #16301F; margin-bottom: 20px; border-bottom: 1px solid #E7E3D3; padding-bottom: 12px; }

.price-row { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 12px; }
.price-row.total { border-top: 1px solid #E7E3D3; padding-top: 14px; margin-top: 14px; font-size: 16px; font-weight: 800; }
.price-row.total b { font-family: 'JetBrains Mono', monospace; font-size: 22px; color: #16301F; }

.pay-badge { background: #EEF7C4; border: 1px solid #d2e85a; border-radius: 16px; padding: 12px 16px; font-size: 13px; color: #3B4A12; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 24px; }

/* SUCCESS MODAL OVERLAY */
.modal-overlay { position: fixed; inset: 0; background: rgba(22,24,15,0.6); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 100; padding: 20px; }
.modal-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 32px; padding: 40px; max-width: 480px; width: 100%; text-align: center; box-shadow: 0 16px 36px rgba(22,24,15,0.10); animation: popUp 250ms ease-out; }
@keyframes popUp { from { transform: scale(0.92); opacity: 0; } to { transform: scale(1); opacity: 1; } }

.modal-icon { width: 64px; height: 64px; border-radius: 50%; background: #DCFCE7; color: #15803D; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }

.modal-card h2 { font-size: 24px; font-weight: 800; color: #16301F; margin-bottom: 8px; }
.modal-code-badge { background: #F8F6EF; border: 1px dashed #E7E3D3; border-radius: 16px; padding: 12px 16px; font-family: 'JetBrains Mono', monospace; font-size: 15px; font-weight: 700; color: #16301F; margin-bottom: 28px; }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 52px; padding: 0 28px; border-radius: 999px; font-size: 15px; font-weight: 600; transition: transform 150ms ease, background-color 150ms ease; cursor: pointer; border: 0; }
.btn-primary { background: #16301F; color: #fff; }
.btn-primary:hover { background: #1E4029; }
</style>
