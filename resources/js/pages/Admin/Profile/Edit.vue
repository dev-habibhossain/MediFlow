<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const page = usePage()
const user = computed(() => page.props.auth.user as any)

const previewUrl = ref<string | null>(null)
const imageInput = ref<HTMLInputElement | null>(null)
const showToast = ref(false)
const toastMessage = ref('')

const form = useForm({
    _method: 'POST',
    name: user.value?.name || '',
    email: user.value?.email || '',
    avatar: null as File | null,
    password: '',
    password_confirmation: '',
})

function triggerFileInput() {
    imageInput.value?.click()
}

function handleFileChange(event: Event) {
    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        const file = target.files[0]
        form.avatar = file
        previewUrl.value = URL.createObjectURL(file)
    }
}

function submitProfile() {
    form.post('/admin/profile', {
        preserveScroll: true,
        onSuccess: () => {
            form.password = ''
            form.password_confirmation = ''
            toastMessage.value = 'Profile updated successfully!'
            showToast.value = true
            setTimeout(() => {
                showToast.value = false
            }, 3000)
        },
    })
}

function getInitials(name?: string) {
    if (!name) return 'ADM'
    return name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2)
}
</script>

<template>
    <Head title="Personal Profile & Account Security — Admin Portal" />

    <!-- PAGE HEADER CARD -->
    <div class="page-header-card mb-6">
        <div>
            <span class="ref-badge">Account Settings</span>
            <h1>Personal Profile & Security</h1>
            <p class="text-xs text-[var(--ink-muted)] mt-1">Manage your administrator account credentials, profile photo, email address, and authentication password.</p>
        </div>
    </div>

    <!-- TOAST SUCCESS ALERT -->
    <div v-if="showToast" class="toast-success mb-6">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
            <polyline points="20 6 9 17 4 12" />
        </svg>
        <span>{{ toastMessage }}</span>
    </div>

    <!-- MAIN FORM GRID (2 COLUMNS) -->
    <form @submit.prevent="submitProfile" class="profile-grid">
        <!-- LEFT COLUMN: AVATAR & ACCOUNT SUMMARY CARD -->
        <div class="card-shell avatar-summary-card">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" />
                </svg>
                Administrator Avatar
            </div>

            <div class="avatar-display-box">
                <div class="avatar-large-circle">
                    <img v-if="previewUrl || user?.avatar_url" :src="previewUrl || user?.avatar_url" alt="Avatar" class="avatar-img-fit" />
                    <span v-else class="avatar-fallback-text">{{ getInitials(user?.name) }}</span>
                </div>

                <div class="user-identity">
                    <h3>{{ user?.name || 'Administrator' }}</h3>
                    <p>{{ user?.email || 'admin@mediflow.com' }}</p>
                    <span class="role-pill">Super Administrator</span>
                </div>

                <div class="avatar-actions mt-4">
                    <input ref="imageInput" type="file" accept="image/*" class="hidden-file-input" @change="handleFileChange" />
                    <button type="button" class="btn-select-photo" @click="triggerFileInput">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="17 8 12 3 7 8" /><line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                        Upload Profile Photo
                    </button>
                    <span class="file-hint">JPG, PNG or WEBP (Max 3MB)</span>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: FORM CARDS -->
        <div class="main-form-col">
            <!-- PERSONAL INFORMATION CARD -->
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                    </svg>
                    Personal Information
                </div>

                <div class="form-group mb-4">
                    <label for="adminName">Full Display Name <span>*</span></label>
                    <input id="adminName" v-model="form.name" type="text" class="form-control" placeholder="e.g. Dr. Alex Vance" required />
                    <span v-if="form.errors.name" class="error-msg">{{ form.errors.name }}</span>
                </div>

                <div class="form-group mb-4">
                    <label for="adminEmail">Email Address <span>*</span></label>
                    <input id="adminEmail" v-model="form.email" type="email" class="form-control" placeholder="admin@mediflow.com" required />
                    <span v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</span>
                </div>

                <div class="form-group">
                    <label>System Privilege Level</label>
                    <input type="text" class="form-control disabled-input" value="System Administrator (Full Access)" disabled />
                </div>
            </div>

            <!-- SECURITY & PASSWORD CARD -->
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    Update Password (Optional)
                </div>
                <p class="card-desc">Leave blank if you do not wish to change your current password.</p>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input id="newPassword" v-model="form.password" type="password" class="form-control" placeholder="Minimum 8 characters" />
                        <span v-if="form.errors.password" class="error-msg">{{ form.errors.password }}</span>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <input id="confirmPassword" v-model="form.password_confirmation" type="password" class="form-control" placeholder="Repeat new password" />
                    </div>
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="form-actions">
                <button type="submit" class="btn-primary" :disabled="form.processing">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" /><polyline points="7 3 7 8 15 8" />
                    </svg>
                    <span>{{ form.processing ? 'Saving Changes...' : 'Save Profile Changes' }}</span>
                </button>
            </div>
        </div>
    </form>
