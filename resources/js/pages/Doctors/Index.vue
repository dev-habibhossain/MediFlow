<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

interface Department {
    id: number
    name: string
    slug: string
}

interface Doctor {
    id: number
    specialization: string
    qualification: string
    consultation_fee: string | number
    license_number: string
    user: {
        id: number
        name: string
        avatar_path?: string
    }
    department: Department
}

interface PaginatedDoctors {
    data: Doctor[]
    current_page: number
    last_page: number
    links: { url: string | null; label: string; active: boolean }[]
}

const props = defineProps<{
    doctors: PaginatedDoctors
    departments: Department[]
    filters: {
        search: string
        department: string
    }
}>()

const search = ref(props.filters.search)
const selectedDept = ref(props.filters.department)

function applyFilters() {
    router.get('/doctors', {
        search: search.value || undefined,
        department: selectedDept.value || undefined,
    }, { preserveState: true, replace: true })
}

function setDepartment(slug: string) {
    selectedDept.value = slug
    applyFilters()
}
</script>

<template>
    <PublicLayout>
        <div class="wrap py-12">
            <!-- HERO -->
            <section class="page-hero mb-8">
                <span class="pill mb-4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Medical Specialists</span>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">Find Your Specialist</h1>
                <p class="text-lg text-[#62655A] max-w-2xl">Browse our directory of board-certified doctors, check consultation fees, and view available clinic schedules.</p>
            </section>

            <!-- SEARCH & FILTER -->
            <div class="bg-white border border-[#E7E3D3] rounded-2xl p-4 mb-10 shadow-card flex flex-wrap gap-4 items-center justify-between">
                <div class="relative flex-1 min-w-[280px]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#62655A] pointer-events-none"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                    <input v-model="search" type="text" placeholder="Search doctor by name or specialization..." @input="applyFilters" class="w-full h-12 rounded-full border border-[#E7E3D3] bg-[#F8F6EF] pl-11 pr-4 text-sm focus:border-[#16301F] focus:bg-white outline-none transition-all">
                </div>

                <div class="flex flex-wrap gap-2">
                    <button :class="['px-4 py-2 rounded-full text-xs font-semibold transition-all', selectedDept === '' ? 'bg-[#16301F] text-white' : 'bg-[#F0EEE3] text-[#62655A] hover:text-[#16180F]']" @click="setDepartment('')">
                        All Departments
                    </button>
                    <button v-for="dept in departments" :key="dept.id" :class="['px-4 py-2 rounded-full text-xs font-semibold transition-all', selectedDept === dept.slug ? 'bg-[#16301F] text-white' : 'bg-[#F0EEE3] text-[#62655A] hover:text-[#16180F]']" @click="setDepartment(dept.slug)">
                        {{ dept.name }}
                    </button>
                </div>
            </div>

            <!-- DOCTORS GRID -->
            <div v-if="doctors.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
                <div v-for="doc in doctors.data" :key="doc.id" class="bg-white border border-[#E7E3D3] rounded-2xl p-6 shadow-card hover:shadow-lift transition-all duration-150 flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#16301F] to-[#4c7a5c] text-white flex items-center justify-center font-bold text-xl mb-4">
                            {{ doc.user.name.charAt(0) }}
                        </div>
                        <span class="inline-block text-[11px] font-semibold bg-[#EEF7C4] text-[#3B4A12] px-2.5 py-1 rounded-full mb-2">{{ doc.department.name }}</span>
                        <h3 class="font-bold text-base mb-1">{{ doc.user.name }}</h3>
                        <p class="text-xs text-[#62655A] mb-3">{{ doc.specialization }}</p>
                        <p class="text-xs text-[#62655A] mb-4">Fee: <b class="text-[#16301F]">${{ doc.consultation_fee }}</b></p>
                    </div>
                    <Link :href="`/doctors/${doc.license_number}`" class="w-full text-center bg-[#16301F] text-white hover:bg-[#1E4029] text-xs font-semibold py-3 rounded-full transition-colors">
                        View Schedule & Profile
                    </Link>
                </div>
            </div>

            <!-- NO RESULTS -->
            <div v-else class="text-center py-16 bg-white border border-[#E7E3D3] rounded-2xl mb-12">
                <h4 class="text-lg font-bold mb-1">No doctors found</h4>
                <p class="text-sm text-[#62655A]">Try resetting your filter or searching with another term.</p>
            </div>

            <!-- PAGINATION -->
            <div v-if="doctors.links.length > 3" class="flex justify-center gap-2 mb-16">
                <template v-for="(link, i) in doctors.links" :key="i">
                    <Link v-if="link.url" :href="link.url" :class="['px-4 py-2 rounded-full text-xs font-semibold', link.active ? 'bg-[#16301F] text-white' : 'bg-white border border-[#E7E3D3] text-[#62655A]']" v-html="link.label" />
                </template>
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
