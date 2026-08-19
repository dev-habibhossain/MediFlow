<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface FaqItem {
    question: string
    answer: string
}

const props = defineProps<{
    faqs?: FaqItem[]
}>()

const search = ref('')
const activeCategory = ref('all')
const openPanelIndex = ref<number | null>(0)

// Comprehensive categorized FAQ dataset matching 6.faq.html
const faqCategories = [
    {
        id: 'booking',
        title: 'Booking & Consultations',
        icon: 'calendar',
        quickCards: [
            { title: 'No Phone Call Needed', desc: 'Pick a doctor, choose a live time slot, and confirm instantly through our online booking system.', keywords: 'phone booking online schedule slot' },
            { title: 'Free Rescheduling', desc: 'Change or cancel your visit up to 2 hours before your scheduled appointment at no charge.', keywords: 'reschedule cancel change time slot' }
        ],
        items: [
            { question: 'How do same-day consultations work?', answer: 'Open slots appear on doctor schedules in real time when cancellations occur. You can reserve available same-day slots through the Doctors directory or visit our 24/7 Urgent Unit.', keywords: 'same day urgent walk in visit consultation' },
            { question: 'Can I book an appointment for a family member?', answer: 'Yes! Inside your MediFlow patient account, you can add household dependents and select them during appointment checkout.', keywords: 'family child dependent parent account' }
        ]
    },
    {
        id: 'records',
        title: 'Records & Prescriptions',
        icon: 'file-text',
        quickCards: [],
        items: [
            { question: 'Where can I download my prescription PDF?', answer: 'All doctor notes, diagnosis summaries, and digital prescriptions are attached to your visit history inside your patient account immediately after your consultation.', keywords: 'prescriptions notes history diagnosis download pdf portal' },
            { question: 'How long does it take to receive lab test results?', answer: 'Standard blood panels and express diagnostics are processed within 4 to 12 hours. Reports are delivered directly to your portal with an automated notification.', keywords: 'lab results blood test x-ray report turnaround time portal' }
        ]
    },
    {
        id: 'billing',
        title: 'Fees & Insurance',
        icon: 'credit-card',
        quickCards: [],
        items: [
            { question: 'Are consultation fees shown before booking?', answer: 'Yes. Every physician profile displays their exact consultation fee upfront before you confirm your appointment. MediFlow charges zero extra administrative fees.', keywords: 'consultation fee pricing transparent cost upfront surprise billing' },
            { question: 'How do health insurance claims work?', answer: 'We support direct billing with major partner insurance networks. Present your card during check-in or enter policy details online to generate pre-formatted claim receipts.', keywords: 'insurance health coverage claim reimbursement direct billing health card' }
        ]
    },
    {
        id: 'policies',
        title: 'Clinic Policies',
        icon: 'shield',
        quickCards: [],
        items: [
            { question: 'What should I bring for my first clinic visit?', answer: 'Please bring a government-issued photo ID, your insurance card, and any relevant past medical records. Arriving 10 minutes prior allows for quick reception check-in.', keywords: 'first visit ID arrival documents check in front desk' },
            { question: 'Do you offer virtual/telehealth consultations?', answer: 'Yes. Many of our specialists offer video consultations. Look for the Telehealth badge on the doctor directory to book a virtual appointment.', keywords: 'telehealth online consultation video call virtual doctor' }
        ]
    }
]

function togglePanel(index: number) {
    openPanelIndex.value = openPanelIndex.value === index ? null : index
}

// Search filtering logic
const filteredCategories = computed(() => {
    const q = search.value.toLowerCase().trim()
    if (!q) return faqCategories

    return faqCategories.map(cat => {
        const matchingCards = cat.quickCards.filter(card =>
            card.title.toLowerCase().includes(q) ||
            card.desc.toLowerCase().includes(q) ||
            card.keywords.toLowerCase().includes(q)
        )

        const matchingItems = cat.items.filter(item =>
            item.question.toLowerCase().includes(q) ||
            item.answer.toLowerCase().includes(q) ||
            (item.keywords && item.keywords.toLowerCase().includes(q))
        )

        return {
            ...cat,
            quickCards: matchingCards,
            items: matchingItems
        }
    }).filter(cat => cat.quickCards.length > 0 || cat.items.length > 0)
})
</script>