</template>

<style scoped>
.page-header-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px 32px; box-shadow: var(--shadow-card); }
.ref-badge { font-family: var(--font-mono); font-size: 12px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 4px 10px; border-radius: var(--radius-sm); display: inline-block; margin-bottom: 4px; }
.page-header-card h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; }

.toast-success { display: flex; align-items: center; gap: 10px; background: #DCFCE7; border: 1px solid #BBF7D0; color: #15803D; padding: 14px 20px; border-radius: var(--radius-lg); font-weight: 700; font-size: 13.5px; }

.profile-grid { display: grid; grid-template-columns: 320px 1fr; gap: 28px; align-items: start; }
@media (max-width: 900px) { .profile-grid { grid-template-columns: 1fr; } }

.main-form-col { display: flex; flex-direction: column; gap: 24px; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px; box-shadow: var(--shadow-card); }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 16px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }
.card-title svg { width: 18px; height: 18px; color: var(--forest); }
.card-desc { font-size: 12.5px; color: var(--ink-muted); margin-bottom: 16px; }

.avatar-summary-card { text-align: center; }
.avatar-display-box { display: flex; flex-direction: column; align-items: center; gap: 12px; padding: 12px 0; }
.avatar-large-circle { width: 96px; height: 96px; border-radius: 50%; background: var(--forest); color: var(--lime); font-weight: 800; font-size: 32px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 3px solid var(--lime-soft); box-shadow: var(--shadow-sm); }
.avatar-img-fit { width: 100%; height: 100%; object-fit: cover; }
.user-identity h3 { font-size: 17px; font-weight: 800; color: var(--forest); margin: 0 0 2px 0; }
.user-identity p { font-size: 12.5px; color: var(--ink-muted); margin: 0 0 8px 0; }
.role-pill { font-size: 11px; font-weight: 700; text-transform: uppercase; background: var(--lime-soft); color: var(--lime-text); padding: 3px 10px; border-radius: 999px; }

.hidden-file-input { display: none; }
.btn-select-photo { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 40px; padding: 0 18px; border-radius: 999px; background: var(--cream); border: 1px solid var(--line); color: var(--forest); font-size: 12.5px; font-weight: 700; cursor: pointer; transition: all 150ms ease; width: 100%; }
.btn-select-photo:hover { background: var(--card); border-color: var(--forest); }
.file-hint { font-size: 11px; color: var(--ink-muted); display: block; margin-top: 6px; }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-group label span { color: #DC2626; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); outline: none; transition: border-color 150ms ease, background-color 150ms ease; }
.form-control:focus { border-color: var(--forest); background: var(--card); }
.disabled-input { background: var(--gray-card); color: var(--ink-muted); cursor: not-allowed; }

.form-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
@media (max-width: 600px) { .form-grid-2 { grid-template-columns: 1fr; } }

.error-msg { font-size: 11.5px; color: #DC2626; font-weight: 600; margin-top: 4px; display: block; }

.form-actions { display: flex; justify-content: flex-end; }
.btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 32px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 14.5px; font-weight: 700; border: none; cursor: pointer; box-shadow: var(--shadow-sm); transition: all 150ms ease; }
.btn-primary:hover { background: var(--forest-2); transform: translateY(-1px); }
</style>
