<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

const props = defineProps<{
    q?: string
}>()

const searchQuery = ref(props.q || '')
const activeCategory = ref('all')

watch(
    () => props.q,
    (newVal) => {
        if (newVal !== undefined) {
            searchQuery.value = newVal
        }
    }
)

const searchDatabase = [
    // DOCTORS
    {
        id: 'doc-1',
        type: 'doctor',
        category: 'doctors',
        title: 'Dr. Sarah Jenkins',
        subtitle: 'Cardiology Specialist · MD, FACC',
        meta: 'Harbor Ave Clinic · 12 Yrs Exp · $120 Fee',
        rating: '4.9 ★ (128 reviews)',
        description: 'Board-certified cardiologist specializing in preventive heart health, hypertension, coronary disease management, and echocardiograms.',
        badge: 'Top Doctor',
        url: '/doctors/DOC-10293',
        actionLabel: 'Book Appointment',
        searchTokens: 'dr sarah jenkins cardiology heart doctor specialist physician harbor',
    },
    {
        id: 'doc-2',
        type: 'doctor',
        category: 'doctors',
        title: 'Dr. Marcus Vance',
        subtitle: 'Neurology Department Head · MD, PhD',
        meta: 'St. Jude Pavilion · 15 Yrs Exp · $140 Fee',
        rating: '4.8 ★ (94 reviews)',
        description: 'Expert neurologist treating stroke, migraines, epilepsy, and neuro-degenerative conditions with advanced clinical care.',
        badge: 'Specialist',
        url: '/doctors/DOC-20491',
        actionLabel: 'Book Appointment',
        searchTokens: 'dr marcus vance neurology brain nerve stroke head st jude',
    },
    {
        id: 'doc-3',
        type: 'doctor',
        category: 'doctors',
        title: 'Dr. Emily Watson',
        subtitle: 'Pediatrics Lead · MD, FAAP',
        meta: 'Main Hospital Annex · 9 Yrs Exp · $100 Fee',
        rating: '5.0 ★ (210 reviews)',
        description: 'Compassionate pediatric healthcare provider offering newborn checkups, immunizations, and child wellness programs.',
        badge: 'Top Rated',
        url: '/doctors/DOC-30112',
        actionLabel: 'Book Appointment',
        searchTokens: 'dr emily watson pediatrics child kids baby checkup doctor annex',
    },
    {
        id: 'doc-4',
        type: 'doctor',
        category: 'doctors',
        title: 'Dr. Alan Grant',
        subtitle: 'Orthopedics & Sports Medicine · MD',
        meta: 'Harbor Ave Clinic · 11 Yrs Exp · $130 Fee',
        rating: '4.7 ★ (86 reviews)',
        description: 'Orthopedic specialist in joint restoration, fracture recovery, arthroscopic surgery, and sports injury rehabilitation.',
        badge: 'Surgeon',
        url: '/doctors/DOC-40918',
        actionLabel: 'Book Appointment',
        searchTokens: 'dr alan grant orthopedics bone joint fracture surgery sports harbor',
    },

    // DEPARTMENTS
    {
        id: 'dept-1',
        type: 'department',
        category: 'departments',
        title: 'Cardiology & Cardiovascular Center',
        subtitle: 'Specialty Medical Department',
        meta: '8 Physicians · 24/7 Cardiac ER',
        rating: '1,420 Patients Treated',
        description: 'Comprehensive cardiovascular diagnosis, ECG monitoring, cardiac rehabilitation, and minimally invasive treatments.',
        badge: 'Department',
        url: '/departments/cardiology',
        actionLabel: 'Explore Department',
        searchTokens: 'cardiology heart cardiovascular department ecg er chest pain',
    },
    {
        id: 'dept-2',
        type: 'department',
        category: 'departments',
        title: 'Neurology & Brain Sciences',
        subtitle: 'Specialty Medical Department',
        meta: '5 Physicians · Advanced EEG Suite',
        rating: '980 Patients Treated',
        description: 'Dedicated neurological care center providing advanced EEG diagnostics, migraine clinics, and stroke recovery care.',
        badge: 'Department',
        url: '/departments/neurology',
        actionLabel: 'Explore Department',
        searchTokens: 'neurology brain nerve eeg stroke migraine department',
    },
    {
        id: 'dept-3',
        type: 'department',
        category: 'departments',
        title: 'Pediatrics & Infant Care',
        subtitle: 'Specialty Medical Department',
        meta: '6 Physicians · Child-Friendly Clinic',
        rating: '2,100 Young Patients Treated',
        description: 'Holistic pediatric medical services, growth milestone tracking, vaccination schedules, and urgent pediatric care.',
        badge: 'Department',
        url: '/departments/pediatrics',
        actionLabel: 'Explore Department',
        searchTokens: 'pediatrics child kids infant baby vaccination department',
    },

    // SERVICES & TELEHEALTH
    {
        id: 'srv-1',
        type: 'service',
        category: 'services',
        title: 'Virtual Telehealth Consultation',
        subtitle: 'Online Remote Appointment',
        meta: 'Available Daily 8 AM – 8 PM',
        rating: 'Instant Video Access',
        description: 'Connect with a certified doctor from home via secure HD video stream. Prescriptions issued directly to your patient portal.',
        badge: 'Online Service',
        url: '/doctors',
        actionLabel: 'Find Telehealth Doctor',
        searchTokens: 'telehealth virtual online call consultation video appointment home prescription remote',
    },
    {
        id: 'srv-2',
        type: 'service',
        category: 'services',
        title: 'Full Body Preventive Screening',
        subtitle: 'Comprehensive Diagnostic Package',
        meta: 'Same-day Lab Reports',
        rating: '$290 Complete Package',
        description: 'Includes complete blood count, lipid profile, cardiac risk markers, metabolic panel, and physician consultation.',
        badge: 'Health Package',
        url: '/departments',
        actionLabel: 'Book Screening',
        searchTokens: 'screening checkup package lab blood test preventive body health profile',
    },

    // FAQ & HELP
    {
        id: 'faq-1',
        type: 'faq',
        category: 'faq',
        title: 'How do I cancel or reschedule my appointment?',
        subtitle: 'Patient Portal Guide · FAQ',
        meta: 'Cancellation Policy: Free up to 24 hours prior',
        rating: 'Help Article',
        description: 'Log into your Patient Dashboard -> Navigate to My Appointments -> Select your booking -> Click Reschedule or Cancel Appointment.',
        badge: 'FAQ',
        url: '/faq',
        actionLabel: 'View FAQ Center',
        searchTokens: 'cancel reschedule change appointment booking time refund policy portal faq',
    },
    {
        id: 'faq-2',
        type: 'faq',
        category: 'faq',
        title: 'What payment methods does MediFlow accept?',
        subtitle: 'Billing & Payments · FAQ',
        meta: 'Stripe, Credit/Debit Cards, Cash at Clinic',
        rating: 'Help Article',
        description: 'MediFlow accepts all major credit/debit cards (Visa, Mastercard, AMEX) via secure Stripe checkout, as well as in-person payments at clinic reception.',
        badge: 'FAQ',
        url: '/faq',
        actionLabel: 'View FAQ Center',
        searchTokens: 'payment pay stripe credit debit card cash fee bill receipt invoice checkout faq',
    },
]

