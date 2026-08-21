<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

interface DoctorUser {
    id: number
    name: string
    email: string
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
    license_number: string
    user: DoctorUser
    department: Department
}

const props = defineProps<{
    doctor: Doctor
    availableDoctors?: Doctor[]
}>()

const page = usePage()

const selectedMode = ref('In-Person Visit')
const selectedDate = ref('Friday, Aug 7')
const selectedTime = ref('10:00 AM')

const consultationModes = [
    {
        title: 'In-Person Clinic',
        description: 'Visit 120 Harbor Ave Clinic',
        value: 'In-Person Visit',
        icon: 'M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16M9 7h6M9 11h6M9 15h4',
    },
    {
        title: 'Video Consultation',
        description: 'HD Online Call via Portal',
        value: 'Video Telehealth',
        icon: 'M23 7l-7 5 7 5V7z M1 5h15v14H1z',
    },
]

const availableDates = [
    { day: '2', dateStr: 'Sunday, Aug 2', disabled: true },
    { day: '3', dateStr: 'Monday, Aug 3', disabled: true },
    { day: '4', dateStr: 'Tuesday, Aug 4', disabled: true },
    { day: '5', dateStr: 'Wednesday, Aug 5', disabled: true },
    { day: '6', dateStr: 'Thursday, Aug 6', disabled: true },
    { day: '7', dateStr: 'Friday, Aug 7', disabled: false },
    { day: '8', dateStr: 'Saturday, Aug 8', disabled: false },
    { day: '9', dateStr: 'Sunday, Aug 9', disabled: false },
    { day: '10', dateStr: 'Monday, Aug 10', disabled: false },
    { day: '11', dateStr: 'Tuesday, Aug 11', disabled: false },
    { day: '12', dateStr: 'Wednesday, Aug 12', disabled: false },
    { day: '13', dateStr: 'Thursday, Aug 13', disabled: false },
    { day: '14', dateStr: 'Friday, Aug 14', disabled: false },
    { day: '15', dateStr: 'Saturday, Aug 15', disabled: false },
]

const morningSlots = [
    { time: '09:00 AM', disabled: true },
    { time: '09:30 AM', disabled: false },
    { time: '10:00 AM', disabled: false },
    { time: '10:30 AM', disabled: false },
]

const afternoonSlots = [
    { time: '02:00 PM', disabled: false },
    { time: '02:30 PM', disabled: false },
    { time: '03:00 PM', disabled: false },
    { time: '03:30 PM', disabled: true },
]

onMounted(() => {
    const savedMode = localStorage.getItem('booking_mode') || sessionStorage.getItem('booking_mode')
    const savedDate = localStorage.getItem('booking_date') || sessionStorage.getItem('booking_date')
    const savedTime = localStorage.getItem('booking_time') || sessionStorage.getItem('booking_time')

    if (savedMode) selectedMode.value = savedMode
    if (savedDate) selectedDate.value = savedDate
    if (savedTime) selectedTime.value = savedTime
})

function setMode(mode: string) {
    selectedMode.value = mode
}

function setDate(dateStr: string) {
    selectedDate.value = dateStr
}

function setSlot(timeStr: string) {
    selectedTime.value = timeStr
}

function handleDoctorChange(event: Event) {
    const target = event.target as HTMLSelectElement
    if (target && target.value) {
        router.get(`/appointments/book/${target.value}`)
    }
}

