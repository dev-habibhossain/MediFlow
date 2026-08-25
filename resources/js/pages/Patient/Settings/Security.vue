<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const currentPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')
const showCurrent = ref(false)
const showNew = ref(false)
const showConfirm = ref(false)

const is2FA = ref(false)

const sessions = ref([
    {
        id: 1,
        device: 'Chrome on Linux (Ubuntu)',
        location: 'Dighali, Chittagong · IP: 103.14.22.81 · Active now',
        isCurrent: true,
        revoked: false,
    },
    {
        id: 2,
        device: 'MediFlow App on iPhone 14 Pro',
        location: 'Dhaka, Bangladesh · IP: 103.95.12.04 · 3 days ago',
        isCurrent: false,
        revoked: false,
    },
])

const toastMessage = ref('')
const showToast = ref(false)

function triggerToast(msg: string) {
    toastMessage.value = msg
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 3000)
}

function handleChangePassword() {
    if (newPassword.value !== confirmPassword.value) {
        alert('New passwords do not match!')
        return
    }
    triggerToast('Password successfully changed!')
    currentPassword.value = ''
    newPassword.value = ''
    confirmPassword.value = ''
}

function handle2FAToggle() {
    if (is2FA.value) {
        triggerToast('Two-Factor Authentication Enabled')
    } else {
        triggerToast('Two-Factor Authentication Disabled')
    }
}

function revokeSession(id: number) {
    const s = sessions.value.find((sess) => sess.id === id)
    if (s) {
        s.revoked = true
        triggerToast('Session revoked successfully')
    }
}
</script>

<template>
    <Head title="Profile Settings — Security" />

    <!-- SETTINGS NAVIGATION TABS -->
    <div class="settings-tabs-row">
        <nav class="settings-nav">
            <Link href="/patient/settings/profile" class="settings-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                Personal Info
            </Link>
            <Link href="/patient/settings/security" class="settings-link active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Security & Password
            </Link>
        </nav>
    </div>

    <!-- CARD 1: CHANGE PASSWORD FORM -->
    <div class="settings-card">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            Change Account Password
        </div>

        <form @submit.prevent="handleChangePassword">
            <div class="form-group max-w-480">
                <label for="currentPassword">Current Password</label>
                <div class="form-control-wrap">
                    <input id="currentPassword" v-model="currentPassword" :type="showCurrent ? 'text' : 'password'" class="form-control" required placeholder="••••••••" />
                    <span class="toggle-pwd" @click="showCurrent = !showCurrent">{{ showCurrent ? 'Hide' : 'Show' }}</span>
                </div>
            </div>

            <div class="grid-2-col">
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <div class="form-control-wrap">
                        <input id="newPassword" v-model="newPassword" :type="showNew ? 'text' : 'password'" class="form-control" required placeholder="Min. 8 characters" />
                        <span class="toggle-pwd" @click="showNew = !showNew">{{ showNew ? 'Hide' : 'Show' }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm New Password</label>
                    <div class="form-control-wrap">
                        <input id="confirmPassword" v-model="confirmPassword" :type="showConfirm ? 'text' : 'password'" class="form-control" required placeholder="Re-type new password" />
                        <span class="toggle-pwd" @click="showConfirm = !showConfirm">{{ showConfirm ? 'Hide' : 'Show' }}</span>
                    </div>
                </div>
            </div>

            <div class="submit-btn-row">
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>
    </div>

    <!-- CARD 2: TWO-FACTOR AUTHENTICATION (2FA) -->
    <div class="settings-card">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            Two-Factor Authentication (2FA)
        </div>

        <div class="toggle-row">
            <div class="toggle-info">
                <h4>Authenticator App (TOTP)</h4>
                <p>Require a 6-digit verification code from Google Authenticator or Authy when signing in.</p>
            </div>
            <label class="switch">
                <input v-model="is2FA" type="checkbox" @change="handle2FAToggle" />
                <span class="slider"></span>
            </label>
        </div>
    </div>

    <!-- CARD 3: ACTIVE SIGN-IN SESSIONS -->
    <div class="settings-card">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
            Active Sessions & Recognized Devices
        </div>

        <div class="session-list">
            <div v-for="sess in sessions" :key="sess.id" class="session-item" :class="{ 'opacity-50': sess.revoked }">
                <div class="session-left">
                    <div class="session-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                    </div>
                    <div class="session-meta">
                        <b>
                            {{ sess.device }}
                            <span v-if="sess.isCurrent" class="current-badge">Current Device</span>
                        </b>
                        <span>{{ sess.location }}</span>
                    </div>
                </div>
                <span v-if="sess.isCurrent" class="this-device-lbl">This Device</span>
                <button v-else-if="!sess.revoked" class="btn-sm-danger" @click="revokeSession(sess.id)">Revoke Session</button>
                <button v-else class="btn-sm-danger" disabled>Terminated</button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        <span>{{ toastMessage }}</span>
    </div>
</template>

<style scoped>
.settings-tabs-row { border-bottom: 1px solid var(--line); padding-bottom: 12px; margin-bottom: 24px; }
.settings-nav { display: flex; gap: 8px; flex-wrap: wrap; }
.settings-link { padding: 10px 20px; border-radius: 999px; font-size: 13.5px; font-weight: 600; color: var(--ink-muted); text-decoration: none; transition: all 150ms ease; display: inline-flex; align-items: center; gap: 8px; }
.settings-link:hover { color: var(--ink); background: var(--card); }
.settings-link.active { background: var(--forest); color: #fff; }

.settings-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-card); margin-bottom: 24px; }
@media (max-width: 600px) { .settings-card { padding: 24px; } }