<template>
    <PublicLayout title="Help Center & FAQ — MediFlow">
        <main class="py-8">
            <div class="wrap">
                <!-- HERO SECTION WITH INTEGRATED SEARCH -->
                <section class="faq-hero mb-8">
                    <div class="faq-hero-box">
                        <div class="faq-hero-left">
                            <span class="pill mb-3">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                                Knowledge Base
                            </span>
                            <h1>How can we <b>assist you today</b>?</h1>
                            <p>Find quick answers regarding clinic procedures, online appointments, digital health records, and insurance coverage.</p>
                        </div>
                        <div class="faq-search-widget">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Search keywords (e.g. reschedule, lab, fee)..."
                                aria-label="Search Knowledge Base"
                            />
                        </div>
                    </div>
                </section>

                <!-- 2-COLUMN SPLIT LAYOUT -->
                <div class="faq-split-layout">
                    <!-- SIDEBAR CATEGORY NAVIGATION -->
                    <aside class="faq-sidebar">
                        <div class="sidebar-title">Categories</div>
                        <button
                            @click="activeCategory = 'all'"
                            :class="['nav-cat-link', activeCategory === 'all' ? 'active' : '']"
                        >
                            <span>All Topics</span>
                            <span class="badge">8</span>
                        </button>
                        <button
                            v-for="cat in faqCategories"
                            :key="cat.id"
                            @click="activeCategory = cat.id"
                            :class="['nav-cat-link', activeCategory === cat.id ? 'active' : '']"
                        >
                            <span>{{ cat.title }}</span>
                            <span class="badge">{{ cat.items.length + cat.quickCards.length }}</span>
                        </button>

                        <div class="sidebar-contact-card mt-6">
                            <h5>Have a <b>unique question</b>?</h5>
                            <p>Our front desk assistance team is standing by to help.</p>
                            <Link href="/contact" class="btn btn-outline btn-sm w-full mt-3">
                                Contact Support Desk
                            </Link>
                        </div>
                    </aside>

                    <!-- CONTENT AREA -->
                    <div class="faq-content-area">
                        <template v-if="filteredCategories.length > 0">
                            <template v-for="cat in filteredCategories" :key="cat.id">
                                <div
                                    v-if="activeCategory === 'all' || activeCategory === cat.id"
                                    class="faq-topic-block"
                                >
                                    <!-- TOPIC HEADER -->
                                    <div class="topic-header">
                                        <div class="topic-icon">
                                            <svg v-if="cat.id === 'booking'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                            <svg v-else-if="cat.id === 'records'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                            <svg v-else-if="cat.id === 'billing'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                            <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                        </div>
                                        <h2><b>{{ cat.title }}</b></h2>
                                    </div>

                                    <!-- QUICK CARDS (IF ANY) -->
                                    <div v-if="cat.quickCards.length > 0" class="popular-cards-grid">
                                        <div v-for="(card, cIdx) in cat.quickCards" :key="cIdx" class="quick-card">
                                            <h4><b>{{ card.title }}</b></h4>
                                            <p>{{ card.desc }}</p>
                                        </div>
                                    </div>

                                    <!-- ACCORDION STACK -->
                                    <div class="panel-stack">
                                        <div
                                            v-for="(item, itemIdx) in cat.items"
                                            :key="itemIdx"
                                            :class="['panel-item', openPanelIndex === (cat.id + '-' + itemIdx) ? 'open' : '']"
                                        >
                                            <button
                                                @click="togglePanel(cat.id + '-' + itemIdx)"
                                                class="panel-q"
                                                type="button"
                                            >
                                                <span><b>{{ item.question }}</b></span>
                                                <span class="icon-wrap">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                                                </span>
                                            </button>
                                            <div
                                                v-show="openPanelIndex === (cat.id + '-' + itemIdx)"
                                                class="panel-a-inner"
                                            >
                                                {{ item.answer }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </template>

                        <!-- NO SEARCH RESULTS -->
                        <div v-else class="no-search-results">
                            <h4>No matching <b>questions</b> found</h4>
                            <p>Try searching with another keyword or reach out directly to our patient support desk.</p>
                            <button @click="search = ''" class="btn btn-outline btn-sm mt-4">Clear Search</button>
                        </div>
                    </div>
                </div>

                <!-- CLOSING CTA BANNER -->
                <div class="closing mb-16">
                    <div>
                        <h2>Still have <b>unanswered questions</b>?</h2>
                        <p>Our patient care desk is available 24/7 to match you with the right specialist.</p>
                    </div>
                    <Link href="/contact" class="btn btn-primary">Contact Support Desk</Link>
                </div>
            </div>
        </main>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; position: relative; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }

