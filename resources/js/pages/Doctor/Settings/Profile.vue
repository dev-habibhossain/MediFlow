<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = ref({
    name: 'Dr. Sarah Jenkins',
    title: 'MD, FACC — Senior Cardiologist',
    department: 'Cardiology',
    licenseNumber: 'MD-7890123',
    experienceYears: '12',
    consultationFee: '120.00',
    education: 'Harvard Medical School (Class of 2012), Residency at Massachusetts General Hospital.',
    specialties: 'Hypertension, Preventive Cardiology, Lipid Disorders, Electrocardiography',
    bio: 'Board-certified cardiologist specializing in preventive cardiovascular care and non-invasive diagnostic hypertension management with over 12 years of clinical excellence.',
    avatarUrl: 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=300',
})

function saveProfile() {
    alert('Doctor Profile & Directory listing saved successfully!')
}

function triggerUpload() {
    alert('Upload Photo triggered!')
}
</script>

<template>
    <Head title="Doctor Profile Settings" />

    <!-- PAGE HEADER -->
    <div class="page-title-row">
        <div>
            <h2>Doctor Profile & Directory Settings</h2>
            <p>Manage your professional credentials, biography, and public physician profile preview</p>
        </div>
    </div>

    <!-- MAIN PROFILE GRID -->
    <div class="profile-grid">
        <!-- LEFT: FORM CARDS -->
        <div class="left-col">
            <!-- PERSONAL & PROFESSIONAL DETAILS -->
            <div class="form-card">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        Professional Profile Information
                    </h3>
                </div>

                <form @submit.prevent="saveProfile">
                    <!-- AVATAR UPLOAD SECTION -->
                    <div class="avatar-upload-block">
                        <img :src="form.avatarUrl" alt="Avatar preview" class="profile-avatar-preview" />
                        <div>
                            <b>Profile Headshot Photo</b>
                            <p>Upload a high-resolution professional portrait (PNG/JPG up to 5MB).</p>
                            <button type="button" class="btn-sm btn-outline" @click="triggerUpload">Change Photo</button>
                        </div>
                    </div>

                    <div class="form-grid-dual">
                        <div class="form-group">
                            <label>Full Name & Prefix <span>*</span></label>
                            <input v-model="form.name" type="text" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label>Professional Title & Credentials <span>*</span></label>
                            <input v-model="form.title" type="text" class="form-control" required />
                        </div>
                    </div>

                    <div class="form-grid-dual" style="margin-top: 16px;">
                        <div class="form-group">
                            <label>Department Affiliation <span>*</span></label>
                            <select v-model="form.department" class="form-control" required>
                                <option value="Cardiology">Cardiology Department</option>
                                <option value="Neurology">Neurology Department</option>
                                <option value="Pediatrics">Pediatrics Department</option>
                                <option value="Orthopedics">Orthopedics Department</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>License / Registration Number <span>*</span></label>
                            <input v-model="form.licenseNumber" type="text" class="form-control mono" required />
                        </div>
                    </div>

                    <div class="form-grid-dual" style="margin-top: 16px;">
                        <div class="form-group">
                            <label>Years of Clinical Practice</label>
                            <input v-model="form.experienceYears" type="number" class="form-control mono" />
                        </div>

                        <div class="form-group">
                            <label>Consultation Standard Fee ($)</label>
                            <input v-model="form.consultationFee" type="text" class="form-control mono" />
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 16px;">
                        <label>Specialization Keywords (Comma-separated)</label>
                        <input v-model="form.specialties" type="text" class="form-control" />
                    </div>

                    <div class="form-group" style="margin-top: 16px;">
                        <label>Education & Training Background</label>
                        <textarea v-model="form.education" class="form-control textarea" rows="2"></textarea>
                    </div>

                    <div class="form-group" style="margin-top: 16px;">
                        <label>Professional Biography</label>
                        <textarea v-model="form.bio" class="form-control textarea" rows="4"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Doctor Profile</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIGHT: LIVE DIRECTORY PREVIEW -->
        <div class="right-col">
            <div class="preview-card">
                <div class="preview-header">
                    <span class="preview-badge">Live Public Preview</span>
                </div>

                <div class="public-doctor-card">
                    <img :src="form.avatarUrl" alt="Doctor" class="public-avatar" />
                    <h4>{{ form.name }}</h4>
                    <span class="public-title">{{ form.title }}</span>
                    <span class="public-dept">{{ form.department }} Department</span>

                    <div class="public-rating">
                        ★★★★★ <span>4.9 (98 reviews)</span>
                    </div>

                    <div class="public-fee-row">
                        <span>Consultation Fee:</span>
                        <b>${{ form.consultationFee }}</b>
                    </div>

                    <p class="public-bio">{{ form.bio }}</p>

                    <div class="public-tags">
                        <span v-for="tag in form.specialties.split(',')" :key="tag" class="tag-pill">
                            {{ tag.trim() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.page-title-row { margin-bottom: 24px; }
.page-title-row h2 { font-size: 22px; font-weight: 800; color: var(--forest); }
.page-title-row p { font-size: 13px; color: var(--ink-muted); }

.profile-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    align-items: start;
}
@media (max-width: 992px) { .profile-grid { grid-template-columns: 1fr; } }

.left-col, .right-col { display: flex; flex-direction: column; gap: 20px; }

.form-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 32px;
    box-shadow: var(--shadow-card);
}

