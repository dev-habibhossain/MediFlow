import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AdminLayout from '@/layouts/AdminLayout.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import DoctorLayout from '@/layouts/DoctorLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome' ||
                name === 'Home' ||
                name === 'About' ||
                name.startsWith('Departments/') ||
                name.startsWith('Doctors/') ||
                name.startsWith('Booking/') ||
                name === 'Contact' ||
                name === 'Faq' ||
                name === 'PrivacyPolicy' ||
                name === 'TermsOfService':
                return null;
            case name.startsWith('Admin/'):
                return AdminLayout;
            case name.startsWith('Doctor/'):
                return DoctorLayout;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
