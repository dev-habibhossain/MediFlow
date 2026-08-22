<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface DepartmentItem {
    id: number
    name: string
}

interface UserItem {
    id: number
    name: string
    email: string
    phone?: string
    avatar?: string
    role?: string
    joined?: string
}

const props = withDefaults(
    defineProps<{
        departments?: DepartmentItem[]
        eligibleUsers?: UserItem[]
        selectedUserId?: number | null
    }>(),
    {
        departments: () => [],
        eligibleUsers: () => [],
        selectedUserId: null,
    }
)

const avatarPreviewUrl = ref<string | null>(null)
const userSearchQuery = ref<string>('')

const initialUserId = props.selectedUserId || (props.eligibleUsers && props.eligibleUsers.length > 0 ? props.eligibleUsers[0].id : '')

const form = useForm({
    user_id: initialUserId,
    license_number: '',
    department_id: '',
    qualifications: '',
    experience_years: 5,
    bio: '',
    consultation_fee: 120,
    room_number: 'Suite 402, North Wing',
    status: 'active',
    avatar: null as File | null,
})

const filteredUsers = computed(() => {
    const q = userSearchQuery.value.toLowerCase().trim()
    if (!q) return props.eligibleUsers
    return props.eligibleUsers.filter(
        (user) =>
            user.name.toLowerCase().includes(q) ||
            user.email.toLowerCase().includes(q) ||
            (user.phone && user.phone.toLowerCase().includes(q))
    )
})

const selectedUser = computed(() => {
    if (!form.user_id) return null
    return props.eligibleUsers.find((u) => u.id === Number(form.user_id)) ?? null
})

