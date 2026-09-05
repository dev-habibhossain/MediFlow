<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import PatientLayout from '@/layouts/PatientLayout.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

interface Department {
    id: number;
    name: string;
    slug: string;
}

interface Doctor {
    id: number;
    specialization: string;
    qualifications?: string;
    qualification?: string;
    years_of_experience?: number;
    experience_years?: number;
    bio?: string;
    consultation_fee: string | number;
    license_number: string;
    reviews_avg_rating: number | null;
    reviews_count: number;
    user: {
        id: number;
        name: string;
        avatar_path?: string;
        avatar_url?: string;
    };
    department?: Department | null;
}

interface PaginatedDoctors {
    data: Doctor[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
}

const props = defineProps<{
    doctors: PaginatedDoctors;
    departments: Department[];
    filters: {
        search: string;
        department: string;
    };
}>();

const page = usePage();
const isPatient = computed(() => {
    const role = (
        page.props.auth?.user as { role?: string } | undefined
    )?.role?.toLowerCase();

    return role === 'patient';
});
const activeLayout = computed(() =>
    isPatient.value ? PatientLayout : PublicLayout,
);

const search = ref(props.filters.search || '');
const selectedDept = ref(props.filters.department || '');
const sortBy = ref('featured');

// Quick Modal State
const selectedDoctorForModal = ref<Doctor | null>(null);
const isModalOpen = ref(false);

function openModal(doc: Doctor) {
    selectedDoctorForModal.value = doc;
    isModalOpen.value = true;
}

function closeModal() {
    isModalOpen.value = false;
    selectedDoctorForModal.value = null;
}

let searchDebounceTimer: ReturnType<typeof setTimeout> | null = null;
function onSearchInput() {
    if (searchDebounceTimer) {
        clearTimeout(searchDebounceTimer);
    }

    searchDebounceTimer = setTimeout(() => {
        applyFilters();
    }, 300);
}

function applyFilters() {
    router.get(
        '/doctors',
        {
            search: search.value || undefined,
            department: selectedDept.value || undefined,
        },
        { preserveState: true, replace: true },
    );
}

function setDepartment(slug: string) {
    selectedDept.value = slug;
    applyFilters();
}

function getAvatarUrl(doc: Doctor): string | null {
    if (doc.user.avatar_url) {
        return doc.user.avatar_url;
    }

    if (doc.user.avatar_path) {
        if (
            doc.user.avatar_path.startsWith('http://') ||
            doc.user.avatar_path.startsWith('https://')
        ) {
            return doc.user.avatar_path;
        }

        return `/storage/${doc.user.avatar_path}`;
    }

    return null;
}

function getInitials(name?: string): string {
    if (!name) {
        return 'DR';
    }

    return name
        .replace(/^Dr\.\s*/i, '')
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
}

// Client side sorting for current page doctors
const sortedDoctors = computed(() => {
    const list = [...props.doctors.data];

    if (sortBy.value === 'fee-low') {
        return list.sort(
            (a, b) => Number(a.consultation_fee) - Number(b.consultation_fee),
        );
    }

    if (sortBy.value === 'fee-high') {
        return list.sort(
            (a, b) => Number(b.consultation_fee) - Number(a.consultation_fee),
        );
    }

    if (sortBy.value === 'exp') {
        return list.sort((a, b) => {
            const expA = a.years_of_experience ?? a.experience_years ?? 10;
            const expB = b.years_of_experience ?? b.experience_years ?? 10;

            return expB - expA;
        });
    }

    return list;
});
</script>

<template>
    <component :is="activeLayout" title="Doctors Directory — MediFlow">
        <main class="py-6">
            <div class="wrap">
                <!-- PATIENT PORTAL HEADER (When inside Patient Dashboard) -->
                <div v-if="isPatient" class="patient-directory-header mb-8">
                    <div class="header-left">
                        <span class="pill mb-2">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                            >
                                <circle cx="11" cy="11" r="7" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                            Verified Hospital Specialists
                        </span>
                        <h1 class="portal-heading">Doctors Directory</h1>
                        <p class="portal-subheading">
                            Search our credentialed physicians, inspect verified
                            reviews & consultation fees, and schedule an
                            appointment directly.
                        </p>
                    </div>
                    <Link
                        href="/patient/dashboard"
                        class="btn btn-outline btn-sm"
                    >
                        ← Back to Overview
                    </Link>
                </div>

