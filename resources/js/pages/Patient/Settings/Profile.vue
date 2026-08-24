<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = ref({
    firstName: 'Habib',
    lastName: 'Hossain',
    email: 'habib@example.com',
    phone: '(555) 340-2199',
    dob: '1998-04-12',
    gender: 'male',
    bloodGroup: 'O+',
    patientId: '#MDF-9021',
    emergName: 'Tanvir Hossain',
    emergRel: 'Brother',
    emergPhone: '(555) 890-4122',
    street: '128 Agrabad Access Road',
    city: 'Chittagong',
    zip: '4100',
})

const showToast = ref(false)

function handleSaveProfile() {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 3000)
}
</script>

<template>
    <Head title="Profile Settings — Personal Info" />

    <!-- SETTINGS NAVIGATION TABS -->
    <div class="settings-tabs-row">
        <nav class="settings-nav">
            <Link href="/patient/settings/profile" class="settings-link active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                Personal Info
            </Link>
            <Link href="/patient/settings/security" class="settings-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Security & Password
            </Link>
        </nav>
    </div>

    <!-- MAIN PROFILE EDIT CARD -->
    <div class="settings-card">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            Personal & Identification Information
        </div>

        <!-- AVATAR UPLOAD BLOCK -->
        <div class="avatar-section">
            <div class="avatar-preview">HH</div>
            <div class="avatar-actions">
                <h4>Profile Photo</h4>
                <p>PNG, JPG or WEBP max 2MB. Displayed across your patient record.</p>
                <button type="button" class="btn-upload" @click="alert('Select a photo file to upload.')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Upload New Picture
                </button>
            </div>
        </div>

        <!-- FORM INPUTS GRID -->
        <form @submit.prevent="handleSaveProfile">
            <div class="form-grid">
                <div class="form-group">
                    <label for="firstName">First Name <span class="req">*</span></label>
                    <input id="firstName" v-model="form.firstName" type="text" class="form-control" required />
                </div>

                <div class="form-group">
                    <label for="lastName">Last Name <span class="req">*</span></label>
                    <input id="lastName" v-model="form.lastName" type="text" class="form-control" required />
                </div>

                <div class="form-group">
                    <label for="email">Email Address <span class="req">*</span></label>
                    <input id="email" v-model="form.email" type="email" class="form-control" required />
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number <span class="req">*</span></label>
                    <input id="phone" v-model="form.phone" type="tel" class="form-control" required />
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input id="dob" v-model="form.dob" type="date" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" v-model="form.gender" class="form-control">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="bloodGroup">Blood Group</label>
                    <select id="bloodGroup" v-model="form.bloodGroup" class="form-control">
                        <option value="O+">O Positive (O+)</option>
                        <option value="A+">A Positive (A+)</option>
                        <option value="B+">B Positive (B+)</option>
                        <option value="AB+">AB Positive (AB+)</option>
                        <option value="O-">O Negative (O-)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="patientId">Patient Record ID</label>
                    <input id="patientId" :value="form.patientId" type="text" class="form-control" disabled />
                </div>

                <!-- EMERGENCY CONTACT SUBHEAD -->
                <div class="form-subhead">Emergency Contact</div>

                <div class="form-group">
                    <label for="emergName">Contact Name</label>
                    <input id="emergName" v-model="form.emergName" type="text" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="emergRel">Relationship</label>
                    <input id="emergRel" v-model="form.emergRel" type="text" class="form-control" />
                </div>

                <div class="form-group full-width">
                    <label for="emergPhone">Emergency Phone Number</label>
                    <input id="emergPhone" v-model="form.emergPhone" type="tel" class="form-control" />
                </div>

                <!-- ADDRESS SUBHEAD -->
                <div class="form-subhead">Residential Address</div>

                <div class="form-group full-width">
                    <label for="street">Street Address</label>
                    <input id="street" v-model="form.street" type="text" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input id="city" v-model="form.city" type="text" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="zip">Zip / Postal Code</label>
                    <input id="zip" v-model="form.zip" type="text" class="form-control" />
                </div>
            </div>

            <!-- BUTTON ROW -->
            <div class="form-actions">
                <button type="reset" class="btn btn-outline">Reset Changes</button>
                <button type="submit" class="btn btn-primary">Save Profile Changes</button>
            </div>
        </form>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        Profile details updated successfully!
    </div>
</template>

<style scoped>
.settings-tabs-row { border-bottom: 1px solid var(--line); padding-bottom: 12px; margin-bottom: 24px; }
.settings-nav { display: flex; gap: 8px; flex-wrap: wrap; }
.settings-link { padding: 10px 20px; border-radius: 999px; font-size: 13.5px; font-weight: 600; color: var(--ink-muted); text-decoration: none; transition: all 150ms ease; display: inline-flex; align-items: center; gap: 8px; }
.settings-link:hover { color: var(--ink); background: var(--card); }
.settings-link.active { background: var(--forest); color: #fff; }

.settings-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-card); }
@media (max-width: 600px) { .settings-card { padding: 24px; } }

.card-title { font-size: 17px; font-weight: 800; color: var(--forest); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 14px; }
.card-title svg { width: 20px; height: 20px; color: var(--forest); }

.avatar-section { display: flex; align-items: center; gap: 24px; margin-bottom: 32px; background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 20px; }
.avatar-preview { width: 80px; height: 80px; border-radius: 50%; background: var(--lime); color: var(--lime-text); font-size: 28px; font-weight: 800; display: flex; align-items: center; justify-content: center; border: 3px solid var(--card); box-shadow: var(--shadow-sm); flex-shrink: 0; }
.avatar-actions h4 { font-size: 15px; font-weight: 800; color: var(--forest); margin: 0 0 4px 0; }
.avatar-actions p { font-size: 12.5px; color: var(--ink-muted); margin: 0 0 12px 0; }
.btn-upload { display: inline-flex; align-items: center; gap: 6px; height: 36px; padding: 0 16px; border-radius: 999px; background: var(--card); border: 1px solid var(--line); font-size: 13px; font-weight: 600; color: var(--ink); transition: all 150ms ease; cursor: pointer; }
.btn-upload:hover { border-color: var(--forest); background: var(--forest); color: #fff; }

.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 28px; }
@media (max-width: 680px) { .form-grid { grid-template-columns: 1fr; } }
.full-width { grid-column: 1 / -1; }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.req { color: #DC2626; }
.form-control { width: 100%; height: 46px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); }
.form-control:focus { outline: none; border-color: var(--forest); background: var(--card); }
.form-control:disabled { opacity: 0.6; cursor: not-allowed; }

.form-subhead { font-size: 14px; font-weight: 800; color: var(--forest); text-transform: uppercase; letter-spacing: 0.04em; margin: 16px 0 12px; grid-column: 1 / -1; border-top: 1px dashed var(--line); padding-top: 20px; }

.form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 12px; border-top: 1px solid var(--line); }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 28px; border-radius: 999px; font-size: 14.5px; font-weight: 600; transition: all 150ms ease; cursor: pointer; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); border: none; }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; }
</style>
