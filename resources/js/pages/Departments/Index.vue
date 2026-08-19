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

const filteredDepartments = computed(() => {
    return props.departments.filter(dept => {
        const query = searchQuery.value.toLowerCase().trim()
        if (!query) return true
        return (
            dept.name.toLowerCase().includes(query) ||
            dept.description.toLowerCase().includes(query)
        )
    })
})

function getDepartmentTags(slug: string): string[] {
    const tagsMap: Record<string, string[]> = {
        cardiology: ['ECG & Echo', 'Angiography', 'Heart Failure Care'],
        neurology: ['Brain MRI', 'Stroke Care', 'Epilepsy & EEG'],
        pediatrics: ['Neonatal Care', 'Vaccinations', 'Growth Monitoring'],
        orthopedics: ['Joint Replacement', 'Fracture Care', 'Sports Injury'],
        ophthalmology: ['Cataract Surgery', 'Laser Vision', 'Glaucoma Care'],
        dermatology: ['Skin Pathology', 'Acne Treatment', 'Laser Therapy'],
        ent: ['Hearing Tests', 'Sinus Surgery', 'Throat Care'],
        'general-medicine': ['Routine Checkups', 'Diabetes Care', 'Hypertension'],
        'emergency-care': ['Trauma Care', '24/7 ICU Support', 'Critical Care'],
        gastroenterology: ['Endoscopy', 'Liver Health', 'Digestive Care'],
        oncology: ['Chemotherapy', 'Tumor Board', 'Cancer Care'],
    }
    return tagsMap[slug] || ['Specialized Consults', 'Advanced Diagnostics', 'Inpatient Support']
}
</script>

