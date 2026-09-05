<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface NotificationItem {
    id: string;
    title?: string;
    message?: string;
    url?: string;
    text?: string;
    bg_class?: string;
    time?: string;
}

const page = usePage();
const isSidebarOpen = ref(false);
const isProfileOpen = ref(false);
const isNotificationsOpen = ref(false);

const user = computed(
    () =>
        page.props.auth?.user as
            { name?: string; email?: string; avatar_url?: string } | undefined,
);
const currentUrl = computed(() => page.url);

const notifications = computed<NotificationItem[]>(() => {
    const propNotifs = page.props.notifications as
        NotificationItem[] | undefined;

    if (propNotifs && propNotifs.length > 0) {
        return propNotifs;
    }

    return [
        {
            id: '1',
            title: 'Appointment Confirmed',
            message: 'Appointment confirmed with Dr. Sarah Jenkins.',
            url: '/patient/appointments',
            bg_class: 'bg-green',
            time: '2 hours ago',
        },
        {
            id: '2',
            title: 'Prescription Ready',
            message: 'New prescription uploaded for Amoxicillin 500mg.',
            url: '/patient/prescriptions',
            bg_class: 'bg-amber',
            time: 'Yesterday',
        },
    ];
});

function toggleSidebar() {
    isSidebarOpen.value = !isSidebarOpen.value;
}

function toggleProfile() {
    isProfileOpen.value = !isProfileOpen.value;

    if (isProfileOpen.value) {
        isNotificationsOpen.value = false;
    }
}

function toggleNotifications() {
    isNotificationsOpen.value = !isNotificationsOpen.value;

    if (isNotificationsOpen.value) {
        isProfileOpen.value = false;
    }
}

function closeDropdowns() {
    isProfileOpen.value = false;
    isNotificationsOpen.value = false;
}

function isActive(path: string) {
    if (path === '/patient/dashboard') {
        return (
            currentUrl.value === '/patient/dashboard' ||
            currentUrl.value === '/patient'
        );
    }

    return currentUrl.value === path || currentUrl.value.startsWith(path + '/');
}

function getInitials(name?: string) {
    if (!name) {
        return 'HH';
    }

    return name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
}
</script>