                <!-- PUBLIC GUEST HERO -->
                <section v-else class="about-hero">
                    <span class="pill mb-4">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.5"
                            stroke-linecap="round"
                        >
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Board-Certified Physicians
                    </span>
                    <h1>Find Your <b>Specialist Doctor</b></h1>
                    <p class="lead">
                        Browse our team of credentialed doctors. See real-time
                        availability, clear consultation fees, and book directly
                        online without waiting for a phone callback.
                    </p>
                </section>

                <!-- SEARCH AND FILTER CONTROL BAR -->
                <div class="filter-bar">
                    <div class="filter-top-row">
                        <!-- Search Input -->
                        <div class="search-input-wrap">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <circle cx="11" cy="11" r="7" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Search by doctor name, specialty, or condition..."
                                aria-label="Search doctors"
                                @input="onSearchInput"
                            />
                        </div>

                        <!-- Sort Select -->
                        <div class="select-wrap">
                            <select v-model="sortBy" aria-label="Sort doctors">
                                <option value="featured">
                                    Sort by: Featured
                                </option>
                                <option value="exp">Most Experienced</option>
                                <option value="fee-low">
                                    Fee: Low to High
                                </option>
                                <option value="fee-high">
                                    Fee: High to Low
                                </option>
                            </select>
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </div>
                    </div>

