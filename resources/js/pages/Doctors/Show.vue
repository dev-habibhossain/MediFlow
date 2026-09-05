<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

interface Schedule {
    id: number;
    day_of_week: number;
    start_time: string;
    end_time: string;
    slot_duration: number;
    max_patients_per_slot: number;
}

interface Review {
    id: number;
    rating: number;
    comment: string;
    patient: {
        user: {
            name: string;
        };
    };
}

interface Doctor {
    id: number;
    specialization: string;
    qualification: string;
    experience_years?: number;
    bio?: string;
    consultation_fee: string | number;
    license_number: string;
    status?: string;
    user: {
        id: number;
        name: string;
        email: string;
        phone: string;
        avatar_path?: string;
    };
    department: {
        id: number;
        name: string;
        slug: string;
    };
    schedules: Schedule[];
    reviews: Review[];
}

const props = defineProps<{
    doctor: Doctor;
}>();

const dayNames = [
    'Sunday',
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
];

const selectedDay = ref('Friday, Aug 7');
const selectedTime = ref('10:00 AM');

const sampleSlots = [
    { day: 'Friday, Aug 7', time: '09:30 AM' },
    { day: 'Friday, Aug 7', time: '10:00 AM' },
    { day: 'Friday, Aug 7', time: '02:00 PM' },
    { day: 'Saturday, Aug 8', time: '11:00 AM' },
    { day: 'Saturday, Aug 8', time: '02:30 PM' },
    { day: 'Monday, Aug 10', time: '10:30 AM' },
];

function selectSlot(dayStr: string, timeStr: string) {
    selectedDay.value = dayStr;
    selectedTime.value = timeStr;

    localStorage.setItem('booking_date', dayStr);
    localStorage.setItem('booking_time', timeStr);
    sessionStorage.setItem('booking_date', dayStr);
    sessionStorage.setItem('booking_time', timeStr);
}

function startBooking() {
    localStorage.setItem('booking_date', selectedDay.value);
    localStorage.setItem('booking_time', selectedTime.value);
    localStorage.setItem('booking_doctor_license', props.doctor.license_number);
    sessionStorage.setItem('booking_date', selectedDay.value);
    sessionStorage.setItem('booking_time', selectedTime.value);

    router.get(`/appointments/book/${props.doctor.license_number}`);
}
</script>

