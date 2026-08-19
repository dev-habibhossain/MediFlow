<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

interface Doctor {
    id: number
    specialization: string
    qualifications?: string
    years_of_experience?: number
    consultation_fee: string | number
    license_number: string
    bio?: string
    user: {
        id: number
        name: string
        email: string
        avatar_path?: string
    }
    department?: {
        id: number
        name: string
        slug: string
    }
}

interface Department {
    id: number
    name: string
    slug: string
    description: string
    doctors: Doctor[]
}

const props = defineProps<{
    department: Department
}>()

const selectedModalDoctor = ref<Doctor | null>(null)

function openDoctorModal(doc: Doctor) {
    selectedModalDoctor.value = doc
}

function closeDoctorModal() {
    selectedModalDoctor.value = null
}

function getProcedureCards(slug: string) {
    const defaultProcedures = [
        {
            title: 'Comprehensive Evaluation',
            desc: 'In-depth diagnostic assessment and personalized treatment planning managed by senior specialists.',
            icon: 'pulse'
        },
        {
            title: 'Advanced Diagnostics',
            desc: 'High-resolution ultrasound imaging and digital monitoring mapping clinical function.',
            icon: 'heart'
        },
        {
            title: 'Targeted Management',
            desc: 'Customized pharmaceutical and therapeutic protocols designed for optimal recovery.',
            icon: 'shield'
        }
    ]

    if (slug === 'cardiology') {
        return [
            { title: 'Electrocardiogram (ECG)', desc: 'Real-time heart rhythm recording and automated evaluation for arrhythmia and ischemia detection.', icon: 'pulse' },
            { title: 'Echocardiography', desc: 'High-resolution ultrasound imaging mapping heart chamber structure, valve motion, and blood velocity.', icon: 'heart' },
            { title: 'Hypertension Management', desc: 'Customized pharmaceutical and lifestyle protocols designed to stabilize chronic high blood pressure.', icon: 'shield' }
        ]
    }

    return defaultProcedures
}
</script>

