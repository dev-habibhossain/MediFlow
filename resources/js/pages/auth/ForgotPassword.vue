<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3'
import InputError from '@/components/InputError.vue'
import TextLink from '@/components/TextLink.vue'
import { Spinner } from '@/components/ui/spinner'
import { login } from '@/routes'
import { email } from '@/routes/password'

defineOptions({
    layout: {
        title: 'Forgot password?',
        description: "Enter your registered account email and we'll send you a password reset link.",
    },
})

defineProps<{
    status?: string
}>()
</script>

<template>
    <Head title="Reset Password — MediFlow" />

    <div
        v-if="status"
        class="mb-6 rounded-xl bg-emerald-50 p-4 text-center text-sm font-medium text-emerald-800 border border-emerald-200"
    >
        {{ status }}
    </div>

    <Form v-bind="email.form()" v-slot="{ errors, processing }" class="auth-form">
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-wrap">
                <input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    autofocus
                    required
                    placeholder="you@example.com"
                />
            </div>
            <InputError :message="errors.email" />
        </div>

        <button
            type="submit"
            class="btn btn-primary"
            :disabled="processing"
            data-test="email-password-reset-link-button"
        >
            <Spinner v-if="processing" />
            Send Reset Link
        </button>
    </Form>

    <div class="auth-footer">
        Remember your password? <TextLink :href="login()" class="link-bold">Sign in</TextLink>
    </div>
</template>

<style scoped>
.auth-form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 24px;
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

.input-wrap input:focus {
    border-color: #16301F;
    background: #FFFFFF;
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
