<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface NotificationItem {
    id: string
    title: string
    message: string
    url: string
    type?: string
    bg_class?: string
    time?: string
}

const page = usePage()
const isSidebarOpen = ref(false)
const isProfileOpen = ref(false)
const isNotificationsOpen = ref(false)

const user = computed(() => page.props.auth?.user as { name?: string; email?: string; avatar_url?: string } | undefined)
const currentUrl = computed(() => page.url)
const notifications = computed(() => (page.props.notifications as NotificationItem[]) || [])

function toggleSidebar() {
    isSidebarOpen.value = !isSidebarOpen.value
}

function toggleProfile() {
    isProfileOpen.value = !isProfileOpen.value
    if (isProfileOpen.value) {
        isNotificationsOpen.value = false
    }
}

function toggleNotifications() {
    isNotificationsOpen.value = !isNotificationsOpen.value
    if (isNotificationsOpen.value) {
        isProfileOpen.value = false
    }
}

function closeDropdowns() {
    isProfileOpen.value = false
    isNotificationsOpen.value = false
}

function isActive(path: string) {
    return currentUrl.value === path || currentUrl.value.startsWith(path + '/')
}

function getInitials(name?: string) {
    if (!name) return 'ADM'
    return name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2)
}
</script>

<template>
    <div class="admin-shell app-shell">
        <!-- SIDEBAR NAVIGATION -->
        <aside class="sidebar" :class="{ open: isSidebarOpen }">
            <div class="sidebar-brand">
                <Link href="/admin/dashboard" class="logo" @click="closeDropdowns">
                    <span class="logo-mark">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M12 21s-7-4.35-9.5-8.5C.6 8.9 2.3 5 6 5c2 0 3.3 1.1 4 2 .7-.9 2-2 4-2 3.7 0 5.4 3.9 3.5 7.5C19 16.65 12 21 12 21z"/>
                        </svg>
                    </span>
                    MediFlow Admin
                </Link>
                <button class="mobile-toggle" @click="toggleSidebar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-label">Main Console</div>
                <Link href="/admin/dashboard" class="nav-item" :class="{ active: isActive('/admin/dashboard') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Dashboard Home
                </Link>

                <div class="menu-label">Management</div>
                <Link href="/admin/doctors" class="nav-item" :class="{ active: isActive('/admin/doctors') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Doctors List
                </Link>
                <Link href="/admin/patients" class="nav-item" :class="{ active: isActive('/admin/patients') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    </svg>
                    Patients List
                </Link>
                <Link href="/admin/departments" class="nav-item" :class="{ active: isActive('/admin/departments') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 21h18M3 7v14M21 7v14M6 3h12a2 2 0 0 1 2 2v2H4V5a2 2 0 0 1 2-2z"/>
                    </svg>
                    Departments
                </Link>
                <Link href="/admin/appointments" class="nav-item" :class="{ active: isActive('/admin/appointments') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    All Appointments
                </Link>

                <div class="menu-label">Analytics & Access</div>
                <Link href="/admin/reports" class="nav-item" :class="{ active: isActive('/admin/reports') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    Reports Overview
                </Link>
                <Link href="/admin/users" class="nav-item" :class="{ active: isActive('/admin/users') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    User Accounts
                </Link>
                <Link href="/admin/roles" class="nav-item" :class="{ active: isActive('/admin/roles') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    Roles & Permissions
                </Link>
                <Link href="/admin/activity-logs" class="nav-item" :class="{ active: isActive('/admin/activity-logs') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Activity Logs
                </Link>

                <div class="menu-label">Reviews & Announcements</div>
                <Link href="/admin/reviews" class="nav-item" :class="{ active: isActive('/admin/reviews') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    Reviews Moderation
                </Link>
                <Link href="/admin/announcements" class="nav-item" :class="{ active: isActive('/admin/announcements') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    Announcements
                </Link>

                <div class="menu-label">Payments (Phase 3)</div>
                <Link href="/admin/payments" class="nav-item" :class="{ active: isActive('/admin/payments') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                    Transactions List
                </Link>

                <div class="menu-label">System Settings</div>
                <Link href="/admin/profile" class="nav-item" :class="{ active: isActive('/admin/profile') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Personal Profile Settings
                </Link>
                <Link href="/admin/settings" class="nav-item" :class="{ active: isActive('/admin/settings') }" @click="closeDropdowns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                    Hospital Settings
                </Link>
            </nav>
        </aside>

        <!-- MAIN WRAPPER -->
        <div class="main-content">
            <!-- TOP HEADER -->
            <header class="top-bar">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button class="mobile-toggle" @click="toggleSidebar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                            <line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>
                    <div class="top-left">
                        <!-- Clean header left space -->
                    </div>
                </div>

                <div class="top-right" style="display: flex; align-items: center; gap: 16px; position: relative;">
                    <span class="status-pill">
                        <span class="status-dot"></span> System Status: Optimal
                    </span>

                    <!-- NOTIFICATION BELL ICON -->
                    <div class="header-dropdown-wrap">
                        <button class="btn-icon-head" title="Notifications" @click.stop="toggleNotifications">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="19" height="19">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            <span v-if="notifications.length > 0" class="notif-badge">{{ notifications.length }}</span>
                        </button>

                        <!-- NOTIFICATIONS DROPDOWN -->
                        <div v-if="isNotificationsOpen" class="head-dropdown-panel notif-panel">
                            <div class="dropdown-header">
                                <b>Notifications</b>
                                <span class="badge-count">{{ notifications.length }} New</span>
                            </div>
                            <div class="dropdown-body">
                                <Link
                                    v-for="notif in notifications"
                                    :key="notif.id"
                                    :href="notif.url"
                                    class="notif-item"
                                    @click="closeDropdowns"
                                >
                                    <div class="notif-dot" :class="notif.bg_class || 'bg-blue'"></div>
                                    <div class="notif-text">
                                        <p><strong>{{ notif.title }}</strong>: {{ notif.message }}</p>
                                        <small>{{ notif.time }}</small>
                                    </div>
                                </Link>

                                <div v-if="notifications.length === 0" class="p-4 text-center text-xs text-[var(--ink-muted)]">
                                    No new system notifications.
                                </div>
                            </div>
                            <div class="dropdown-footer">
                                <Link href="/admin/announcements" @click="closeDropdowns">View All Alerts →</Link>
                            </div>
                        </div>
                    </div>

                    <!-- CLICKABLE CIRCLE PROFILE PIC -->
                    <div class="header-dropdown-wrap">
                        <button class="user-avatar-btn" title="User Profile Menu" @click.stop="toggleProfile">
                            <img v-if="user?.avatar_url" :src="user.avatar_url" alt="Avatar" class="avatar-circle-img" />
                            <span v-else class="avatar-circle">{{ getInitials(user?.name) }}</span>
                            <span class="online-indicator"></span>
                        </button>

                        <!-- PROFILE DROPDOWN MENU -->
                        <div v-if="isProfileOpen" class="head-dropdown-panel profile-panel">
                            <div class="user-dropdown-info">
                                <b>{{ user?.name || 'System Admin' }}</b>
                                <span>{{ user?.email || 'admin@mediflow.com' }}</span>
                                <div class="user-role-badge">Admin</div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <Link href="/admin/profile" class="dropdown-menu-item" @click="closeDropdowns">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                                Settings
                            </Link>

                            <Link href="/admin/settings" class="dropdown-menu-item" @click="closeDropdowns">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                </svg>
                                Hospital Settings
                            </Link>

                            <div class="dropdown-divider"></div>

                            <Link href="/logout" method="post" as="button" class="dropdown-menu-item text-danger" @click="closeDropdowns">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
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
.admin-shell {
    --cream:      #F8F6EF;
    --cream-alt:  #F0EEE3;
    --card:       #FFFFFF;
    --lime:       #DDF15C;
    --lime-soft:  #EEF7C4;
    --lime-text:  #3B4A12;
    --forest:     #16301F;
    --forest-2:   #1E4029;
    --ink:        #16180F;
    --ink-muted:  #62655A;
    --line:       #E7E3D3;
    --gray-card:  #EFEDE4;

    --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    --font-mono: 'JetBrains Mono', ui-monospace, monospace;

    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 24px;

    --shadow-sm: 0 1px 2px rgba(22,24,15,0.04);
    --shadow-card: 0 1px 3px rgba(22,24,15,0.05), 0 6px 16px rgba(22,24,15,0.04);
    --shadow-lift: 0 4px 12px rgba(22,24,15,0.08);

    font-family: var(--font);
    color: var(--ink);
    background: var(--cream);
    min-height: 100vh;
    display: flex;
    width: 100%;
}