<template>
    <div class="patient-shell app-shell">
        <!-- SIDEBAR NAVIGATION -->
        <aside class="sidebar" :class="{ open: isSidebarOpen }">
            <div class="sidebar-brand">
                <Link
                    href="/patient/dashboard"
                    class="logo"
                    @click="closeDropdowns"
                >
                    <span class="logo-mark">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.2"
                        >
                            <path
                                d="M12 21s-7-4.35-9.5-8.5C.6 8.9 2.3 5 6 5c2 0 3.3 1.1 4 2 .7-.9 2-2 4-2 3.7 0 5.4 3.9 3.5 7.5C19 16.65 12 21 12 21z"
                            />
                        </svg>
                    </span>
                    MediFlow
                </Link>
                <button class="mobile-toggle" @click="toggleSidebar">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        width="18"
                        height="18"
                    >
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-label">Main Menu</div>
                <Link
                    href="/patient/dashboard"
                    class="nav-item"
                    :class="{ active: isActive('/patient/dashboard') }"
                    @click="closeDropdowns"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                    Overview
                </Link>

                <Link
                    href="/patient/appointments"
                    class="nav-item"
                    :class="{ active: isActive('/patient/appointments') }"
                    @click="closeDropdowns"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                    My Appointments
                </Link>

                <Link
                    href="/doctors"
                    class="nav-item"
                    :class="{ active: isActive('/doctors') }"
                    @click="closeDropdowns"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M19 8v6M22 11h-6" />
                    </svg>
                    Find Doctors
                </Link>

                <Link
                    href="/patient/medical-records"
                    class="nav-item"
                    :class="{ active: isActive('/patient/medical-records') }"
                    @click="closeDropdowns"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                    Medical History
                </Link>

                <Link
                    href="/patient/prescriptions"
                    class="nav-item"
                    :class="{ active: isActive('/patient/prescriptions') }"
                    @click="closeDropdowns"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"
                        />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                    </svg>
                    Prescriptions
                </Link>

                <Link
                    href="/patient/payments"
                    class="nav-item"
                    :class="{ active: isActive('/patient/payments') }"
                    @click="closeDropdowns"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <rect
                            x="1"
                            y="4"
                            width="22"
                            height="16"
                            rx="2"
                            ry="2"
                        />
                        <line x1="1" y1="10" x2="23" y2="10" />
                    </svg>
                    Payment History
                </Link>

                <div class="menu-label">Settings</div>
                <Link
                    href="/patient/notifications"
                    class="nav-item"
                    :class="{ active: isActive('/patient/notifications') }"
                    @click="closeDropdowns"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    Notifications
                </Link>

                <Link
                    href="/patient/settings/profile"
                    class="nav-item"
                    :class="{ active: isActive('/patient/settings/profile') }"
                    @click="closeDropdowns"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    Profile Settings
                </Link>

                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="nav-item text-danger"
                    @click="closeDropdowns"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                    Sign Out
                </Link>
            </nav>

            <div class="sidebar-footer">
                <div class="user-pill">
                    <div class="user-avatar">{{ getInitials(user?.name) }}</div>
                    <div class="user-meta">
                        <b>{{ user?.name || 'Patient Account' }}</b>
                        <span>Patient Portal</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN WRAPPER -->
        <div class="main-content">
            <!-- TOP HEADER -->
            <header class="top-bar">
                <div style="display: flex; align-items: center; gap: 12px">
                    <button class="mobile-toggle" @click="toggleSidebar">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            width="18"
                            height="18"
                        >
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>
                    <div class="top-left">
                        <h1>Patient Portal</h1>
                        <p>
                            Manage consultations, health records, and
                            prescriptions
                        </p>
                    </div>
                </div>

                <div
                    class="top-right"
                    style="
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        position: relative;
                    "
                >
                    <!-- NOTIFICATION BELL ICON -->
                    <div class="header-dropdown-wrap">
                        <button
                            class="btn-icon-head"
                            title="Notifications"
                            @click.stop="toggleNotifications"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="18"
                            >
                                <path
                                    d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"
                                />
                                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                            </svg>
                            <span class="notif-badge-dot"></span>
                        </button>

                        <!-- NOTIFICATIONS DROPDOWN PANEL -->
                        <div
                            v-if="isNotificationsOpen"
                            class="head-dropdown-panel notif-panel"
                        >
                            <div class="dropdown-header">
                                <b>Notifications</b>
                                <span class="badge-count"
                                    >{{ notifications.length }} New</span
                                >
                            </div>
                            <div class="dropdown-body">
                                <Link
                                    v-for="notif in notifications"
                                    :key="notif.id"
                                    :href="
                                        notif.url || '/patient/notifications'
                                    "
                                    class="notif-item"
                                    @click="closeDropdowns"
                                >
                                    <div
                                        class="notif-dot"
                                        :class="notif.bg_class || 'bg-green'"
                                    ></div>
                                    <div class="notif-text">
                                        <p>
                                            <strong v-if="notif.title"
                                                >{{ notif.title }}: </strong
                                            >{{ notif.message || notif.text }}
                                        </p>
                                        <small>{{ notif.time }}</small>
                                    </div>
                                </Link>
                            </div>
                            <div class="dropdown-footer">
                                <Link
                                    href="/patient/notifications"
                                    @click="closeDropdowns"
                                    >View All Notifications →</Link
                                >
                            </div>
                        </div>
                    </div>

                    <!-- NEW APPOINTMENT QUICK BUTTON -->
                    <Link href="/doctors" class="btn-book-quick">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.5"
                            width="14"
                            height="14"
                        >
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        New Appointment
                    </Link>

                    <!-- CLICKABLE PROFILE ICON -->
                    <div class="header-dropdown-wrap">
                        <button
                            class="user-avatar-btn"
                            title="User Profile Menu"
                            @click.stop="toggleProfile"
                        >
                            <img
                                v-if="user?.avatar_url"
                                :src="user.avatar_url"
                                alt="Avatar"
                                class="avatar-circle-img"
                            />
                            <span v-else class="avatar-circle">{{
                                getInitials(user?.name)
                            }}</span>
                        </button>

                        <!-- PROFILE DROPDOWN MENU -->
                        <div
                            v-if="isProfileOpen"
                            class="head-dropdown-panel profile-panel"
                        >
                            <div class="user-dropdown-info">
                                <b>{{ user?.name || 'Patient Account' }}</b>
                                <span>{{
                                    user?.email || 'patient@mediflow.com'
                                }}</span>
                                <div class="user-role-badge">Patient</div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <Link
                                href="/patient/settings/profile"
                                class="dropdown-menu-item"
                                @click="closeDropdowns"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    width="16"
                                    height="16"
                                >
                                    <path
                                        d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                                    />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                Profile Settings
                            </Link>

                            <Link
                                href="/patient/appointments"
                                class="dropdown-menu-item"
                                @click="closeDropdowns"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    width="16"
                                    height="16"
                                >
                                    <rect
                                        x="3"
                                        y="4"
                                        width="18"
                                        height="18"
                                        rx="2"
                                    />
                                    <path d="M16 2v4M8 2v4M3 10h18" />
                                </svg>
                                My Appointments
                            </Link>

                            <div class="dropdown-divider"></div>

                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                class="dropdown-menu-item text-danger"
                                @click="closeDropdowns"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    width="16"
                                    height="16"
                                >
                                    <path
                                        d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"
                                    />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" y1="12" x2="9" y2="12" />
                                </svg>
                                Sign Out
                            </Link>
                        </div>
                    </div>
                </div>
            </header>

            <!-- MAIN CONTENT CANVAS -->
            <main class="dashboard-container" @click="closeDropdowns">
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
.patient-shell {
    --cream: #f8f6ef;
    --cream-alt: #f0eee3;
    --card: #ffffff;
    --lime: #ddf15c;
    --lime-soft: #eef7c4;
    --lime-text: #3b4a12;
    --forest: #16301f;
    --forest-2: #1e4029;
    --ink: #16180f;
    --ink-muted: #62655a;
    --line: #e7e3d3;
    --gray-card: #efede4;

    --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    --font-mono: 'JetBrains Mono', ui-monospace, monospace;

    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 24px;

    --shadow-sm: 0 1px 2px rgba(22, 24, 15, 0.04);
    --shadow-card:
        0 1px 3px rgba(22, 24, 15, 0.05), 0 6px 16px rgba(22, 24, 15, 0.04);
    --shadow-lift: 0 4px 12px rgba(22, 24, 15, 0.08);

    font-family: var(--font);
    color: var(--ink);
    background: var(--cream);
    min-height: 100vh;
    display: flex;
    width: 100%;
}

