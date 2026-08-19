<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

interface Department {
    id: number
    name: string
    slug: string
}

defineProps<{
    contactDetails: {
        address: string
        phone: string
        email: string
        hours: string
    }
    departments?: Department[]
}>()

const form = useForm({
    name: '',
    email: '',
    phone: '',
    department_id: '',
    subject: '',
    message: '',
})

const page = usePage()
const flashSuccess = computed(() => page.props.flash?.success)

function submit() {
    form.post('/contact', {
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <PublicLayout title="Contact Us — MediFlow">
        <main class="py-8">
            <div class="wrap">
                <!-- PAGE HERO -->
                <section class="about-hero">
                    <span class="pill mb-4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Get in Touch</span>
                    <h1>Contact <b>MediFlow Desk</b> & <b>Support</b></h1>
                    <p class="lead">Have questions about our specialists, appointments, or medical services? Send us a message or call our desk directly.</p>
                </section>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-20">
                    <!-- CONTACT DETAILS -->
                    <div class="space-y-6">
                        <div class="bg-white border border-[#E7E3D3] rounded-2xl p-7 shadow-card">
                            <div class="w-10 h-10 rounded-xl bg-[#EEF7C4] text-[#3B4A12] flex items-center justify-center mb-4">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <h4 class="font-bold text-base mb-1"><b>Phone Assistance</b></h4>
                            <p class="text-sm font-semibold text-[#16301F] mb-1"><b>{{ contactDetails.phone }}</b></p>
                            <p class="text-xs text-[#62655A]">{{ contactDetails.hours }}</p>
                        </div>

                        <div class="bg-white border border-[#E7E3D3] rounded-2xl p-7 shadow-card">
                            <div class="w-10 h-10 rounded-xl bg-[#EEF7C4] text-[#3B4A12] flex items-center justify-center mb-4">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <h4 class="font-bold text-base mb-1"><b>Email Support</b></h4>
                            <p class="text-sm font-semibold text-[#16301F]"><b>{{ contactDetails.email }}</b></p>
                        </div>

                        <div class="bg-white border border-[#E7E3D3] rounded-2xl p-7 shadow-card">
                            <div class="w-10 h-10 rounded-xl bg-[#EEF7C4] text-[#3B4A12] flex items-center justify-center mb-4">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <h4 class="font-bold text-base mb-1"><b>Hospital Location</b></h4>
                            <p class="text-sm text-[#62655A] leading-relaxed">{{ contactDetails.address }}</p>
                        </div>
                    </div>

                    <!-- CONTACT FORM -->
                    <div class="lg:col-span-2 bg-white border border-[#E7E3D3] rounded-3xl p-8 md:p-10 shadow-card">
                        <h3 class="text-2xl font-bold mb-2 text-[#16301F]">Send Us a <b>Direct Message</b></h3>
                        <p class="text-sm text-[#62655A] mb-6">Fill out the form below and our administrative desk will save your inquiry in our system.</p>

                        <div v-if="flashSuccess" class="mb-6 p-4 rounded-xl bg-[#EEF7C4] text-[#3B4A12] text-sm font-semibold">
                            <b>{{ flashSuccess }}</b>
                        </div>

                        <form @submit.prevent="submit" class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-semibold text-[#62655A] mb-1.5">Your Name *</label>
                                    <input v-model="form.name" type="text" required placeholder="John Doe" class="w-full h-12 rounded-xl border border-[#E7E3D3] bg-[#F8F6EF] px-4 text-sm focus:border-[#16301F] focus:bg-white outline-none transition-all">
                                    <span v-if="form.errors.name" class="text-xs text-red-600 mt-1 block">{{ form.errors.name }}</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-[#62655A] mb-1.5">Email Address *</label>
                                    <input v-model="form.email" type="email" required placeholder="john@example.com" class="w-full h-12 rounded-xl border border-[#E7E3D3] bg-[#F8F6EF] px-4 text-sm focus:border-[#16301F] focus:bg-white outline-none transition-all">
                                    <span v-if="form.errors.email" class="text-xs text-red-600 mt-1 block">{{ form.errors.email }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-semibold text-[#62655A] mb-1.5">Phone Number</label>
                                    <input v-model="form.phone" type="tel" placeholder="+1 (555) 000-0000" class="w-full h-12 rounded-xl border border-[#E7E3D3] bg-[#F8F6EF] px-4 text-sm focus:border-[#16301F] focus:bg-white outline-none transition-all">
                                    <span v-if="form.errors.phone" class="text-xs text-red-600 mt-1 block">{{ form.errors.phone }}</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-[#62655A] mb-1.5">Department (Optional)</label>
                                    <select v-model="form.department_id" class="w-full h-12 rounded-xl border border-[#E7E3D3] bg-[#F8F6EF] px-4 text-sm focus:border-[#16301F] focus:bg-white outline-none transition-all">
                                        <option value="">General Support Desk</option>
                                        <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                                            {{ dept.name }} Department
                                        </option>
                                    </select>
                                    <span v-if="form.errors.department_id" class="text-xs text-red-600 mt-1 block">{{ form.errors.department_id }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#62655A] mb-1.5">Subject *</label>
                                <input v-model="form.subject" type="text" required placeholder="Appointment inquiry, general feedback..." class="w-full h-12 rounded-xl border border-[#E7E3D3] bg-[#F8F6EF] px-4 text-sm focus:border-[#16301F] focus:bg-white outline-none transition-all">
                                <span v-if="form.errors.subject" class="text-xs text-red-600 mt-1 block">{{ form.errors.subject }}</span>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#62655A] mb-1.5">Message *</label>
                                <textarea v-model="form.message" rows="5" required placeholder="How can our care team assist you today?" class="w-full rounded-xl border border-[#E7E3D3] bg-[#F8F6EF] p-4 text-sm focus:border-[#16301F] focus:bg-white outline-none transition-all resize-none"></textarea>
                                <span v-if="form.errors.message" class="text-xs text-red-600 mt-1 block">{{ form.errors.message }}</span>
                            </div>

                            <button type="submit" :disabled="form.processing" class="w-full md:w-auto px-8 py-3.5 rounded-full bg-[#16301F] text-white hover:bg-[#1E4029] font-semibold text-sm transition-colors disabled:opacity-50 cursor-pointer">
                                {{ form.processing ? 'Saving Message...' : 'Send Message' }}
                            </button>
                        </form>
                    </div>
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
</style>