b { font-weight: 700; color: inherit; }

.pill { display: inline-flex; align-items: center; gap: 6px; background: #DDF15C; color: #3B4A12; font-size: 13px; font-weight: 600; padding: 7px 16px 7px 12px; border-radius: 999px; }
.pill svg { width: 14px; height: 14px; }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 54px; padding: 0 28px; border-radius: 999px; font-size: 15.5px; font-weight: 600; transition: transform 150ms ease, background-color 150ms ease, box-shadow 150ms ease; text-decoration: none; border: 0; cursor: pointer; }
.btn:active { transform: scale(0.97); }
.btn-primary { background: #16301F; color: #fff; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.btn-primary:hover { background: #1E4029; }
.btn-outline { background: transparent; color: #16180F; border: 1.5px solid rgba(22,24,15,0.16); }
.btn-outline:hover { border-color: #16180F; }
.btn-sm { height: 44px; padding: 0 20px; font-size: 13.5px; }

.faq-hero { padding: 36px 0 12px; }
.faq-hero-box { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 32px; padding: 40px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); display: grid; grid-template-columns: 1fr 380px; gap: 32px; align-items: center; }
@media (max-width: 960px) { .faq-hero-box { grid-template-columns: 1fr; padding: 28px; } }

.faq-hero-left h1 { font-size: clamp(2.2rem, 1.6rem + 2vw, 3rem); font-weight: 800; letter-spacing: -0.02em; color: #16301F; margin-bottom: 12px; line-height: 1.15; }
.faq-hero-left p { font-size: 16px; color: #62655A; max-width: 52ch; }

.faq-search-widget { position: relative; width: 100%; }
.faq-search-widget svg { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #62655A; pointer-events: none; }
.faq-search-widget input { width: 100%; height: 52px; border-radius: 999px; border: 1.5px solid #E7E3D3; background: #F8F6EF; padding-left: 46px; padding-right: 18px; font-size: 14.5px; outline: none; transition: border-color 150ms ease, background-color 150ms ease; }
.faq-search-widget input:focus { border-color: #16301F; background: #FFFFFF; }

.faq-split-layout { display: grid; grid-template-columns: 280px 1fr; gap: 36px; padding: 32px 0 80px; align-items: start; }
@media (max-width: 960px) { .faq-split-layout { grid-template-columns: 1fr; } }

.faq-sidebar { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 20px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); position: sticky; top: 100px; }
.sidebar-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #62655A; margin-bottom: 14px; padding-left: 8px; }

.nav-cat-link { display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 12px 14px; border-radius: 16px; font-size: 14px; font-weight: 600; color: #62655A; transition: all 150ms ease; margin-bottom: 4px; border: 0; background: transparent; cursor: pointer; text-align: left; }
.nav-cat-link:hover { background: #F8F6EF; color: #16180F; }
.nav-cat-link.active { background: #16301F; color: #fff; }
.nav-cat-link span.badge { font-family: 'JetBrains Mono', monospace; font-size: 11.5px; font-weight: 600; background: rgba(22,24,15,0.06); padding: 2px 8px; border-radius: 999px; }
.nav-cat-link.active span.badge { background: rgba(255,255,255,0.2); color: #fff; }

.sidebar-contact-card { background: #F0EEE3; border: 1px solid #E7E3D3; border-radius: 16px; padding: 18px; text-align: center; }
.sidebar-contact-card h5 { font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #16301F; }
.sidebar-contact-card p { font-size: 12.5px; color: #62655A; line-height: 1.45; }

.faq-content-area { display: flex; flex-direction: column; gap: 32px; }
.faq-topic-block { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 32px; padding: 32px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.topic-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; border-bottom: 1px solid #E7E3D3; padding-bottom: 16px; }
.topic-icon { width: 40px; height: 40px; border-radius: 12px; background: #EEF7C4; color: #3B4A12; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.topic-icon svg { width: 20px; height: 20px; }
.topic-header h2 { font-size: 20px; font-weight: 800; color: #16301F; }

.popular-cards-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px; }
@media (max-width: 600px) { .popular-cards-grid { grid-template-columns: 1fr; } }
.quick-card { background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 16px; padding: 20px; transition: border-color 150ms ease; }
.quick-card:hover { border-color: #16301F; }
.quick-card h4 { font-size: 15px; font-weight: 700; margin-bottom: 6px; color: #16180F; }
.quick-card p { font-size: 13.5px; color: #62655A; line-height: 1.55; }

.panel-stack { display: flex; flex-direction: column; gap: 12px; }
.panel-item { border: 1px solid #E7E3D3; border-radius: 16px; background: #FFFFFF; overflow: hidden; transition: border-color 150ms ease; }
.panel-item.open { border-color: #16301F; }

.panel-q { width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 20px; text-align: left; font-size: 15px; font-weight: 700; color: #16180F; border: 0; background: transparent; cursor: pointer; }
.panel-q .icon-wrap { width: 28px; height: 28px; border-radius: 50%; background: #F0EEE3; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: transform 200ms ease, background-color 200ms ease; }
.panel-q .icon-wrap svg { width: 13px; height: 13px; color: #16301F; }
.panel-item.open .panel-q .icon-wrap { background: #DDF15C; transform: rotate(180deg); }

.panel-a-inner { padding: 16px 20px; font-size: 14px; color: #62655A; line-height: 1.6; border-top: 1px solid #E7E3D3; background: #F8F6EF; }

.no-search-results { padding: 48px 20px; text-align: center; background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 24px; }
.no-search-results h4 { font-size: 18px; font-weight: 700; margin-bottom: 6px; color: #16301F; }
.no-search-results p { font-size: 14px; color: #62655A; }

.closing { background: #16301F; border-radius: 32px; padding: 52px 40px; display: flex; align-items: center; justify-content: space-between; gap: 28px; flex-wrap: wrap; color: #fff; position: relative; overflow: hidden; }
.closing::before { content: ""; position: absolute; top: -80px; right: -60px; width: 240px; height: 240px; border-radius: 50%; background: #DDF15C; opacity: 0.15; filter: blur(10px); }
.closing h2 { font-size: 26px; font-weight: 800; letter-spacing: -0.015em; max-width: 24ch; position: relative; }
.closing p { color: rgba(255,255,255,0.65); font-size: 14px; margin-top: 6px; position: relative; }
.closing .btn-primary { background: #DDF15C; color: #3B4A12; position: relative; border: 0; }
.closing .btn-primary:hover { background: #ecf99c; }
</style>
