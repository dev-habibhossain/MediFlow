<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'

interface DoctorItem {
    id: number
    name: string
    title: string
    license_number: string
    experience_years: number
    avatar?: string
}

interface DepartmentData {
    id: number
    slug: string
    name: string
    code?: string
    location?: string
    status?: string
    description?: string
    is_active?: boolean
    doctors?: DoctorItem[]
}

const props = defineProps<{
    department: DepartmentData
}>()

const form = useForm({
    name: props.department?.name ?? '',
    code: props.department?.code ?? props.department?.name?.substring(0, 4).toUpperCase() ?? 'DEPT',
    location: props.department?.location ?? 'Main Hospital Wing, Level 2',
    status: props.department?.status ?? (props.department?.is_active ? 'active' : 'maintenance'),
    description: props.department?.description ?? '',
})

function updateDepartment() {
    form.put(`/admin/departments/${props.department.slug}`, {
        preserveScroll: true,
    })
}

function handleDeactivate() {
    if (confirm('Are you sure you want to deactivate or remove this department unit?')) {
        const deleteForm = useForm({})
        deleteForm.delete(`/admin/departments/${props.department.slug}`)
    }
}
</script>

<template>
    <Head :title="`${props.department?.name || 'Department'} Details — Admin Portal`" />

    <!-- BACK BUTTON -->
    <div class="mb-6">
        <Link href="/admin/departments" class="back-btn">← Back to Departments List</Link>
    </div>

    <!-- HEADER BANNER -->
    <div class="dept-header-card mb-6">
        <div class="dept-info-left">
            <div class="dept-icon-lg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                </svg>
            </div>
            <div class="dept-meta-lg">
                <h1>{{ props.department?.name }} Department</h1>
                <p>Code: <strong>{{ form.code }}</strong> · Location: <strong>{{ form.location }}</strong></p>
                <span class="status-badge" :class="form.status === 'active' ? 'status-active' : 'status-maint'">
                    ● {{ form.status === 'active' ? 'Active Unit' : 'Under Maintenance' }}
                </span>
            </div>
        </div>
    </div>

    <!-- MAIN SPLIT GRID -->
    <div class="detail-grid">
        <!-- LEFT COLUMN -->
        <div class="main-col">
            <!-- EDIT FORM -->
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Edit Department Details
                </div>

                <form @submit.prevent="updateDepartment">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="deptName">Department Name</label>
                            <input id="deptName" v-model="form.name" type="text" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="deptCode">Department Code</label>
                            <input id="deptCode" v-model="form.code" type="text" class="form-control font-mono" required />
                        </div>

                        <div class="form-group">
                            <label for="facilityWing">Facility Wing & Location</label>
                            <input id="facilityWing" v-model="form.location" type="text" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="deptStatus">Department Status</label>
                            <select id="deptStatus" v-model="form.status" class="form-control" required>
                                <option value="active">Active & Accepting Bookings</option>
                                <option value="maintenance">Under Maintenance</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label for="description">Scope & Description</label>
                            <textarea id="description" v-model="form.description" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary" style="width: auto; padding: 0 28px;">
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- ASSIGNED DOCTORS ROSTER TABLE -->
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    Assigned Physicians Roster ({{ props.department?.doctors?.length || 0 }} Active)
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Physician</th>
                                <th>License No</th>
                                <th>Experience</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="doc in (props.department?.doctors || [])" :key="doc.id">
                                <td>
                                    <div class="doctor-cell">
                                        <img v-if="doc.avatar" :src="doc.avatar" :alt="doc.name" class="doctor-avatar" />
                                        <div v-else class="doctor-avatar-fallback">{{ doc.name.charAt(0) }}</div>
                                        <div class="doctor-meta">
                                            <b>{{ doc.name }}</b>
                                            <span>{{ doc.title }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-family: var(--font-mono);">{{ doc.license_number }}</td>
                                <td>{{ doc.experience_years }} Yrs Exp</td>
                                <td>
                                    <button class="btn-remove-sm" title="Reassign Doctor">✕</button>
                                </td>
                            </tr>

                            <tr v-if="!props.department?.doctors || props.department.doctors.length === 0">
                                <td colspan="4" style="text-align: center; padding: 24px; color: var(--ink-muted);">
                                    No physicians assigned to this department yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="sidebar-col">
            <div class="action-card">
                <h4>Department Management</h4>
                <p>Deactivate this department unit or archive historical records.</p>

                <button type="button" class="btn btn-outline-danger" @click="handleDeactivate">
                    Deactivate / Delete Unit
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.back-btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 6px 14px; border-radius: 999px; transition: all 150ms ease; text-decoration: none; }
.back-btn:hover { background: var(--card); border-color: var(--forest); }

.dept-header-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px 32px; box-shadow: var(--shadow-card); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; }
.dept-info-left { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
.dept-icon-lg { width: 72px; height: 72px; border-radius: var(--radius-lg); background: var(--cream); color: var(--forest); display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--line); }
.dept-icon-lg svg { width: 32px; height: 32px; }
.dept-meta-lg h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin-bottom: 2px; }
.dept-meta-lg p { font-size: 13.5px; color: var(--ink-muted); font-weight: 500; }

.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 999px; font-size: 12.5px; font-weight: 700; margin-top: 8px; }
.status-active { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-maint { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }

.detail-grid { display: grid; grid-template-columns: 1fr 360px; gap: 28px; align-items: start; }
@media (max-width: 1024px) { .detail-grid { grid-template-columns: 1fr; } }

.main-col { display: flex; flex-direction: column; gap: 24px; }
.sidebar-col { display: flex; flex-direction: column; gap: 24px; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px; box-shadow: var(--shadow-card); }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }
.card-title svg { width: 18px; height: 18px; color: var(--forest); }

.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px; }
@media (max-width: 680px) { .form-grid { grid-template-columns: 1fr; } }
.full-width { grid-column: 1 / -1; }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); transition: border-color 150ms ease; outline: none; }
.form-control:focus { border-color: var(--forest); background: var(--card); }
textarea.form-control { height: auto; min-height: 90px; padding: 12px 16px; resize: vertical; }

.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 12px 16px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 14px 16px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.doctor-cell { display: flex; align-items: center; gap: 10px; }
.doctor-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; background: var(--cream-alt); flex-shrink: 0; }
.doctor-avatar-fallback { width: 36px; height: 36px; border-radius: 50%; background: var(--forest); color: var(--lime); font-weight: 800; font-size: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.doctor-meta b { display: block; font-size: 13.5px; font-weight: 700; color: var(--forest); }
.doctor-meta span { display: block; font-size: 11.5px; color: var(--ink-muted); font-family: var(--font-mono); }

.btn-remove-sm { width: 32px; height: 32px; border-radius: var(--radius-sm); border: 1px solid #FCA5A5; background: #FEE2E2; color: #DC2626; display: inline-flex; align-items: center; justify-content: center; transition: all 150ms ease; cursor: pointer; }
.btn-remove-sm:hover { background: #DC2626; color: #fff; }

.action-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; gap: 14px; }
.action-card h4 { font-size: 15px; font-weight: 800; color: var(--forest); }
.action-card p { font-size: 12.5px; color: var(--ink-muted); }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 20px; border-radius: 999px; font-size: 14px; font-weight: 700; transition: all 150ms ease; width: 100%; cursor: pointer; text-decoration: none; border: 0; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline-danger { background: transparent; color: #DC2626; border: 1.5px solid #FCA5A5; }
.btn-outline-danger:hover { background: #FEF2F2; border-color: #DC2626; }
</style>