<template>
    <PublicLayout :title="`${department.name} Department — MediFlow`">
        <main class="py-8">
            <div class="wrap">
                <!-- BREADCRUMB & HERO -->
                <section class="dept-hero">
                    <div class="breadcrumb">
                        <Link href="/departments">Departments</Link>
                        <span>/</span>
                        <span class="active-crumb"><b>{{ department.name }}</b></span>
                    </div>

                    <div class="dept-hero-card">
                        <div class="dept-hero-main">
                            <span class="pill mb-4">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                                Clinical Division
                            </span>
                            <h1><b>{{ department.name }}</b> & Care Services</h1>
                            <p class="lead">{{ department.description }}</p>
                            <a href="#specialists" class="btn btn-primary font-semibold">Meet Specialists ↓</a>
                        </div>

                        <div class="dept-stats-grid">
                            <div class="dept-stat-item">
                                <b>{{ department.doctors.length || 8 }}</b>
                                <span>Senior Physicians</span>
                            </div>
                            <div class="dept-stat-item">
                                <b>15k+</b>
                                <span>Patients Treated</span>
                            </div>
                            <div class="dept-stat-item">
                                <b>24/7</b>
                                <span>Emergency Care</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SPECIALIZED SERVICES & PROCEDURES -->
                <section class="mb-16">
                    <div class="section-head">
                        <h2>Specialized Services & <b>Procedures</b></h2>
                        <p>Advanced diagnostic facilities and individualized treatment plans managed by senior specialists.</p>
                    </div>

                    <div class="treatments-grid">
                        <div v-for="(proc, idx) in getProcedureCards(department.slug)" :key="idx" class="treatment-card">
                            <div class="treatment-icon">
                                <svg v-if="proc.icon === 'pulse'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                                <svg v-else-if="proc.icon === 'heart'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.8 1-1a5.5 5.5 0 0 0 0-7.6z"/></svg>
                                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                            <h4><b>{{ proc.title }}</b></h4>
                            <p>{{ proc.desc }}</p>
                        </div>
                    </div>
                </section>

                <!-- DEPARTMENT DOCTORS ROSTER -->
                <section id="specialists" class="mb-20">
                    <div class="section-head">
                        <h2><b>{{ department.name }}</b> Specialists</h2>
                        <p>Board-certified specialists currently accepting new patients at MediFlow.</p>
                    </div>

                    <div v-if="department.doctors.length > 0" class="doctors-grid">
                        <div v-for="doc in department.doctors" :key="doc.id" class="doctor-card-full">
                            <div>
                                <!-- Photo Wrap -->
                                <div class="doc-photo-wrap">
                                    <span class="status-indicator doc-status-pin">
                                        <span class="status-dot"></span> Available Today
                                    </span>
                                    <div class="avatar-ph">
                                        <img v-if="doc.user.avatar_path" :src="doc.user.avatar_path" :alt="doc.user.name" class="w-full h-full object-cover rounded-2xl" />
                                        <div v-else class="initials-avatar">
                                            {{ doc.user.name.charAt(0) }}
                                        </div>
                                    </div>
                                    <span class="doc-dept-badge">{{ doc.specialization }}</span>
                                </div>

                                <!-- Doc Info -->
                                <div class="doc-info">
                                    <h3><b>{{ doc.user.name }}</b></h3>
                                    <p class="doc-exp">{{ doc.specialization }} · <b>{{ doc.years_of_experience || 12 }} yrs</b> exp</p>
                                </div>

                                <!-- Meta Row -->
                                <div class="doc-meta-row">
                                    <div class="doc-rating">
                                        <b>4.9</b> <span class="stars">★★★★★</span> <span class="rev-count">(128)</span>
                                    </div>
                                    <div class="doc-fee"><b>${{ doc.consultation_fee }}</b> / visit</div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="doc-actions">
                                <button @click="openDoctorModal(doc)" class="btn btn-outline btn-sm font-semibold" type="button">
                                    Quick Profile
                                </button>
                                <Link :href="`/doctors/${doc.license_number}`" class="btn btn-primary btn-sm font-semibold">
                                    View Profile
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- EMPTY DOCTORS STATE -->
                    <div v-else class="no-doctors-box">
                        <h4>No specialists assigned to <b>{{ department.name }}</b> yet</h4>
                        <p>Our clinical roster is currently being updated. Please check back shortly or browse all available doctors.</p>
                        <Link href="/doctors" class="btn btn-primary btn-sm mt-4">Browse All Doctors</Link>
                    </div>
                </section>

                <!-- CLOSING BANNER -->
                <div class="closing mb-16">
                    <div>
                        <h2>Need an urgent appointment with a <b>{{ department.name }} specialist</b>?</h2>
                        <p>Book a consultation directly or reach out to our front desk for rapid clinical guidance.</p>
                    </div>
                    <Link href="/doctors" class="btn btn-primary">Book Consultation</Link>
                </div>
            </div>
        </main>

        <!-- DOCTOR QUICK PROFILE MODAL -->
        <Teleport to="body">
            <div v-if="selectedModalDoctor" class="modal-overlay" @click.self="closeDoctorModal">
                <div class="modal-card">
                    <button @click="closeDoctorModal" class="modal-close-btn" type="button" aria-label="Close Modal">✕</button>
                    
                    <div class="modal-head">
                        <div class="modal-avatar">
                            {{ selectedModalDoctor.user.name.charAt(0) }}
                        </div>
                        <div>
                            <h3><b>{{ selectedModalDoctor.user.name }}</b></h3>
                            <p class="text-sm text-[#62655A]">{{ selectedModalDoctor.specialization }}</p>
                            <span class="inline-block mt-1 text-xs font-semibold bg-[#EEF7C4] text-[#3B4A12] px-2.5 py-0.5 rounded-full">
                                License: {{ selectedModalDoctor.license_number }}
                            </span>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="info-row">
                            <span class="lbl">Department:</span>
                            <span class="val"><b>{{ department.name }}</b></span>
                        </div>
                        <div class="info-row">
                            <span class="lbl">Consultation Fee:</span>
                            <span class="val font-mono text-[#16301F]"><b>${{ selectedModalDoctor.consultation_fee }}</b> / visit</span>
                        </div>
                        <div class="info-row">
                            <span class="lbl">Qualifications:</span>
                            <span class="val"><b>{{ selectedModalDoctor.qualifications || 'MD, Board Certified' }}</b></span>
                        </div>
                        <div class="info-row" v-if="selectedModalDoctor.bio">
                            <span class="lbl">Biography:</span>
                            <p class="text-xs text-[#62655A] mt-1 leading-relaxed">{{ selectedModalDoctor.bio }}</p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <Link :href="`/doctors/${selectedModalDoctor.license_number}`" class="btn btn-primary btn-sm w-full">
                            Full Profile & Booking Schedule →
                        </Link>
                    </div>
                </div>
            </div>
        </Teleport>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; position: relative; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }

