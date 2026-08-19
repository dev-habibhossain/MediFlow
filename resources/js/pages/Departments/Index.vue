<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface Department {
    id: number
    name: string
    slug: string
    description: string
    active_doctors_count: number
}

const props = defineProps<{
    departments: Department[]
}>()

const searchQuery = ref('')
const selectedCategory = ref('all')

const filteredDepartments = computed(() => {
    return props.departments.filter(dept => {
        const matchesSearch = searchQuery.value === '' ||
            dept.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            dept.description.toLowerCase().includes(searchQuery.value.toLowerCase())
        return matchesSearch
    })
})
</script>

<template>
    <PublicLayout>
        <div class="wrap py-12">
            <!-- PAGE HERO -->
            <section class="page-hero mb-8">
                <span class="pill mb-4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Specialized Care</span>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">Our Medical Departments</h1>
                <p class="text-lg text-[#62655A] max-w-2xl">Explore our clinical divisions. Select a department to view dedicated physicians and book an appointment.</p>
            </section>

            <!-- SEARCH AND FILTER CONTROL -->
            <div class="filter-bar bg-white border border-[#E7E3D3] rounded-2xl p-4 mb-10 shadow-card flex flex-wrap gap-4 items-center justify-between">
                <div class="search-input-wrap relative flex-1 min-w-[280px]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#62655A] pointer-events-none"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                    <input v-model="searchQuery" type="text" placeholder="Search departments or medical conditions..." class="w-full h-12 rounded-full border border-[#E7E3D3] bg-[#F8F6EF] pl-11 pr-4 text-sm focus:border-[#16301F] focus:bg-white outline-none transition-all">
                </div>
            </div>

            <!-- DEPARTMENTS GRID -->
            <div v-if="filteredDepartments.length > 0" class="dept-page-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-20">
                <div v-for="dept in filteredDepartments" :key="dept.id" class="dept-card-full bg-white border border-[#E7E3D3] rounded-2xl p-7 shadow-card hover:shadow-lift transition-all duration-150 hover:-translate-y-1 flex flex-col justify-between">
                    <div>
                        <div class="dept-header flex items-start justify-between mb-5">
                            <div class="dept-icon w-13 h-13 rounded-2xl bg-[#EEF7C4] text-[#3B4A12] flex items-center justify-center p-3">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.8 1-1a5.5 5.5 0 0 0 0-7.6z"/></svg>
                            </div>
                            <span class="status-indicator inline-flex items-center gap-1.5 text-xs font-semibold text-[#3B4A12] bg-[#EEF7C4] px-3 py-1 rounded-full">
                                <span class="status-dot w-2 h-2 rounded-full bg-[#22C55E]"></span>
                                {{ dept.active_doctors_count }} Active Specialists
                            </span>
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ dept.name }}</h3>
                        <p class="text-sm text-[#62655A] leading-relaxed mb-6">{{ dept.description }}</p>
                    </div>
                    <div class="dept-footer border-t border-[#E7E3D3] pt-4 flex items-center justify-between">
                        <Link :href="`/departments/${dept.slug}`" class="info-link text-sm font-semibold inline-flex items-center gap-1 text-[#16301F] hover:underline">
                            View Department & Doctors
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M9 18l6-6-6-6"/></svg>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- NO RESULTS -->
            <div v-else class="text-center py-16 bg-white border border-[#E7E3D3] rounded-2xl mb-20">
                <h4 class="text-lg font-bold mb-1">No matching departments found</h4>
                <p class="text-sm text-[#62655A]">Try searching with a different keyword.</p>
            </div>

            <!-- CLOSING BANNER -->
            <div class="closing bg-[#16301F] rounded-3xl p-10 md:p-14 flex items-center justify-between gap-8 flex-wrap text-white relative overflow-hidden mb-16">
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight max-w-[24ch]">Can't find the specialty you're looking for?</h2>
                    <p class="text-white/65 text-sm mt-2">Contact our care assistance desk or book a general consultation for guidance.</p>
                </div>
                <Link href="/contact" class="btn bg-[#DDF15C] text-[#3B4A12] hover:bg-[#ecf99c] font-semibold px-6 py-3.5 rounded-full">Contact Care Desk</Link>
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
