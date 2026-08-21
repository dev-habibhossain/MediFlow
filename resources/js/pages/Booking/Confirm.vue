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
const patientReason = ref('Routine consultation & checkup')

const paymentMethod = ref<'clinic' | 'stripe'>('clinic')
const showStripeModal = ref(false)

const cardNumber = ref('4242 4242 4242 4242')
const cardExpiry = ref('12/28')
const cardCvc = ref('123')

const acceptTerms = ref(true)
const isSubmitting = ref(false)

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

function handleConfirmAction() {
    if (!acceptTerms.value) {
        alert('Please accept the terms and policies before confirming.')
        return
    }

    if (paymentMethod.value === 'stripe') {
        showStripeModal.value = true
    } else {
        processBookingStore('clinic')
    }
}

function submitStripePayment() {
    if (!cardNumber.value || !cardExpiry.value || !cardCvc.value) {
        alert('Please enter valid test card details.')
        return
    }

    processBookingStore('stripe')
}

function processBookingStore(method: 'clinic' | 'stripe') {
    isSubmitting.value = true

    router.post('/appointments/book/store', {
        doctor_id: props.doctor.id,
        appointment_date: bookingDate.value,
        start_time: bookingTime.value,
        reason: patientReason.value,
        payment_method: method,
    }, {
        onSuccess: () => {
            showStripeModal.value = false
            localStorage.removeItem('booking_date')
            localStorage.removeItem('booking_time')
            localStorage.removeItem('booking_mode')
            sessionStorage.removeItem('booking_date')
            sessionStorage.removeItem('booking_time')
            sessionStorage.removeItem('booking_mode')
        },
        onFinish: () => {
            isSubmitting.value = false
        },
    })
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

                    <!-- PAYMENT METHOD SELECTION -->
                    <div class="section-head mt-8">
                        <h2>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-[#16301F]">
                                <rect x="2" y="5" width="20" height="14" rx="2" />
                                <line x1="2" y1="10" x2="22" y2="10" />
                            </svg>
                            Select Payment Option
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                        <label
                            class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex items-start gap-3 select-none"
                            :class="paymentMethod === 'clinic' ? 'border-[#16301F] bg-white shadow-sm ring-2 ring-[#16301F]/10' : 'border-[#E7E3D3] bg-[#F8F6EF]'"
                        >
                            <input type="radio" v-model="paymentMethod" value="clinic" class="mt-1 accent-[#16301F] shrink-0 w-4 h-4" />
                            <div>
                                <h4 class="font-bold text-sm text-[#16301F]">Pay at Clinic</h4>
                                <p class="text-xs text-[#62655A]">Pay upon arrival. Added as due to your dashboard.</p>
                            </div>
                        </label>

                        <label
                            class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex items-start gap-3 select-none"
                            :class="paymentMethod === 'stripe' ? 'border-[#16301F] bg-white shadow-sm ring-2 ring-[#16301F]/10' : 'border-[#E7E3D3] bg-[#F8F6EF]'"
                        >
                            <input type="radio" v-model="paymentMethod" value="stripe" class="mt-1 accent-[#16301F] shrink-0 w-4 h-4" />
                            <div>
                                <h4 class="font-bold text-sm text-[#16301F] flex items-center gap-1.5">
                                    Pay Online (Stripe)
                                    <span class="text-[10px] uppercase font-extrabold bg-[#DDF15C] text-[#3B4A12] px-1.5 py-0.5 rounded">Instant</span>
                                </h4>
                                <p class="text-xs text-[#62655A]">Pay now via Demo Stripe payment modal.</p>
                            </div>
                        </label>
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
                        {{ paymentMethod === 'stripe' ? 'Pay Instantly via Stripe Gateway' : 'Pay at Clinic / Recorded in Dashboard' }}
                    </div>

                    <button class="btn btn-primary w-full" :disabled="isSubmitting" @click="handleConfirmAction">
                        {{ isSubmitting ? 'Processing...' : (paymentMethod === 'stripe' ? 'Proceed to Stripe Payment' : 'Confirm & Pay at Clinic') }}
                    </button>
                </div>
            </div>

            <!-- DEMO STRIPE PAYMENT MODAL -->
            <div v-if="showStripeModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="showStripeModal = false">
                <div class="bg-white border border-[#E7E3D3] rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-5 animate-popUp relative">
                    <div class="flex items-center justify-between border-b border-[#E7E3D3] pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#16301F] text-[#DDF15C] flex items-center justify-center font-bold text-sm">
                                S
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-[#16301F]">Stripe Test Payment</h3>
                                <p class="text-[11px] text-[#62655A]">256-bit Encrypted Demo Sandbox</p>
                            </div>
                        </div>
                        <button @click="showStripeModal = false" class="text-gray-400 hover:text-gray-600 font-bold text-lg">✕</button>
                    </div>

                    <div class="bg-[#F8F6EF] p-4 rounded-2xl border border-[#E7E3D3] flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-[#62655A]">Consultation Fee</span>
                            <h4 class="text-xl font-extrabold text-[#16301F]">${{ typeof doctor.consultation_fee === 'number' ? doctor.consultation_fee.toFixed(2) : doctor.consultation_fee }}</h4>
                        </div>
                        <span class="text-xs font-extrabold text-[#15803D] bg-[#DCFCE7] px-3 py-1 rounded-full border border-[#BBF7D0]">Demo Mode</span>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-[#62655A] mb-1">Card Number</label>
                            <input v-model="cardNumber" type="text" class="w-full bg-[#F8F6EF] border border-[#E7E3D3] rounded-xl px-3.5 py-2.5 text-sm font-mono font-bold text-[#16301F] outline-none focus:border-[#16301F]" placeholder="4242 4242 4242 4242" />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-[#62655A] mb-1">Expiry Date</label>
                                <input v-model="cardExpiry" type="text" class="w-full bg-[#F8F6EF] border border-[#E7E3D3] rounded-xl px-3.5 py-2.5 text-sm font-mono font-bold text-[#16301F] outline-none focus:border-[#16301F]" placeholder="MM/YY" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#62655A] mb-1">CVC Code</label>
                                <input v-model="cardCvc" type="text" class="w-full bg-[#F8F6EF] border border-[#E7E3D3] rounded-xl px-3.5 py-2.5 text-sm font-mono font-bold text-[#16301F] outline-none focus:border-[#16301F]" placeholder="123" />
                            </div>
                        </div>
                    </div>

                    <button @click="submitStripePayment" :disabled="isSubmitting" class="w-full bg-[#16301F] text-white font-bold py-3.5 rounded-full hover:bg-[#1E4029] transition-all flex items-center justify-center gap-2">
                        <span v-if="isSubmitting">Processing Payment...</span>
                        <span v-else>Pay ${{ typeof doctor.consultation_fee === 'number' ? doctor.consultation_fee.toFixed(2) : doctor.consultation_fee }} Now & Confirm</span>
                    </button>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; width: 100%; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }

.wizard-bar { padding: 16px 0; border-bottom: 1px solid #E7E3D3; margin-bottom: 24px; }
.wizard-steps { display: flex; align-items: center; justify-content: center; gap: 32px; }
@media (max-width: 600px) { .wizard-steps { gap: 12px; } }

.step-item { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; color: #62655A; }
.step-num { width: 30px; height: 30px; border-radius: 50%; background: #F0EEE3; border: 1px solid #E7E3D3; display: flex; align-items: center; justify-content: center; font-family: monospace; font-size: 13px; }
.step-item.active { color: #16301F; font-weight: 700; }
.step-item.active .step-num { background: #16301F; color: #fff; border-color: #16301F; }
.step-item.completed { color: #15803D; }
.step-item.completed .step-num { background: #DCFCE7; color: #15803D; border-color: #BBF7D0; }
.step-divider { width: 40px; height: 1px; background: #E7E3D3; }

.confirm-grid { display: grid; grid-template-columns: 1fr 380px; gap: 32px; align-items: start; }
@media (max-width: 960px) { .confirm-grid { grid-template-columns: 1fr; } }

.review-panel { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 32px; box-shadow: 0 1px 2px rgba(22,24,15,0.04); }
@media (max-width: 600px) { .review-panel { padding: 20px; } }

.section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; margin-top: 24px; }
.section-head:first-child { margin-top: 0; }
.section-head h2 { font-size: 17px; font-weight: 700; color: #16301F; display: flex; align-items: center; gap: 8px; }
.edit-link { font-size: 13px; font-weight: 600; color: #16301F; text-decoration: underline; }

.doc-review-card { display: flex; align-items: center; gap: 16px; background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 16px; padding: 16px; margin-bottom: 24px; }
.doc-review-card img { width: 64px; height: 64px; border-radius: 14px; object-fit: cover; }
.doc-review-info h3 { font-size: 16px; font-weight: 700; color: #16180F; margin: 0; }
.doc-review-info p { font-size: 13px; color: #62655A; margin: 2px 0 6px; }
.dept-badge { font-size: 12px; font-weight: 600; color: #3B4A12; background: #DDF15C; padding: 3px 10px; border-radius: 999px; }

.info-block-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
@media (max-width: 500px) { .info-block-grid { grid-template-columns: 1fr; } }

.info-item { background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 14px; padding: 14px 16px; }
.info-item label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #62655A; display: block; margin-bottom: 4px; }
.info-item span { font-size: 14px; font-weight: 600; color: #16180F; }
.input-field { width: 100%; background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 8px; padding: 6px 10px; font-size: 13px; font-weight: 600; color: #16180F; outline: none; }
.input-field:focus { border-color: #16301F; }

.policy-box { background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 16px; padding: 16px; margin-top: 24px; }

.billing-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 28px; box-shadow: 0 1px 2px rgba(22,24,15,0.04); position: sticky; top: 100px; }
.billing-card h3 { font-size: 18px; font-weight: 800; color: #16301F; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #E7E3D3; }
.price-row { display: flex; align-items: center; justify-content: space-between; font-size: 14px; margin-bottom: 12px; }
.price-row.total { border-top: 1px dashed #E7E3D3; padding-top: 14px; margin-top: 14px; font-size: 17px; font-weight: 800; color: #16301F; }

.pay-badge { background: #F0EEE3; border: 1px solid #E7E3D3; border-radius: 12px; padding: 10px 14px; font-size: 12.5px; font-weight: 600; color: #16301F; display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 52px; padding: 0 28px; border-radius: 999px; font-size: 15px; font-weight: 600; transition: transform 150ms ease, background-color 150ms ease; cursor: pointer; text-decoration: none; border: 0; }
.btn-primary { background: #16301F; color: #fff; }
.btn-primary:hover { background: #1E4029; }
.btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }

@keyframes popUp {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-popUp { animation: popUp 200ms ease-out forwards; }
</style>