.admin-shell .app-shell { display: flex; width: 100%; min-height: 100vh; }

.admin-shell .sidebar { width: 260px; background: var(--card); border-right: 1px solid var(--line); display: flex; flex-direction: column; position: fixed; top: 0; bottom: 0; left: 0; z-index: 30; transition: transform 200ms ease; }
.admin-shell .sidebar-brand { padding: 20px 24px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; }
.admin-shell .logo { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 18px; color: var(--forest); text-decoration: none; }
.admin-shell .logo-mark { width: 32px; height: 32px; border-radius: 8px; background: var(--forest); display: flex; align-items: center; justify-content: center; }
.admin-shell .logo-mark svg { width: 16px; height: 16px; color: var(--lime); }

.admin-shell .sidebar-menu { padding: 20px 16px; flex: 1; display: flex; flex-direction: column; gap: 4px; overflow-y: auto; }
.admin-shell .menu-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-muted); padding: 8px 12px; margin-top: 12px; }
.admin-shell .menu-label:first-child { margin-top: 0; }

.admin-shell .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: var(--radius-sm); font-size: 13.5px; font-weight: 600; color: var(--ink-muted); transition: all 150ms ease; text-decoration: none; border: 0; background: transparent; width: 100%; text-align: left; cursor: pointer; }
.admin-shell .nav-item svg { width: 18px; height: 18px; color: var(--ink-muted); transition: color 150ms ease; flex-shrink: 0; }
.admin-shell .nav-item:hover { background: var(--cream); color: var(--ink); }
.admin-shell .nav-item:hover svg { color: var(--forest); }
.admin-shell .nav-item.active { background: var(--forest); color: #fff; }
.admin-shell .nav-item.active svg { color: var(--lime); }

.admin-shell .main-content { flex: 1; margin-left: 260px; min-width: 0; display: flex; flex-direction: column; }
@media (max-width: 992px) {
    .admin-shell .sidebar { transform: translateX(-100%); }
    .admin-shell .main-content { margin-left: 0; }
    .admin-shell .sidebar.open { transform: translateX(0); }
}

.admin-shell .top-bar { height: 68px; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; padding: 0 32px; position: sticky; top: 0; z-index: 20; }
.admin-shell .mobile-toggle { display: none; width: 36px; height: 36px; border-radius: var(--radius-sm); border: 1px solid var(--line); align-items: center; justify-content: center; background: white; }
@media (max-width: 992px) { .admin-shell .mobile-toggle { display: flex; } }

.admin-shell .top-left h1 { font-size: 20px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin: 0; }
.admin-shell .top-left p { font-size: 12.5px; color: var(--ink-muted); margin: 0; }

.admin-shell .top-right { display: flex; align-items: center; gap: 16px; }
.admin-shell .status-pill { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: #15803D; background: #DCFCE7; border: 1px solid #BBF7D0; padding: 5px 12px; border-radius: 999px; }
.admin-shell .status-dot { width: 7px; height: 7px; border-radius: 50%; background: #16A34A; }

.admin-shell .header-dropdown-wrap { position: relative; display: inline-flex; }

.admin-shell .btn-icon-head { width: 40px; height: 40px; border-radius: 50%; background: var(--cream); border: 1px solid var(--line); color: var(--forest); display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative; transition: all 150ms ease; outline: none; }
.admin-shell .btn-icon-head:hover { background: var(--card); border-color: var(--forest); transform: translateY(-1px); }

.admin-shell .notif-badge { position: absolute; top: -2px; right: -2px; background: #DC2626; color: white; font-size: 10px; font-weight: 800; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid var(--card); }

.admin-shell .user-avatar-btn { position: relative; background: none; border: none; padding: 0; cursor: pointer; outline: none; }
.admin-shell .avatar-circle { width: 40px; height: 40px; border-radius: 50%; background: var(--forest); color: var(--lime); font-weight: 800; font-size: 13.5px; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm); transition: all 150ms ease; border: 2px solid transparent; }
.admin-shell .avatar-circle-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid transparent; box-shadow: var(--shadow-sm); transition: all 150ms ease; display: block; }
.admin-shell .user-avatar-btn:hover .avatar-circle,
.admin-shell .user-avatar-btn:hover .avatar-circle-img { border-color: var(--lime); transform: scale(1.05); }

.admin-shell .online-indicator { position: absolute; bottom: 0; right: 0; width: 11px; height: 11px; border-radius: 50%; background: #16A34A; border: 2px solid var(--card); }

.admin-shell .head-dropdown-panel { position: absolute; top: calc(100% + 10px); right: 0; background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-lg); box-shadow: var(--shadow-lift); z-index: 50; animation: fadeInDown 150ms ease-out; }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

.admin-shell .notif-panel { width: 300px; }
.admin-shell .profile-panel { width: 230px; padding: 12px; }

.admin-shell .dropdown-header { padding: 12px 16px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; }
.admin-shell .dropdown-header b { font-size: 13px; color: var(--forest); }
.admin-shell .badge-count { font-size: 11px; font-weight: 700; color: #15803D; background: #DCFCE7; padding: 2px 8px; border-radius: 999px; }

.admin-shell .dropdown-body { padding: 8px 0; max-height: 260px; overflow-y: auto; }
.admin-shell .notif-item { padding: 10px 16px; display: flex; gap: 10px; align-items: flex-start; transition: background 150ms ease; cursor: pointer; text-decoration: none; }
.admin-shell .notif-item:hover { background: var(--cream); }
.admin-shell .notif-dot { width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
.admin-shell .notif-dot.bg-green { background: #16A34A; }
.admin-shell .notif-dot.bg-blue { background: #0284C7; }
.admin-shell .notif-dot.bg-amber { background: #D97706; }
.admin-shell .notif-text p { font-size: 12px; color: var(--ink); margin: 0 0 2px 0; line-height: 1.3; }
.admin-shell .notif-text small { font-size: 10.5px; color: var(--ink-muted); font-family: var(--font-mono); }

.admin-shell .dropdown-footer { padding: 10px 16px; border-top: 1px solid var(--line); text-align: center; }
.admin-shell .dropdown-footer a { font-size: 12px; font-weight: 700; color: var(--forest); text-decoration: none; }

.admin-shell .user-dropdown-info { padding: 8px 8px 12px 8px; }
.admin-shell .user-dropdown-info b { font-size: 13.5px; font-weight: 800; color: var(--forest); display: block; }
.admin-shell .user-dropdown-info span { font-size: 11.5px; color: var(--ink-muted); display: block; margin-bottom: 6px; }
.admin-shell .user-role-badge { display: inline-block; font-size: 10px; font-weight: 700; background: var(--cream); color: var(--forest); padding: 2px 8px; border-radius: 4px; border: 1px solid var(--line); }

.admin-shell .dropdown-divider { height: 1px; background: var(--line); margin: 6px 0; }

.admin-shell .dropdown-menu-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; color: var(--ink); text-decoration: none; border: none; background: transparent; width: 100%; text-align: left; cursor: pointer; transition: all 150ms ease; }
.admin-shell .dropdown-menu-item:hover { background: var(--cream); color: var(--forest); }
.admin-shell .dropdown-menu-item.text-danger { color: #DC2626; }
.admin-shell .dropdown-menu-item.text-danger:hover { background: #FEF2F2; color: #DC2626; }

.admin-shell .dashboard-container { padding: 32px; max-width: 1280px; width: 100%; margin: 0 auto; display: flex; flex-direction: column; gap: 28px; }
</style>