<template>
    <PublicLayout title="Medical Departments — MediFlow">
        <main class="py-8">
            <div class="wrap">
                <!-- PAGE HERO -->
                <section class="about-hero">
                    <span class="pill mb-4">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                        Specialized Clinical Divisions
                    </span>
                    <h1>Our <b>Medical Departments</b> & <b>Specialists</b></h1>
                    <p class="lead">Select a department below to view active specialists, check real-time availability, and book your consultation directly.</p>
                </section>

                <!-- SEARCH AND FILTER BAR -->
                <div class="search-box">
                    <div class="search-input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search by department name, e.g. Cardiology, Neurology, Pediatrics..."
                            aria-label="Search departments"
                        />
                        <button v-if="searchQuery" @click="searchQuery = ''" class="clear-btn" type="button">Clear</button>
                    </div>
                </div>

                <!-- DEPARTMENTS GRID -->
                <div v-if="filteredDepartments.length > 0" class="dept-grid">
                    <div
                        v-for="dept in filteredDepartments"
                        :key="dept.id"
                        class="dept-card"
                    >
                        <!-- Card Top Header -->
                        <div class="dept-card-head">
                            <div class="dept-icon-wrapper">
                                <svg v-if="dept.slug === 'cardiology'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                <svg v-else-if="dept.slug === 'neurology'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-2.04Z"/><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-2.04Z"/></svg>
                                <svg v-else-if="dept.slug === 'pediatrics'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="M12 13v8M9 18h6"/></svg>
                                <svg v-else-if="dept.slug === 'orthopedics'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-4V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v6H2v4h4v6a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-6h4z"/></svg>
                                <svg v-else-if="dept.slug === 'ophthalmology'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg v-else-if="dept.slug === 'dermatology'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4.8 2.3A.3.3 0 0 0 4.5 2.6V5a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2.6a.3.3 0 0 0-.3-.3h-3.4Z"/><path d="M6 6v4a6 6 0 0 0 12 0V6"/><path d="M12 16v5M8 21h8"/></svg>
                            </div>
                            <span class="doctor-badge">
                                <span class="dot"></span>
                                <b>{{ dept.active_doctors_count }}</b> Specialists
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="dept-card-body">
                            <h3><b>{{ dept.name }}</b> Department</h3>
                            <p>{{ dept.description }}</p>

                            <!-- Quick Sub-Services Tags -->
                            <div class="dept-tags">
                                <span v-for="(tag, idx) in getDepartmentTags(dept.slug)" :key="idx" class="tag">
                                    {{ tag }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer & Actions -->
                        <div class="dept-card-actions">
                            <Link :href="`/doctors?department=${dept.slug}`" class="btn btn-primary btn-sm w-full font-semibold">
                                View Doctors in <b>{{ dept.name }}</b> →
                            </Link>
                            <Link :href="`/departments/${dept.slug}`" class="sub-link">
                                Department Overview & Information
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- NO RESULTS STATE -->
                <div v-else class="no-results">
                    <h4>No matching <b>departments</b> found</h4>
                    <p>Try searching for another medical specialty or keyword.</p>
                </div>

                <!-- CLOSING BANNER -->
                <div class="closing mt-16">
                    <div>
                        <h2>Need guidance choosing the <b>right department</b>?</h2>
                        <p>Our patient assistance team can help match your symptoms with the appropriate specialist.</p>
                    </div>
                    <Link href="/contact" class="btn btn-primary">Contact Care Desk</Link>
                </div>
            </div>
        </main>
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
.btn-sm { height: 46px; padding: 0 22px; font-size: 14px; }

.about-hero { padding: 40px 0 24px; }
.about-hero h1 { font-size: clamp(2.2rem, 1.6rem + 2vw, 3.2rem); font-weight: 800; letter-spacing: -0.02em; margin-bottom: 14px; line-height: 1.15; color: #16301F; }
.about-hero h1 b { color: #16301F; border-bottom: 3px solid #DDF15C; }
.about-hero p.lead { font-size: 17px; color: #62655A; max-width: 62ch; line-height: 1.6; }

.search-box { margin-bottom: 36px; }
.search-input-wrap { position: relative; width: 100%; max-width: 640px; }
.search-input-wrap input { width: 100%; height: 52px; border-radius: 999px; border: 1px solid #E7E3D3; background: #FFFFFF; padding: 0 48px 0 48px; font-size: 14.5px; outline: none; transition: border-color 150ms ease, box-shadow 150ms ease; }
.search-input-wrap input:focus { border-color: #16301F; box-shadow: 0 0 0 3px rgba(22,48,31,0.1); }
.search-icon { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #62655A; pointer-events: none; }
.clear-btn { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); font-size: 12px; font-weight: 600; color: #62655A; background: #EEF7C4; padding: 4px 10px; border-radius: 999px; cursor: pointer; border: 0; }

.dept-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
@media (max-width: 1024px) { .dept-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .dept-grid { grid-template-columns: 1fr; } }

.dept-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 28px; padding: 30px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); transition: transform 200ms ease, box-shadow 200ms ease, border-color 200ms ease; display: flex; flex-direction: column; justify-content: space-between; position: relative; }
.dept-card:hover { transform: translateY(-4px); box-shadow: 0 4px 10px rgba(22,24,15,0.06), 0 16px 36px rgba(22,24,15,0.10); border-color: #C7E86B; }

.dept-card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.dept-icon-wrapper { width: 52px; height: 52px; border-radius: 16px; background: #EEF7C4; color: #3B4A12; display: flex; align-items: center; justify-content: center; transition: background-color 200ms ease; }
.dept-card:hover .dept-icon-wrapper { background: #DDF15C; }
.dept-icon-wrapper svg { width: 26px; height: 26px; }

.doctor-badge { display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 600; color: #3B4A12; background: #EEF7C4; padding: 6px 12px; border-radius: 999px; }
.doctor-badge .dot { width: 7px; height: 7px; border-radius: 50%; background: #22C55E; }

.dept-card-body h3 { font-size: 20px; font-weight: 800; color: #16301F; margin-bottom: 8px; line-height: 1.25; }
.dept-card-body p { font-size: 14px; color: #62655A; line-height: 1.6; margin-bottom: 18px; min-height: 44px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

.dept-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 24px; }
.dept-tags .tag { font-size: 12px; font-weight: 500; color: #16180F; background: #F8F6EF; border: 1px solid #E7E3D3; padding: 4px 10px; border-radius: 999px; }

.dept-card-actions { border-top: 1px solid #E7E3D3; padding-top: 20px; display: flex; flex-direction: column; gap: 10px; text-align: center; }
.sub-link { font-size: 12.5px; font-weight: 600; color: #62655A; text-decoration: none; transition: color 150ms ease; }
.sub-link:hover { color: #16301F; text-decoration: underline; }

.no-results { text-align: center; padding: 64px 20px; background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; color: #62655A; }
.no-results h4 { font-size: 18px; color: #16301F; margin-bottom: 6px; }

.closing { background: #16301F; border-radius: 32px; padding: 52px 40px; display: flex; align-items: center; justify-content: space-between; gap: 28px; flex-wrap: wrap; color: #fff; position: relative; overflow: hidden; }
.closing::before { content: ""; position: absolute; top: -80px; right: -60px; width: 240px; height: 240px; border-radius: 50%; background: #DDF15C; opacity: 0.15; filter: blur(10px); }
.closing h2 { font-size: 26px; font-weight: 800; letter-spacing: -0.015em; max-width: 24ch; position: relative; }
.closing p { color: rgba(255,255,255,0.65); font-size: 14px; margin-top: 6px; position: relative; }
.closing .btn-primary { background: #DDF15C; color: #3B4A12; position: relative; border: 0; }
.closing .btn-primary:hover { background: #ecf99c; }
</style>