<template>
    <PublicLayout
        :title="`${doctor.user.name} — ${doctor.specialization} | MediFlow`"
    >
        <main class="py-8">
            <div class="wrap">
                <!-- BACK LINK -->
                <div class="mb-6">
                    <Link
                        href="/doctors"
                        class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#62655A] hover:text-[#16301F]"
                    >
                        ← Back to <b>all specialists</b>
                    </Link>
                </div>

                <!-- PROFILE GRID LAYOUT -->
                <div class="profile-grid">
                    <!-- LEFT STICKY CARD -->
                    <aside class="profile-card-sticky">
                        <div class="doc-photo-large">
                            <span class="status-indicator">
                                <span class="status-dot"></span>
                                {{
                                    doctor.status === 'active'
                                        ? 'Available Today'
                                        : 'Available by Schedule'
                                }}
                            </span>
                            <img
                                v-if="doctor.user.avatar_path"
                                :src="doctor.user.avatar_path"
                                :alt="doctor.user.name"
                                class="doc-img-large"
                            />
                            <div v-else class="avatar-large">
                                {{ doctor.user.name.charAt(0) }}
                            </div>
                        </div>

                        <div class="doc-main-info">
                            <h1>
                                <b>{{ doctor.user.name }}</b>
                            </h1>
                            <p class="doc-subtitle">
                                Senior Specialist — {{ doctor.department.name }}
                            </p>
                            <span class="pill"
                                >★ 4.9 ({{
                                    (doctor.reviews?.length || 0) + 128
                                }}
                                Patient Reviews)</span
                            >
                        </div>

                        <div class="fee-badge-box">
                            <span>Consultation Fee</span>
                            <b>${{ doctor.consultation_fee }}</b>
                        </div>

                        <button
                            @click="startBooking"
                            class="btn btn-primary btn-block"
                        >
                            Book Appointment Now
                        </button>
                    </aside>

                    <!-- RIGHT CONTENT SECTIONS -->
                    <div class="profile-content">
                        <!-- ABOUT SECTION -->
                        <section class="section-box">
                            <h2>
                                About <b>{{ doctor.user.name }}</b>
                            </h2>
                            <p>
                                {{
                                    doctor.bio ||
                                    `Dr. ${doctor.user.name} is a board-certified ${doctor.specialization} with over ${doctor.experience_years || 14} years of clinical experience in non-invasive diagnosis, patient care, and modern treatment plans.`
                                }}
                            </p>
                        </section>

                        <!-- QUALIFICATIONS & EDUCATION SECTION -->
                        <section class="section-box">
                            <h2>Qualifications & <b>Clinical Expertise</b></h2>
                            <div class="qual-grid">
                                <div class="qual-item">
                                    <h4>
                                        <b>{{
                                            doctor.qualification ||
                                            'MD — Internal Medicine'
                                        }}</b>
                                    </h4>
                                    <p>Primary Medical Degree & Residency</p>
                                </div>
                                <div class="qual-item">
                                    <h4>
                                        <b
                                            >Fellowship in
                                            {{ doctor.specialization }}</b
                                        >
                                    </h4>
                                    <p>Advanced Clinical Specialty Training</p>
                                </div>
                                <div class="qual-item">
                                    <h4><b>Board Certification</b></h4>
                                    <p>
                                        Verified License #:
                                        {{ doctor.license_number }}
                                    </p>
                                </div>
                                <div class="qual-item">
                                    <h4><b>Languages Spoken</b></h4>
                                    <p>English, Spanish, Bengali</p>
                                </div>
                            </div>
                        </section>

                        <!-- AVAILABILITY SLOTS PREVIEW -->
                        <section class="section-box">
                            <h2>Next Available <b>Consultation Slots</b></h2>
                            <p class="mb-4 text-sm text-[#62655A]">
                                Select a time slot to reserve your consultation
                                directly.
                            </p>

                            <div class="slots-grid mb-6">
                                <div
                                    v-for="(slot, idx) in sampleSlots"
                                    :key="idx"
                                    :class="[
                                        'slot-pill',
                                        selectedDay === slot.day &&
                                        selectedTime === slot.time
                                            ? 'selected'
                                            : '',
                                    ]"
                                    @click="selectSlot(slot.day, slot.time)"
                                >
                                    <span>{{ slot.day }}</span>
                                    <b>{{ slot.time }}</b>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button
                                    @click="startBooking"
                                    class="btn btn-primary px-6 py-2.5 text-sm"
                                >
                                    Book Selected Slot ({{ selectedTime }}) →
                                </button>
                            </div>

                            <!-- WEEKLY SCHEDULE SUMMARY -->
                            <div
                                v-if="
                                    doctor.schedules &&
                                    doctor.schedules.length > 0
                                "
                                class="mt-6 border-t border-[#E7E3D3] pt-6"
                            >
                                <h4
                                    class="mb-3 text-sm font-bold text-[#16301F]"
                                >
                                    Weekly Clinic Schedule
                                </h4>
                                <div
                                    class="grid grid-cols-1 gap-3 sm:grid-cols-2"
                                >
                                    <div
                                        v-for="sch in doctor.schedules"
                                        :key="sch.id"
                                        class="flex items-center justify-between rounded-xl border border-[#E7E3D3] bg-[#F8F6EF] p-3 text-xs"
                                    >
                                        <span
                                            class="font-bold text-[#16301F]"
                                            >{{
                                                dayNames[sch.day_of_week]
                                            }}</span
                                        >
                                        <span
                                            class="rounded-full bg-[#EEF7C4] px-2.5 py-1 font-mono font-bold text-[#3B4A12]"
                                        >
                                            {{ sch.start_time }} –
                                            {{ sch.end_time }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- REVIEWS SECTION -->
                        <section class="section-box">
                            <h2>Patient <b>Feedback</b></h2>

                            <div
                                v-if="
                                    doctor.reviews && doctor.reviews.length > 0
                                "
                                class="space-y-4"
                            >
                                <div
                                    v-for="rev in doctor.reviews"
                                    :key="rev.id"
                                    class="review-item"
                                >
                                    <div class="review-head">
                                        <span class="review-author"
                                            ><b>{{
                                                rev.patient.user.name
                                            }}</b></span
                                        >
                                        <span class="review-stars">★★★★★</span>
                                    </div>
                                    <p>"{{ rev.comment }}"</p>
                                    <span class="review-date"
                                        >Verified Patient Consultation</span
                                    >
                                </div>
                            </div>

                            <!-- Fallback sample reviews if none yet -->
                            <div v-else class="space-y-4">
                                <div class="review-item">
                                    <div class="review-head">
                                        <span class="review-author"
                                            ><b>Marcus Webb</b></span
                                        >
                                        <span class="review-stars">★★★★★</span>
                                    </div>
                                    <p>
                                        "Dr.
                                        {{
                                            doctor.user.name.split(' ')[0]
                                        }}
                                        took the time to explain my test results
                                        thoroughly. I felt truly listened to and
                                        confident in the care plan."
                                    </p>
                                    <span class="review-date"
                                        >Reviewed on August 12, 2026</span
                                    >
                                </div>
                                <div class="review-item">
                                    <div class="review-head">
                                        <span class="review-author"
                                            ><b>Elena Novak</b></span
                                        >
                                        <span class="review-stars">★★★★★</span>
                                    </div>
                                    <p>
                                        "Punctual, professional, and very warm
                                        demeanor. Booking online took less than
                                        two minutes."
                                    </p>
                                    <span class="review-date"
                                        >Reviewed on July 28, 2026</span
                                    >
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </main>
    </PublicLayout>
</template>

<style scoped>
.wrap {
    max-width: 1320px;
    margin-inline: auto;
    padding-inline: 32px;
    position: relative;
}
@media (max-width: 640px) {
    .wrap {
        padding-inline: 20px;
    }
}

b {
    font-weight: 700;
    color: inherit;
}

.pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ddf15c;
    color: #3b4a12;
    font-size: 13px;
    font-weight: 600;
    padding: 7px 16px 7px 12px;
    border-radius: 999px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 54px;
    padding: 0 28px;
    border-radius: 999px;
    font-size: 15.5px;
    font-weight: 600;
    transition:
        transform 150ms ease,
        background-color 150ms ease,
        box-shadow 150ms ease;
    text-decoration: none;
    border: 0;
    cursor: pointer;
}
.btn:active {
    transform: scale(0.97);
}
.btn-primary {
    background: #16301f;
    color: #fff;
    box-shadow:
        0 1px 2px rgba(22, 24, 15, 0.04),
        0 8px 24px rgba(22, 24, 15, 0.06);
}
.btn-primary:hover {
    background: #1e4029;
}
.btn-block {
    width: 100%;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #3b4a12;
    background: #eef7c4;
    padding: 4px 12px 4px 10px;
    border-radius: 999px;
    border: 1px solid rgba(59, 74, 18, 0.12);
    position: absolute;
    top: 12px;
    right: 12px;
}
.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e;
    position: relative;
    flex-shrink: 0;
}
.status-dot::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(34, 197, 94, 0.4);
    animation: pulse-dot 2s infinite ease-in-out;
}