                    <!-- SPECIALTY SCROLL FILTER -->
                    <div class="filter-tags-scroll">
                        <button
                            :class="[
                                'filter-btn',
                                selectedDept === '' ? 'active' : '',
                            ]"
                            @click="setDepartment('')"
                        >
                            All Specialties
                        </button>
                        <button
                            v-for="dept in departments"
                            :key="dept.id"
                            :class="[
                                'filter-btn',
                                selectedDept === dept.slug ? 'active' : '',
                            ]"
                            @click="setDepartment(dept.slug)"
                        >
                            {{ dept.name }}
                        </button>
                    </div>
                </div>

                <!-- DOCTORS GRID -->
                <div v-if="sortedDoctors.length > 0" class="doctors-grid">
                    <div
                        v-for="(doc, idx) in sortedDoctors"
                        :key="doc.id"
                        class="doctor-card-full"
                    >
                        <div>
                            <!-- Photo / Avatar Wrap -->
                            <div class="doc-photo-wrap">
                                <span class="status-indicator doc-status-pin">
                                    <span class="status-dot"></span> Available
                                    {{ idx % 2 === 0 ? 'Today' : 'Tomorrow' }}
                                </span>

                                <img
                                    v-if="getAvatarUrl(doc)"
                                    :src="getAvatarUrl(doc)!"
                                    :alt="doc.user.name"
                                    class="doc-img"
                                />
                                <div v-else class="avatar-ph">
                                    {{ getInitials(doc.user.name) }}
                                </div>

                                <span class="doc-dept-badge"
                                    ><b>{{
                                        doc.department?.name || 'Specialist'
                                    }}</b></span
                                >
                            </div>

                            <!-- Info -->
                            <div class="doc-info">
                                <h3>
                                    <b>{{ doc.user.name }}</b>
                                </h3>
                                <p class="doc-exp">
                                    {{ doc.specialization }} ·
                                    <b
                                        >{{
                                            doc.years_of_experience ??
                                            doc.experience_years ??
                                            10
                                        }}
                                        yrs exp</b
                                    >
                                </p>
                            </div>

                            <!-- Rating & Fee Row -->
                            <div class="doc-meta-row">
                                <div class="doc-rating">
                                    <b>{{
                                        doc.reviews_avg_rating
                                            ? Number(
                                                  doc.reviews_avg_rating,
                                              ).toFixed(1)
                                            : 'New'
                                    }}</b>
                                    <span
                                        v-if="doc.reviews_avg_rating"
                                        class="stars"
                                        >★★★★★</span
                                    >
                                    <span class="review-count"
                                        >({{
                                            doc.reviews_count || 0
                                        }}
                                        reviews)</span
                                    >
                                </div>
                                <div class="doc-fee">
                                    <b>${{ doc.consultation_fee }}</b>
                                    <small>/ visit</small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="doc-actions">
                            <button
                                @click="openModal(doc)"
                                class="btn btn-outline btn-sm"
                                type="button"
                                title="Quick doctor overview"
                            >
                                Quick View
                            </button>
                            <Link
                                :href="`/doctors/${doc.license_number}`"
                                class="btn btn-outline btn-sm"
                            >
                                Profile
                            </Link>
                            <Link
                                :href="`/appointments/book/${doc.license_number}`"
                                class="btn btn-primary btn-sm"
                            >
                                Book
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- NO RESULTS -->
                <div v-else class="no-results">
                    <h4>No matching <b>doctors</b> found</h4>
                    <p>
                        Try clearing your search query or choosing another
                        department filter.
                    </p>
                    <button
                        @click="
                            setDepartment('');
                            search = '';
                            applyFilters();
                        "
                        class="btn btn-outline btn-sm mt-4"
                    >
                        Reset Filters
                    </button>
                </div>

                <!-- PAGINATION -->
                <div
                    v-if="doctors.links.length > 3"
                    class="my-12 flex justify-center gap-2"
                >
                    <template v-for="(link, i) in doctors.links" :key="i">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                'rounded-full px-4 py-2 text-xs font-semibold',
                                link.active
                                    ? 'bg-[#16301F] text-white'
                                    : 'border border-[#E7E3D3] bg-white text-[#62655A]',
                            ]"
                            v-html="link.label"
                        />
                    </template>
                </div>

                <!-- CLOSING CTA BANNER (Guests only) -->
                <div v-if="!isPatient" class="closing mb-16">
                    <div>
                        <h2>
                            Need help selecting the <b>right specialist</b>?
                        </h2>
                        <p>
                            Our patient coordination team is available to assist
                            you in matching with the best physician for your
                            needs.
                        </p>
                    </div>
                    <Link href="/contact" class="btn btn-primary"
                        >Contact Assistant Desk</Link
                    >
                </div>
            </div>

            <!-- DOCTOR QUICK PROFILE MODAL -->
            <Teleport to="body">
                <div
                    v-if="isModalOpen && selectedDoctorForModal"
                    class="modal-overlay open"
                    @click.self="closeModal"
                >
                    <div class="modal-card">
                        <button
                            @click="closeModal"
                            class="modal-close"
                            aria-label="Close modal"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                width="18"
                                height="18"
                            >
                                <path d="M18 6L6 18M6 6l12 12" />
                            </svg>
                        </button>
                        <div class="modal-head">
                            <img
                                v-if="getAvatarUrl(selectedDoctorForModal)"
                                :src="getAvatarUrl(selectedDoctorForModal)!"
                                :alt="selectedDoctorForModal.user.name"
                                class="modal-avatar-img"
                            />
                            <div v-else class="modal-avatar">
                                {{
                                    getInitials(
                                        selectedDoctorForModal.user.name,
                                    )
                                }}
                            </div>
                            <div>
                                <h3>
                                    <b>{{
                                        selectedDoctorForModal.user.name
                                    }}</b>
                                </h3>
                                <span class="pill modal-spec-pill">{{
                                    selectedDoctorForModal.specialization
                                }}</span>
                            </div>
                        </div>
                        <div class="modal-body">
                            <h4><b>About the Doctor</b></h4>
                            <p>
                                {{
                                    selectedDoctorForModal.bio ||
                                    `Dr. ${selectedDoctorForModal.user.name} is a board-certified specialist with extensive experience in ${selectedDoctorForModal.department?.name || 'specialized'} clinical care.`
                                }}
                            </p>

                            <h4><b>Consultation & Location</b></h4>
                            <p>
                                120 Harbor Ave, Suite 300 · Consultation Fee:
                                <b
                                    >${{
                                        selectedDoctorForModal.consultation_fee
                                    }}</b
                                >
                            </p>
                        </div>
                        <div
                            class="modal-footer"
                            style="display: flex; gap: 12px"
                        >
                            <Link
                                :href="`/doctors/${selectedDoctorForModal.license_number}`"
                                class="btn btn-outline btn-sm"
                                style="flex: 1"
                            >
                                View Full Profile
                            </Link>
                            <Link
                                :href="`/appointments/book/${selectedDoctorForModal.license_number}`"
                                class="btn btn-primary btn-sm"
                                style="flex: 1"
                            >
                                Book Appointment →
                            </Link>
                        </div>
                    </div>
                </div>
            </Teleport>
        </main>
    </component>
</template>

