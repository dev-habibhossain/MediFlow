<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import InputError from '@/components/InputError.vue'
import TextLink from '@/components/TextLink.vue'
import { Spinner } from '@/components/ui/spinner'
import { login } from '@/routes'
import { update } from '@/routes/password'

defineOptions({
    layout: {
        title: 'Set new password',
        description: 'Your new password must be different from previously used passwords.',
    },
})

const props = defineProps<{
    token: string
    email: string
    passwordRules: string
}>()

const inputEmail = ref(props.email)
const showPassword = ref(false)
const strengthScore = ref(0)
const strengthLabel = ref('Enter a password')

function togglePassword() {
    showPassword.value = !showPassword.value
}

function assessPasswordStrength(val: string) {
    if (!val) {
        strengthScore.value = 0
        strengthLabel.value = 'Enter a password'
        return
    }
    let score = 0
    if (val.length >= 8) score++
    if (/[A-Z]/.test(val)) score++
    if (/[0-9]/.test(val)) score++
    if (/[^A-Za-z0-9]/.test(val)) score++

    strengthScore.value = score
    const labels = ['Weak', 'Fair', 'Good', 'Strong']
    strengthLabel.value = labels[score - 1] || 'Weak'
}

function segmentColor(segmentIndex: number) {
    if (segmentIndex > strengthScore.value) return '#E7E3D3'
    const colors = ['#EF4444', '#F59E0B', '#22C55E', '#16A34A']
    return colors[strengthScore.value - 1] || '#EF4444'
}
</script>

<template>
    <Head title="Set New Password — MediFlow" />

    <Form
        v-bind="update.form()"
        :transform="(data) => ({ ...data, token, email: inputEmail })"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="auth-form"
    >
        <!-- EMAIL FIELD (READONLY) -->
        <div class="form-group">
            <label for="email" class="form-label">Account Email</label>
            <div class="input-wrap">
                <input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    v-model="inputEmail"
                    readonly
                    class="readonly-input"
                />
            </div>
            <InputError :message="errors.email" />
        </div>

        <!-- NEW PASSWORD FIELD -->
        <div class="form-group">
            <label for="password" class="form-label">New Password *</label>
            <div class="input-wrap">
                <input
                    id="password"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    autocomplete="new-password"
                    autofocus
                    required
                    placeholder="Create new password"
                    @input="assessPasswordStrength(($event.target as HTMLInputElement).value)"
                />
                <button
                    type="button"
                    class="input-icon-btn"
                    @click="togglePassword"
                    aria-label="Toggle password visibility"
                >
                    <svg v-if="!showPassword" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.45 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>

            <!-- STRENGTH METER -->
            <div class="strength-bar-wrap">
                <div class="strength-segment" :style="{ backgroundColor: segmentColor(1) }"></div>
                <div class="strength-segment" :style="{ backgroundColor: segmentColor(2) }"></div>
                <div class="strength-segment" :style="{ backgroundColor: segmentColor(3) }"></div>
                <div class="strength-segment" :style="{ backgroundColor: segmentColor(4) }"></div>
            </div>
            <span class="strength-text">{{ strengthLabel }}</span>
            <InputError :message="errors.password" />
        </div>

        <!-- CONFIRM NEW PASSWORD FIELD -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm New Password *</label>
            <div class="input-wrap">
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    autocomplete="new-password"
                    required
                    placeholder="Re-enter new password"
                />
            </div>
            <InputError :message="errors.password_confirmation" />
        </div>

        <!-- SUBMIT CTA -->
        <button
            type="submit"
            class="btn btn-primary"
            :disabled="processing"
            data-test="reset-password-button"
        >
            <Spinner v-if="processing" />
            Reset Password
        </button>

        <div class="auth-footer">
            Remember your password? <TextLink :href="login()" class="link-bold">Sign in</TextLink>
        </div>
    </Form>
</template>

<style scoped>
.auth-form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 18px;
}

.form-label {
    font-size: 12.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    color: #62655A;
    display: block;
    margin-bottom: 6px;
}

.input-wrap {
    position: relative;
}

.input-wrap input {
    width: 100%;
    height: 48px;
    border-radius: 10px;
    border: 1px solid #E7E3D3;
    background: #F8F6EF;
    padding: 0 16px;
    font-size: 14.5px;
    color: #16180F;
    transition: border-color 150ms ease, background-color 150ms ease;
    outline: none;
}

.readonly-input {
    background: #F0EEE3 !important;
    color: #62655A !important;
    cursor: not-allowed;
}

.input-wrap input:focus {
    border-color: #16301F;
    background: #FFFFFF;
}

.input-icon-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #62655A;
    background: none;
    border: none;
    cursor: pointer;
    transition: color 150ms ease;
}

.input-icon-btn:hover {
    color: #16180F;
}

.input-icon-btn svg {
    width: 18px;
    height: 18px;
}

.strength-bar-wrap {
    margin-top: 8px;
    display: flex;
    gap: 4px;
    height: 4px;
}

.strength-segment {
    flex: 1;
    height: 100%;
    border-radius: 2px;
    transition: background-color 200ms ease;
}

.strength-text {
    font-size: 12px;
    color: #62655A;
    margin-top: 4px;
    display: block;
    text-align: right;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 50px;
    padding: 0 28px;
    border-radius: 999px;
    font-size: 15px;
    font-weight: 600;
    transition: transform 150ms ease, background-color 150ms ease, box-shadow 150ms ease;
    width: 100%;
    cursor: pointer;
    border: none;
    margin-top: 8px;
}

.btn-primary {
    background: #16301F;
    color: #FFFFFF;
    box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06);
}

.btn-primary:hover {
    background: #1E4029;
    box-shadow: 0 4px 10px rgba(22,24,15,0.06), 0 16px 36px rgba(22,24,15,0.10);
}

.auth-footer {
    text-align: center;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid #E7E3D3;
    font-size: 14px;
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