const filteredResults = computed(() => {
    const q = searchQuery.value.toLowerCase().trim()
    if (!q) return []

    return searchDatabase.filter((item) => {
        const matchesCategory = activeCategory.value === 'all' || item.category === activeCategory.value
        const matchesQuery = item.searchTokens.toLowerCase().includes(q) || item.title.toLowerCase().includes(q) || item.description.toLowerCase().includes(q)
        return matchesCategory && matchesQuery
    })
})

const categoryCounts = computed(() => {
    const q = searchQuery.value.toLowerCase().trim()
    if (!q) {
        return { all: 0, doctors: 0, departments: 0, services: 0, faq: 0 }
    }
    const matching = searchDatabase.filter((item) => item.searchTokens.toLowerCase().includes(q) || item.title.toLowerCase().includes(q) || item.description.toLowerCase().includes(q))
    return {
        all: matching.length,
        doctors: matching.filter((m) => m.category === 'doctors').length,
        departments: matching.filter((m) => m.category === 'departments').length,
        services: matching.filter((m) => m.category === 'services').length,
        faq: matching.filter((m) => m.category === 'faq').length,
    }
})

function setQuery(term: string) {
    searchQuery.value = term
}

function clearQuery() {
    searchQuery.value = ''
}
</script>

<template>
    <PublicLayout :title="searchQuery ? `Search Results for '${searchQuery}' — MediFlow` : 'Global Search — MediFlow'">
        <!-- HERO SEARCH BANNER -->
        <section class="search-hero">
            <div class="wrap">
                <div class="hero-content">
                    <span class="hero-tag">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="14" height="14">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        MediFlow Clinical Search Engine
                    </span>
                    <h1>Find Doctors, Departments & Medical Services</h1>
                    <p>Search across physicians, clinical specialties, diagnostic packages, and patient support resources.</p>

                    <!-- SEARCH INPUT CONTAINER -->
                    <div class="search-input-card">
                        <div class="input-inner">
                            <svg class="search-lens" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search by doctor name, specialty (e.g. Cardiology), or service..."
                                class="main-search-input"
                            />
                            <button v-if="searchQuery" class="clear-input-btn" title="Clear search" @click="clearQuery">✕</button>
                        </div>
                    </div>

                    <!-- POPULAR SUGGESTION PILLS -->
                    <div class="popular-tags">
                        <span class="tags-label">Popular Searches:</span>
                        <button class="tag-btn" @click="setQuery('Cardiology')">Cardiology</button>
                        <button class="tag-btn" @click="setQuery('Dr. Sarah')">Dr. Sarah Jenkins</button>
                        <button class="tag-btn" @click="setQuery('Telehealth')">Telehealth Call</button>
                        <button class="tag-btn" @click="setQuery('Pediatrics')">Pediatrics</button>
                        <button class="tag-btn" @click="setQuery('Payment')">Payments & Invoices</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- MAIN SEARCH RESULTS SECTION -->
        <section class="results-section">
            <div class="wrap">
                <!-- TOOLBAR & CATEGORY FILTER TABS -->
                <div v-if="searchQuery.trim()" class="search-toolbar">
                    <div class="results-meta">
                        <h2>
                            Search Results for <span class="highlight-query">"{{ searchQuery }}"</span>
                        </h2>
                        <span class="match-count">{{ categoryCounts.all }} relevant matches found</span>
                    </div>

                    <div class="category-tabs">
                        <button class="cat-tab" :class="{ active: activeCategory === 'all' }" @click="activeCategory = 'all'">
                            All Results <span class="tab-badge">{{ categoryCounts.all }}</span>
                        </button>
                        <button class="cat-tab" :class="{ active: activeCategory === 'doctors' }" @click="activeCategory = 'doctors'">
                            Doctors <span class="tab-badge">{{ categoryCounts.doctors }}</span>
                        </button>
                        <button class="cat-tab" :class="{ active: activeCategory === 'departments' }" @click="activeCategory = 'departments'">
                            Departments <span class="tab-badge">{{ categoryCounts.departments }}</span>
                        </button>
                        <button class="cat-tab" :class="{ active: activeCategory === 'services' }" @click="activeCategory = 'services'">
                            Services & Telehealth <span class="tab-badge">{{ categoryCounts.services }}</span>
                        </button>
                        <button class="cat-tab" :class="{ active: activeCategory === 'faq' }" @click="activeCategory = 'faq'">
                            FAQ & Help <span class="tab-badge">{{ categoryCounts.faq }}</span>
                        </button>
                    </div>
                </div>

                <!-- RESULTS GRID -->
                <div v-if="searchQuery.trim() && filteredResults.length > 0" class="results-grid">
                    <div v-for="item in filteredResults" :key="item.id" class="result-card" :class="item.type">
                        <div class="card-top">
                            <span class="result-badge" :class="`badge-${item.type}`">
                                {{ item.badge }}
                            </span>
                            <span class="rating-tag">{{ item.rating }}</span>
                        </div>

                        <div class="card-main">
                            <h3 class="result-title">{{ item.title }}</h3>
                            <p class="result-subtitle">{{ item.subtitle }}</p>
                            <p class="result-desc">{{ item.description }}</p>

                            <div class="card-footer">
                                <span class="meta-info">{{ item.meta }}</span>
                                <Link :href="item.url" class="btn-card-action">
                                    <span>{{ item.actionLabel }}</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EMPTY STATE: NO MATCHES -->
                <div v-else-if="searchQuery.trim()" class="empty-search-card">
                    <div class="empty-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="32" height="32">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </div>
                    <h3>No exact matches found for "{{ searchQuery }}"</h3>
                    <p>We couldn't find any doctor, department, or medical service matching your exact search terms.</p>

                    <div class="suggestions-box">
                        <b>Suggestions to improve your search:</b>
                        <ul>
                            <li>Check for typos or misspellings in doctor or department names.</li>
                            <li>Try using broader terms such as <b>"Heart"</b>, <b>"Brain"</b>, <b>"Checkup"</b>, or <b>"Doctor"</b>.</li>
                            <li>Browse our complete physician directory or department listing.</li>
                        </ul>
                        <div class="empty-actions">
                            <Link href="/doctors" class="btn-primary">Browse All Doctors</Link>
                            <Link href="/departments" class="btn-outline">View Departments</Link>
                            <button class="btn-text" @click="clearQuery">Clear Search</button>
                        </div>
                    </div>
                </div>

                <!-- INITIAL EMPTY SEARCH PROMPT -->
                <div v-else class="initial-search-prompt">
                    <div class="prompt-grid">
                        <div class="prompt-card" @click="setQuery('Cardiology')">
                            <div class="prompt-icon icon-green">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l8.78-8.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </div>
                            <h4>Cardiology Services</h4>
                            <p>Heart consultations, ECG diagnostics & cardiovascular specialists</p>
                            <span class="prompt-link">Search Cardiology →</span>
                        </div>

                        <div class="prompt-card" @click="setQuery('Neurology')">
                            <div class="prompt-icon icon-blue">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            </div>
                            <h4>Neurology & Brain Sciences</h4>
                            <p>Neurological care, stroke recovery, EEG scans & migraine relief</p>
                            <span class="prompt-link">Search Neurology →</span>
                        </div>

                        <div class="prompt-card" @click="setQuery('Telehealth')">
                            <div class="prompt-icon icon-amber">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            </div>
                            <h4>Telehealth Consultation</h4>
                            <p>Secure online video appointments with licensed physicians</p>
                            <span class="prompt-link">Search Telehealth →</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1280px; margin: 0 auto; padding: 0 24px; }