<style scoped>
.patient-directory-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    background: var(--card, #fff);
    border: 1px solid var(--line, #e7e3d3);
    border-radius: 20px;
    padding: 24px 28px;
    box-shadow: 0 1px 3px rgba(22, 24, 15, 0.05);
}
.portal-heading {
    font-size: 24px;
    font-weight: 800;
    color: #16301f;
    margin: 4px 0 6px 0;
    letter-spacing: -0.01em;
}
.portal-subheading {
    font-size: 14px;
    color: #62655a;
    margin: 0;
    max-width: 640px;
    line-height: 1.45;
}
.modal-avatar-img {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--lime, #ddf15c);
}

.wrap {
    max-width: 1320px;
    margin-inline: auto;
    padding-inline: 32px;
    position: relative;
}
@media (max-width: 640px) {
    .wrap {
        padding-inline: 20px;
    }
}

b {
    font-weight: 700;
    color: inherit;
}

.pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ddf15c;
    color: #3b4a12;
    font-size: 13px;
    font-weight: 600;
    padding: 7px 16px 7px 12px;
    border-radius: 999px;
}
.pill svg {
    width: 14px;
    height: 14px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 54px;
    padding: 0 28px;
    border-radius: 999px;
    font-size: 15.5px;
    font-weight: 600;
    transition:
        transform 150ms ease,
        background-color 150ms ease,
        box-shadow 150ms ease;
    text-decoration: none;
    border: 0;
    cursor: pointer;
}
.btn:active {
    transform: scale(0.97);
}
.btn-primary {
    background: #16301f;
    color: #fff;
    box-shadow:
        0 1px 2px rgba(22, 24, 15, 0.04),
        0 8px 24px rgba(22, 24, 15, 0.06);
}
.btn-primary:hover {
    background: #1e4029;
}
.btn-outline {
    background: transparent;
    color: #16180f;
    border: 1.5px solid rgba(22, 24, 15, 0.16);
}
.btn-outline:hover {
    border-color: #16180f;
}
.btn-sm {
    height: 44px;
    padding: 0 20px;
    font-size: 13.5px;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #3b4a12;
    background: #eef7c4;
    padding: 4px 12px 4px 10px;
    border-radius: 999px;
    border: 1px solid rgba(59, 74, 18, 0.12);
}
.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e;
    position: relative;
    flex-shrink: 0;
}
.status-dot::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(34, 197, 94, 0.4);
    animation: pulse-dot 2s infinite ease-in-out;
}

@keyframes pulse-dot {
    0% {
        transform: scale(0.8);
        opacity: 0.8;
    }
    50% {
        transform: scale(1.4);
        opacity: 0;
    }
    100% {
        transform: scale(0.8);
        opacity: 0;
    }
}

.about-hero {
    padding: 40px 0 24px;
}
.about-hero h1 {
    font-size: clamp(2.2rem, 1.6rem + 2vw, 3.2rem);
    font-weight: 800;
    letter-spacing: -0.02em;
    margin-bottom: 14px;
    line-height: 1.15;
    color: #16301f;
}
.about-hero p.lead {
    font-size: 17px;
    color: #62655a;
    max-width: 62ch;
    line-height: 1.6;
}

.filter-bar {
    background: #ffffff;
    border: 1px solid #e7e3d3;
    border-radius: 24px;
    padding: 20px;
    margin-bottom: 40px;
    box-shadow:
        0 1px 2px rgba(22, 24, 15, 0.04),
        0 8px 24px rgba(22, 24, 15, 0.06);
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.filter-top-row {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: center;
    justify-content: space-between;
}
.search-input-wrap {
    position: relative;
    flex: 1;
    min-width: 280px;
}
.search-input-wrap svg {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: #62655a;
    pointer-events: none;
}
.search-input-wrap input {
    width: 100%;
    height: 48px;
    border-radius: 999px;
    border: 1px solid #e7e3d3;
    background: #f8f6ef;
    padding-left: 44px;
    padding-right: 16px;
    font-size: 14.5px;
    outline: none;
    transition: border-color 150ms ease;
}
.search-input-wrap input:focus {
    border-color: #16301f;
    background: #ffffff;
}

.select-wrap {
    position: relative;
    min-width: 200px;
}
.select-wrap select {
    width: 100%;
    height: 48px;
    border-radius: 999px;
    border: 1px solid #e7e3d3;
    background: #f8f6ef;
    padding: 0 40px 0 18px;
    font-size: 14px;
    font-weight: 600;
    color: #16180f;
    appearance: none;
    cursor: pointer;
    outline: none;
}
.select-wrap svg {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    color: #62655a;
    pointer-events: none;
}

.filter-tags-scroll {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 4px;
    scrollbar-width: none;
}
.filter-tags-scroll::-webkit-scrollbar {
    display: none;
}
.filter-btn {
    padding: 8px 18px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    background: #f0eee3;
    color: #62655a;
    transition: all 150ms ease;
    flex-shrink: 0;
    border: 0;
    cursor: pointer;
}
.filter-btn:hover {
    background: rgba(22, 24, 15, 0.08);
    color: #16180f;
}
.filter-btn.active {
    background: #16301f;
    color: #fff;
}

.doctors-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 60px;
}
@media (max-width: 980px) {
    .doctors-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 600px) {
    .doctors-grid {
        grid-template-columns: 1fr;
    }
}

