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
    <PublicLayout>
        <div class="wrap py-12">
            <div class="mb-6">
                <Link href="/departments" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#62655A] hover:text-[#16301F]">
                    ← Back to all departments
                </Link>
            </div>

            <!-- DEPARTMENT HEADER -->
            <div class="bg-white border border-[#E7E3D3] rounded-3xl p-8 md:p-12 shadow-card mb-12">
                <span class="pill mb-4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Department Details</span>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-4">{{ department.name }}</h1>
                <p class="text-base text-[#62655A] leading-relaxed max-w-3xl">{{ department.description }}</p>
            </div>

            <!-- SPECIALISTS LIST -->
            <div>
                <h2 class="text-2xl font-bold mb-6">Assigned Specialists ({{ department.doctors.length }})</h2>

                <div v-if="department.doctors.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                    <div v-for="doc in department.doctors" :key="doc.id" class="bg-white border border-[#E7E3D3] rounded-2xl p-6 shadow-card hover:shadow-lift transition-all duration-150 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#16301F] to-[#4c7a5c] text-white flex items-center justify-center text-lg font-bold">
                                    {{ doc.user.name.charAt(0) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-base">{{ doc.user.name }}</h3>
                                    <span class="text-xs text-[#62655A] block">{{ doc.specialization }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-[#62655A] mb-3">Qualification: {{ doc.qualification }}</p>
                            <div class="text-sm font-semibold text-[#16301F] mb-4">Consultation Fee: ${{ doc.consultation_fee }}</div>
                        </div>
                        <Link :href="`/doctors/${doc.license_number}`" class="w-full text-center bg-[#16301F] text-white hover:bg-[#1E4029] text-xs font-semibold py-3 rounded-full transition-colors">
                            View Profile & Schedule
                        </Link>
                    </div>
                </div>

                <div v-else class="text-center py-12 bg-white border border-[#E7E3D3] rounded-2xl mb-16">
                    <p class="text-sm text-[#62655A]">No doctors currently listed under this department.</p>
                </div>
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
