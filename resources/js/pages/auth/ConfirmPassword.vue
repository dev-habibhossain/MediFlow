<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import InputError from '@/components/InputError.vue'
import { Spinner } from '@/components/ui/spinner'
import { store } from '@/routes/password/confirm'

defineOptions({
    layout: {
        title: 'Confirm password',
        description: 'This is a secure area of the application. Please confirm your password before continuing.',
    },
})

const showPassword = ref(false)

function togglePassword() {
    showPassword.value = !showPassword.value
}
</script>

<template>
    <Head title="Confirm Password — MediFlow" />

    <Form
        v-bind="store.form()"
        reset-on-success
        v-slot="{ errors, processing }"
        class="auth-form"
    >
        <div class="form-group">
            <label for="password" class="form-label">Password *</label>
            <div class="input-wrap">
                <input
                    id="password"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    autofocus
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

        <button
            type="submit"
            class="btn btn-primary"
            :disabled="processing"
            data-test="confirm-password-button"
        >
            <Spinner v-if="processing" />
            Confirm Password
        </button>
    </Form>
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
</style>
