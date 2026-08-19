<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps<{
    stats: {
        doctors_count: number
        departments_count: number
        patients_count: number
        appointments_count: number
    }
}>()
</script>

<template>
    <PublicLayout>
        <div class="wrap py-12">
            <!-- PAGE HERO -->
            <section class="page-hero mb-12">
                <span class="pill mb-4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>About MediFlow</span>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">Modern Hospital Care for Every Family</h1>
                <p class="text-lg text-[#62655A] max-w-2xl">We combine board-certified clinical expertise with digital appointment coordination, giving patients seamless access to specialized healthcare.</p>
            </section>

            <!-- STATS BAND -->
            <div class="bg-white border border-[#E7E3D3] rounded-3xl p-8 md:p-12 shadow-card mb-16">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <b class="block text-4xl font-extrabold font-mono text-[#16301F] mb-1">{{ stats.doctors_count }}+</b>
                        <span class="text-sm text-[#62655A]">Active Specialists</span>
                    </div>
                    <div>
                        <b class="block text-4xl font-extrabold font-mono text-[#16301F] mb-1">{{ stats.departments_count }}</b>
                        <span class="text-sm text-[#62655A]">Specialized Departments</span>
                    </div>
                    <div>
                        <b class="block text-4xl font-extrabold font-mono text-[#16301F] mb-1">{{ stats.patients_count }}+</b>
                        <span class="text-sm text-[#62655A]">Registered Patients</span>
                    </div>
                    <div>
                        <b class="block text-4xl font-extrabold font-mono text-[#16301F] mb-1">{{ stats.appointments_count }}+</b>
                        <span class="text-sm text-[#62655A]">Completed Consultations</span>
                    </div>
                </div>
            </div>

            <!-- MISSION & VALUES -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                <div class="bg-white border border-[#E7E3D3] rounded-2xl p-8 shadow-card">
                    <div class="w-12 h-12 rounded-xl bg-[#EEF7C4] text-[#3B4A12] flex items-center justify-center mb-6">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Clinical Excellence</h3>
                    <p class="text-sm text-[#62655A] leading-relaxed">Our medical team undergoes continuous peer review and training, ensuring evidence-based clinical standards across all specialties.</p>
                </div>

                <div class="bg-white border border-[#E7E3D3] rounded-2xl p-8 shadow-card">
                    <div class="w-12 h-12 rounded-xl bg-[#EEF7C4] text-[#3B4A12] flex items-center justify-center mb-6">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Zero Waiting Friction</h3>
                    <p class="text-sm text-[#62655A] leading-relaxed">Real-time scheduling replaces phone tag and waiting lines. Book, reschedule, or cancel instantly from any device.</p>
                </div>

                <div class="bg-white border border-[#E7E3D3] rounded-2xl p-8 shadow-card">
                    <div class="w-12 h-12 rounded-xl bg-[#EEF7C4] text-[#3B4A12] flex items-center justify-center mb-6">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Integrated Records</h3>
                    <p class="text-sm text-[#62655A] leading-relaxed">Prescriptions, diagnostic lab results, and consultation notes are securely consolidated under one central patient account.</p>
                </div>
            </div>

            <!-- CLOSING BANNER -->
            <div class="closing bg-[#16301F] rounded-3xl p-10 md:p-14 flex items-center justify-between gap-8 flex-wrap text-white relative overflow-hidden mb-16">
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight max-w-[24ch]">Ready to schedule a visit?</h2>
                    <p class="text-white/65 text-sm mt-2">Browse our departments or search for a specific doctor by name.</p>
                </div>
                <Link href="/doctors" class="btn bg-[#DDF15C] text-[#3B4A12] hover:bg-[#ecf99c] font-semibold px-6 py-3.5 rounded-full">Explore Doctors</Link>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }
.pill { display: inline-flex; align-items: center; gap: 6px; background: #DDF15C; color: #3B4A12; font-size: 13px; font-weight: 600; padding: 7px 16px 7px 12px; border-radius: 999px; }
.pill svg { width: 14px; height: 14px; }
</style>