b { font-weight: 700; color: inherit; }

.pill { display: inline-flex; align-items: center; gap: 6px; background: #DDF15C; color: #3B4A12; font-size: 13px; font-weight: 600; padding: 7px 16px 7px 12px; border-radius: 999px; }
.pill svg { width: 14px; height: 14px; }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 54px; padding: 0 28px; border-radius: 999px; font-size: 15.5px; font-weight: 600; transition: transform 150ms ease, background-color 150ms ease, box-shadow 150ms ease; text-decoration: none; border: 0; cursor: pointer; }
.btn:active { transform: scale(0.97); }
.btn-primary { background: #16301F; color: #fff; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.btn-primary:hover { background: #1E4029; }
.btn-outline { background: transparent; color: #16180F; border: 1.5px solid rgba(22,24,15,0.16); }
.btn-outline:hover { border-color: #16180F; }
.btn-sm { height: 44px; padding: 0 20px; font-size: 13.5px; }

/* STATUS DOT ANIMATION */
.status-indicator { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #3B4A12; background: #EEF7C4; padding: 4px 12px 4px 10px; border-radius: 999px; border: 1px solid rgba(59, 74, 18, 0.12); }
.status-dot { width: 8px; height: 8px; border-radius: 50%; background: #22C55E; position: relative; flex-shrink: 0; }
.status-dot::after { content: ''; position: absolute; top: -2px; left: -2px; width: 12px; height: 12px; border-radius: 50%; background: rgba(34, 197, 94, 0.4); animation: pulse-dot 2s infinite ease-in-out; }
@keyframes pulse-dot { 0% { transform: scale(0.8); opacity: 0.8; } 50% { transform: scale(1.4); opacity: 0; } 100% { transform: scale(0.8); opacity: 0; } }

.dept-hero { padding: 36px 0 28px; }
.breadcrumb { font-size: 13.5px; color: #62655A; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
.breadcrumb a { text-decoration: none; color: #62655A; }
.breadcrumb a:hover { color: #16301F; text-decoration: underline; }
.active-crumb { color: #16180F; }

.dept-hero-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 32px; padding: 44px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); display: grid; grid-template-columns: 1fr 340px; gap: 40px; align-items: center; }
@media (max-width: 980px) { .dept-hero-card { grid-template-columns: 1fr; padding: 28px; } }

.dept-hero-main h1 { font-size: clamp(2.2rem, 1.6rem + 2vw, 3.2rem); font-weight: 800; letter-spacing: -0.02em; margin-bottom: 16px; line-height: 1.15; color: #16301F; }
.dept-hero-main p.lead { font-size: 16.5px; color: #62655A; line-height: 1.65; max-width: 58ch; margin-bottom: 24px; }

.dept-stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 20px; text-align: center; }
.dept-stat-item b { display: block; font-family: 'JetBrains Mono', monospace; font-size: 22px; color: #16301F; }
.dept-stat-item span { font-size: 12.5px; color: #62655A; }

.section-head { margin-bottom: 32px; margin-top: 48px; }
.section-head h2 { font-size: 26px; font-weight: 800; letter-spacing: -0.015em; color: #16301F; margin-bottom: 8px; }
.section-head p { font-size: 15px; color: #62655A; }

.treatments-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
@media (max-width: 900px) { .treatments-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px) { .treatments-grid { grid-template-columns: 1fr; } }

.treatment-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 26px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.treatment-icon { width: 44px; height: 44px; border-radius: 12px; background: #EEF7C4; color: #3B4A12; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
.treatment-icon svg { width: 22px; height: 22px; }
.treatment-card h4 { font-size: 16.5px; font-weight: 700; margin-bottom: 6px; color: #16180F; }
.treatment-card p { font-size: 13.5px; color: #62655A; line-height: 1.6; }

.doctors-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
@media (max-width: 980px) { .doctors-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px) { .doctors-grid { grid-template-columns: 1fr; } }

.doctor-card-full { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 24px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); transition: box-shadow 150ms ease, transform 150ms ease; display: flex; flex-direction: column; justify-content: space-between; position: relative; }
.doctor-card-full:hover { box-shadow: 0 4px 10px rgba(22,24,15,0.06), 0 16px 36px rgba(22,24,15,0.10); transform: translateY(-3px); }

.doc-photo-wrap { position: relative; width: 100%; aspect-ratio: 4/3; border-radius: 16px; background: linear-gradient(150deg, #EDEAD9, #C7CBB4); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; overflow: hidden; }
.initials-avatar { font-size: 2.5rem; font-weight: 800; color: #16301F; }

.doc-status-pin { position: absolute; top: 12px; right: 12px; z-index: 2; }
.doc-dept-badge { position: absolute; bottom: 12px; left: 12px; background: rgba(255,255,255,0.92); backdrop-filter: blur(6px); color: #16180F; font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 999px; z-index: 2; }

.doc-info h3 { font-size: 19px; font-weight: 700; margin-bottom: 4px; color: #16180F; }
.doc-exp { font-size: 13px; color: #62655A; margin-bottom: 12px; }

.doc-meta-row { display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #E7E3D3; border-bottom: 1px solid #E7E3D3; padding: 12px 0; margin-bottom: 20px; font-size: 13.5px; }
.doc-rating { display: flex; align-items: center; gap: 4px; font-weight: 700; color: #16180F; }
.doc-rating span.stars { color: #16301F; font-size: 12px; }
.doc-rating span.rev-count { color: #62655A; font-weight: 400; }
.doc-fee { font-family: 'JetBrains Mono', monospace; font-weight: 600; color: #16301F; }

.doc-actions { display: flex; gap: 10px; }
.doc-actions .btn { flex: 1; }

.no-doctors-box { text-align: center; padding: 48px 24px; background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; }
.no-doctors-box h4 { font-size: 18px; font-weight: 700; color: #16301F; margin-bottom: 6px; }
.no-doctors-box p { font-size: 14px; color: #62655A; }

.closing { background: #16301F; border-radius: 32px; padding: 60px 44px; display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap; color: #fff; position: relative; overflow: hidden; }
.closing::before { content: ""; position: absolute; top: -80px; right: -60px; width: 260px; height: 260px; border-radius: 50%; background: #DDF15C; opacity: 0.15; filter: blur(10px); }
.closing h2 { font-size: 29px; font-weight: 800; letter-spacing: -0.015em; max-width: 24ch; position: relative; }
.closing p { color: rgba(255,255,255,0.65); font-size: 14.5px; margin-top: 8px; position: relative; }
.closing .btn-primary { background: #DDF15C; color: #3B4A12; position: relative; border: 0; }
.closing .btn-primary:hover { background: #ecf99c; }

/* MODAL STYLING */
.modal-overlay { position: fixed; inset: 0; z-index: 100; background: rgba(22, 24, 15, 0.5); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; padding: 20px; }
.modal-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; width: 100%; max-width: 480px; padding: 28px; box-shadow: 0 4px 10px rgba(22,24,15,0.06), 0 16px 36px rgba(22,24,15,0.10); position: relative; }
.modal-close-btn { position: absolute; top: 20px; right: 20px; font-size: 18px; color: #62655A; background: #F8F6EF; border: 0; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; }
.modal-close-btn:hover { color: #16180F; background: #EEF7C4; }
.modal-head { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; border-bottom: 1px solid #E7E3D3; padding-bottom: 16px; }
.modal-avatar { width: 56px; height: 56px; border-radius: 50%; background: #16301F; color: #fff; font-size: 22px; font-weight: 800; display: flex; align-items: center; justify-content: center; }
.modal-head h3 { font-size: 18px; font-weight: 700; color: #16180F; }
.modal-body { display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; }
.info-row { display: flex; justify-content: space-between; font-size: 13.5px; border-bottom: 1px dashed #E7E3D3; padding-bottom: 8px; flex-wrap: wrap; }
.info-row .lbl { color: #62655A; }
.info-row .val { color: #16180F; }
</style>
