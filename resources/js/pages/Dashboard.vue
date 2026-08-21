<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'

interface DoctorUser {
    id: number
    name: string
    avatar_path: string | null
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
    user: DoctorUser
}

interface Payment {
    id: number
    amount: number | string
    status: string
    appointment_id: number
    paid_at: string | null
    appointment?: {
        doctor?: {
            user?: {
                name: string
            }
        }
    }
}

interface Appointment {
    id: number
    appointment_code: string
    appointment_date: string
    start_time: string
    reason: string
    status: string
    doctor: Doctor
    department: Department
    payment?: Payment
}

const props = defineProps<{
    appointments?: Appointment[]
    unpaidPayments?: Payment[]
}>()

const activePayment = ref<Payment | null>(null)
const isPaying = ref(false)
const cardNumber = ref('4242 4242 4242 4242')
const cardExpiry = ref('12/28')
const cardCvc = ref('123')

function openPayModal(payment: Payment) {
    activePayment.value = payment
}

function closePayModal() {
    activePayment.value = null
}

function processDuePayment() {
    if (!activePayment.value) return
    isPaying.value = true

    router.post(`/dashboard/payments/${activePayment.value.id}/pay`, {}, {
        onSuccess: () => {
            closePayModal()
        },
        onFinish: () => {
            isPaying.value = false
        },
    })
}
</script>