.card-header {
    border-bottom: 1px solid var(--line);
    padding-bottom: 16px;
    margin-bottom: 24px;
}
.card-header h3 { font-size: 18px; font-weight: 800; color: var(--forest); display: flex; align-items: center; gap: 10px; }

.avatar-upload-block {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 16px;
    background: var(--cream);
    border-radius: var(--radius-lg);
    border: 1px solid var(--line);
    margin-bottom: 24px;
}

.profile-avatar-preview {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--forest);
}

.avatar-upload-block b { display: block; font-size: 14px; color: var(--forest); }
.avatar-upload-block p { font-size: 12px; color: var(--ink-muted); margin: 2px 0 8px 0; }

.form-grid-dual {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
@media (max-width: 768px) { .form-grid-dual { grid-template-columns: 1fr; } }

.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); }
.form-group label span { color: #DC2626; }

.form-control {
    height: 44px;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--cream);
    padding: 0 14px;
    font-size: 14px;
    color: var(--ink);
}
.form-control.mono { font-family: var(--font-mono); }
.form-control.textarea { height: auto; padding: 12px 14px; resize: vertical; }

.form-actions { display: flex; justify-content: flex-end; border-top: 1px solid var(--line); padding-top: 20px; margin-top: 24px; }

.preview-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 24px;
    box-shadow: var(--shadow-card);
}

.preview-header { margin-bottom: 16px; text-align: center; }
.preview-badge { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; background: var(--lime-soft); color: var(--lime-text); padding: 4px 12px; border-radius: 999px; border: 1px solid #d2e85a; }

.public-doctor-card { text-align: center; display: flex; flex-direction: column; align-items: center; }
.public-avatar { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; margin-bottom: 12px; border: 3px solid var(--forest); }
.public-doctor-card h4 { font-size: 18px; font-weight: 800; color: var(--forest); margin: 0 0 2px 0; }
.public-title { font-size: 13px; font-weight: 600; color: var(--ink-muted); display: block; }
.public-dept { font-size: 12px; font-weight: 700; color: var(--forest); background: var(--cream); padding: 2px 10px; border-radius: 999px; display: inline-block; margin: 6px 0 12px 0; }

.public-rating { color: #F59E0B; font-size: 14px; letter-spacing: 2px; margin-bottom: 12px; }
.public-rating span { color: var(--ink-muted); font-size: 12px; font-weight: 600; letter-spacing: normal; }

.public-fee-row { display: flex; gap: 8px; font-size: 13px; margin-bottom: 12px; border-top: 1px dashed var(--line); border-bottom: 1px dashed var(--line); padding: 8px 0; width: 100%; justify-content: center; }
.public-fee-row b { font-family: var(--font-mono); color: var(--forest); }

.public-bio { font-size: 12.5px; color: var(--ink); line-height: 1.4; margin-bottom: 14px; text-align: left; }
.public-tags { display: flex; gap: 6px; flex-wrap: wrap; justify-content: center; }
.tag-pill { font-size: 11px; font-weight: 600; background: var(--cream); border: 1px solid var(--line); padding: 3px 8px; border-radius: 999px; }

.btn { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 24px; border-radius: 999px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 150ms ease; }
.btn-sm { display: inline-flex; align-items: center; justify-content: center; padding: 5px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; cursor: pointer; }
.btn-outline { background: transparent; color: var(--forest); border: 1.5px solid var(--line); }
.btn-outline:hover { background: var(--cream); border-color: var(--forest); }
.btn-primary { background: var(--forest); color: white; border: 1.5px solid var(--forest); }
.btn-primary:hover { background: var(--forest-2); }
</style>
