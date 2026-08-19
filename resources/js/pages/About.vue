<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Link } from '@inertiajs/vue3'

interface Stats {
    doctors_count: number
    departments_count: number
    patients_count: number
    appointments_count: number
}

defineProps<{
    stats: Stats
}>()
</script>

<template>
    <PublicLayout title="About Us — MediFlow">
        <main class="py-6">
            <div class="wrap">
                <!-- PAGE HERO -->
                <section class="about-hero">
                    <span class="pill mb-5">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                        About MediFlow
                    </span>
                    <h1>Redefining Healthcare Through <b>Compassion & Precision</b></h1>
                    <p class="lead">MediFlow was founded on a simple principle: high-quality medical care should be easy to access, entirely transparent, and designed around the patient's peace of mind.</p>
                </section>

                <!-- HOSPITAL STORY SECTION -->
                <section class="story-grid">
                    <div class="story-content">
                        <h2>Our Founding Story</h2>
                        <p>MediFlow started with a clear observation: patients were spending hours on phone calls, navigating confusing clinic schedules, and repeating medical histories to different specialists.</p>
                        <p>We built MediFlow to change that. By connecting board-certified specialists across <b>25 medical fields</b> into one unified clinical network, we replaced fragmented phone scheduling with real-time digital access and consolidated health records.</p>
                        <p>Today, MediFlow serves over <b>40,000 patients</b> annually across our outpatient suites, diagnostic labs, and specialty care units — delivering modern, family-centered healthcare without administrative friction.</p>
                    </div>

                    <div class="story-card-box">
                        <h3>A Calm & Operational Approach</h3>
                        <p>Every step of the MediFlow experience — from instant online slot confirmation to clear upfront consultation fees — is engineered for absolute operational precision and patient trust.</p>
                        <Link href="/doctors" class="btn btn-outline btn-sm">Browse Clinical Roster</Link>
                    </div>
                </section>

                <!-- MISSION & VISION -->
                <section class="mission-grid">
                    <div class="mission-card dark">
                        <div class="mission-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-4.35-9.5-8.5C.6 8.9 2.3 5 6 5c2 0 3.3 1.1 4 2 .7-.9 2-2 4-2 3.7 0 5.4 3.9 3.5 7.5C19 16.65 12 21 12 21z"/></svg>
                        </div>
                        <h3>Our Mission</h3>
                        <p>To provide <b>personalized, high-precision medical care</b> for every family member, eliminating healthcare barriers through seamless digital access and board-certified clinical excellence.</p>
                    </div>

                    <div class="mission-card">
                        <div class="mission-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        </div>
                        <h3>Our Vision</h3>
                        <p>To become the <b>leading integrated healthcare management standard</b> — where every consultation, diagnostic test, and prescription lives in one accessible, trustworthy platform.</p>
                    </div>
                </section>

                <!-- TIMELINE MILESTONES -->
                <section class="mb-14">
                    <div class="section-head">
                        <h2>Our Journey & Milestones</h2>
                        <p>Key moments that shaped MediFlow into a modern healthcare network.</p>
                    </div>

                    <div class="timeline-grid">
                        <div class="timeline-card">
                            <span class="timeline-year">2020</span>
                            <h4>Foundation</h4>
                            <p>Established as a specialized cardiovascular clinic with <b>10 senior doctors</b>.</p>
                        </div>
                        <div class="timeline-card">
                            <span class="timeline-year">2022</span>
                            <h4>Digital Platform</h4>
                            <p>Launched <b>real-time online appointment scheduling</b> and digital patient records.</p>
                        </div>
                        <div class="timeline-card">
                            <span class="timeline-year">2024</span>
                            <h4>Diagnostic Expansion</h4>
                            <p>Added 3D Imaging, automated pathology labs, and <b>15 new clinical specialties</b>.</p>
                        </div>
                        <div class="timeline-card">
                            <span class="timeline-year">2026</span>
                            <h4>40k+ Patients Served</h4>
                            <p>Grew to <b>68+ board-certified specialists</b> across 25 fields of medicine.</p>
                        </div>
                    </div>
                </section>

                <!-- FACILITIES & INFRASTRUCTURE -->
                <section class="mb-14">
                    <div class="section-head">
                        <h2>Clinical Facilities & Infrastructure</h2>
                        <p>Designed for sterile safety, rapid diagnostics, and patient comfort.</p>
                    </div>

                    <div class="facilities-grid">
                        <div class="facility-card">
                            <div class="facility-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                            </div>
                            <h4>Express Diagnostics Lab</h4>
                            <p>Automated biochemistry, hormone panels, and molecular testing with <b>same-day reports</b>.</p>
                        </div>

                        <div class="facility-card">
                            <div class="facility-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="8" rx="1.5"/><path d="M5 11V7a3 3 0 0 1 3-3h3M9 19v2M16 19v2"/></svg>
                            </div>
                            <h4>Laparoscopic Operating Suites</h4>
                            <p>Ultra-clean surgical units equipped with high-definition minimally invasive technology.</p>
                        </div>

                        <div class="facility-card">
                            <div class="facility-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M6 8h12M6 12h8"/></svg>
                            </div>
                            <h4>Patient Record Portal</h4>
                            <p>Encrypted digital portal storing consultation notes, lab results, and prescriptions.</p>
                        </div>
                    </div>
                </section>

                <!-- EXECUTIVE LEADERSHIP -->
                <section class="mb-14">
                    <div class="section-head">
                        <h2>Executive Leadership</h2>
                        <p>Guided by experienced medical clinicians and operational experts.</p>
                    </div>

                    <div class="leadership-grid">
                        <div class="leader-card">
                            <div class="leader-avatar">AM</div>
                            <div class="leader-info">
                                <h4>Dr. Arthur Miller</h4>
                                <span>Chief Medical Officer</span>
                                <p><b>20+ yrs</b> in healthcare management & clinical cardiology.</p>
                            </div>
                        </div>

                        <div class="leader-card">
                            <div class="leader-avatar">SL</div>
                            <div class="leader-info">
                                <h4>Dr. Sophia Lin</h4>
                                <span>Head of Diagnostics</span>
                                <p>Former research fellow leading our lab & imaging division.</p>
                            </div>
                        </div>

                        <div class="leader-card">
                            <div class="leader-avatar">DH</div>
                            <div class="leader-info">
                                <h4>Daniel Hays</h4>
                                <span>Director of Operations</span>
                                <p>Specializes in clinical workflow efficiency & patient care standards.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ACCREDITATIONS & TRUST -->
                <div class="trust-band mb-14">
                    <div class="trust-title">Recognized & Accredited By</div>
                    <div class="trust-logos">
                        <span class="trust-tag">JCI Accredited Standard</span>
                        <span class="trust-tag">ISO 9001:2015 Certified Lab</span>
                        <span class="trust-tag">Board Verified Specialists</span>
                        <span class="trust-tag">HIPAA Compliant Data Security</span>
                    </div>
                </div>

                <!-- CORE VALUES SECTION -->
                <section class="mb-14">
                    <div class="section-head">
                        <h2>Our Core Values</h2>
                        <p>Four principles that guide every interaction at MediFlow.</p>
                    </div>

                    <div class="values-grid">
                        <div class="value-card">
                            <div class="value-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <h4>Clinical Integrity</h4>
                            <p>Every physician on staff is credential-verified and completes annual professional training upgrades.</p>
                        </div>

                        <div class="value-card">
                            <div class="value-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                            </div>
                            <h4>Absolute Punctuality</h4>
                            <p>We respect your time. Real availability means your appointment time is reserved exclusively for you.</p>
                        </div>

                        <div class="value-card">
                            <div class="value-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                            </div>
                            <h4>Transparent Pricing</h4>
                            <p>No surprise billing. Consultation fees are listed clearly on doctor profiles before booking.</p>
                        </div>

                        <div class="value-card">
                            <div class="value-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            </div>
                            <h4>Patient Privacy</h4>
                            <p>Your medical records, prescriptions, and lab results are secured with enterprise-level encryption.</p>
                        </div>
                    </div>
                </section>

                <!-- STATS BAND -->
                <div class="stats-band mb-14">
                    <div class="stats-grid">
                        <div class="stat-item"><b>{{ stats.doctors_count || 68 }}+</b><span>Specialists on staff</span></div>
                        <div class="stat-item"><b>{{ stats.departments_count || 25 }}</b><span>Fields of medicine</span></div>
                        <div class="stat-item"><b>{{ stats.appointments_count || 40000 }}+</b><span>Appointments booked</span></div>
                        <div class="stat-item"><b>4.9</b><span>Average patient rating</span></div>
                    </div>
                </div>

                <!-- CLOSING CTA BANNER -->
                <div class="closing mb-16">
                    <div>
                        <h2>Ready to experience modern healthcare?</h2>
                        <p>Create your account in under two minutes and schedule your first visit today.</p>
                    </div>
                    <Link href="/register" class="btn btn-primary">Book Your Visit</Link>
                </div>
            </div>
        </main>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; position: relative; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }

b { font-weight: 700; color: #16180F; }

.pill { display: inline-flex; align-items: center; gap: 6px; background: #DDF15C; color: #3B4A12; font-size: 13px; font-weight: 600; padding: 7px 16px 7px 12px; border-radius: 999px; }
.pill svg { width: 14px; height: 14px; }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 54px; padding: 0 28px; border-radius: 999px; font-size: 15.5px; font-weight: 600; transition: transform 150ms ease, background-color 150ms ease, box-shadow 150ms ease; text-decoration: none; border: 0; cursor: pointer; }
.btn:active { transform: scale(0.97); }
.btn-primary { background: #16301F; color: #fff; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.btn-primary:hover { background: #1E4029; }
.btn-outline { background: transparent; color: #16180F; border: 1.5px solid rgba(22,24,15,0.16); }
.btn-outline:hover { border-color: #16180F; }
.btn-sm { height: 44px; padding: 0 20px; font-size: 13.5px; }

.about-hero { padding: 48px 0 32px; }
.about-hero h1 { font-size: clamp(2.2rem, 1.6rem + 2vw, 3.2rem); font-weight: 800; letter-spacing: -0.02em; margin-bottom: 16px; line-height: 1.15; color: #16301F; }
.about-hero p.lead { font-size: 17px; color: #62655A; max-width: 62ch; line-height: 1.6; }

.section-head { margin-bottom: 28px; margin-top: 40px; }
.section-head h2 { font-size: 26px; font-weight: 800; letter-spacing: -0.015em; color: #16301F; margin-bottom: 6px; }
.section-head p { font-size: 14.5px; color: #62655A; }

.story-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; align-items: center; padding: 16px 0 48px; }
@media (max-width: 900px) { .story-grid { grid-template-columns: 1fr; } }

.story-content h2 { font-size: 28px; font-weight: 800; letter-spacing: -0.015em; margin-bottom: 16px; color: #16301F; }
.story-content p { font-size: 14.5px; color: #62655A; line-height: 1.65; margin-bottom: 14px; }

.story-card-box { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 32px; padding: 32px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); position: relative; overflow: hidden; }
.story-card-box::before { content: ""; position: absolute; top: -40px; right: -40px; width: 160px; height: 160px; border-radius: 50%; background: #DDF15C; opacity: 0.25; filter: blur(20px); }
.story-card-box h3 { font-size: 18px; font-weight: 800; color: #16301F; margin-bottom: 10px; }
.story-card-box p { font-size: 14px; color: #62655A; line-height: 1.6; margin-bottom: 18px; }

.mission-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 48px; }
@media (max-width: 768px) { .mission-grid { grid-template-columns: 1fr; } }

.mission-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 30px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.mission-card.dark { background: #16301F; color: #fff; }
.mission-card.dark p { color: rgba(255,255,255,0.75); }
.mission-card.dark b { color: #DDF15C; }
.mission-icon { width: 44px; height: 44px; border-radius: 12px; background: #EEF7C4; color: #3B4A12; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
.mission-card.dark .mission-icon { background: rgba(255,255,255,0.12); color: #DDF15C; }
.mission-icon svg { width: 22px; height: 22px; }
.mission-card h3 { font-size: 20px; font-weight: 800; margin-bottom: 8px; }
.mission-card p { font-size: 14px; color: #62655A; line-height: 1.6; }

.timeline-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
@media (max-width: 900px) { .timeline-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .timeline-grid { grid-template-columns: 1fr; } }

.timeline-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 20px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.timeline-year { font-family: 'JetBrains Mono', monospace; font-size: 12.5px; font-weight: 700; color: #3B4A12; background: #DDF15C; padding: 3px 10px; border-radius: 999px; display: inline-block; margin-bottom: 10px; }
.timeline-card h4 { font-size: 15px; font-weight: 700; margin-bottom: 4px; }
.timeline-card p { font-size: 13px; color: #62655A; line-height: 1.55; }

.facilities-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
@media (max-width: 900px) { .facilities-grid { grid-template-columns: 1fr; } }

.facility-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 24px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.facility-icon { width: 40px; height: 40px; border-radius: 10px; background: #F0EEE3; color: #16301F; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
.facility-icon svg { width: 20px; height: 20px; }
.facility-card h4 { font-size: 15.5px; font-weight: 700; margin-bottom: 6px; }
.facility-card p { font-size: 13.5px; color: #62655A; line-height: 1.55; }

.leadership-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
@media (max-width: 900px) { .leadership-grid { grid-template-columns: 1fr; } }

.leader-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 20px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); display: flex; gap: 14px; align-items: center; }
.leader-avatar { width: 52px; height: 52px; border-radius: 50%; background: linear-gradient(135deg, #16301F, #3a6b4c); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 17px; flex-shrink: 0; }
.leader-info h4 { font-size: 15px; font-weight: 700; margin-bottom: 2px; }
.leader-info span { font-size: 12.5px; color: #16301F; font-weight: 600; display: block; margin-bottom: 2px; }
.leader-info p { font-size: 12px; color: #62655A; }

.trust-band { background: #F0EEE3; border: 1px solid #E7E3D3; border-radius: 32px; padding: 28px 32px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.trust-title { font-size: 16px; font-weight: 800; color: #16301F; }
.trust-logos { display: flex; gap: 12px; flex-wrap: wrap; }
.trust-tag { background: #FFFFFF; border: 1px solid #E7E3D3; padding: 6px 14px; border-radius: 999px; font-size: 12.5px; font-weight: 600; color: #62655A; }

.values-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
@media (max-width: 980px) { .values-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px) { .values-grid { grid-template-columns: 1fr; } }

.value-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 24px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.value-icon { width: 40px; height: 40px; border-radius: 10px; background: #16301F; color: #DDF15C; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
.value-icon svg { width: 18px; height: 18px; }
.value-card h4 { font-size: 15.5px; font-weight: 700; margin-bottom: 6px; }
.value-card p { font-size: 13.5px; color: #62655A; line-height: 1.55; }

.stats-band { border-top: 1px solid #E7E3D3; border-bottom: 1px solid #E7E3D3; padding: 36px 0; }
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; text-align: center; }
@media (max-width: 720px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
.stat-item b { display: block; font-size: 36px; font-weight: 800; letter-spacing: -0.02em; font-family: 'JetBrains Mono', monospace; color: #16301F; }
.stat-item span { font-size: 13px; color: #62655A; }

.closing { background: #16301F; border-radius: 32px; padding: 52px 40px; display: flex; align-items: center; justify-content: space-between; gap: 28px; flex-wrap: wrap; color: #fff; position: relative; overflow: hidden; }
.closing::before { content: ""; position: absolute; top: -80px; right: -60px; width: 240px; height: 240px; border-radius: 50%; background: #DDF15C; opacity: 0.15; filter: blur(10px); }
.closing h2 { font-size: 26px; font-weight: 800; letter-spacing: -0.015em; max-width: 24ch; position: relative; }
.closing p { color: rgba(255,255,255,0.65); font-size: 14px; margin-top: 6px; position: relative; }
.closing .btn-primary { background: #DDF15C; color: #3B4A12; position: relative; border: 0; }
.closing .btn-primary:hover { background: #ecf99c; }
</style>
