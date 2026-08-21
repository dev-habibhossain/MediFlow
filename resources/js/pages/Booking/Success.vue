<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
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
}>()

const bookingDate = ref('Friday, Aug 7')
const bookingTime = ref('10:00 AM')
const bookingMode = ref('In-Person Visit')

const referenceCode = ref('MDF-89240')
const copied = ref(false)

onMounted(() => {
    const savedDate = sessionStorage.getItem('booking_date')
    const savedTime = sessionStorage.getItem('booking_time')
    const savedMode = sessionStorage.getItem('booking_mode')

    if (savedDate) bookingDate.value = savedDate
    if (savedTime) bookingTime.value = savedTime
    if (savedMode) bookingMode.value = savedMode
})

function copyReferenceCode() {
    navigator.clipboard.writeText(referenceCode.value)
    copied.value = true
    setTimeout(() => {
        copied.value = false
    }, 2000)
}

function printSummary() {
    window.print()
}

function addToGoogleCalendar() {
    alert('Appointment added to your Google Calendar!')
}

function downloadICal() {
    alert('iCal calendar event (.ics) downloaded!')
}
</script>

<template>
    <PublicLayout title="Booking Confirmed">
        <Head title="Booking Confirmed — MediFlow" />

        <div class="wrap py-12 flex items-center justify-center min-h-[75vh]">
            <div class="success-card w-full">
                <!-- ICON BADGE -->
                <div class="icon-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>

                <span class="pill mb-4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-3.5 h-3.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg>
                    Appointment Confirmed
                </span>

                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#16301F] tracking-tight mb-2">
                    Your Visit Is Booked!
                </h1>
                <p class="lead">
                    We have reserved your appointment with <strong>{{ doctor.user.name }}</strong>. A confirmation email and SMS reminder have been sent to your registered contact.
                </p>

                <!-- REFERENCE CODE BOX -->
                <div class="code-box">
                    <label>Appointment Reference Code</label>
                    <div class="code-val">
                        <span>{{ referenceCode }}</span>
                        <button class="copy-btn" :class="{ copied: copied }" @click="copyReferenceCode" title="Copy code">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- APPOINTMENT TILES -->
                <div class="summary-grid">
                    <div class="summary-tile">
                        <label>Physician</label>
                        <p>{{ doctor.user.name }}</p>
                        <span>{{ doctor.department.name }} Dept</span>
                    </div>
                    <div class="summary-tile">
                        <label>Date & Time</label>
                        <p>{{ bookingDate }}</p>
                        <span>{{ bookingTime }}</span>
                    </div>
                    <div class="summary-tile">
                        <label>Location / Mode</label>
                        <p>{{ bookingMode }}</p>
                        <span>120 Harbor Ave, Suite 300</span>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="action-buttons">
                    <Link href="/dashboard" class="btn btn-primary">
                        Go to Patient Dashboard
                    </Link>
                    <button @click="printSummary" class="btn btn-outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                            <polyline points="6 9 6 2 18 2 18 9" />
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <rect x="6" y="14" width="12" height="8" />
                        </svg>
                        Print Summary
                    </button>
                </div>

                <!-- CALENDAR ADD SHORTCUTS -->
                <div class="cal-add-box">
                    <h5>Add to your personal calendar</h5>
                    <div class="cal-links">
                        <button class="cal-btn" @click="addToGoogleCalendar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" /></svg>
                            Google Calendar
                        </button>
                        <button class="cal-btn" @click="downloadICal">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="7 10 12 15 17 10" /><line x1="12" y1="15" x2="12" y2="3" /></svg>
                            Apple iCal / Outlook (.ics)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; width: 100%; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }

.pill { display: inline-flex; align-items: center; gap: 6px; background: #DCFCE7; color: #15803D; font-size: 13px; font-weight: 600; padding: 7px 16px 7px 12px; border-radius: 999px; border: 1px solid #BBF7D0; }

.success-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 32px; padding: 56px 48px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); text-align: center; max-width: 760px; margin: 0 auto; position: relative; overflow: hidden; }
@media (max-width: 600px) { .success-card { padding: 36px 24px; } }

.success-card::before { content: ""; position: absolute; top: -60px; left: 50%; transform: translateX(-50%); width: 260px; height: 260px; border-radius: 50%; background: #BBF7D0; opacity: 0.35; filter: blur(40px); pointer-events: none; }

.icon-badge { margin: 0 auto 20px; width: 68px; height: 68px; border-radius: 50%; background: #DCFCE7; color: #15803D; display: flex; align-items: center; justify-content: center; position: relative; }

.success-card p.lead { font-size: 15.5px; color: #62655A; max-width: 52ch; margin: 0 auto 28px; line-height: 1.6; position: relative; }

/* CODE BADGE DISPLAY */
.code-box { background: #F8F6EF; border: 1px dashed #E7E3D3; border-radius: 24px; padding: 20px; max-width: 480px; margin: 0 auto 36px; position: relative; }
.code-box label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #62655A; display: block; margin-bottom: 6px; }
.code-val { font-family: 'JetBrains Mono', monospace; font-size: clamp(1.4rem, 1.2rem + 0.8vw, 2rem); font-weight: 700; color: #16301F; letter-spacing: 0.05em; display: flex; align-items: center; justify-content: center; gap: 10px; }
.copy-btn { width: 32px; height: 32px; border-radius: 8px; background: #FFFFFF; border: 1px solid #E7E3D3; color: #62655A; display: inline-flex; align-items: center; justify-content: center; transition: all 150ms ease; cursor: pointer; }
.copy-btn:hover, .copy-btn.copied { color: #15803D; border-color: #15803D; }

/* APPOINTMENT DETAILS SUMMARY GRID */
.summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; text-align: left; margin-bottom: 36px; position: relative; }
@media (max-width: 680px) { .summary-grid { grid-template-columns: 1fr; } }

.summary-tile { background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 16px; padding: 18px; }
.summary-tile label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; color: #62655A; display: block; margin-bottom: 6px; }
.summary-tile p { font-size: 14.5px; font-weight: 700; color: #16180F; margin: 0; }
.summary-tile span { font-size: 12.5px; color: #62655A; display: block; margin-top: 2px; }

/* ACTION BUTTONS */
.action-buttons { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-bottom: 32px; position: relative; }

/* CALENDAR SHORTCUTS */
.cal-add-box { border-top: 1px solid #E7E3D3; padding-top: 28px; position: relative; }
.cal-add-box h5 { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #62655A; margin-bottom: 14px; }
.cal-links { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
.cal-btn { background: #F0EEE3; border: 1px solid #E7E3D3; padding: 8px 14px; border-radius: 999px; font-size: 13px; font-weight: 600; color: #16180F; display: inline-flex; align-items: center; gap: 6px; transition: all 150ms ease; cursor: pointer; }
.cal-btn:hover { background: #16301F; color: #fff; border-color: #16301F; }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 52px; padding: 0 28px; border-radius: 999px; font-size: 15px; font-weight: 600; transition: transform 150ms ease, background-color 150ms ease; cursor: pointer; text-decoration: none; border: 0; }
.btn-primary { background: #16301F; color: #fff; }
.btn-primary:hover { background: #1E4029; }
.btn-outline { background: transparent; color: #16180F; border: 1.5px solid rgba(22,24,15,0.16); }
.btn-outline:hover { border-color: #16180F; }
</style>
