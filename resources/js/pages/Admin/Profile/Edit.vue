<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue'
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
    <Head title="Admin Profile Settings - MediFlow" />

    <AdminLayout>
        <div class="profile-settings-page">
            <div class="page-header">
                <div>
                    <span class="badge-tag">Account Management</span>
                    <h2>Admin Profile Settings</h2>
                    <p>Manage your administrator personal credentials, email, avatar image, and password.</p>
                </div>
            </div>

            <!-- TOAST NOTIFICATION -->
            <div v-if="showToast" class="toast-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                <span>{{ toastMessage }}</span>
            </div>

            <form @submit.prevent="submitProfile" class="profile-form-grid">
                <!-- AVATAR UPLOAD SECTION -->
                <div class="card-shell avatar-card">
                    <h3 class="card-title">Profile Picture & Avatar</h3>
                    <p class="card-desc">Upload a high-resolution photo or logo for your administrator profile across MediFlow.</p>

                    <div class="avatar-preview-area">
                        <div class="avatar-large-circle">
                            <img v-if="previewUrl || user?.avatar_url" :src="previewUrl || user?.avatar_url" alt="Avatar" class="avatar-img-fit" />
                            <span v-else class="avatar-fallback-text">{{ getInitials(user?.name) }}</span>
                        </div>

                        <div class="avatar-actions">
                            <input ref="imageInput" type="file" accept="image/*" class="hidden-file-input" @change="handleFileChange" />
                            <button type="button" class="btn-secondary" @click="triggerFileInput">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" y1="3" x2="12" y2="15" />
                                </svg>
                                Select Profile Photo
                            </button>
                            <small class="file-hint">Allowed formats: JPG, PNG, WEBP, SVG (Max 5MB)</small>
                        </div>
                    </div>
                </div>

                <!-- PERSONAL CREDENTIALS -->
                <div class="card-shell credentials-card">
                    <h3 class="card-title">Personal Credentials</h3>

                    <div class="form-group">
                        <label>Full Name <span class="req">*</span></label>
                        <input v-model="form.name" type="text" class="form-input" placeholder="e.g. Dr. Alex Vance" required />
                        <span v-if="form.errors.name" class="error-msg">{{ form.errors.name }}</span>
                    </div>

                    <div class="form-group">
                        <label>Email Address <span class="req">*</span></label>
                        <input v-model="form.email" type="email" class="form-input" placeholder="admin@mediflow.com" required />
                        <span v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</span>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" class="form-input disabled-input" value="Admin (Administrator)" disabled />
                    </div>
                </div>

                <!-- PASSWORD UPDATE -->
                <div class="card-shell password-card">
                    <h3 class="card-title">Change Password</h3>
                    <p class="card-desc">Leave password fields blank if you do not wish to update your password.</p>

                    <div class="form-group">
                        <label>New Password</label>
                        <input v-model="form.password" type="password" class="form-input" placeholder="Minimum 8 characters" />
                        <span v-if="form.errors.password" class="error-msg">{{ form.errors.password }}</span>
                    </div>

                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input v-model="form.password_confirmation" type="password" class="form-input" placeholder="Repeat new password" />
                    </div>
                </div>

                <!-- SUBMIT ACTIONS -->
                <div class="form-actions-bar">
                    <button type="submit" class="btn-primary" :disabled="form.processing">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        <span>{{ form.processing ? 'Saving Changes...' : 'Save Profile Settings' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<style scoped>
.profile-settings-page {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.badge-tag {
    display: inline-block;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--forest);
    background: var(--lime-soft);
    padding: 3px 10px;
    border-radius: 999px;
    margin-bottom: 6px;
}

.page-header h2 {
    font-size: 22px;
    font-weight: 800;
    color: var(--forest);
    margin: 0 0 4px 0;
}

.page-header p {
    font-size: 13px;
    color: var(--ink-muted);
    margin: 0;
}

.toast-success {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #DCFCE7;
    border: 1px solid #BBF7D0;
    color: #15803D;
    padding: 12px 16px;
    border-radius: var(--radius-md);
    font-weight: 700;
    font-size: 13.5px;
}

.profile-form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}

.card-shell {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 24px;
}

.card-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--forest);
    margin: 0 0 6px 0;
}

.card-desc {
    font-size: 12.5px;
    color: var(--ink-muted);
    margin: 0 0 20px 0;
}

.avatar-preview-area {
    display: flex;
    align-items: center;
    gap: 24px;
}

.avatar-large-circle {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: var(--forest);
    color: var(--lime);
    font-weight: 800;
    font-size: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border: 3px solid var(--line);
    box-shadow: var(--shadow-sm);
}

.avatar-img-fit {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.hidden-file-input {
    display: none;
}

.file-hint {
    font-size: 11.5px;
    color: var(--ink-muted);
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 16px;
}

.form-group label {
    font-size: 12.5px;
    font-weight: 700;
    color: var(--forest);
}

.req {
    color: #DC2626;
}

.form-input {
    height: 42px;
    padding: 0 14px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--line);
    background: var(--cream);
    font-size: 13.5px;
    color: var(--ink);
    outline: none;
    transition: all 150ms ease;
}

.form-input:focus {
    border-color: var(--forest);
    background: white;
    box-shadow: 0 0 0 3px rgba(22, 48, 31, 0.1);
}

.disabled-input {
    background: var(--gray-card);
    color: var(--ink-muted);
    cursor: not-allowed;
}

.error-msg {
    font-size: 11.5px;
    color: #DC2626;
    font-weight: 600;
}

.form-actions-bar {
    display: flex;
    justify-content: flex-end;
}

.btn-primary {
    height: 44px;
    padding: 0 24px;
    border-radius: 999px;
    background: var(--forest);
    color: var(--lime);
    font-size: 13.5px;
    font-weight: 800;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 150ms ease;
}

.btn-primary:hover {
    background: var(--forest-2);
    transform: translateY(-1px);
}

.btn-secondary {
    height: 38px;
    padding: 0 16px;
    border-radius: var(--radius-sm);
    background: var(--cream);
    border: 1px solid var(--line);
    color: var(--forest);
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 150ms ease;
}

.btn-secondary:hover {
    background: var(--card);
    border-color: var(--forest);
}
</style>
