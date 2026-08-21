<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    code: '',
    location: '',
    status: 'active',
    description: '',
})

function submitForm() {
    form.post('/admin/departments', {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Add New Department — Admin Portal" />

    <!-- BACK BUTTON -->
    <div class="mb-6">
        <Link href="/admin/departments" class="back-btn">← Back to Departments List</Link>
    </div>

    <!-- HEADER BANNER CARD -->
    <div class="page-header-card mb-6">
        <div>
            <span class="ref-badge">Department Setup Form</span>
            <h1>Create New Medical Department</h1>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="form-card">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 21h18M3 7v14M21 7v14M6 3h12a2 2 0 0 1 2 2v2H4V5a2 2 0 0 1 2-2z" />
            </svg>
            Department Configuration Details
        </div>

        <form @submit.prevent="submitForm">
            <div class="form-grid">
                <div class="form-group">
                    <label for="deptName">Department Name <span>*</span></label>
                    <input id="deptName" v-model="form.name" type="text" class="form-control" required placeholder="e.g. Dermatology & Skin Care" />
                </div>

                <div class="form-group">
                    <label for="deptCode">Department Code <span>*</span></label>
                    <input id="deptCode" v-model="form.code" type="text" class="form-control font-mono" required placeholder="e.g. DERM" />
                </div>

                <div class="form-group">
                    <label for="facilityWing">Facility Wing & Location <span>*</span></label>
                    <input id="facilityWing" v-model="form.location" type="text" class="form-control" required placeholder="e.g. East Wing, Level 3" />
                </div>

                <div class="form-group">
                    <label for="status">Initial Status <span>*</span></label>
                    <select id="status" v-model="form.status" class="form-control" required>
                        <option value="active">Active & Accepting Bookings</option>
                        <option value="maintenance">Under Maintenance / Setup</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="description">Department Description & Scope <span>*</span></label>
                    <textarea id="description" v-model="form.description" class="form-control" required placeholder="Describe specialized medical services, diagnostic capabilities, and patient care scope..."></textarea>
                </div>
            </div>

            <!-- BUTTON ROW -->
            <div class="form-actions">
                <Link href="/admin/departments" class="btn btn-outline">Cancel</Link>
                <button type="submit" :disabled="form.processing" class="btn btn-primary">
                    {{ form.processing ? 'Creating...' : 'Create Department Unit' }}
                </button>
            </div>
        </form>
    </div>
</template>

<style>
.back-btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 6px 14px; border-radius: 999px; transition: all 150ms ease; text-decoration: none; }
.back-btn:hover { background: var(--card); border-color: var(--forest); }

.page-header-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px 32px; box-shadow: var(--shadow-card); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; }
.ref-badge { font-family: var(--font-mono); font-size: 12.5px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 4px 10px; border-radius: var(--radius-sm); display: inline-block; margin-bottom: 4px; }
.page-header-card h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; }

.form-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-card); }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }
.card-title svg { width: 18px; height: 18px; color: var(--forest); }

.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 28px; }
@media (max-width: 680px) { .form-grid { grid-template-columns: 1fr; } }
.full-width { grid-column: 1 / -1; }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-group label span { color: #DC2626; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); transition: border-color 150ms ease, background-color 150ms ease; outline: none; }
.form-control:focus { border-color: var(--forest); background: var(--card); }
textarea.form-control { height: auto; min-height: 110px; padding: 12px 16px; resize: vertical; }

.form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid var(--line); }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 28px; border-radius: 999px; font-size: 14.5px; font-weight: 600; transition: all 150ms ease; cursor: pointer; text-decoration: none; border: 0; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }
</style>
