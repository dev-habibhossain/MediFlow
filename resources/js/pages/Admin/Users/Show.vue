<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

interface UserDetail {
    id: number | string
    name: string
    email: string
    role: string
    status: string
    initials: string
    registered_at: string
}

const props = withDefaults(
    defineProps<{
        user?: UserDetail
    }>(),
    {
        user: () => ({
            id: 1,
            name: 'System Admin',
            email: 'admin@mediflow.com',
            role: 'admin',
            status: 'active',
            initials: 'SA',
            registered_at: 'Oct 12, 2024',
        }),
    }
)

const name = ref(props.user.name)
const email = ref(props.user.email)
const role = ref(props.user.role)
const status = ref(props.user.status)
const showToast = ref(false)

function handleSaveUser() {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 2500)
}

function handleResetPassword() {
    alert(`Password reset link sent to ${email.value}`)
}

function handleSuspend() {
    alert('User account suspended.')
}
</script>

<template>
    <Head title="User Detail & Edit — Admin Portal" />

    <div class="mb-6">
        <Link href="/admin/users" class="back-btn">← Back to User Accounts Registry</Link>
    </div>

    <!-- HEADER BANNER -->
    <div class="user-header-card mb-6">
        <div class="user-info-left">
            <div class="user-avatar-lg">{{ user.initials }}</div>
            <div class="user-meta-lg">
                <h1>{{ user.name }}</h1>
                <p>User ID: <strong>#{{ user.id }}</strong> · Registered {{ user.registered_at }}</p>
                <span class="status-badge">● {{ user.status === 'active' ? 'Active Account' : 'Suspended Account' }}</span>
            </div>
        </div>
    </div>

    <!-- MAIN SPLIT GRID -->
    <div class="detail-grid">
        <!-- LEFT: EDIT USER FORM -->
        <div class="main-col">
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" /><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Edit User Account Details
                </div>

                <form @submit.prevent="handleSaveUser">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="userName">Full Name</label>
                            <input id="userName" v-model="name" type="text" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="userEmail">Email Address</label>
                            <input id="userEmail" v-model="email" type="email" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="userRole">Assigned Role</label>
                            <select id="userRole" v-model="role" class="form-control" required>
                                <option value="admin">Administrator (Super Admin)</option>
                                <option value="doctor">Doctor</option>
                                <option value="patient">Patient</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="userStatus">Account Status</label>
                            <select id="userStatus" v-model="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="width: auto; padding: 0 28px;">
                            Save User Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIGHT: SECURITY ACTIONS -->
        <div class="sidebar-col">
            <div class="action-card">
                <h4>Security Actions</h4>
                <p>Trigger password reset emails or suspend portal access for this user.</p>

                <button type="button" class="btn" style="background: var(--cream); color: var(--forest); border: 1px solid var(--line);" @click="handleResetPassword">
                    Send Password Reset Link
                </button>

                <button type="button" class="btn btn-outline-danger" @click="handleSuspend">
                    Suspend Account Access
                </button>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12" /></svg>
        User profile updated successfully!
    </div>
</template>

<style>
.back-btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 6px 14px; border-radius: 999px; text-decoration: none; transition: all 150ms ease; }
.back-btn:hover { background: var(--card); border-color: var(--forest); }

.user-header-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px 32px; box-shadow: var(--shadow-card); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; }
.user-info-left { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
.user-avatar-lg { width: 72px; height: 72px; border-radius: 50%; background: #FEF3C7; color: #B45309; font-weight: 800; font-size: 24px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid #FDE68A; }
.user-meta-lg h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin-bottom: 2px; }
.user-meta-lg p { font-size: 13.5px; color: var(--ink-muted); font-weight: 500; }
.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 999px; font-size: 12.5px; font-weight: 700; margin-top: 8px; background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

.detail-grid { display: grid; grid-template-columns: 1fr 360px; gap: 28px; align-items: start; }
@media (max-width: 1024px) { .detail-grid { grid-template-columns: 1fr; } }
.main-col { display: flex; flex-direction: column; gap: 24px; }
.sidebar-col { display: flex; flex-direction: column; gap: 24px; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px; box-shadow: var(--shadow-card); }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }

.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px; }
@media (max-width: 680px) { .form-grid { grid-template-columns: 1fr; } }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); outline: none; transition: border-color 150ms ease; }
.form-control:focus { border-color: var(--forest); background: var(--card); }

.action-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; gap: 14px; }
.action-card h4 { font-size: 15px; font-weight: 800; color: var(--forest); }
.action-card p { font-size: 12.5px; color: var(--ink-muted); }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 20px; border-radius: 999px; font-size: 14px; font-weight: 700; transition: all 150ms ease; width: 100%; border: 0; cursor: pointer; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline-danger { background: transparent; color: #DC2626; border: 1.5px solid #FCA5A5; }
.btn-outline-danger:hover { background: #FEF2F2; border-color: #DC2626; }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
