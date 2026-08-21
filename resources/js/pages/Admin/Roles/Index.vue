<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

interface PermissionRow {
    name: string
    admin: boolean
    doctor: boolean
    receptionist: boolean
    patient: boolean
}

const permissions = ref<PermissionRow[]>([
    { name: 'manage-users', admin: true, doctor: false, receptionist: false, patient: false },
    { name: 'manage-doctors', admin: true, doctor: false, receptionist: false, patient: false },
    { name: 'manage-departments', admin: true, doctor: false, receptionist: false, patient: false },
    { name: 'manage-appointments', admin: true, doctor: true, receptionist: true, patient: false },
    { name: 'write-prescriptions', admin: true, doctor: true, receptionist: false, patient: false },
    { name: 'book-consultation', admin: true, doctor: false, receptionist: true, patient: true },
    { name: 'system-settings', admin: true, doctor: false, receptionist: false, patient: false },
])

const showToast = ref(false)

function savePermissions() {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 2500)
}
</script>

<template>
    <Head title="Roles & Permissions — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">Spatie Roles & Permissions Matrix</h1>
            <p class="text-xs text-[var(--ink-muted)]">Configure role-based access control (RBAC) privileges across MediFlow</p>
        </div>
        <button class="btn-primary-add" @click="savePermissions">Save Permission Changes</button>
    </div>

    <!-- MATRIX TABLE CARD -->
    <div class="card-shell">
        <div class="table-responsive">
            <table class="matrix-table">
                <thead>
                    <tr>
                        <th>Permission Name</th>
                        <th>Administrator</th>
                        <th>Doctor</th>
                        <th>Receptionist</th>
                        <th>Patient</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="perm in permissions" :key="perm.name">
                        <td><span class="permission-label">{{ perm.name }}</span></td>
                        <td><div class="checkbox-wrap"><input v-model="perm.admin" type="checkbox" class="matrix-checkbox" /></div></td>
                        <td><div class="checkbox-wrap"><input v-model="perm.doctor" type="checkbox" class="matrix-checkbox" /></div></td>
                        <td><div class="checkbox-wrap"><input v-model="perm.receptionist" type="checkbox" class="matrix-checkbox" /></div></td>
                        <td><div class="checkbox-wrap"><input v-model="perm.patient" type="checkbox" class="matrix-checkbox" /></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12" /></svg>
        Spatie roles & permissions updated successfully!
    </div>
</template>

<style>
.btn-primary-add { display: inline-flex; align-items: center; gap: 8px; height: 42px; padding: 0 20px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 13.5px; font-weight: 700; box-shadow: var(--shadow-sm); border: 0; cursor: pointer; transition: background-color 150ms ease; }
.btn-primary-add:hover { background: var(--forest-2); }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.matrix-table { width: 100%; border-collapse: collapse; text-align: left; }
.matrix-table th { background: var(--cream); padding: 16px 20px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.matrix-table td { padding: 16px 20px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.permission-label { font-family: var(--font-mono); font-size: 13px; font-weight: 700; color: var(--forest); }
.checkbox-wrap { display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; }
.matrix-checkbox { width: 18px; height: 18px; accent-color: var(--forest); cursor: pointer; border-radius: 4px; }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