<template>
    <AppLayout breadcrumbs="[{ title: 'Dashboard', href: '/dashboard' }]">
        <Head title="Patient Dashboard — MediFlow" />

        <div class="p-6 space-y-8 max-w-7xl mx-auto">
            <!-- HEADER GREETING -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-[#E7E3D3] shadow-sm">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-wider text-[#62655A]">MediFlow Care Portal</span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#16301F] tracking-tight">Patient Overview & Appointments</h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="/doctors" class="inline-flex items-center justify-center gap-2 bg-[#16301F] text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-[#1E4029] transition-all">
                        + Book New Consultation
                    </a>
                </div>
            </div>

            <!-- STATS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-[#E7E3D3] shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#EEF7C4] text-[#3B4A12] flex items-center justify-center font-bold">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" /></svg>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#62655A]">Total Bookings</span>
                        <h3 class="text-2xl font-extrabold text-[#16301F]">{{ appointments?.length || 0 }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-[#E7E3D3] shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#DCFCE7] text-[#15803D] flex items-center justify-center font-bold">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><polyline points="20 6 9 17 4 12" /></svg>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#62655A]">Scheduled Visits</span>
                        <h3 class="text-2xl font-extrabold text-[#16301F]">{{ appointments?.filter(a => a.status === 'scheduled').length || 0 }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-[#E7E3D3] shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#FEF3C7] text-[#92400E] flex items-center justify-center font-bold">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#62655A]">Unpaid Dues</span>
                        <h3 class="text-2xl font-extrabold text-[#92400E]">{{ unpaidPayments?.length || 0 }} Fee Dues</h3>
                    </div>
                </div>
            </div>

            <!-- UNPAID DUES ALERT SECTION -->
            <div v-if="unpaidPayments && unpaidPayments.length > 0" class="bg-[#FFFBEB] border-2 border-[#FDE68A] rounded-3xl p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <span class="p-2 rounded-xl bg-[#FEF3C7] text-[#92400E]">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-extrabold text-[#92400E]">Outstanding Consultation Dues</h3>
                        <p class="text-xs text-[#B45309]">You have unpaid consultation fees due at the clinic. Pay online via Stripe to complete instant verification.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div v-for="pay in unpaidPayments" :key="pay.id" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-white rounded-2xl border border-[#FDE68A]">
                        <div>
                            <span class="text-xs font-mono font-bold text-[#62655A]">Payment ID: #PAY-{{ pay.id }}</span>
                            <h4 class="font-extrabold text-sm text-[#16301F]">Consultation Fee — ${{ typeof pay.amount === 'number' ? pay.amount.toFixed(2) : pay.amount }}</h4>
                            <p class="text-xs text-[#62655A]" v-if="pay.appointment?.doctor?.user">Physician: {{ pay.appointment.doctor.user.name }}</p>
                        </div>
                        <button @click="openPayModal(pay)" class="bg-[#16301F] text-white text-xs font-bold px-4 py-2.5 rounded-full hover:bg-[#1E4029] transition-all shrink-0">
                            Pay Fee Now (Stripe) →
                        </button>
                    </div>
                </div>
            </div>

            <!-- APPOINTMENTS LIST TABLE -->
            <div class="bg-white rounded-3xl border border-[#E7E3D3] p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-extrabold text-[#16301F]">My Consultations & Appointments</h2>
                </div>

                <div v-if="!appointments || appointments.length === 0" class="text-center py-12">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-12 h-12 text-[#62655A] mx-auto mb-3"><rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" /></svg>
                    <p class="text-sm font-semibold text-[#62655A] mb-4">No appointments scheduled yet.</p>
                    <a href="/doctors" class="inline-flex items-center gap-2 bg-[#16301F] text-white text-sm font-bold px-5 py-2.5 rounded-full">
                        Browse Doctors & Book
                    </a>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-[#E7E3D3] text-xs font-extrabold uppercase tracking-wider text-[#62655A]">
                                <th class="pb-3">Code</th>
                                <th class="pb-3">Physician</th>
                                <th class="pb-3">Department</th>
                                <th class="pb-3">Date & Time</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Payment</th>
                                <th class="pb-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E7E3D3]">
                            <tr v-for="app in appointments" :key="app.id" class="hover:bg-[#F8F6EF]/50 transition-colors">
                                <td class="py-4 font-mono font-bold text-[#16301F]">#{{ app.appointment_code }}</td>
                                <td class="py-4 font-bold text-[#16301F]">{{ app.doctor.user.name }}</td>
                                <td class="py-4 text-[#62655A]">{{ app.department.name }}</td>
                                <td class="py-4">
                                    <span class="font-bold text-[#16180F] block">{{ app.appointment_date }}</span>
                                    <span class="text-xs text-[#62655A]">{{ app.start_time }}</span>
                                </td>
                                <td class="py-4">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-extrabold bg-[#DCFCE7] text-[#15803D] border border-[#BBF7D0]">
                                        {{ app.status }}
                                    </span>
                                </td>
                                <td class="py-4">
                                    <span v-if="app.payment?.status === 'paid'" class="inline-block px-2.5 py-1 rounded-full text-xs font-extrabold bg-[#DCFCE7] text-[#15803D]">
                                        ✓ Paid (${{ app.payment.amount }})
                                    </span>
                                    <span v-else class="inline-block px-2.5 py-1 rounded-full text-xs font-extrabold bg-[#FEF3C7] text-[#92400E]">
                                        ⚠ Unpaid Due (${{ app.payment?.amount || app.doctor.consultation_fee }})
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <button v-if="app.payment && app.payment.status !== 'paid'" @click="openPayModal(app.payment)" class="text-xs font-bold text-[#16301F] underline hover:text-[#1E4029]">
                                        Pay Fee →
                                    </button>
                                    <span v-else class="text-xs font-semibold text-[#62655A]">Confirmed</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- STRIPE PAY DUE MODAL -->
        <div v-if="activePayment" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="closePayModal">
            <div class="bg-white border border-[#E7E3D3] rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-5 animate-popUp">
                <div class="flex items-center justify-between border-b border-[#E7E3D3] pb-4">
                    <h3 class="text-lg font-extrabold text-[#16301F]">Stripe Online Fee Payment</h3>
                    <button @click="closePayModal" class="text-gray-400 hover:text-gray-600 font-bold">✕</button>
                </div>

                <div class="bg-[#F8F6EF] p-4 rounded-2xl border border-[#E7E3D3]">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#62655A]">Outstanding Consultation Fee</span>
                    <h4 class="text-2xl font-mono font-extrabold text-[#16301F]">${{ typeof activePayment.amount === 'number' ? activePayment.amount.toFixed(2) : activePayment.amount }}</h4>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-[#62655A] mb-1">Credit Card Number</label>
                        <input v-model="cardNumber" type="text" class="w-full bg-[#F8F6EF] border border-[#E7E3D3] rounded-xl px-3 py-2 text-sm font-mono font-bold outline-none focus:border-[#16301F]" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#62655A] mb-1">Expiry Date</label>
                            <input v-model="cardExpiry" type="text" class="w-full bg-[#F8F6EF] border border-[#E7E3D3] rounded-xl px-3 py-2 text-sm font-mono font-bold outline-none focus:border-[#16301F]" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#62655A] mb-1">CVC</label>
                            <input v-model="cardCvc" type="text" class="w-full bg-[#F8F6EF] border border-[#E7E3D3] rounded-xl px-3 py-2 text-sm font-mono font-bold outline-none focus:border-[#16301F]" />
                        </div>
                    </div>
                </div>

                <button @click="processDuePayment" :disabled="isPaying" class="w-full bg-[#16301F] text-white font-bold py-3.5 rounded-full hover:bg-[#1E4029] transition-all">
                    {{ isPaying ? 'Processing Payment...' : 'Pay Fee Now' }}
                </button>
            </div>
        </div>
    </AppLayout>
</template>