.patient-shell .app-shell {
    display: flex;
    width: 100%;
    min-height: 100vh;
}

.patient-shell .sidebar {
    width: 260px;
    background: var(--card);
    border-right: 1px solid var(--line);
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 30;
    transition: transform 200ms ease;
}
.patient-shell .sidebar-brand {
    padding: 20px 24px;
    border-bottom: 1px solid var(--line);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.patient-shell .logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 800;
    font-size: 18px;
    color: var(--forest);
    text-decoration: none;
}
.patient-shell .logo-mark {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--forest);
    display: flex;
    align-items: center;
    justify-content: center;
}
.patient-shell .logo-mark svg {
    width: 16px;
    height: 16px;
    color: var(--lime);
}

.patient-shell .sidebar-menu {
    padding: 20px 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
    overflow-y: auto;
}
.patient-shell .menu-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--ink-muted);
    padding: 8px 12px;
    margin-top: 12px;
}
.patient-shell .menu-label:first-child {
    margin-top: 0;
}

.patient-shell .nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: var(--radius-sm);
    font-size: 14px;
    font-weight: 600;
    color: var(--ink-muted);
    transition: all 150ms ease;
    text-decoration: none;
    border: 0;
    background: transparent;
    width: 100%;
    text-align: left;
    cursor: pointer;
}
.patient-shell .nav-item svg {
    width: 18px;
    height: 18px;
    color: var(--ink-muted);
    transition: color 150ms ease;
    flex-shrink: 0;
}
.patient-shell .nav-item:hover {
    background: var(--cream);
    color: var(--ink);
}
.patient-shell .nav-item:hover svg {
    color: var(--forest);
}
.patient-shell .nav-item.active {
    background: var(--forest);
    color: #fff;
}
.patient-shell .nav-item.active svg {
    color: var(--lime);
}

.patient-shell .sidebar-footer {
    padding: 16px;
    border-top: 1px solid var(--line);
}
.patient-shell .user-pill {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
    border-radius: var(--radius-sm);
    background: var(--cream);
}
.patient-shell .user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--lime);
    color: var(--lime-text);
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}
.patient-shell .user-meta {
    flex: 1;
    overflow: hidden;
}
.patient-shell .user-meta b {
    display: block;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--ink);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.patient-shell .user-meta span {
    display: block;
    font-size: 11.5px;
    color: var(--ink-muted);
}

.patient-shell .main-content {
    flex: 1;
    margin-left: 260px;
    min-width: 0;
    display: flex;
    flex-direction: column;
}
@media (max-width: 992px) {
    .patient-shell .sidebar {
        transform: translateX(-100%);
    }
    .patient-shell .main-content {
        margin-left: 0;
    }
    .patient-shell .sidebar.open {
        transform: translateX(0);
    }
}

.patient-shell .top-bar {
    height: 68px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--line);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 32px;
    position: sticky;
    top: 0;
    z-index: 20;
}
.patient-shell .mobile-toggle {
    display: none;
    width: 36px;
    height: 36px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--line);
    align-items: center;
    justify-content: center;
    background: white;
}
@media (max-width: 992px) {
    .patient-shell .mobile-toggle {
        display: flex;
    }
}

.patient-shell .top-left h1 {
    font-size: 20px;
    font-weight: 800;
    color: var(--forest);
    letter-spacing: -0.01em;
    margin: 0;
}
.patient-shell .top-left p {
    font-size: 12.5px;
    color: var(--ink-muted);
    margin: 0;
}