/* HERO SEARCH BANNER */
.search-hero { background: var(--forest); color: #ffffff; padding: 56px 0 64px 0; position: relative; overflow: hidden; }
.search-hero::after { content: ''; position: absolute; right: -60px; top: -60px; width: 300px; height: 300px; border-radius: 50%; background: var(--lime); opacity: 0.15; filter: blur(30px); pointer-events: none; }

.hero-content { max-width: 820px; margin: 0 auto; text-align: center; position: relative; z-index: 1; }
.hero-tag { display: inline-flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; background: rgba(221, 241, 92, 0.15); border: 1px solid rgba(221, 241, 92, 0.3); color: var(--lime); padding: 5px 14px; border-radius: 999px; margin-bottom: 16px; }

.hero-content h1 { font-size: clamp(2rem, 1.5rem + 2vw, 3rem); font-weight: 800; letter-spacing: -0.02em; margin: 0 0 10px 0; line-height: 1.15; }
.hero-content p { font-size: 16px; opacity: 0.85; margin: 0 0 32px 0; }

.search-input-card { background: #ffffff; border-radius: 999px; padding: 6px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25); max-width: 720px; margin: 0 auto 20px; }
.input-inner { display: flex; align-items: center; position: relative; width: 100%; }
.search-lens { width: 22px; height: 22px; color: var(--forest); margin-left: 18px; flex-shrink: 0; }

.main-search-input { flex: 1; height: 52px; border: none; outline: none; background: transparent; padding: 0 44px 0 14px; font-size: 16px; font-weight: 600; color: var(--ink); }
.main-search-input::placeholder { color: var(--ink-muted); font-weight: 400; }

.clear-input-btn { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 26px; height: 26px; border-radius: 50%; background: var(--cream-alt); border: none; font-size: 12px; color: var(--ink-muted); cursor: pointer; display: flex; align-items: center; justify-content: center; }
.clear-input-btn:hover { background: var(--line); color: var(--ink); }

.popular-tags { display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 8px; font-size: 13px; opacity: 0.9; }
.tags-label { font-weight: 600; opacity: 0.75; }
.tag-btn { background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2); color: #ffffff; padding: 4px 12px; border-radius: 999px; font-size: 12.5px; font-weight: 600; cursor: pointer; transition: all 150ms ease; }
.tag-btn:hover { background: var(--lime); color: var(--lime-text); border-color: var(--lime); }

/* MAIN RESULTS SECTION */
.results-section { padding: 40px 0 80px 0; background: var(--cream); min-height: 50vh; }

.search-toolbar { margin-bottom: 28px; border-bottom: 1px solid var(--line); padding-bottom: 20px; }
.results-meta { display: flex; align-items: baseline; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
.results-meta h2 { font-size: 22px; font-weight: 800; color: var(--forest); margin: 0; }
.highlight-query { color: var(--forest); font-style: italic; }
.match-count { font-size: 13.5px; font-weight: 700; color: var(--ink-muted); font-family: var(--font-mono); }

.category-tabs { display: flex; flex-wrap: wrap; gap: 8px; }
.cat-tab { padding: 8px 18px; border-radius: 999px; font-size: 13px; font-weight: 700; color: var(--ink-muted); background: var(--card); border: 1px solid var(--line); cursor: pointer; transition: all 150ms ease; display: inline-flex; align-items: center; gap: 6px; box-shadow: var(--shadow-sm); }
.cat-tab:hover { border-color: var(--forest); color: var(--ink); }
.cat-tab.active { background: var(--forest); color: #ffffff; border-color: var(--forest); }
.tab-badge { font-family: var(--font-mono); font-size: 11px; padding: 2px 7px; border-radius: 999px; background: rgba(22, 24, 15, 0.06); color: inherit; font-weight: 700; }
.cat-tab.active .tab-badge { background: rgba(255, 255, 255, 0.22); color: #ffffff; }

/* RESULTS GRID */
.results-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
@media (max-width: 880px) { .results-grid { grid-template-columns: 1fr; } }

.result-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; justify-content: space-between; transition: all 200ms ease; position: relative; }
.result-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lift); border-color: var(--forest); }

.card-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
.result-badge { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 4px 10px; border-radius: 6px; }
.badge-doctor { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.badge-department { background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; }
.badge-service { background: var(--lime-soft); color: var(--lime-text); border: 1px solid #d2e85a; }
.badge-faq { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }

.rating-tag { font-family: var(--font-mono); font-size: 12px; font-weight: 700; color: var(--ink-muted); }

.result-title { font-size: 18px; font-weight: 800; color: var(--forest); margin: 0 0 4px 0; }
.result-subtitle { font-size: 13px; font-weight: 700; color: var(--ink-muted); margin: 0 0 10px 0; }
.result-desc { font-size: 13.5px; color: var(--ink); line-height: 1.5; margin: 0 0 20px 0; }

.card-footer { display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--line); padding-top: 16px; flex-wrap: wrap; gap: 12px; }
.meta-info { font-size: 12px; font-weight: 600; color: var(--ink-muted); }

.btn-card-action { height: 38px; padding: 0 16px; border-radius: 999px; background: var(--forest); color: #ffffff; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 150ms ease; }
.btn-card-action:hover { background: var(--forest-2); transform: translateY(-1px); }

/* EMPTY STATE */
.empty-search-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 48px 32px; text-align: center; max-width: 640px; margin: 0 auto; box-shadow: var(--shadow-card); }
.empty-icon { width: 64px; height: 64px; border-radius: 50%; background: var(--cream); color: var(--ink-muted); display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; border: 1px solid var(--line); }
.empty-search-card h3 { font-size: 20px; font-weight: 800; color: var(--forest); margin: 0 0 8px 0; }
.empty-search-card p { font-size: 14px; color: var(--ink-muted); margin: 0 0 24px 0; line-height: 1.5; }

.suggestions-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 20px; text-align: left; }
.suggestions-box b { font-size: 13.5px; color: var(--forest); display: block; margin-bottom: 8px; }
.suggestions-box ul { margin: 0 0 20px 0; padding-left: 20px; font-size: 13px; color: var(--ink); line-height: 1.6; }

.empty-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.btn-primary { height: 38px; padding: 0 18px; border-radius: 999px; background: var(--forest); color: #ffffff; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; }
.btn-outline { height: 38px; padding: 0 18px; border-radius: 999px; background: transparent; border: 1px solid var(--line); color: var(--ink); font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; }
.btn-outline:hover { border-color: var(--forest); background: var(--card); }
.btn-text { background: none; border: none; color: var(--ink-muted); font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: underline; }

/* INITIAL PROMPT GRID */
.initial-search-prompt { max-width: 960px; margin: 0 auto; }
.prompt-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
@media (max-width: 800px) { .prompt-grid { grid-template-columns: 1fr; } }

.prompt-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px 24px; box-shadow: var(--shadow-sm); cursor: pointer; transition: all 200ms ease; display: flex; flex-direction: column; align-items: flex-start; }
.prompt-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lift); border-color: var(--forest); }

.prompt-icon { width: 48px; height: 48px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
.prompt-icon svg { width: 24px; height: 24px; }
.prompt-icon.icon-green { background: #DCFCE7; color: #15803D; }
.prompt-icon.icon-blue { background: #E0F2FE; color: #0369A1; }
.prompt-icon.icon-amber { background: #FEF3C7; color: #B45309; }

.prompt-card h4 { font-size: 17px; font-weight: 800; color: var(--forest); margin: 0 0 6px 0; }
.prompt-card p { font-size: 13px; color: var(--ink-muted); line-height: 1.45; margin: 0 0 18px 0; flex: 1; }
.prompt-link { font-size: 13px; font-weight: 700; color: var(--forest); }
</style>
