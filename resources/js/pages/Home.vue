<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Link } from '@inertiajs/vue3'

interface Department {
    id: number
    name: string
    slug: string
    description: string
    icon_path?: string
    active_doctors_count?: number
}

interface Doctor {
    id: number
    specialization: string
    consultation_fee: string | number
    license_number: string
    user: {
        id: number
        name: string
        avatar_path?: string
    }
    department: {
        id: number
        name: string
        slug: string
    }
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

interface Stats {
    specialists_count: number
    departments_count: number
    appointments_count: number
    average_rating: number
}

defineProps<{
    departments: Department[]
    doctors: Doctor[]
    reviews: Review[]
    stats: Stats
    hospitalName: string
}>()
</script>

<template>
    <PublicLayout>
        <!-- HERO -->
        <section class="hero relative pt-16 pb-12 z-10">
            <div class="wrap hero-grid">
                <div class="hero-left flex flex-col">
                    <span class="pill mb-6 w-fit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                        Healthcare for everyone
                    </span>
                    <h1>Personalized care plans for <span class="hl">your whole family's</span> health</h1>
                    <p class="lead">MediFlow brings together board-certified specialists across multiple fields of medicine — book real availability, manage every visit, and keep your family's care in one place.</p>
                    <div class="hero-cta">
                        <Link href="/register" class="btn btn-primary">Book an appointment</Link>
                        <Link href="/departments" class="btn btn-outline">
                            Browse services
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
                        </Link>
                    </div>
                    <div class="hero-proof">
                        <div class="avatar-stack" aria-hidden="true">
                            <span class="av"></span>
                            <span class="av" style="background:linear-gradient(135deg,#3a6b4c,#84a468)"></span>
                            <span class="av" style="background:linear-gradient(135deg,#84a468,#DDF15C)"></span>
                            <span class="count">{{ stats.specialists_count }}+</span>
                        </div>
                        <p>Board-certified specialists across active fields of medicine</p>
                    </div>
                </div>

                <div class="hero-photo relative rounded-3xl overflow-hidden min-h-[480px] bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.5),transparent_40%),linear-gradient(150deg,#E4EED0_0%,#B9CFA0_45%,#7C8F6C_100%)] flex items-end justify-center shadow-card">
                    <span class="ph-caption absolute top-[22px] left-[22px] text-[11.5px] font-semibold text-white/85 bg-black/25 px-3 py-1.5 rounded-full backdrop-blur-md">Modern Hospital Care</span>
                    <span class="ph-icon opacity-35 mb-11" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" width="100" height="100"><circle cx="9" cy="8" r="3.2"/><path d="M2.5 21c0-4 3-6.5 6.5-6.5s6.5 2.5 6.5 6.5"/><circle cx="17.5" cy="9" r="2.6"/><path d="M14.8 21c.3-3 2.4-5 5.2-5 2.5 0 4.4 1.6 5 4"/></svg>
                    </span>
                    <div class="rating-float absolute bottom-[22px] right-[22px] bg-white rounded-2xl shadow-lift p-[15px_19px] flex items-center gap-[10px]">
                        <span class="pin w-9 h-9 rounded-full bg-[#EEF7C4] text-[#3B4A12] flex items-center justify-center shrink-0" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <div>
                            <b class="text-[15px] block">{{ stats.average_rating }} <span class="stars text-[#16301F] text-xs">★★★★★</span></b>
                            <span class="src text-[11px] text-[#62655A]">Verified Patient Rating</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- STATS BAND -->
        <div class="wrap">
            <div class="stats-band border-y border-[#E7E3D3] py-11">
                <div class="stats-grid">
                    <div class="stat-item"><b>{{ stats.specialists_count }}+</b><span>Specialists on staff</span></div>
                    <div class="stat-item"><b>{{ stats.departments_count }}</b><span>Medical departments</span></div>
                    <div class="stat-item"><b>{{ stats.appointments_count }}+</b><span>Appointments booked</span></div>
                    <div class="stat-item"><b>{{ stats.average_rating }}</b><span>Average patient rating</span></div>
                </div>
            </div>
        </div>