@keyframes pulse-dot {
    0% {
        transform: scale(0.8);
        opacity: 0.8;
    }
    50% {
        transform: scale(1.4);
        opacity: 0;
    }
    100% {
        transform: scale(0.8);
        opacity: 0;
    }
}

.profile-grid {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 40px;
    align-items: start;
}
@media (max-width: 980px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
}

.profile-card-sticky {
    background: #ffffff;
    border: 1px solid #e7e3d3;
    border-radius: 32px;
    padding: 32px;
    box-shadow:
        0 1px 2px rgba(22, 24, 15, 0.04),
        0 8px 24px rgba(22, 24, 15, 0.06);
    position: sticky;
    top: 100px;
}
.doc-photo-large {
    position: relative;
    width: 100%;
    aspect-ratio: 1;
    border-radius: 24px;
    background: linear-gradient(150deg, #16301f, #3a6b4c);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    overflow: hidden;
}
.doc-img-large {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    border-radius: 24px;
}
.avatar-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 42px;
    font-weight: 800;
}

.doc-main-info h1 {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -0.01em;
    margin-bottom: 4px;
    color: #16301f;
}
.doc-subtitle {
    font-size: 14px;
    color: #62655a;
    margin-bottom: 16px;
}

.fee-badge-box {
    background: #f8f6ef;
    border: 1px solid #e7e3d3;
    border-radius: 16px;
    padding: 16px;
    margin: 20px 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.fee-badge-box span {
    font-size: 13px;
    color: #62655a;
}
.fee-badge-box b {
    font-family: 'JetBrains Mono', monospace;
    font-size: 22px;
    color: #16301f;
}

.profile-content {
    display: flex;
    flex-direction: column;
    gap: 32px;
}
.section-box {
    background: #ffffff;
    border: 1px solid #e7e3d3;
    border-radius: 32px;
    padding: 36px;
    box-shadow:
        0 1px 2px rgba(22, 24, 15, 0.04),
        0 8px 24px rgba(22, 24, 15, 0.06);
}
.section-box h2 {
    font-size: 20px;
    font-weight: 800;
    margin-bottom: 16px;
    letter-spacing: -0.01em;
    color: #16301f;
}
.section-box p {
    font-size: 15px;
    color: #62655a;
    line-height: 1.7;
}

.qual-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-top: 16px;
}
@media (max-width: 600px) {
    .qual-grid {
        grid-template-columns: 1fr;
    }
}
.qual-item {
    background: #f8f6ef;
    border: 1px solid #e7e3d3;
    border-radius: 16px;
    padding: 18px;
}
.qual-item h4 {
    font-size: 14.5px;
    font-weight: 700;
    margin-bottom: 4px;
    color: #16301f;
}
.qual-item p {
    font-size: 13px;
    color: #62655a;
    margin: 0;
}

.slots-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-top: 16px;
}
@media (max-width: 600px) {
    .slots-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
.slot-pill {
    background: #f8f6ef;
    border: 1.5px solid #e7e3d3;
    border-radius: 16px;
    padding: 14px 10px;
    text-align: center;
    cursor: pointer;
    transition: all 150ms ease;
}
.slot-pill:hover,
.slot-pill.selected {
    border-color: #16301f;
    background: #eef7c4;
}
.slot-pill span {
    display: block;
    font-size: 12px;
    color: #62655a;
    margin-bottom: 2px;
}
.slot-pill b {
    font-family: 'JetBrains Mono', monospace;
    font-size: 14px;
    color: #16180f;
}

.review-item {
    border-bottom: 1px solid #e7e3d3;
    padding: 20px 0;
}
.review-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.review-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}
.review-author {
    font-size: 14.5px;
    font-weight: 700;
    color: #16301f;
}
.review-stars {
    color: #16301f;
    font-size: 13px;
}
.review-date {
    font-size: 12px;
    color: #62655a;
    display: block;
    margin-top: 6px;
}
</style>
