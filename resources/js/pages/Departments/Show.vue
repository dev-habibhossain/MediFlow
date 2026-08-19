<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Link } from '@inertiajs/vue3'

interface Doctor {
    id: number
    specialization: string
    qualification: string
    consultation_fee: string | number
    license_number: string
    user: {
        id: number
        name: string
        email: string
        avatar_path?: string
    }
}

interface Department {
    id: number
    name: string
    slug: string
    description: string
    doctors: Doctor[]
}

defineProps<{
    department: Department
}>()
</script>

<template>
    <PublicLayout :title="`${department.name} Department — MediFlow`">
        <main class="py-8">
            <div class="wrap">
                <div class="mb-6">
                    <Link href="/departments" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#62655A] hover:text-[#16301F]">
                        ← Back to <b>all departments</b>
                    </Link>
                </div>

                <!-- DEPARTMENT HEADER -->
                <div class="bg-white border border-[#E7E3D3] rounded-3xl p-8 md:p-12 shadow-card mb-12 relative overflow-hidden">
                    <span class="pill mb-4">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                        Department Overview
                    </span>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-4 text-[#16301F]"><b>{{ department.name }}</b> Division</h1>
                    <p class="text-base text-[#62655A] leading-relaxed max-w-3xl mb-6">{{ department.description }}</p>

                    <div class="flex items-center gap-4">
                        <Link :href="`/doctors?department=${department.slug}`" class="btn btn-primary btn-sm">
                            Filter All <b>{{ department.name }} Doctors</b> →
                        </Link>
                    </div>
                </div>

                <!-- SPECIALISTS LIST -->
                <div class="mb-16">
                    <div class="section-head mb-6">
                        <h2>Assigned <b>Specialists</b> ({{ department.doctors.length }})</h2>
                        <p>Board-certified physicians available in the {{ department.name }} department.</p>
                    </div>

                    <div v-if="department.doctors.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="doc in department.doctors" :key="doc.id" class="doc-card bg-white border border-[#E7E3D3] rounded-2xl p-6 shadow-card hover:shadow-lift transition-all duration-150 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#16301F] to-[#4c7a5c] text-white flex items-center justify-center text-lg font-bold shrink-0">
                                        {{ doc.user.name.charAt(0) }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-base text-[#16301F]"><b>{{ doc.user.name }}</b></h3>
                                        <span class="text-xs text-[#62655A] block">{{ doc.specialization }}</span>
                                    </div>
                                </div>
                                <p class="text-xs text-[#62655A] mb-3">Qualification: <b>{{ doc.qualification || 'Board Certified' }}</b></p>
                                <div class="text-sm font-semibold text-[#16301F] mb-4">Fee: <b>${{ doc.consultation_fee }}</b></div>
                            </div>
                            <Link :href="`/doctors/${doc.license_number}`" class="w-full text-center bg-[#16301F] text-white hover:bg-[#1E4029] text-xs font-semibold py-3 rounded-full transition-colors block">
                                View Profile & Schedule
                            </Link>
                        </div>
                    </div>

                    <div v-else class="text-center py-12 bg-white border border-[#E7E3D3] rounded-2xl">
                        <p class="text-sm text-[#62655A]">No doctors currently listed under this department.</p>
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

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 46px; padding: 0 22px; border-radius: 999px; font-size: 14px; font-weight: 600; text-decoration: none; border: 0; cursor: pointer; }
.btn-primary { background: #16301F; color: #fff; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.btn-primary:hover { background: #1E4029; }
</style>