        <!-- DEPARTMENTS -->
        <section id="departments">
            <div class="wrap">
                <span class="pill mb-4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Departments</span>
                <div class="section-head max-w-[580px] mb-11">
                    <h2 class="text-4xl font-extrabold tracking-tight mt-3 mb-2.5">Find care by department</h2>
                    <p class="text-[#62655A] text-base">Start with the kind of care you need, then choose a specialist whose schedule fits yours.</p>
                </div>
                <div class="dept-grid">
                    <div v-for="dept in departments" :key="dept.id" class="dept-card bg-white rounded-2xl p-6 shadow-card hover:shadow-lift transition-all duration-150 hover:-translate-y-1">
                        <div class="dept-icon w-12 h-12 rounded-xl bg-[#EEF7C4] text-[#3B4A12] flex items-center justify-center mb-5" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" width="23" height="23"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.8 1-1a5.5 5.5 0 0 0 0-7.6z"/></svg>
                        </div>
                        <h4 class="text-base font-bold mb-1.5">{{ dept.name }}</h4>
                        <p class="text-[13.5px] text-[#62655A] mb-4 line-clamp-2">{{ dept.description }}</p>
                        <Link :href="`/departments/${dept.slug}`" class="info-link text-[13.5px] font-semibold inline-flex items-center gap-1 text-[#16301F]">
                            View doctors
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14"><path d="M9 18l6-6-6-6"/></svg>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- DOCTORS -->
        <section id="doctors" class="bg-[#F0EEE3] py-20">
            <div class="wrap">
                <span class="pill mb-4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Doctors</span>
                <div class="section-head mb-11">
                    <h2 class="text-4xl font-extrabold tracking-tight mt-3 mb-2.5">Meet our specialists</h2>
                    <p class="text-[#62655A] text-base">Board-certified physicians with practical clinical experience.</p>
                </div>
                <div class="doctor-scroll grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div v-for="doc in doctors" :key="doc.id" class="doctor-card">
                        <div class="doctor-photo rounded-2xl aspect-[3/4] bg-gradient-to-br from-[#EDEAD9] to-[#C7CBB4] shadow-card flex items-center justify-center mb-3 relative overflow-hidden">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" width="44" height="44" class="text-white opacity-55"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/></svg>
                            <span class="spec-tag absolute bottom-3 left-3 bg-white text-[11.5px] font-semibold px-3 py-1.5 rounded-full">{{ doc.department.name }}</span>
                        </div>
                        <h4 class="text-[14.5px] font-semibold leading-snug">{{ doc.user.name }}</h4>
                        <p class="text-xs text-[#62655A] mb-2">{{ doc.specialization }}</p>
                        <Link :href="`/doctors/${doc.license_number}`" class="text-xs font-semibold text-[#16301F] hover:underline">View Profile & Schedule →</Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- REVIEWS -->
        <section id="testimonials" class="py-20">
            <div class="wrap">
                <span class="pill mb-4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Reviews</span>
                <div class="section-head mb-11">
                    <h2 class="text-4xl font-extrabold tracking-tight mt-3 mb-2.5">Patient experiences</h2>
                    <p class="text-[#62655A] text-base">Feedback shared by patients following their consultations.</p>
                </div>
                <div class="testimonial-grid grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div v-for="review in reviews" :key="review.id" class="review-card bg-white rounded-2xl p-7 shadow-card flex flex-col">
                        <div class="review-head flex items-center gap-3 mb-4">
                            <div class="review-avatar w-11 h-11 rounded-full bg-gradient-to-br from-[#16301F] to-[#4c7a5c]"></div>
                            <div>
                                <div class="review-name text-[14.5px] font-semibold">{{ review.patient.user.name }}</div>
                                <div class="review-stars text-[#16301F] text-xs tracking-wider">★★★★★</div>
                            </div>
                        </div>
                        <p class="body text-sm text-[#62655A] leading-relaxed mb-3 flex-grow">"{{ review.comment }}"</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CLOSING CTA -->
        <section class="py-12">
            <div class="wrap">
                <div class="closing bg-[#16301F] rounded-3xl p-11 md:p-14 flex items-center justify-between gap-8 flex-wrap text-white relative overflow-hidden">
                    <div>
                        <h2 class="text-3xl font-extrabold tracking-tight max-w-[24ch]">Your next appointment is a couple of clicks away.</h2>
                        <p class="text-white/65 text-[14.5px] mt-2">Create a free account and book your visit today.</p>
                    </div>
                    <Link href="/register" class="btn btn-primary bg-[#DDF15C] text-[#3B4A12] hover:bg-[#ecf99c] border-0">Create your account</Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; position: relative; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }

.pill { display: inline-flex; align-items: center; gap: 6px; background: #DDF15C; color: #3B4A12; font-size: 13px; font-weight: 600; padding: 7px 16px 7px 12px; border-radius: 999px; }
.pill svg { width: 14px; height: 14px; }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 54px; padding: 0 28px; border-radius: 999px; font-size: 15.5px; font-weight: 600; text-decoration: none; transition: transform 150ms ease, background-color 150ms ease; cursor: pointer; }
.btn:active { transform: scale(0.97); }
.btn-primary { background: #16301F; color: #fff; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.btn-primary:hover { background: #1E4029; }
.btn-outline { background: transparent; color: #16180F; border: 1.5px solid rgba(22,24,15,0.16); }
.btn-outline:hover { border-color: #16180F; }

.hero-grid { display: grid; grid-template-columns: 0.85fr 1.15fr; gap: 44px; align-items: stretch; }
@media (max-width: 980px) { .hero-grid { grid-template-columns: 1fr; } }
.hero h1 { font-size: clamp(2.4rem, 1.8rem + 2.4vw, 3.6rem); line-height: 1.1; letter-spacing: -0.02em; font-weight: 800; margin-bottom: 26px; }
.hl { background: #DDF15C; padding: 2px 8px; border-radius: 8px; display: inline; box-decoration-break: clone; -webkit-box-decoration-break: clone; }
.hero-left p.lead { font-size: 17px; color: #62655A; max-width: 44ch; margin-bottom: 34px; }
.hero-cta { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 38px; }
.hero-proof { display: flex; align-items: center; gap: 16px; margin-top: auto; }
.avatar-stack { display: flex; align-items: center; }
.avatar-stack .av { width: 46px; height: 46px; border-radius: 50%; border: 3px solid #F8F6EF; margin-left: -13px; background: linear-gradient(135deg, #16301F, #3a6b4c); }
.avatar-stack .av:first-child { margin-left: 0; }
.avatar-stack .count { width: 46px; height: 46px; border-radius: 50%; border: 3px solid #F8F6EF; margin-left: -13px; background: #DDF15C; color: #3B4A12; font-size: 12.5px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
.hero-proof p { font-size: 13.5px; color: #62655A; max-width: 22ch; }

.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; text-align: center; }
@media (max-width: 720px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
.stat-item b { display: block; font-size: 40px; font-weight: 800; letter-spacing: -0.02em; font-family: 'JetBrains Mono', monospace; }
.stat-item span { font-size: 13.5px; color: #62655A; }

.dept-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
@media (max-width: 960px) { .dept-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .dept-grid { grid-template-columns: 1fr; } }
</style>