function goToConfirm() {
    localStorage.setItem('booking_mode', selectedMode.value)
    localStorage.setItem('booking_date', selectedDate.value)
    localStorage.setItem('booking_time', selectedTime.value)
    localStorage.setItem('booking_doctor_license', props.doctor.license_number)
    localStorage.setItem('doctor_name', props.doctor.user.name)
    localStorage.setItem('doctor_fee', String(props.doctor.consultation_fee))
    localStorage.setItem('department_name', props.doctor.department.name)

    sessionStorage.setItem('booking_mode', selectedMode.value)
    sessionStorage.setItem('booking_date', selectedDate.value)
    sessionStorage.setItem('booking_time', selectedTime.value)
    sessionStorage.setItem('doctor_name', props.doctor.user.name)
    sessionStorage.setItem('doctor_fee', String(props.doctor.consultation_fee))
    sessionStorage.setItem('department_name', props.doctor.department.name)

    const confirmUrl = `/appointments/book/${props.doctor.license_number}/confirm`

    const user = page.props.auth?.user
    if (!user) {
        router.get(`/login?redirect=${encodeURIComponent(confirmUrl)}`)
    } else {
        router.get(confirmUrl)
    }
}
</script>

<template>
    <PublicLayout title="Book Appointment — Select Slot">
        <Head title="Book Appointment — Select Slot — MediFlow" />

        <div class="wrap py-8">
            <!-- WIZARD STEP HEADER -->
            <div class="wizard-bar">
                <div class="wizard-steps">
                    <div class="step-item active">
                        <span class="step-num">1</span>
                        <span class="step-text">Select Date & Time</span>
                    </div>
                    <div class="step-divider"></div>
                    <div class="step-item">
                        <span class="step-num">2</span>
                        <span class="step-text">Patient Information</span>
                    </div>
                    <div class="step-divider"></div>
                    <div class="step-item">
                        <span class="step-num">3</span>
                        <span class="step-text">Confirmation</span>
                    </div>
                </div>
            </div>

            <!-- MAIN BOOKING GRID -->
            <div class="booking-grid mt-6">
                <!-- LEFT SELECTION PANEL -->
                <div class="picker-panel">
                    <!-- DOCTOR SWITCHER HEADER -->
                    <div v-if="availableDoctors && availableDoctors.length > 1" class="mb-8 p-4 bg-[#EFEDE4] rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border border-[#E7E3D3]">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-[#62655A]">Select Physician</span>
                            <h3 class="text-base font-extrabold text-[#16301F]">{{ doctor.user.name }}</h3>
                        </div>
                        <select :value="doctor.license_number" @change="handleDoctorChange" class="bg-white border border-[#E7E3D3] text-[#16180F] text-sm font-semibold rounded-xl px-3 py-2 outline-none focus:border-[#16301F]">
                            <option v-for="doc in availableDoctors" :key="doc.id" :value="doc.license_number">
                                {{ doc.user.name }} ({{ doc.department.name }})
                            </option>
                        </select>
                    </div>

                    <!-- STEP 1: CONSULTATION MODE -->
                    <div class="section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-[#16301F]">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                        </svg>
                        1. Select Consultation Type
                    </div>

                    <div class="mode-grid">
                        <div
                            v-for="mode in consultationModes"
                            :key="mode.value"
                            class="mode-card"
                            :class="{ active: selectedMode === mode.value }"
                            @click="setMode(mode.value)"
                        >
                            <div class="mode-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                    <path :d="mode.icon" />
                                </svg>
                            </div>
                            <div class="mode-info">
                                <h4>{{ mode.title }}</h4>
                                <p>{{ mode.description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: CALENDAR DATE SELECTOR -->
                    <div class="section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-[#16301F]">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <path d="M16 2v4M8 2v4M3 10h18" />
                        </svg>
                        2. Choose Appointment Date
                    </div>

                    <div class="calendar-box">
                        <div class="calendar-header">
                            <h3>August 2026</h3>
                            <div class="flex gap-1">
                                <button class="cal-nav-btn" disabled aria-label="Previous month">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M15 18l-6-6 6-6" /></svg>
                                </button>
                                <button class="cal-nav-btn" aria-label="Next month">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M9 18l6-6-6-6" /></svg>
                                </button>
                            </div>
                        </div>

                        <div class="days-row">
                            <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                        </div>

                        <div class="dates-grid">
                            <button
                                v-for="item in availableDates"
                                :key="item.day"
                                class="date-btn"
                                :class="{ active: selectedDate === item.dateStr }"
                                :disabled="item.disabled"
                                @click="setDate(item.dateStr)"
                            >
                                {{ item.day }}
                                <span v-if="!item.disabled && selectedDate === item.dateStr" class="dot"></span>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: TIME SLOT SELECTOR -->
                    <div class="section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-[#16301F]">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        3. Select Available Time Slot
                    </div>

                    <div class="slots-section">
                        <div class="slot-group-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5" /><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" /></svg>
                            Morning Slots
                        </div>
                        <div class="slots-grid">
                            <button
                                v-for="slot in morningSlots"
                                :key="slot.time"
                                class="slot-btn"
                                :class="{ active: selectedTime === slot.time }"
                                :disabled="slot.disabled"
                                @click="setSlot(slot.time)"
                            >
                                {{ slot.time }}
                            </button>
                        </div>

                        <div class="slot-group-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" /></svg>
                            Afternoon & Evening Slots
                        </div>
                        <div class="slots-grid">
                            <button
                                v-for="slot in afternoonSlots"
                                :key="slot.time"
                                class="slot-btn"
                                :class="{ active: selectedTime === slot.time }"
                                :disabled="slot.disabled"
                                @click="setSlot(slot.time)"
                            >
                                {{ slot.time }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- RIGHT SUMMARY SIDEBAR -->
                <div class="summary-card">
                    <div class="doc-mini-profile">
                        <img
                            :src="doctor.user.avatar_path || 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=200'"
                            :alt="doctor.user.name"
                            class="doc-avatar"
                        />
                        <div class="doc-info">
                            <h3>{{ doctor.user.name }}</h3>
                            <p>{{ doctor.specialization }}</p>
                            <span class="dept-badge">{{ doctor.department.name }}</span>
                        </div>
                    </div>

                    <div class="summary-list">
                        <div class="summary-item">
                            <span class="label">Consultation Type</span>
                            <span class="val">{{ selectedMode }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Selected Date</span>
                            <span class="val">{{ selectedDate }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Selected Time</span>
                            <span class="val">{{ selectedTime }}</span>
                        </div>
                    </div>

                    <div class="fee-total-box">
                        <span>Consultation Fee</span>
                        <b>${{ typeof doctor.consultation_fee === 'number' ? doctor.consultation_fee.toFixed(2) : doctor.consultation_fee }}</b>
                    </div>

                    <button @click="goToConfirm" class="btn btn-primary w-full">
                        Continue to Patient Info →
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
.step-divider { width: 40px; height: 1px; background: #E7E3D3; }
@media (max-width: 600px) { .step-divider { width: 16px; } .step-text { display: none; } }

/* BOOKING GRID LAYOUT */
.booking-grid { display: grid; grid-template-columns: 1fr 380px; gap: 32px; align-items: start; }
@media (max-width: 960px) { .booking-grid { grid-template-columns: 1fr; } }

/* MAIN PICKER PANEL */
.picker-panel { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 32px; padding: 36px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
@media (max-width: 600px) { .picker-panel { padding: 24px; } }

.section-title { font-size: 18px; font-weight: 800; color: #16301F; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }

/* CONSULTATION MODE SWITCHER */
.mode-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 32px; }
@media (max-width: 500px) { .mode-grid { grid-template-columns: 1fr; } }
.mode-card { border: 1.5px solid #E7E3D3; border-radius: 16px; padding: 16px; cursor: pointer; transition: all 150ms ease; display: flex; align-items: center; gap: 12px; background: #F8F6EF; }
.mode-card:hover { border-color: #16301F; }
.mode-card.active { border-color: #16301F; background: #FFFFFF; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.mode-icon { width: 38px; height: 38px; border-radius: 10px; background: #EEF7C4; color: #3B4A12; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.mode-info h4 { font-size: 14.5px; font-weight: 700; color: #16180F; }
.mode-info p { font-size: 12.5px; color: #62655A; }

/* CALENDAR DATE SELECTOR */
.calendar-box { background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 20px; margin-bottom: 32px; }
.calendar-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.calendar-header h3 { font-size: 16px; font-weight: 800; color: #16301F; }
.cal-nav-btn { width: 32px; height: 32px; border-radius: 50%; border: 1px solid #E7E3D3; background: #FFFFFF; display: flex; align-items: center; justify-content: center; transition: background-color 150ms ease; }
.cal-nav-btn:hover:not(:disabled) { background: #F0EEE3; }

.days-row { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; text-align: center; font-size: 12px; font-weight: 700; text-transform: uppercase; color: #62655A; margin-bottom: 8px; }
.dates-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }

.date-btn { height: 44px; border-radius: 10px; border: 1px solid transparent; background: #FFFFFF; font-size: 14px; font-weight: 600; color: #16180F; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: all 150ms ease; position: relative; cursor: pointer; }
.date-btn:hover:not(:disabled) { border-color: #16301F; background: #FFFFFF; }
.date-btn.active { background: #16301F; color: #fff; border-color: #16301F; }
.date-btn:disabled { opacity: 0.3; cursor: not-allowed; background: transparent; }
.date-btn .dot { width: 4px; height: 4px; border-radius: 50%; background: #DDF15C; position: absolute; bottom: 4px; }

/* TIME SLOT SELECTOR GRID */
.slots-section { margin-bottom: 12px; }
.slot-group-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; color: #62655A; margin: 20px 0 10px; display: flex; align-items: center; gap: 6px; }
.slot-group-title svg { width: 15px; height: 15px; }

.slots-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
@media (max-width: 500px) { .slots-grid { grid-template-columns: repeat(2, 1fr); } }

.slot-btn { height: 44px; border-radius: 10px; border: 1px solid #E7E3D3; background: #F8F6EF; font-family: 'JetBrains Mono', monospace; font-size: 13.5px; font-weight: 600; color: #16180F; transition: all 150ms ease; display: flex; align-items: center; justify-content: center; cursor: pointer; }
.slot-btn:hover:not(:disabled) { border-color: #16301F; background: #FFFFFF; }
.slot-btn.active { background: #DDF15C; color: #3B4A12; border-color: #c4dc3c; font-weight: 700; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.slot-btn:disabled { opacity: 0.35; cursor: not-allowed; background: transparent; text-decoration: line-through; }

/* RIGHT SUMMARY SIDEBAR */
.summary-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 32px; padding: 28px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); position: sticky; top: 100px; }

.doc-mini-profile { display: flex; gap: 16px; align-items: center; padding-bottom: 20px; border-bottom: 1px solid #E7E3D3; margin-bottom: 20px; }
.doc-avatar { width: 64px; height: 64px; border-radius: 16px; object-fit: cover; background: #F0EEE3; }
.doc-info h3 { font-size: 16px; font-weight: 800; color: #16301F; margin-bottom: 2px; }
.doc-info p { font-size: 13px; color: #62655A; font-weight: 500; }
.doc-info .dept-badge { display: inline-block; font-size: 11.5px; font-weight: 600; background: #EEF7C4; color: #3B4A12; padding: 2px 8px; border-radius: 999px; margin-top: 4px; }

.summary-list { margin-bottom: 24px; }
.summary-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #E7E3D3; font-size: 14px; }
.summary-item:last-child { border-bottom: none; }
.summary-item .label { color: #62655A; }
.summary-item .val { font-weight: 700; color: #16180F; }

.fee-total-box { background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 16px; padding: 16px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.fee-total-box span { font-size: 14px; font-weight: 600; color: #62655A; }
.fee-total-box b { font-family: 'JetBrains Mono', monospace; font-size: 20px; font-weight: 800; color: #16301F; }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 52px; padding: 0 28px; border-radius: 999px; font-size: 15px; font-weight: 600; transition: transform 150ms ease, background-color 150ms ease; cursor: pointer; border: 0; }
.btn-primary { background: #16301F; color: #fff; }
.btn-primary:hover { background: #1E4029; }
</style>