.patient-shell .top-right {
    display: flex;
    align-items: center;
    gap: 12px;
}
.patient-shell .btn-icon-head {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid var(--line);
    background: var(--card);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ink-muted);
    position: relative;
    transition: all 150ms ease;
    cursor: pointer;
    outline: none;
}
.patient-shell .btn-icon-head:hover {
    border-color: var(--forest);
    color: var(--forest);
}
.patient-shell .notif-badge-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ef4444;
    position: absolute;
    top: 8px;
    right: 8px;
    border: 2px solid var(--card);
}

.patient-shell .btn-book-quick {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 40px;
    padding: 0 18px;
    border-radius: 999px;
    background: var(--forest);
    color: #fff;
    font-size: 13.5px;
    font-weight: 600;
    box-shadow: var(--shadow-sm);
    text-decoration: none;
    transition: background-color 150ms ease;
}
.patient-shell .btn-book-quick:hover {
    background: var(--forest-2);
}

.patient-shell .header-dropdown-wrap {
    position: relative;
    display: inline-flex;
}
.patient-shell .user-avatar-btn {
    position: relative;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    outline: none;
}
.patient-shell .avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--lime);
    color: var(--lime-text);
    font-weight: 800;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-sm);
    transition: all 150ms ease;
}
.patient-shell .avatar-circle-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid transparent;
    box-shadow: var(--shadow-sm);
    display: block;
}
.patient-shell .user-avatar-btn:hover .avatar-circle {
    transform: scale(1.05);
}

.patient-shell .head-dropdown-panel {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lift);
    z-index: 50;
    animation: fadeInDown 150ms ease-out;
}
.patient-shell .notif-panel {
    width: 300px;
}
.patient-shell .profile-panel {
    width: 230px;
    padding: 12px;
}

.patient-shell .dropdown-header {
    padding: 12px 16px;
    border-bottom: 1px solid var(--line);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.patient-shell .dropdown-header b {
    font-size: 13px;
    color: var(--forest);
}
.patient-shell .badge-count {
    font-size: 11px;
    font-weight: 700;
    color: #15803d;
    background: #dcfce7;
    padding: 2px 8px;
    border-radius: 999px;
}

.patient-shell .dropdown-body {
    padding: 8px 0;
    max-height: 260px;
    overflow-y: auto;
}
.patient-shell .notif-item {
    padding: 10px 16px;
    display: flex;
    gap: 10px;
    align-items: flex-start;
    transition: background 150ms ease;
    cursor: pointer;
    text-decoration: none;
}
.patient-shell .notif-item:hover {
    background: var(--cream);
}
.patient-shell .notif-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-top: 5px;
    flex-shrink: 0;
}
.patient-shell .notif-dot.bg-green {
    background: #16a34a;
}
.patient-shell .notif-dot.bg-amber {
    background: #d97706;
}
.patient-shell .notif-text p {
    font-size: 12px;
    color: var(--ink);
    margin: 0 0 2px 0;
    line-height: 1.3;
}
.patient-shell .notif-text small {
    font-size: 10.5px;
    color: var(--ink-muted);
    font-family: var(--font-mono);
}

.patient-shell .dropdown-footer {
    padding: 10px 16px;
    border-top: 1px solid var(--line);
    text-align: center;
}
.patient-shell .dropdown-footer a {
    font-size: 12px;
    font-weight: 700;
    color: var(--forest);
    text-decoration: none;
}

.patient-shell .user-dropdown-info {
    padding: 8px 8px 12px 8px;
}
.patient-shell .user-dropdown-info b {
    font-size: 13.5px;
    font-weight: 800;
    color: var(--forest);
    display: block;
}
.patient-shell .user-dropdown-info span {
    font-size: 11.5px;
    color: var(--ink-muted);
    display: block;
    margin-bottom: 6px;
}
.patient-shell .user-role-badge {
    display: inline-block;
    font-size: 10px;
    font-weight: 700;
    background: var(--lime-soft);
    color: var(--lime-text);
    padding: 2px 8px;
    border-radius: 4px;
    border: 1px solid var(--line);
}

.patient-shell .dropdown-divider {
    height: 1px;
    background: var(--line);
    margin: 6px 0;
}

.patient-shell .dropdown-menu-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: var(--radius-sm);
    font-size: 13px;
    font-weight: 600;
    color: var(--ink);
    text-decoration: none;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
    cursor: pointer;
    transition: all 150ms ease;
}
.patient-shell .dropdown-menu-item:hover {
    background: var(--cream);
    color: var(--forest);
}
.patient-shell .dropdown-menu-item.text-danger {
    color: #dc2626;
}
.patient-shell .dropdown-menu-item.text-danger:hover {
    background: #fef2f2;
    color: #dc2626;
}

.patient-shell .dashboard-container {
    padding: 32px;
    max-width: 1280px;
    width: 100%;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 28px;
}
</style>
