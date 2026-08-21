<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface UserItem {
    id: number | string
    name: string
    subtext: string
    role: 'admin' | 'doctor' | 'patient'
    role_label: string
    email: string
    status: string
    initials: string
}

const props = withDefaults(
    defineProps<{
        users?: UserItem[]
    }>(),
    {
        users: () => [
            {
                id: 1,
                name: 'System Admin',
                subtext: 'Super Administrator',
                role: 'admin',
                role_label: 'Administrator',
                email: 'admin@mediflow.com',
                status: 'Active',
                initials: 'SA',
            },
            {
                id: 901,
                name: 'Dr. Sarah Jenkins',
                subtext: 'Cardiology Department',
                role: 'doctor',
                role_label: 'Doctor',
                email: 's.jenkins@mediflow.com',
                status: 'Active',
                initials: 'SJ',
            },
            {
                id: 9021,
                name: 'Habib Hossain',
                subtext: 'Patient Account (#MDF-9021)',
                role: 'patient',
                role_label: 'Patient',
                email: 'habib@example.com',
                status: 'Active',
                initials: 'HH',
            },
        ],
    }
)

const activeTab = ref<string>('all')
const searchQuery = ref<string>('')

const filteredUsers = computed(() => {
    return props.users.filter((user) => {
        const matchesTab = activeTab.value === 'all' || user.role === activeTab.value
        const q = searchQuery.value.toLowerCase().trim()
        const matchesQuery = !q || user.name.toLowerCase().includes(q) || user.email.toLowerCase().includes(q)
        return matchesTab && matchesQuery
    })
})

const countAll = computed(() => props.users.length)
const countAdmin = computed(() => props.users.filter((u) => u.role === 'admin').length)
const countDoctor = computed(() => props.users.filter((u) => u.role === 'doctor').length)
const countPatient = computed(() => props.users.filter((u) => u.role === 'patient').length)
</script>

<template>
    <Head title="User Accounts Registry — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">User Accounts Registry</h1>
            <p class="text-xs text-[var(--ink-muted)]">System-wide directory of all user accounts across roles and access levels</p>
        </div>
    </div>

    <!-- TOOLBAR & FILTERS -->
    <div class="toolbar-row">
        <div class="tab-group">
            <button class="tab-btn" :class="{ active: activeTab === 'all' }" @click="activeTab = 'all'">
                All Users <span class="tab-badge">{{ countAll }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'admin' }" @click="activeTab = 'admin'">
                Admins <span class="tab-badge">{{ countAdmin }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'doctor' }" @click="activeTab = 'doctor'">
                Doctors <span class="tab-badge">{{ countDoctor }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'patient' }" @click="activeTab = 'patient'">
                Patients <span class="tab-badge">{{ countPatient }}</span>
            </button>
        </div>

        <div class="filter-controls">
            <div class="search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input v-model="searchQuery" type="text" class="search-input" placeholder="Search name or email..." />
            </div>
        </div>
    </div>

    <!-- DATA TABLE CARD -->
    <div class="card-shell">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User Account</th>
                        <th>Role</th>
                        <th>Email Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in filteredUsers" :key="user.id">
                        <td>
                            <div class="user-cell">
                                <div
                                    class="user-avatar-cell"
                                    :style="
                                        user.role === 'doctor'
                                            ? 'background:#DCFCE7; color:#15803D;'
                                            : user.role === 'patient'
                                              ? 'background:#E0F2FE; color:#0369A1;'
                                              : ''
                                    "
                                >
                                    {{ user.initials }}
                                </div>
                                <div class="user-meta">
                                    <b>{{ user.name }}</b>
                                    <span>{{ user.subtext }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span
                                class="role-badge"
                                :class="{
                                    'role-admin': user.role === 'admin',
                                    'role-doctor': user.role === 'doctor',
                                    'role-patient': user.role === 'patient',
                                }"
                            >
                                {{ user.role_label }}
                            </span>
                        </td>
                        <td style="font-family: var(--font-mono); font-size: 13px;">{{ user.email }}</td>
                        <td><span class="status-badge status-active">● {{ user.status }}</span></td>
                        <td>
                            <Link :href="`/admin/users/${user.id}`" class="btn-action-icon" title="View / Edit User">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" /><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </Link>
                        </td>
                    </tr>

                    <tr v-if="filteredUsers.length === 0">
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--ink-muted);">
                            No user accounts found matching your search.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style>
.toolbar-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--line); padding-bottom: 16px; margin-bottom: 24px; }
.tab-group { display: flex; background: var(--card); border: 1px solid var(--line); border-radius: 999px; padding: 4px; gap: 4px; box-shadow: var(--shadow-sm); }
.tab-btn { padding: 8px 16px; border-radius: 999px; font-size: 13px; font-weight: 600; color: var(--ink-muted); transition: all 150ms ease; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; border: 0; background: transparent; }
.tab-btn:hover { color: var(--ink); }
.tab-btn.active { background: var(--forest); color: #fff; }
.tab-badge { font-family: var(--font-mono); font-size: 11px; padding: 2px 6px; border-radius: 999px; background: rgba(22,24,15,0.08); color: inherit; }
.tab-btn.active .tab-badge { background: rgba(255,255,255,0.2); color: #fff; }

.filter-controls { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.search-box { position: relative; width: 260px; }
.search-box svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); }
.search-input { width: 100%; height: 40px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); padding: 0 16px 0 40px; font-size: 13.5px; color: var(--ink); outline: none; transition: border-color 150ms ease; }
.search-input:focus { border-color: var(--forest); }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 16px 24px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.user-cell { display: flex; align-items: center; gap: 12px; }
.user-avatar-cell { width: 38px; height: 38px; border-radius: 50%; background: var(--cream-alt); color: var(--forest); font-weight: 800; font-size: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--line); }
.user-meta b { display: block; font-size: 14px; font-weight: 700; color: var(--forest); }
.user-meta span { display: block; font-size: 12px; color: var(--ink-muted); }

.role-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
.role-admin { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.role-doctor { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.role-patient { background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; }

.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
.status-active { background: #DCFCE7; color: #15803D; }

.btn-action-icon { width: 34px; height: 34px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--cream); display: inline-flex; align-items: center; justify-content: center; color: var(--forest); transition: all 150ms ease; text-decoration: none; }
.btn-action-icon:hover { border-color: var(--forest); background: var(--forest); color: #fff; }
</style>
