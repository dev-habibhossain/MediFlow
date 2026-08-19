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
    <PublicLayout>
        <div class="wrap py-12">
            <!-- PAGE HERO -->
            <section class="page-hero mb-12">
                <span class="pill mb-4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Help Center</span>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">Frequently Asked Questions</h1>
                <p class="text-lg text-[#62655A] max-w-2xl">Find quick answers to common questions about appointments, consultations, medical records, and payments.</p>
            </section>

            <!-- FAQ LIST -->
            <div class="max-w-3xl mx-auto space-y-4 mb-20">
                <div v-for="(faq, i) in faqs" :key="i" class="bg-white border border-[#E7E3D3] rounded-2xl overflow-hidden shadow-card">
                    <button @click="toggleFaq(i)" class="w-full p-6 text-left flex items-center justify-between gap-4 font-bold text-base hover:bg-[#F8F6EF]/50 transition-colors">
                        <span>{{ faq.question }}</span>
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
            <div class="closing bg-[#16301F] rounded-3xl p-10 md:p-14 flex items-center justify-between gap-8 flex-wrap text-white relative overflow-hidden mb-16">
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight max-w-[24ch]">Still have questions?</h2>
                    <p class="text-white/65 text-sm mt-2">Our support desk team is here to guide you.</p>
                </div>
                <Link href="/contact" class="btn bg-[#DDF15C] text-[#3B4A12] hover:bg-[#ecf99c] font-semibold px-6 py-3.5 rounded-full">Contact Support Desk</Link>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }
.pill { display: inline-flex; align-items: center; gap: 6px; background: #DDF15C; color: #3B4A12; font-size: 13px; font-weight: 600; padding: 7px 16px 7px 12px; border-radius: 999px; }
.pill svg { width: 14px; height: 14px; }
</style>
