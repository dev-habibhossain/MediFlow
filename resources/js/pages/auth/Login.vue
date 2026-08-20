<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import InputError from '@/components/InputError.vue'
import TextLink from '@/components/TextLink.vue'
import { Spinner } from '@/components/ui/spinner'
import { register } from '@/routes'
import { store } from '@/routes/login'
import { request } from '@/routes/password'

defineOptions({
    layout: {
        title: 'Welcome back',
        description: 'Sign in to access your appointments & medical records',
    },
})

defineProps<{
    status?: string
    canResetPassword: boolean
}>()

const showPassword = ref(false)

function togglePassword() {
    showPassword.value = !showPassword.value
}
</script>

<template>
    <Head title="Sign In — MediFlow" />

    <div
        v-if="status"
        class="mb-4 rounded-xl bg-emerald-50 p-3 text-center text-sm font-medium text-emerald-800 border border-emerald-200"
    >
        {{ status }}
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="auth-form"
    >
        <!-- EMAIL FIELD -->
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-wrap">
                <input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="you@example.com"
                />
            </div>
            <InputError :message="errors.email" />
        </div>

        <!-- PASSWORD FIELD -->
        <div class="form-group">
            <div class="form-label-row">
                <label for="password" class="form-label">Password</label>
                <TextLink
                    v-if="canResetPassword"
                    :href="request()"
                    class="forgot-link"
                    :tabindex="5"
                >
                    Forgot password?
                </TextLink>
            </div>
            <div class="input-wrap">
                <input
                    id="password"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="••••••••"
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
            <InputError :message="errors.password" />
        </div>

        <!-- REMEMBER ME -->
        <label class="remember-row">
            <input type="checkbox" id="remember" name="remember" :tabindex="3" checked />
            <span>Remember this device for 30 days</span>
        </label>

        <!-- SUBMIT CTA -->
        <button
            type="submit"
            class="btn btn-primary"
            :tabindex="4"
            :disabled="processing"
            data-test="login-button"
        >
            <Spinner v-if="processing" />
            Sign in to Account
        </button>

        <div class="auth-footer">
            Don't have an account? <TextLink :href="register()" :tabindex="6" class="link-bold">Create account</TextLink>
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

.form-label-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
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

.forgot-link {
    font-size: 13px;
    font-weight: 600;
    color: #16301F;
    text-decoration: none;
}
.forgot-link:hover {
    text-decoration: underline;
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

.remember-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 24px;
    cursor: pointer;
}

.remember-row input[type="checkbox"] {
    width: 18px;
    height: 18px;
    border-radius: 4px;
    accent-color: #16301F;
    cursor: pointer;
}

.remember-row span {
    font-size: 13.5px;
    color: #62655A;
    font-weight: 500;
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
