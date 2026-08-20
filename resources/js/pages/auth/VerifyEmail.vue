<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'
import TextLink from '@/components/TextLink.vue'
import { Spinner } from '@/components/ui/spinner'
import { logout } from '@/routes'
import { send } from '@/routes/verification'

defineOptions({
    layout: {
        title: 'Check your email',
        description: 'Please verify your email address to complete your MediFlow account setup.',
    },
})

defineProps<{
    status?: string
}>()

const countdown = ref(59)
const timerDisabled = ref(true)
let timerInterval: any = null

onMounted(() => {
    startCountdown()
})

onUnmounted(() => {
    if (timerInterval) clearInterval(timerInterval)
})

function startCountdown() {
    countdown.value = 59
    timerDisabled.value = true
    if (timerInterval) clearInterval(timerInterval)
    timerInterval = setInterval(() => {
        countdown.value--
        if (countdown.value <= 0) {
            clearInterval(timerInterval)
            timerDisabled.value = false
        }
    }, 1000)
}
</script>

<template>
    <Head title="Check Your Email — MediFlow" />

    <div
        v-if="status === 'verification-link-sent'"
        class="mb-6 rounded-xl bg-emerald-50 p-4 text-center text-sm font-medium text-emerald-800 border border-emerald-200"
    >
        A new verification link has been sent to your email address.
    </div>

    <!-- QUICK MAIL PROVIDER LINKS -->
    <div class="mail-shortcuts">
        <a href="https://mail.google.com" target="_blank" rel="noopener noreferrer" class="mail-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> Gmail
        </a>
        <a href="https://outlook.live.com" target="_blank" rel="noopener noreferrer" class="mail-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 7L2 7"/></svg> Outlook
        </a>
    </div>

    <!-- RESEND SECTION WITH COUNTDOWN -->
    <Form
        v-bind="send.form()"
        @submit="startCountdown"
        class="resend-block"
        v-slot="{ processing }"
    >
        <p>Didn't receive the email? Check your spam folder or request a new link.</p>
        <button
            type="submit"
            class="resend-btn"
            :disabled="timerDisabled || processing"
        >
            <Spinner v-if="processing" />
            <span v-if="timerDisabled">Resend email ({{ countdown }}s)</span>
            <span v-else>Resend verification email</span>
        </button>
    </Form>

    <div class="auth-footer">
        Need to sign out? <TextLink :href="logout()" as="button" class="link-bold">Log out</TextLink>
    </div>
</template>

<style scoped>
.mail-shortcuts {
    display: flex;
    gap: 10px;
    margin-bottom: 24px;
}

.mail-btn {
    flex: 1;
    height: 44px;
    border-radius: 10px;
    border: 1px solid #E7E3D3;
    background: #F8F6EF;
    font-size: 13.5px;
    font-weight: 600;
    color: #16180F;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
    transition: border-color 150ms ease, background-color 150ms ease;
}

.mail-btn:hover {
    border-color: #16301F;
    background: #FFFFFF;
}

.mail-btn svg {
    width: 16px;
    height: 16px;
}

.resend-block {
    background: #F8F6EF;
    border: 1px solid #E7E3D3;
    border-radius: 16px;
    padding: 18px;
    margin-bottom: 24px;
    text-align: center;
}

.resend-block p {
    font-size: 13px;
    color: #62655A;
    margin-bottom: 10px;
}

.resend-btn {
    font-size: 13.5px;
    font-weight: 700;
    color: #16301F;
    cursor: pointer;
    background: none;
    border: none;
    text-decoration: underline;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    justify-content: center;
}

.resend-btn:disabled {
    color: #62655A;
    text-decoration: none;
    cursor: not-allowed;
    opacity: 0.6;
}

.auth-footer {
    text-align: center;
    margin-top: 20px;
    padding-top: 18px;
    border-top: 1px solid #E7E3D3;
    font-size: 13.5px;
    color: #62655A;
}

.link-bold {
    font-weight: 700;
    color: #16301F;
    text-decoration: none;
}
.link-bold:hover {
    text-decoration: underline;
}
</style>
