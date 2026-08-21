<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

interface DepartmentItem {
    id: number | string
    slug: string
    name: string
    description: string
    doctors_count: number
    status: 'active' | 'inactive'
}

const props = withDefaults(
    defineProps<{
        departments?: DepartmentItem[]
    }>(),
    {
        departments: () => [
            {
                id: 1,
                slug: 'cardiology',
                name: 'Cardiology',
                description: 'Comprehensive cardiovascular diagnostics, ECG, hypertension care, and preventative medicine.',
                doctors_count: 8,
                status: 'active',
            },
            {
                id: 2,
                slug: 'neurology',
                name: 'Neurology',
                description: 'Specialized care for neurological disorders, stroke prevention, EEG testing, and headache therapy.',
                doctors_count: 5,
                status: 'active',
            },
            {
                id: 3,
                slug: 'pediatrics',
                name: 'Pediatrics',
                description: 'Dedicated child health, infant vaccination programs, developmental growth monitoring, and pediatric care.',
                doctors_count: 6,
                status: 'active',
            },
            {
                id: 4,
                slug: 'orthopedics',
                name: 'Orthopedics',
                description: 'Bone and joint surgery, physical rehabilitation, sports medicine, and spinal trauma treatment.',
                doctors_count: 4,
                status: 'active',
            },
            {
                id: 5,
                slug: 'general-medicine',
                name: 'General Internal Medicine',
                description: 'Primary adult healthcare, chronic disease management, routine checkups, and diagnostic screenings.',
                doctors_count: 5,
                status: 'active',
            },
        ],
    }
)
</script>

<template>
    <Head title="Hospital Departments — Admin Portal" />

    <!-- TOP RIGHT ACTION -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">Hospital Departments</h1>
            <p class="text-xs text-[var(--ink-muted)]">Manage medical specialty units, assigned physician counts, and locations</p>
        </div>
        <Link href="/admin/departments/create" class="btn-primary-add">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Add New Department
        </Link>
    </div>

    <!-- DEPARTMENTS GRID -->
    <div class="departments-grid">
        <div v-for="dept in props.departments" :key="dept.id" class="dept-card">
            <div class="dept-card-top">
                <div class="dept-icon-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                </div>
                <span class="status-tag">Active</span>
            </div>

            <div class="dept-info">
                <h3>{{ dept.name }}</h3>
                <p>{{ dept.description }}</p>
            </div>

            <div class="dept-meta-row">
                <span>Assigned Physicians:</span>
                <b>{{ dept.doctors_count }} Doctors</b>
            </div>

            <div class="dept-actions-row">
                <Link :href="`/admin/departments/${dept.slug}`" class="btn-card-action">View & Edit →</Link>
            </div>
        </div>
    </div>
</template>

<style>
.btn-primary-add { display: inline-flex; align-items: center; gap: 8px; height: 42px; padding: 0 20px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 13.5px; font-weight: 700; box-shadow: var(--shadow-sm); transition: background-color 150ms ease; text-decoration: none; }
.btn-primary-add:hover { background: var(--forest-2); }

.departments-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
@media (max-width: 1100px) { .departments-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 680px) { .departments-grid { grid-template-columns: 1fr; } }

.dept-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; gap: 16px; position: relative; transition: transform 150ms ease, box-shadow 150ms ease; }
.dept-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lift); }

.dept-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
.dept-icon-box { width: 48px; height: 48px; border-radius: var(--radius-md); background: var(--cream); color: var(--forest); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.dept-icon-box svg { width: 22px; height: 22px; }

.status-tag { font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 999px; font-family: var(--font-mono); background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

.dept-info h3 { font-size: 17px; font-weight: 800; color: var(--forest); margin-bottom: 4px; }
.dept-info p { font-size: 13px; color: var(--ink-muted); line-height: 1.45; }

.dept-meta-row { display: flex; justify-content: space-between; align-items: center; padding-top: 14px; border-top: 1px dashed var(--line); font-size: 12.5px; color: var(--ink-muted); }
.dept-meta-row b { font-family: var(--font-mono); color: var(--forest); font-size: 13.5px; }

.dept-actions-row { display: flex; gap: 8px; padding-top: 4px; }
.btn-card-action { flex: 1; height: 36px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--cream); font-size: 12.5px; font-weight: 600; color: var(--forest); display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: all 150ms ease; text-decoration: none; }
.btn-card-action:hover { border-color: var(--forest); background: var(--forest); color: #fff; }
</style>