.doctor-card-full {
    background: #ffffff;
    border: 1px solid #e7e3d3;
    border-radius: 24px;
    padding: 24px;
    box-shadow:
        0 1px 2px rgba(22, 24, 15, 0.04),
        0 8px 24px rgba(22, 24, 15, 0.06);
    transition:
        box-shadow 150ms ease,
        transform 150ms ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
}
.doctor-card-full:hover {
    box-shadow:
        0 4px 10px rgba(22, 24, 15, 0.06),
        0 16px 36px rgba(22, 24, 15, 0.1);
    transform: translateY(-3px);
}

.doc-photo-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 4/3;
    border-radius: 16px;
    background: linear-gradient(150deg, #16301f, #3a6b4c);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    overflow: hidden;
}
.doc-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    border-radius: 16px;
}
.avatar-ph {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.18);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: 800;
}
.doc-status-pin {
    position: absolute;
    top: 12px;
    right: 12px;
}
.doc-dept-badge {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(6px);
    color: #16301f;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 999px;
}

.doc-info h3 {
    font-size: 19px;
    font-weight: 700;
    margin-bottom: 4px;
    color: #16301f;
}
.doc-exp {
    font-size: 13px;
    color: #62655a;
    margin-bottom: 12px;
}

.doc-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top: 1px solid #e7e3d3;
    border-bottom: 1px solid #e7e3d3;
    padding: 12px 0;
    margin-bottom: 20px;
    font-size: 13.5px;
}
.doc-rating {
    display: flex;
    align-items: center;
    gap: 4px;
    font-weight: 700;
    color: #16301f;
}
.doc-rating span.stars {
    color: #16301f;
    font-size: 12px;
}
.doc-rating span.review-count {
    color: #62655a;
    font-weight: 400;
}
.doc-fee {
    font-family: 'JetBrains Mono', monospace;
    font-weight: 600;
    color: #16301f;
    font-size: 15px;
}

.doc-actions {
    display: flex;
    gap: 10px;
}
.doc-actions .btn {
    flex: 1;
}

.no-results {
    text-align: center;
    padding: 60px 20px;
    background: #ffffff;
    border: 1px solid #e7e3d3;
    border-radius: 24px;
    margin-bottom: 40px;
}
.no-results h4 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 6px;
    color: #16301f;
}
.no-results p {
    font-size: 14px;
    color: #62655a;
}

.closing {
    background: #16301f;
    border-radius: 32px;
    padding: 52px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 28px;
    flex-wrap: wrap;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.closing::before {
    content: '';
    position: absolute;
    top: -80px;
    right: -60px;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    background: #ddf15c;
    opacity: 0.15;
    filter: blur(10px);
}
.closing h2 {
    font-size: 26px;
    font-weight: 800;
    letter-spacing: -0.015em;
    max-width: 24ch;
    position: relative;
}
.closing p {
    color: rgba(255, 255, 255, 0.65);
    font-size: 14px;
    margin-top: 6px;
    position: relative;
}
.closing .btn-primary {
    background: #ddf15c;
    color: #3b4a12;
    position: relative;
    border: 0;
}
.closing .btn-primary:hover {
    background: #ecf99c;
}

/* MODAL STYLES */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(22, 24, 15, 0.5);
    backdrop-filter: blur(4px);
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 200ms ease;
}
.modal-overlay.open {
    opacity: 1;
    pointer-events: auto;
}
.modal-card {
    background: #ffffff;
    border-radius: 32px;
    max-width: 540px;
    width: 100%;
    padding: 32px;
    box-shadow:
        0 4px 10px rgba(22, 24, 15, 0.06),
        0 16px 36px rgba(22, 24, 15, 0.1);
    position: relative;
}
.modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f0eee3;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #62655a;
    border: 0;
    cursor: pointer;
}
.modal-close:hover {
    background: #efede4;
    color: #16180f;
}
.modal-head {
    display: flex;
    gap: 20px;
    align-items: center;
    margin-bottom: 24px;
}
.modal-avatar {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: linear-gradient(135deg, #16301f, #3a6b4c);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    font-weight: 700;
    flex-shrink: 0;
}
.modal-spec-pill {
    font-size: 11.5px;
    padding: 4px 12px;
    margin-top: 6px;
}
.modal-body h4 {
    font-size: 14.5px;
    font-weight: 700;
    margin-top: 16px;
    margin-bottom: 6px;
    color: #16301f;
}
.modal-body p {
    font-size: 13.5px;
    color: #62655a;
    line-height: 1.6;
}
.modal-footer {
    margin-top: 24px;
}
</style>
