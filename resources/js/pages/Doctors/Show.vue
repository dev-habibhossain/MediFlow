<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Link } from '@inertiajs/vue3'

interface Schedule {
    id: number
    day_of_week: number
    start_time: string
    end_time: string
    slot_duration: number
    max_patients_per_slot: number
}

interface Review {
    id: number
    rating: number
    comment: string
    patient: {
        user: {
            name: string
        }
    }
}

interface Doctor {
    id: number
    specialization: string
    qualification: string
    experience_years: number
    bio: string
    consultation_fee: string | number
    license_number: string
    user: {
        id: number
        name: string
        email: string
        phone: string
        avatar_path?: string
    }
    department: {
        id: number
        name: string
        slug: string
    }
    schedules: Schedule[]
    reviews: Review[]
}

defineProps<{
    doctor: Doctor
}>()

const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
</script>

<template>
    <PublicLayout :title="`${doctor.user.name} — MediFlow`">
        <main class="py-8">
            <div class="wrap">
                <div class="mb-6">
                    <Link href="/doctors" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#62655A] hover:text-[#16301F]">
                        ← Back to <b>all specialists</b>
                    </Link>
                </div>

                <!-- DOCTOR PROFILE HERO -->
                <div class="bg-white border border-[#E7E3D3] rounded-3xl p-8 md:p-12 shadow-card mb-12 flex flex-col md:flex-row gap-8 items-start">
                    <div class="w-24 h-24 md:w-32 md:h-32 rounded-3xl bg-gradient-to-br from-[#16301F] to-[#4c7a5c] text-white flex items-center justify-center font-extrabold text-4xl shrink-0 shadow-card">
                        {{ doctor.user.name.charAt(0) }}
                    </div>
                    <div class="flex-1">
                        <span class="pill mb-3"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>{{ doctor.department.name }}</span>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 text-[#16301F]"><b>{{ doctor.user.name }}</b></h1>
                        <p class="text-base font-semibold text-[#62655A] mb-4"><b>{{ doctor.specialization }}</b> · {{ doctor.experience_years }} Years Clinical Experience</p>
                        <p class="text-sm text-[#62655A] leading-relaxed mb-6">{{ doctor.bio || 'Dedicated medical professional focused on comprehensive patient care.' }}</p>

                        <div class="flex flex-wrap gap-6 items-center border-t border-[#E7E3D3] pt-6">
                            <div>
                                <span class="text-xs text-[#62655A] block">Qualification</span>
                                <b class="text-sm text-[#16180F]">{{ doctor.qualification }}</b>
                            </div>
                            <div>
                                <span class="text-xs text-[#62655A] block">Consultation Fee</span>
                                <b class="text-lg text-[#16301F]">${{ doctor.consultation_fee }}</b>
                            </div>
                            <div>
                                <span class="text-xs text-[#62655A] block">License</span>
                                <b class="text-sm text-[#16180F]">{{ doctor.license_number }}</b>
                            </div>
                            <div class="ml-auto">
                                <Link href="/register" class="btn bg-[#16301F] text-white hover:bg-[#1E4029] text-sm font-semibold px-6 py-3 rounded-full">
                                    Book Appointment with <b>{{ doctor.user.name.split(' ')[0] }}</b>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
                    <!-- SCHEDULES -->
                    <div class="lg:col-span-2 bg-white border border-[#E7E3D3] rounded-2xl p-7 shadow-card">
                        <h3 class="text-xl font-bold mb-4 text-[#16301F]">Weekly <b>Clinic Schedule</b></h3>

                        <div v-if="doctor.schedules.length > 0" class="space-y-3">
                            <div v-for="sch in doctor.schedules" :key="sch.id" class="flex items-center justify-between p-4 rounded-xl bg-[#F8F6EF] border border-[#E7E3D3]">
                                <div class="font-semibold text-sm"><b>{{ dayNames[sch.day_of_week] }}</b></div>
                                <div class="text-sm font-mono text-[#16301F] bg-[#EEF7C4] px-3 py-1 rounded-full font-bold">
                                    {{ sch.start_time }} – {{ sch.end_time }}
                                </div>
                            </div>
                        </div>
                        <div v-else class="py-8 text-center text-sm text-[#62655A]">
                            No fixed weekly schedule configured.
                        </div>
                    </div>

                    <!-- REVIEWS -->
                    <div class="bg-white border border-[#E7E3D3] rounded-2xl p-7 shadow-card">
                        <h3 class="text-xl font-bold mb-4 text-[#16301F]">Patient <b>Feedback</b></h3>

                        <div v-if="doctor.reviews.length > 0" class="space-y-4">
                            <div v-for="rev in doctor.reviews" :key="rev.id" class="p-4 rounded-xl bg-[#F8F6EF] border border-[#E7E3D3]">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-xs"><b>{{ rev.patient.user.name }}</b></span>
                                    <span class="text-xs text-[#16301F]">★★★★★</span>
                                </div>
                                <p class="text-xs text-[#62655A] leading-relaxed">"{{ rev.comment }}"</p>
                            </div>
                        </div>
                        <div v-else class="py-8 text-center text-xs text-[#62655A]">
                            No patient reviews recorded yet.
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }

b { font-weight: 700; color: inherit; }

.pill { display: inline-flex; align-items: center; gap: 6px; background: #DDF15C; color: #3B4A12; font-size: 13px; font-weight: 600; padding: 7px 16px 7px 12px; border-radius: 999px; }
.pill svg { width: 14px; height: 14px; }
.btn { display: inline-flex; align-items: center; justify-content: center; text-decoration: none; transition: background-color 150ms ease; cursor: pointer; }
</style>