.card-title { font-size: 17px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 14px; }
.card-title svg { width: 20px; height: 20px; color: var(--forest); }

.form-group { margin-bottom: 20px; }
.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.max-w-480 { max-width: 480px; }
.grid-2-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; max-width: 800px; }
@media (max-width: 600px) { .grid-2-col { grid-template-columns: 1fr; } }

.form-control-wrap { position: relative; }
.form-control { width: 100%; height: 46px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 44px 0 16px; font-size: 14px; color: var(--ink); }
.form-control:focus { outline: none; border-color: var(--forest); background: var(--card); }

.toggle-pwd { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--ink-muted); font-size: 12px; font-weight: 600; cursor: pointer; }
.toggle-pwd:hover { color: var(--forest); }

.submit-btn-row { margin-top: 12px; }

.toggle-row { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 20px; background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-lg); }
.toggle-info h4 { font-size: 15px; font-weight: 800; color: var(--forest); margin: 0 0 4px 0; }
.toggle-info p { font-size: 13px; color: var(--ink-muted); margin: 0; }

.switch { position: relative; display: inline-block; width: 48px; height: 26px; flex-shrink: 0; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; inset: 0; background-color: var(--line); border-radius: 999px; transition: .2s; }
.slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .2s; box-shadow: var(--shadow-sm); }
input:checked + .slider { background-color: var(--forest); }
input:checked + .slider:before { transform: translateX(22px); }

.session-list { display: flex; flex-direction: column; gap: 12px; }
.session-item { display: flex; align-items: center; justify-content: space-between; padding: 16px; background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); gap: 16px; }
.session-item.opacity-50 { opacity: 0.5; }
.session-left { display: flex; align-items: center; gap: 14px; }
.session-icon { width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--card); border: 1px solid var(--line); display: flex; align-items: center; justify-content: center; color: var(--forest); flex-shrink: 0; }
.session-meta b { font-size: 14px; font-weight: 700; color: var(--ink); display: block; }
.session-meta span { font-size: 12px; color: var(--ink-muted); font-family: var(--font-mono); display: block; }
.current-badge { font-size: 11px; font-weight: 700; background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; padding: 2px 8px; border-radius: 999px; margin-left: 6px; }
.this-device-lbl { font-size: 12.5px; color: var(--ink-muted); font-weight: 600; }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 28px; border-radius: 999px; font-size: 14.5px; font-weight: 600; transition: all 150ms ease; cursor: pointer; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); border: none; }
.btn-primary:hover { background: var(--forest-2); }

.btn-sm-danger { font-size: 12.5px; font-weight: 600; color: #DC2626; padding: 6px 14px; border-radius: 999px; border: 1px solid #FCA5A5; background: #FEE2E2; transition: all 150ms ease; cursor: pointer; }
.btn-sm-danger:hover:not(:disabled) { background: #DC2626; color: #fff; border-color: #DC2626; }
.btn-sm-danger:disabled { cursor: not-allowed; }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; }
</style>