function selectUser(id: number) {
    form.user_id = id
}

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
    <Head title="Promote Registered User to Doctor — Admin Portal" />

    <!-- TOP NAV BACK BUTTON -->
    <div class="top-left-nav mb-6">
        <Link href="/admin/doctors" class="back-btn">← Back to Doctors Registry</Link>
    </div>

    <!-- HEADER BANNER CARD -->
    <div class="page-header-card mb-6">
        <div>
            <span class="ref-badge">Physician Promotion Matrix</span>
            <h1>Promote Registered Patient to Doctor</h1>
            <p class="text-xs text-[var(--ink-muted)] mt-1">Select an existing patient account and assign official medical department credentials.</p>
        </div>
    </div>

    <!-- NO ELIGIBLE USERS WARNING -->
    <div v-if="!eligibleUsers || eligibleUsers.length === 0" class="card-shell p-10 text-center mb-6">
        <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center mx-auto mb-3 text-xl font-bold">!</div>
        <h3 class="text-base font-extrabold text-[var(--forest)] mb-1">No Patient Accounts Available for Promotion</h3>
        <p class="text-xs text-[var(--ink-muted)] max-w-md mx-auto mb-6">All registered users are already onboarded as doctors, or no patient accounts currently exist.</p>
        <Link href="/admin/users" class="btn-primary inline-flex">View Users Directory</Link>
    </div>

    <!-- MAIN PROMOTION FORM CARD -->
    <div v-else class="form-card">
        <form @submit.prevent="submitForm">
            <!-- STEP 1: SELECT CANDIDATE USER -->
            <div class="card-title justify-between">
                <div class="flex items-center gap-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" />
                    </svg>
                    Step 1: Select Candidate Patient Account
                </div>
                <span class="text-xs font-semibold text-[var(--ink-muted)]">{{ eligibleUsers.length }} Candidates Available</span>
            </div>

            <!-- SEARCH BAR & FILTER -->
            <div class="candidate-search-box mb-4">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="search-icon">
                    <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input v-model="userSearchQuery" type="text" class="candidate-search-input" placeholder="Search candidate by name, email or phone..." />
            </div>

            <!-- CANDIDATES GRID -->
            <div class="candidates-grid mb-6">
                <div
                    v-for="user in filteredUsers"
                    :key="user.id"
                    class="candidate-card"
                    :class="{ selected: Number(form.user_id) === user.id }"
                    @click="selectUser(user.id)"
                >
                    <div class="candidate-header">
                        <div class="candidate-avatar">
                            <img v-if="user.avatar" :src="user.avatar" :alt="user.name" />
                            <span v-else>{{ user.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <div class="radio-indicator" :class="{ checked: Number(form.user_id) === user.id }">
                            <svg v-if="Number(form.user_id) === user.id" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                    </div>

                    <div class="candidate-info">
                        <h4>{{ user.name }}</h4>
                        <p class="email">{{ user.email }}</p>
                        <div class="candidate-tags">
                            <span class="phone-tag">📞 {{ user.phone }}</span>
                            <span class="role-badge">Patient</span>
                        </div>
                    </div>
                </div>

                <div v-if="filteredUsers.length === 0" class="col-span-full p-8 text-center bg-[var(--cream)] border border-[var(--line)] rounded-xl text-xs text-[var(--ink-muted)]">
                    No matching candidate users found for "{{ userSearchQuery }}".
                </div>
            </div>

            <span v-if="form.errors.user_id" class="text-xs text-red-600 font-semibold mb-6 block">{{ form.errors.user_id }}</span>

            <!-- SELECTED USER CONFIRMATION BAR -->
            <div v-if="selectedUser" class="selected-banner mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[var(--forest)] text-white font-bold flex items-center justify-center text-sm flex-shrink-0">
                        ✓
                    </div>
                    <div>
                        <div class="text-xs font-bold text-[var(--forest)] uppercase tracking-wider">Candidate Selected</div>
                        <div class="text-sm font-extrabold text-[var(--ink)]">{{ selectedUser.name }} ({{ selectedUser.email }})</div>
                    </div>
                </div>
                <span class="text-xs font-mono bg-white px-3 py-1 rounded-md border border-[var(--line)] text-[var(--ink-muted)]">User ID: #{{ selectedUser.id }}</span>
            </div>

            <!-- OPTIONAL HEADSHOT UPLOAD -->
            <div class="avatar-section">
                <div class="avatar-preview-lg">
                    <img v-if="avatarPreviewUrl || (selectedUser && selectedUser.avatar)" :src="avatarPreviewUrl || selectedUser?.avatar" alt="Preview" class="w-full h-full rounded-full object-cover" />
                    <span v-else>DR</span>
                </div>
                <div class="avatar-actions">
                    <h4>Doctor Headshot Photo (Optional)</h4>
                    <p>Upload a clear professional headshot (JPG/PNG, max 3MB).</p>
                    <input type="file" class="file-input-custom" accept="image/*" @change="handleAvatarChange" />
                </div>
            </div>

            <!-- STEP 2: ASSIGN MEDICAL CREDENTIALS -->
            <div class="card-title mt-8">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                </svg>
                Step 2: Assign Medical Credentials & Department
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="licenseNo">Medical License Number <span>*</span></label>
                    <input id="licenseNo" v-model="form.license_number" type="text" class="form-control font-mono" required placeholder="e.g. MD-94810" />
                    <span v-if="form.errors.license_number" class="text-xs text-red-600 font-semibold mt-1 block">{{ form.errors.license_number }}</span>
                </div>

                <div class="form-group">
                    <label for="department">Assigned Department <span>*</span></label>
                    <select id="department" v-model="form.department_id" class="form-control" required>
                        <option value="" disabled>Select Department</option>
                        <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                            {{ dept.name }}
                        </option>
                    </select>
                    <span v-if="form.errors.department_id" class="text-xs text-red-600 font-semibold mt-1 block">{{ form.errors.department_id }}</span>
                </div>

                <div class="form-group">
                    <label for="degrees">Qualifications & Specialization <span>*</span></label>
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

                <div class="subhead">Practice Settings & Initial Status</div>

                <div class="form-group">
                    <label for="consultFee">Consultation Fee ($ USD) <span>*</span></label>
                    <input id="consultFee" v-model="form.consultation_fee" type="number" class="form-control" min="0" required placeholder="e.g. 120" />
                </div>

                <div class="form-group">
                    <label for="roomNo">Assigned Clinic Suite / Room</label>
                    <input id="roomNo" v-model="form.room_number" type="text" class="form-control" placeholder="e.g. Suite 402, North Wing" />
                </div>

                <div class="form-group full-width">
                    <label for="accountStatus">Doctor Account Status <span>*</span></label>
                    <select id="accountStatus" v-model="form.status" class="form-control" required>
                        <option value="active">Active (Available for Immediate Appointments)</option>
                        <option value="leave">On Leave / Temporary Pause</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- BUTTON ROW -->
            <div class="form-actions">
                <Link href="/admin/doctors" class="btn btn-outline">Cancel</Link>
                <button type="submit" :disabled="form.processing || !form.user_id" class="btn btn-primary">
                    {{ form.processing ? 'Promoting User...' : 'Promote User & Save Doctor Credentials' }}
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
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }
.card-title svg { width: 18px; height: 18px; color: var(--forest); }

/* CANDIDATE SELECTOR GRID STYLES */
.candidate-search-box { position: relative; width: 100%; }
.candidate-search-box .search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); }
.candidate-search-input { width: 100%; height: 42px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px 0 40px; font-size: 13.5px; color: var(--ink); outline: none; transition: border-color 150ms ease; }
.candidate-search-input:focus { border-color: var(--forest); background: var(--card); }

