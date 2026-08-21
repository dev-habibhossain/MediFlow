<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const avatarPreviewUrl = ref<string | null>(null)

const form = useForm({
    title_name: '',
    email: '',
    phone: '',
    gender: 'male',
    license_number: '',
    department_id: '',
    qualifications: '',
    experience_years: 5,
    bio: '',
    consultation_fee: 120,
    room_number: 'Suite 402, North Wing',
    password: 'MediFlow#2026Doc',
    status: 'active',
    avatar: null as File | null,
})

function handleAvatarChange(e: Event) {
    const target = e.target as HTMLInputElement
    if (target.files && target.files[0]) {
        const file = target.files[0]
        form.avatar = file
        avatarPreviewUrl.value = URL.createObjectURL(file)
    }
}

function submitForm() {
    form.post('/admin/doctors', {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Onboard New Doctor — Admin Portal" />

    <!-- TOP HEADER BAR BACK BUTTON -->
    <div class="top-left-nav mb-6">
        <Link href="/admin/doctors" class="back-btn">← Back to Doctors List</Link>
    </div>

    <!-- HEADER BANNER CARD -->
    <div class="page-header-card mb-6">
        <div>
            <span class="ref-badge">Physician Registration Form</span>
            <h1>Onboard New Doctor Account</h1>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="form-card">
        <!-- AVATAR UPLOAD BLOCK -->
        <div class="avatar-section">
            <div class="avatar-preview-lg">
                <img v-if="avatarPreviewUrl" :src="avatarPreviewUrl" alt="Preview" class="w-full h-full rounded-full object-cover" />
                <span v-else>DR</span>
            </div>
            <div class="avatar-actions">
                <h4>Profile Headshot</h4>
                <p>Upload a clear professional headshot (JPG or PNG, max 3MB).</p>
                <input type="file" class="file-input-custom" accept="image/*" @change="handleAvatarChange" />
            </div>
        </div>

        <form @submit.prevent="submitForm">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" />
                </svg>
                Personal & Contact Information
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="fullName">Title & Full Name <span>*</span></label>
                    <input id="fullName" v-model="form.title_name" type="text" class="form-control" required placeholder="e.g. Dr. Robert Chen" />
                </div>

                <div class="form-group">
                    <label for="email">Email Address <span>*</span></label>
                    <input id="email" v-model="form.email" type="email" class="form-control" required placeholder="e.g. r.chen@mediflow.com" />
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number <span>*</span></label>
                    <input id="phone" v-model="form.phone" type="tel" class="form-control" required placeholder="e.g. (555) 430-8910" />
                </div>

                <div class="form-group">
                    <label for="gender">Gender <span>*</span></label>
                    <select id="gender" v-model="form.gender" class="form-control" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="subhead">Medical Credentials & Department</div>

                <div class="form-group">
                    <label for="licenseNo">Medical License Number <span>*</span></label>
                    <input id="licenseNo" v-model="form.license_number" type="text" class="form-control font-mono" required placeholder="e.g. MD-94810" />
                </div>

                <div class="form-group">
                    <label for="department">Assigned Department <span>*</span></label>
                    <select id="department" v-model="form.department_id" class="form-control" required>
                        <option value="" disabled>Select Department</option>
                        <option value="1">Cardiology Department</option>
                        <option value="2">Neurology Department</option>
                        <option value="3">Pediatrics Department</option>
                        <option value="4">Orthopedics Department</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="degrees">Qualifications & Degrees <span>*</span></label>
                    <input id="degrees" v-model="form.qualifications" type="text" class="form-control" required placeholder="e.g. MD, FACC, MBBS (Johns Hopkins)" />
                </div>

                <div class="form-group">
                    <label for="experienceYrs">Years of Clinical Experience <span>*</span></label>
                    <input id="experienceYrs" v-model="form.experience_years" type="number" class="form-control" min="0" max="60" required placeholder="e.g. 10" />
                </div>

                <div class="form-group full-width">
                    <label for="bio">Professional Biography</label>
                    <textarea id="bio" v-model="form.bio" class="form-control" placeholder="Brief summary of clinical specialties, fellowships, and research..."></textarea>
                </div>

                <div class="subhead">Practice Settings & Initial Account Credentials</div>

                <div class="form-group">
                    <label for="consultFee">Consultation Fee ($ USD) <span>*</span></label>
                    <input id="consultFee" v-model="form.consultation_fee" type="number" class="form-control" min="0" required placeholder="e.g. 120" />
                </div>

                <div class="form-group">
                    <label for="roomNo">Assigned Clinic Suite / Room</label>
                    <input id="roomNo" v-model="form.room_number" type="text" class="form-control" placeholder="e.g. Suite 402, North Wing" />
                </div>

                <div class="form-group">
                    <label for="tempPassword">Temporary Login Password <span>*</span></label>
                    <input id="tempPassword" v-model="form.password" type="text" class="form-control font-mono" required />
                </div>

                <div class="form-group">
                    <label for="accountStatus">Initial Account Status <span>*</span></label>
                    <select id="accountStatus" v-model="form.status" class="form-control" required>
                        <option value="active">Active (Immediate Booking)</option>
                        <option value="leave">On Leave / Pending Shift</option>
                        <option value="inactive">Inactive / Verification Pending</option>
                    </select>
                </div>
            </div>

            <!-- BUTTON ROW -->
            <div class="form-actions">
                <Link href="/admin/doctors" class="btn btn-outline">Cancel</Link>
                <button type="submit" :disabled="form.processing" class="btn btn-primary">
                    {{ form.processing ? 'Registering...' : 'Save & Register Doctor Account' }}
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

.avatar-section { display: flex; align-items: center; gap: 24px; margin-bottom: 28px; background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 20px; }
.avatar-preview-lg { width: 80px; height: 80px; border-radius: 50%; border: 3px solid var(--card); box-shadow: var(--shadow-sm); flex-shrink: 0; background: var(--lime-soft); color: var(--lime-text); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 24px; overflow: hidden; }
.avatar-actions h4 { font-size: 15px; font-weight: 800; color: var(--forest); margin-bottom: 4px; }
.avatar-actions p { font-size: 12.5px; color: var(--ink-muted); margin-bottom: 10px; }
.file-input-custom { font-size: 12.5px; }

.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 28px; }
@media (max-width: 680px) { .form-grid { grid-template-columns: 1fr; } }
.full-width { grid-column: 1 / -1; }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-group label span { color: #DC2626; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); transition: border-color 150ms ease, background-color 150ms ease; outline: none; }
.form-control:focus { border-color: var(--forest); background: var(--card); }
textarea.form-control { height: auto; min-height: 100px; padding: 12px 16px; resize: vertical; }

.subhead { font-size: 13.5px; font-weight: 800; color: var(--forest); text-transform: uppercase; letter-spacing: 0.04em; grid-column: 1 / -1; border-top: 1px dashed var(--line); padding-top: 20px; margin-top: 8px; }

.form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid var(--line); }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 28px; border-radius: 999px; font-size: 14.5px; font-weight: 600; transition: all 150ms ease; cursor: pointer; text-decoration: none; border: 0; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }
</style>
