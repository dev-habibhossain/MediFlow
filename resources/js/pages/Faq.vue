<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

interface FaqItem {
    question: string
    answer: string
}

defineProps<{
    faqs: FaqItem[]
}>()

const activeIndex = ref<number | null>(0)

function toggleFaq(index: number) {
    activeIndex.value = activeIndex.value === index ? null : index
}
</script>

<template>
    <PublicLayout title="Help Center & FAQ — MediFlow">
        <main class="py-8">
            <div class="wrap">
                <!-- PAGE HERO -->
                <section class="about-hero">
                    <span class="pill mb-4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Help Center</span>
                    <h1>Frequently Asked <b>Questions</b></h1>
                    <p class="lead">Find quick answers to common questions about appointments, consultations, medical records, and payments.</p>
                </section>

                <!-- FAQ LIST -->
                <div class="max-w-3xl mx-auto space-y-4 mb-20">
                    <div v-for="(faq, i) in faqs" :key="i" class="bg-white border border-[#E7E3D3] rounded-2xl overflow-hidden shadow-card">
                        <button @click="toggleFaq(i)" class="w-full p-6 text-left flex items-center justify-between gap-4 font-bold text-base hover:bg-[#F8F6EF]/50 transition-colors cursor-pointer text-[#16301F]">
                            <span><b>{{ faq.question }}</b></span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="['w-5 h-5 shrink-0 transition-transform duration-200', activeIndex === i ? 'rotate-180 text-[#16301F]' : 'text-[#62655A]']">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </button>
                        <div v-show="activeIndex === i" class="px-6 pb-6 text-sm text-[#62655A] leading-relaxed border-t border-[#E7E3D3]/50 pt-4">
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>

                <!-- CLOSING BANNER -->
                <div class="closing mb-16">
                    <div>
                        <h2>Still have <b>unanswered questions</b>?</h2>
                        <p>Our support desk team is here to guide you through any inquiry.</p>
                    </div>
                    <Link href="/contact" class="btn btn-primary">Contact Support Desk</Link>
                </div>
            </div>
        </main>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }

b { font-weight: 700; color: inherit; }

.pill { display: inline-flex; align-items: center; gap: 6px; background: #DDF15C; color: #3B4A12; font-size: 13px; font-weight: 600; padding: 7px 16px 7px 12px; border-radius: 999px; }
.pill svg { width: 14px; height: 14px; }

.about-hero { padding: 40px 0 24px; }
.about-hero h1 { font-size: clamp(2.2rem, 1.6rem + 2vw, 3.2rem); font-weight: 800; letter-spacing: -0.02em; margin-bottom: 14px; line-height: 1.15; color: #16301F; }
.about-hero p.lead { font-size: 17px; color: #62655A; max-width: 62ch; line-height: 1.6; }

.closing { background: #16301F; border-radius: 32px; padding: 52px 40px; display: flex; align-items: center; justify-content: space-between; gap: 28px; flex-wrap: wrap; color: #fff; position: relative; overflow: hidden; }
.closing::before { content: ""; position: absolute; top: -80px; right: -60px; width: 240px; height: 240px; border-radius: 50%; background: #DDF15C; opacity: 0.15; filter: blur(10px); }
.closing h2 { font-size: 26px; font-weight: 800; letter-spacing: -0.015em; max-width: 24ch; position: relative; }
.closing p { color: rgba(255,255,255,0.65); font-size: 14px; margin-top: 6px; position: relative; }
.closing .btn-primary { background: #DDF15C; color: #3B4A12; position: relative; border: 0; }
.closing .btn-primary:hover { background: #ecf99c; }
.btn { display: inline-flex; align-items: center; justify-content: center; height: 52px; padding: 0 26px; border-radius: 999px; font-size: 14.5px; font-weight: 600; text-decoration: none; border: 0; cursor: pointer; }
</style>