.candidates-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; max-height: 340px; overflow-y: auto; padding: 4px; }
@media (max-width: 1024px) { .candidates-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .candidates-grid { grid-template-columns: 1fr; } }

.candidate-card { background: var(--cream); border: 2px solid var(--line); border-radius: var(--radius-lg); padding: 14px; cursor: pointer; transition: all 150ms ease; display: flex; flex-direction: column; justify-content: space-between; }
.candidate-card:hover { border-color: var(--forest); transform: translateY(-1px); box-shadow: var(--shadow-sm); }
.candidate-card.selected { border-color: var(--forest); background: #F0FDF4; box-shadow: 0 4px 12px rgba(22, 101, 52, 0.08); }

.candidate-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.candidate-avatar { width: 42px; height: 42px; border-radius: 50%; background: var(--forest); color: #fff; font-weight: 800; font-size: 16px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
.candidate-avatar img { width: 100%; height: 100%; object-fit: cover; }

.radio-indicator { width: 22px; height: 22px; border-radius: 50%; border: 2px solid var(--line); background: #fff; display: flex; align-items: center; justify-content: center; color: #fff; transition: all 150ms ease; }
.radio-indicator.checked { background: var(--forest); border-color: var(--forest); }
.radio-indicator svg { width: 12px; height: 12px; }

.candidate-info h4 { font-size: 14px; font-weight: 800; color: var(--forest); margin: 0 0 2px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.candidate-info .email { font-size: 11.5px; color: var(--ink-muted); margin: 0 0 8px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.candidate-tags { display: flex; align-items: center; justify-content: space-between; gap: 6px; }
.phone-tag { font-size: 11px; font-family: var(--font-mono); color: var(--ink-muted); }
.role-badge { font-size: 10px; font-weight: 700; text-transform: uppercase; background: #E0F2FE; color: #0369A1; padding: 2px 6px; border-radius: 4px; }

.selected-banner { background: #DCFCE7; border: 1.5px solid #86EFAC; border-radius: var(--radius-lg); padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; }

.avatar-section { display: flex; align-items: center; gap: 20px; margin-bottom: 24px; background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 16px 20px; }
.avatar-preview-lg { width: 60px; height: 60px; border-radius: 50%; border: 2px solid var(--card); box-shadow: var(--shadow-sm); flex-shrink: 0; background: var(--lime-soft); color: var(--lime-text); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 20px; overflow: hidden; }
.avatar-actions h4 { font-size: 14px; font-weight: 800; color: var(--forest); margin-bottom: 2px; }
.avatar-actions p { font-size: 12px; color: var(--ink-muted); margin-bottom: 6px; }
.file-input-custom { font-size: 12px; }

.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 28px; }
@media (max-width: 680px) { .form-grid { grid-template-columns: 1fr; } }
.full-width { grid-column: 1 / -1; }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-group label span { color: #DC2626; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); transition: border-color 150ms ease, background-color 150ms ease; outline: none; }
.form-control:focus { border-color: var(--forest); background: var(--card); }
textarea.form-control { height: auto; min-height: 90px; padding: 12px 16px; resize: vertical; }

.subhead { font-size: 13.5px; font-weight: 800; color: var(--forest); text-transform: uppercase; letter-spacing: 0.04em; grid-column: 1 / -1; border-top: 1px dashed var(--line); padding-top: 20px; margin-top: 8px; }

.form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid var(--line); }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 46px; padding: 0 28px; border-radius: 999px; font-size: 14px; font-weight: 700; transition: all 150ms ease; cursor: pointer; text-decoration: none; border: 0; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }
</style>
