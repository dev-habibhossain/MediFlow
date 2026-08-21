<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = withDefaults(
    defineProps<{
        doctor?: {
            id: number
            name: string
            email: string
            phone: string
            license_number: string
            department: string
            status: string
            consultation_fee: number
            experience_years: number
            bio: string
            avatar: string
        }
    }>(),
    {
        doctor: () => ({
            id: 901,
            name: 'Dr. Sarah Jenkins',
            email: 's.jenkins@mediflow.com',
            phone: '(555) 321-9041',
            license_number: 'MD-90412',
            department: 'Cardiology',
            status: 'active',
            consultation_fee: 120,
            experience_years: 12,
            bio: 'Senior Consultant Cardiologist specializing in preventive cardiology, hypertension management, and non-invasive cardiovascular diagnostics.',
            avatar: 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=200',
        }),
    }
)

const form = useForm({
    name: props.doctor.name,
    email: props.doctor.email,
    phone: props.doctor.phone,
    license_number: props.doctor.license_number,
    department: props.doctor.department,
    status: props.doctor.status,
    consultation_fee: props.doctor.consultation_fee,
    experience_years: props.doctor.experience_years,
    bio: props.doctor.bio,
})

function updateDoctor() {
    form.put(`/admin/doctors/${props.doctor.id}`, {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head :title="`${props.doctor.name} Profile — Admin Portal`" />

    <!-- TOP HEADER BAR ACTIONS -->
    <div class="flex items-center justify-between mb-6">
        <Link href="/admin/doctors" class="back-btn">← Back to Doctors List</Link>
        <Link :href="`/admin/doctors/${props.doctor.id}/schedule`" class="btn-action-lg">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" />
            </svg>
            Manage Schedule Override
        </Link>
    </div>

    <!-- PROFILE HEADER CARD -->
    <div class="profile-header-card mb-6">
        <div class="profile-info-left">
            <img :src="props.doctor.avatar" :alt="props.doctor.name" class="profile-avatar-lg" />
            <div class="profile-meta-lg">
                <h1>{{ props.doctor.name }}</h1>
                <p>{{ props.doctor.department }} Department · License: <strong>{{ props.doctor.license_number }}</strong></p>
                <span class="status-badge status-active">● Active Account</span>
            </div>
        </div>
    </div>

    <!-- MAIN SPLIT GRID -->
    <div class="detail-grid">
        <!-- LEFT: EDIT PROFILE FORM -->
        <div class="form-card">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" /><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                Edit Physician Details & Practice Rates
            </div>

            <form @submit.prevent="updateDoctor">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="fullName">Full Name <span>*</span></label>
                        <input id="fullName" v-model="form.name" type="text" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span>*</span></label>
                        <input id="email" v-model="form.email" type="email" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number <span>*</span></label>
                        <input id="phone" v-model="form.phone" type="tel" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="licenseNo">Medical License Number <span>*</span></label>
                        <input id="licenseNo" v-model="form.license_number" type="text" class="form-control font-mono" required />
                    </div>

                    <div class="form-group">
                        <label for="department">Assigned Department <span>*</span></label>
                        <select id="department" v-model="form.department" class="form-control" required>
                            <option value="Cardiology">Cardiology Department</option>
                            <option value="Neurology">Neurology Department</option>
                            <option value="Pediatrics">Pediatrics Department</option>
                            <option value="Orthopedics">Orthopedics Department</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="accountStatus">Account Status <span>*</span></label>
                        <select id="accountStatus" v-model="form.status" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="leave">On Leave</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="consultFee">Consultation Fee ($ USD) <span>*</span></label>
                        <input id="consultFee" v-model="form.consultation_fee" type="number" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="experienceYrs">Years of Experience</label>
                        <input id="experienceYrs" v-model="form.experience_years" type="number" class="form-control" />
                    </div>

                    <div class="form-group full-width">
                        <label for="bio">Professional Biography</label>
                        <textarea id="bio" v-model="form.bio" class="form-control"></textarea>
                    </div>
                </div>

                <!-- BUTTON ROW -->
                <div class="form-actions">
                    <Link href="/admin/doctors" class="btn btn-outline">Cancel</Link>
                    <button type="submit" :disabled="form.processing" class="btn btn-primary">
                        {{ form.processing ? 'Saving...' : 'Save Profile Changes' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- RIGHT: SECURITY & ADMIN ACTIONS -->
        <div class="sidebar-col">
            <div class="action-card">
                <h4>Security & Access</h4>
                <p>Send password reset instructions or temporarily suspend physician portal access.</p>

                <button type="button" class="btn btn-secondary-action">
                    Send Password Reset Email
                </button>

                <button type="button" class="btn btn-outline-danger">
                    Suspend Account Access
                </button>
            </div>

            <div class="action-card">
                <h4>Department Transfer</h4>
                <p>Reassign this physician to another hospital department.</p>

                <select v-model="form.department" class="form-control">
                    <option value="Cardiology">Cardiology</option>
                    <option value="Neurology">Neurology</option>
                    <option value="Pediatrics">Pediatrics</option>
                    <option value="Orthopedics">Orthopedics</option>
                </select>

                <button type="button" class="btn btn-primary mt-1" @click="updateDoctor">
                    Update Department
                </button>
            </div>
        </div>
    </div>
</template>

<style>
.back-btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 6px 14px; border-radius: 999px; transition: all 150ms ease; text-decoration: none; }
.back-btn:hover { background: var(--card); border-color: var(--forest); }

.btn-action-lg { height: 40px; padding: 0 16px; border-radius: 999px; font-size: 13px; font-weight: 700; border: 1px solid var(--line); background: var(--card); color: var(--forest); transition: all 150ms ease; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
.btn-action-lg:hover { background: var(--cream); border-color: var(--forest); }

.profile-header-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px 32px; box-shadow: var(--shadow-card); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; }
.profile-info-left { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
.profile-avatar-lg { width: 76px; height: 76px; border-radius: 50%; object-fit: cover; border: 3px solid var(--card); box-shadow: var(--shadow-sm); flex-shrink: 0; }
.profile-meta-lg h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin-bottom: 2px; }
.profile-meta-lg p { font-size: 13.5px; color: var(--ink-muted); font-weight: 500; }

.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 999px; font-size: 12.5px; font-weight: 700; margin-top: 8px; }
.status-active { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

.detail-grid { display: grid; grid-template-columns: 1fr 340px; gap: 28px; align-items: start; }
@media (max-width: 1024px) { .detail-grid { grid-template-columns: 1fr; } }

.form-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 32px; box-shadow: var(--shadow-card); }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }
.card-title svg { width: 18px; height: 18px; color: var(--forest); }

.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 24px; }
@media (max-width: 680px) { .form-grid { grid-template-columns: 1fr; } }
.full-width { grid-column: 1 / -1; }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-group label span { color: #DC2626; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); transition: border-color 150ms ease, background-color 150ms ease; outline: none; }
.form-control:focus { border-color: var(--forest); background: var(--card); }
textarea.form-control { height: auto; min-height: 100px; padding: 12px 16px; resize: vertical; }

.sidebar-col { display: flex; flex-direction: column; gap: 24px; }
.action-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; gap: 14px; }
.action-card h4 { font-size: 15px; font-weight: 800; color: var(--forest); }
.action-card p { font-size: 12.5px; color: var(--ink-muted); }

.form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid var(--line); }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 24px; border-radius: 999px; font-size: 14px; font-weight: 700; transition: all 150ms ease; cursor: pointer; text-decoration: none; border: 0; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); width: 100%; }
.btn-primary:hover { background: var(--forest-2); }
.btn-secondary-action { background: var(--cream); color: var(--forest); border: 1px solid var(--line); width: 100%; }
.btn-secondary-action:hover { background: var(--card); border-color: var(--forest); }
.btn-outline-danger { background: transparent; color: #DC2626; border: 1.5px solid #FCA5A5; width: 100%; }
.btn-outline-danger:hover { background: #FEF2F2; border-color: #DC2626; }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }
</style>
