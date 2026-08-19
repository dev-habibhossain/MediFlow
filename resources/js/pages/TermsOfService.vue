<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
</script>

<template>
    <PublicLayout>
        <div class="wrap py-12">
            <div class="max-w-3xl mx-auto bg-white border border-[#E7E3D3] rounded-3xl p-8 md:p-12 shadow-card mb-16">
                <span class="pill mb-4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Legal</span>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-6">Terms of Service</h1>
                <div class="prose text-sm text-[#62655A] leading-relaxed space-y-4">
                    <p>Welcome to MediFlow. By creating an account or booking appointments through our platform, you agree to the following terms.</p>
                    <h3 class="text-base font-bold text-[#16180F] mt-6">1. Appointment Booking</h3>
                    <p>Appointments booked through MediFlow reserve dedicated physician consultation slots. Cancellations or rescheduling should be completed at least 2 hours prior to the appointment start time.</p>
                    <h3 class="text-base font-bold text-[#16180F] mt-6">2. Account Responsibility</h3>
                    <p>Users are responsible for maintaining the accuracy of their contact details and medical history provided to attending physicians.</p>
                    <h3 class="text-base font-bold text-[#16180F] mt-6">3. Emergency Care</h3>
                    <p>MediFlow appointment booking is designed for non-emergency consultations. In the event of a medical emergency, please contact local emergency services immediately.</p>
                </div>
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
