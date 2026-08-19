<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

interface Department {
    id: number
    name: string
    slug: string
    description: string
    icon_path?: string
    active_doctors_count?: number
}

interface Doctor {
    id: number
    specialization: string
    consultation_fee: string | number
    license_number: string
    user: {
        id: number
        name: string
        avatar_path?: string
    }
    department: {
        id: number
        name: string
        slug: string
    }
}

interface Review {
    id: number
    rating: number
    comment: string
    patient: {
        user: {
            name: string
        }
    }
}

interface Stats {
    specialists_count: number
    departments_count: number
    appointments_count: number
    average_rating: number
}

defineProps<{
    departments: Department[]
    doctors: Doctor[]
    reviews: Review[]
    stats: Stats
    hospitalName?: string
}>()

// Accordion FAQ state
const openFaqIndex = ref<number | null>(0)

function toggleFaq(index: number) {
    openFaqIndex.value = openFaqIndex.value === index ? null : index
}

// Newsletter state
const newsletterEmail = ref('')
const newsletterSubscribed = ref(false)

function handleNewsletterSubmit() {
    if (newsletterEmail.value) {
        newsletterSubscribed.value = true
        newsletterEmail.value = ''
    }
}

// Location System State & Leaflet Map
interface Campus {
    id: string
    name: string
    badge: string
    address: string
    phone: string
    hours: string
    coords: [number, number]
}

const campuses: Campus[] = [
    {
        id: 'main',
        name: 'MediFlow Main Campus',
        badge: 'Main Hospital',
        address: '120 Harbor Ave, Suite 300, Riverside',
        phone: '(555) 340-2199',
        hours: 'Mon–Fri: 8:00 AM – 8:00 PM · Sat: 9:00 AM – 4:00 PM',
        coords: [40.7128, -74.0060]
    },
    {
        id: 'downtown',
        name: 'MediFlow Downtown Specialty Center',
        badge: 'Outpatient Clinic',
        address: '450 Healthcare Blvd, Suite 102, Metro City',
        phone: '(555) 340-2200',
        hours: 'Mon–Fri: 9:00 AM – 6:00 PM',
        coords: [40.7589, -73.9851]
    },
    {
        id: 'westside',
        name: 'MediFlow Westside Care & Pediatrics',
        badge: 'Pediatric & Family',
        address: '88 West Park Rd, Floor 2, Riverside',
        phone: '(555) 340-2205',
        hours: 'Mon–Sat: 8:00 AM – 6:00 PM',
        coords: [40.7306, -73.9352]
    }
]

const activeCampus = ref<Campus>(campuses[0])
const mapContainer = ref<HTMLElement | null>(null)
let mapInstance: L.Map | null = null
let markerInstance: L.Marker | null = null

onMounted(() => {
    if (mapContainer.value) {
        mapInstance = L.map(mapContainer.value, {
            center: activeCampus.value.coords,
            zoom: 14,
            zoomControl: true
        })

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(mapInstance)

        const customPinIcon = L.divIcon({
            className: 'mediflow-map-pin',
            html: `
                <div class="pin-wrap">
                    <div class="pin-pulse"></div>
                    <div class="pin-body">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                </div>
            `,
            iconSize: [44, 44],
            iconAnchor: [22, 44]
        })

        markerInstance = L.marker(activeCampus.value.coords, { icon: customPinIcon }).addTo(mapInstance)
        markerInstance.bindPopup(`
            <div style="font-family: sans-serif; padding: 4px; min-width: 180px;">
                <b style="color:#16301F; font-size:14px; font-weight:700;">${activeCampus.value.name}</b><br/>
                <span style="font-size:12px; color:#62655A;">${activeCampus.value.address}</span><br/>
                <span style="display:inline-block; margin-top:6px; font-size:11px; font-weight:600; color:#3B4A12; background:#EEF7C4; padding:3px 8px; border-radius:99px;">● Open Today</span>
            </div>
        `).openPopup()
    }
})

onUnmounted(() => {
    if (mapInstance) {
        mapInstance.remove()
        mapInstance = null
    }
})

function selectCampus(campus: Campus) {
    activeCampus.value = campus
    if (mapInstance && markerInstance) {
        mapInstance.flyTo(campus.coords, 14, { duration: 1.2 })
        markerInstance.setLatLng(campus.coords)
        markerInstance.getPopup()?.setContent(`
            <div style="font-family: sans-serif; padding: 4px; min-width: 180px;">
                <b style="color:#16301F; font-size:14px; font-weight:700;">${campus.name}</b><br/>
                <span style="font-size:12px; color:#62655A;">${campus.address}</span><br/>
                <span style="display:inline-block; margin-top:6px; font-size:11px; font-weight:600; color:#3B4A12; background:#EEF7C4; padding:3px 8px; border-radius:99px;">● Open Today</span>
            </div>
        `).openPopup()
    }
}
</script>

<template>
    <PublicLayout>
        <!-- ===================== HERO ===================== -->
        <section class="hero">
            <div class="wrap hero-grid">
                <div class="hero-left">
                    <span class="pill">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                        Healthcare for everyone
                    </span>
                    <h1>Personalized care plans for <span class="hl">your whole family's</span> health</h1>
                    <p class="lead">MediFlow brings together specialists across 25 fields of medicine — book real availability, manage every visit, and keep your family's care in one place.</p>
                    <div class="hero-cta">
                        <Link href="/register" class="btn btn-primary">Book an appointment</Link>
                        <a href="#departments" class="btn btn-outline">
                            Browse services
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
                        </a>
                    </div>
                    <div class="hero-proof">
                        <div class="avatar-stack" aria-hidden="true">
                            <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=120&q=80" class="av object-cover" alt="Doctor" />
                            <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=120&q=80" class="av object-cover" alt="Doctor" />
                            <img src="https://images.unsplash.com/photo-1594824813566-78a0c4f74d0e?auto=format&fit=crop&w=120&q=80" class="av object-cover" alt="Doctor" />
                            <span class="count">{{ stats?.specialists_count || 68 }}+</span>
                        </div>
                        <p>Board-certified specialists across 25 fields of medicine</p>
                    </div>
                </div>

                <div class="hero-photo">
                    <img src="https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=1400&q=80" alt="MediFlow Modern General Hospital" class="hero-img-bg" />
                    <div class="hero-img-overlay"></div>
                    <span class="ph-caption">MediFlow General Hospital</span>
                    <div class="rating-float">
                        <span class="pin" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <div>
                            <b>{{ stats?.average_rating || 4.9 }} <span class="stars">★★★★★</span></b>
                            <span class="src">on Google Reviews</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== INFO ROW ===================== -->
        <div class="wrap info-row">
            <div class="info-card photo">
                <div class="mini-photo" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                </div>
                <h3>A personal approach, built on trust</h3>
                <p>Consultations and follow-up support after every visit.</p>
            </div>
            <div class="info-card lime">
                <h3>Checklists by specialty</h3>
                <div class="checklist-tags">
                    <span class="tag">Post-illness recovery</span><span class="tag">Gynecology</span><span class="tag">Geriatrics</span>
                    <span class="tag">Pediatrics</span><span class="tag">Cardiology</span><span class="tag">Allergy care</span>
                </div>
                <a href="#departments" class="info-link">Learn more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg></a>
            </div>
            <div class="info-card gray">
                <h3>Highly qualified medical care</h3>
                <p>Our specialists complete ongoing training every year.</p>
                <div class="mini-chart" aria-hidden="true"><span style="height:35%"></span><span style="height:55%"></span><span style="height:40%"></span><span style="height:70%"></span><span style="height:60%"></span><span style="height:85%"></span><span style="height:75%"></span></div>
            </div>
        </div>

        <!-- ===================== STATS ===================== -->
        <div class="wrap">
            <div class="stats-band">
                <div class="stats-grid">
                    <div class="stat-item"><b>{{ stats?.specialists_count || 68 }}+</b><span>Specialists on staff</span></div>
                    <div class="stat-item"><b>{{ stats?.departments_count || 25 }}</b><span>Fields of medicine</span></div>
                    <div class="stat-item"><b>{{ stats?.appointments_count || 40000 }}+</b><span>Appointments booked</span></div>
                    <div class="stat-item"><b>{{ stats?.average_rating || 4.9 }}</b><span>Average patient rating</span></div>
                </div>
            </div>
        </div>

        <!-- ===================== DEPARTMENTS ===================== -->
        <section id="departments">
            <div class="wrap">
                <span class="pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Departments</span>
                <div class="section-head">
                    <h2>Find care by department</h2>
                    <p>Start with the kind of care you need, then choose a specialist whose schedule fits yours.</p>
                </div>
                <div class="dept-grid">
                    <div v-for="dept in departments" :key="dept.id" class="dept-card">
                        <div class="dept-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.8 1-1a5.5 5.5 0 0 0 0-7.6z"/></svg>
                        </div>
                        <h4>{{ dept.name }}</h4>
                        <p>{{ dept.description }}</p>
                        <Link :href="`/departments/${dept.slug}`" class="info-link">
                            View doctors
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== SERVICES ===================== -->
        <section id="services" class="section-alt">
            <div class="wrap">
                <span class="pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Services</span>
                <div class="section-head"><h2>A wide range of services</h2></div>
                <div class="services-grid">
                    <div class="service-cards">
                        <div class="service-card">
                            <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
                            <h4>Adult care unit</h4>
                        </div>
                        <div class="service-card">
                            <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="M12 13v8M9 18h6"/></svg></div>
                            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
                            <h4>Children's unit</h4>
                        </div>
                        <div class="service-card dark">
                            <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="8" rx="1.5"/><path d="M5 11V7a3 3 0 0 1 3-3h3M9 19v2M16 19v2"/></svg></div>
                            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
                            <h4>Day clinic</h4>
                        </div>
                        <div class="service-card">
                            <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h16M4 12a2 2 0 0 1 2-2h1V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v3h1a2 2 0 0 1 2 2M4 12v6a1 1 0 0 0 1 1h1M20 12v6a1 1 0 0 1-1 1h-1"/></svg></div>
                            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
                            <h4>Operating suite</h4>
                        </div>
                        <div class="service-card">
                            <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg></div>
                            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
                            <h4>Ultrasound imaging</h4>
                        </div>
                        <div class="service-card">
                            <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H5a2 2 0 0 0-2 2v4M15 3h4a2 2 0 0 1 2 2v4M9 21H5a2 2 0 0 1-2-2v-4M15 21h4a2 2 0 0 0 2-2v-4"/></svg></div>
                            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
                            <h4>X-ray room</h4>
                        </div>
                        <div class="service-card">
                            <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
                            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
                            <h4>Diagnostic testing</h4>
                        </div>
                        <div class="service-card">
                            <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-6 9 6v11a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/></svg></div>
                            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
                            <h4>At-home visits</h4>
                        </div>
                    </div>
                    <div class="services-photo">
                        <img src="https://images.unsplash.com/photo-1586773860418-d37222d8fce3?auto=format&fit=crop&w=1200&q=80" alt="Advanced Outpatient Center" class="services-img-bg" />
                        <div class="services-img-overlay"></div>
                        <span class="ph-caption">Advanced Outpatient Center</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== WHY CHOOSE US ===================== -->
        <section id="why">
            <div class="wrap">
                <span class="pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Why MediFlow</span>
                <div class="section-head">
                    <h2>Built around how people actually get care</h2>
                    <p>Four things that make booking and managing your care simpler.</p>
                </div>
                <div class="why-grid">
                    <div class="why-card">
                        <div class="why-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></div>
                        <h4>Same-week availability</h4>
                        <p>Real, live scheduling — not a callback promise. See open slots and book instantly.</p>
                    </div>
                    <div class="why-card">
                        <div class="why-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 4 5v6c0 5 3.5 8.5 8 10 4.5-1.5 8-5 8-10V5l-8-3z"/><path d="m9 12 2 2 4-4"/></svg></div>
                        <h4>Board-certified specialists</h4>
                        <p>Every doctor on MediFlow is credential-verified before they see their first patient.</p>
                    </div>
                    <div class="why-card">
                        <div class="why-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></svg></div>
                        <h4>Digital medical records</h4>
                        <p>Every visit, diagnosis and prescription lives in one place you can access anytime.</p>
                    </div>
                    <div class="why-card">
                        <div class="why-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg></div>
                        <h4>Transparent pricing</h4>
                        <p>See the consultation fee upfront on every doctor's profile — no surprise billing.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== HOW IT WORKS ===================== -->
        <section id="process" class="section-alt">
            <div class="wrap">
                <span class="pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>How it works</span>
                <div class="section-head"><h2>Three steps, no phone call</h2></div>
                <div class="process-grid">
                    <div class="process-card"><span class="process-num">01</span>
                        <div class="process-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
                        <h4>Search</h4><p>Filter by department, condition, or specialist name until you find the right doctor.</p>
                    </div>
                    <div class="process-card"><span class="process-num">02</span>
                        <div class="process-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 9h18M8 2v4M16 2v4"/></svg></div>
                        <h4>Pick a time</h4><p>See their real, current availability — never a slot that turns out to be taken.</p>
                    </div>
                    <div class="process-card"><span class="process-num">03</span>
                        <div class="process-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></div>
                        <h4>Confirm</h4><p>Get an instant confirmation and a reminder before your visit.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== LAB / DIAGNOSTICS ===================== -->
        <section id="diagnostics">
            <div class="wrap">
                <div class="lab-grid">
                    <div class="lab-photo" aria-hidden="true">
                        <img src="https://images.unsplash.com/photo-1579154204601-01588f351e67?auto=format&fit=crop&w=800&q=80" alt="Express Diagnostic Lab" class="lab-img-bg" />
                        <div class="lab-img-overlay"></div>
                    </div>
                    <div class="lab-main">
                        <h3>Express lab testing</h3>
                        <p>We run a wide range of laboratory tests to help catch conditions at their earliest, most treatable stage.</p>
                        <div class="hero-cta">
                            <Link href="/register" class="btn btn-primary btn-sm">Book a test</Link>
                            <Link href="/faq" class="btn btn-outline btn-sm">Learn more</Link>
                        </div>
                    </div>
                    <div class="lab-tags">
                        <div class="tag-list">
                            <span class="tag">Blood panel</span><span class="tag">Vitamins</span><span class="tag">Enzymes</span>
                            <span class="tag">Specific proteins</span><span class="tag">Hormone testing</span><span class="tag more">More →</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== DOCTORS ===================== -->
        <section id="doctors" class="section-alt">
            <div class="wrap">
                <span class="pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Doctors</span>
                <div class="section-head">
                    <h2>Meet our specialists</h2>
                    <p>Highly qualified physicians with years of practical experience.</p>
                </div>
                <div class="doctor-scroll">
                    <div v-for="doc in doctors" :key="doc.id" class="doctor-card">
                        <div class="doctor-photo">
                            <img v-if="doc.user.avatar_path" :src="doc.user.avatar_path" :alt="doc.user.name" class="w-full h-full object-cover rounded-2xl" />
                            <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/></svg>
                            <span class="spec-tag">{{ doc.department?.name || doc.specialization }}</span>
                        </div>
                        <h4>{{ doc.user.name }}</h4>
                        <Link :href="`/doctors/${doc.license_number}`" class="info-link text-xs mt-1">View profile →</Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== TESTIMONIALS ===================== -->
        <section id="testimonials">
            <div class="wrap">
                <span class="pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Reviews</span>
                <div class="section-head">
                    <h2>Honest feedback from our patients</h2>
                    <p>Share your experience after your visit through our feedback form.</p>
                </div>
                <div class="testimonial-grid">
                    <div v-for="(rev, idx) in reviews" :key="rev.id" class="review-card">
                        <div class="review-head">
                            <img :src="[
                                'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=200&q=80'
                            ][idx % 3]" class="review-avatar object-cover" :alt="rev.patient?.user?.name || 'Patient'" />
                            <div>
                                <div class="review-name">{{ rev.patient?.user?.name || 'Patient' }}</div>
                                <div class="review-stars">★★★★★</div>
                            </div>
                        </div>
                        <p class="body">{{ rev.comment }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== FAQ ===================== -->
        <section id="faq" class="section-alt">
            <div class="wrap">
                <span class="pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>FAQ</span>
                <div class="section-head"><h2>Common questions</h2></div>
                <div class="faq-list">
                    <div class="faq-item" :class="{ open: openFaqIndex === 0 }">
                        <button class="faq-q" @click="toggleFaq(0)">
                            <span>Do I need to call to book an appointment?</span>
                            <span class="chev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
                        </button>
                        <div class="faq-a" :style="{ maxHeight: openFaqIndex === 0 ? '200px' : '0px' }">
                            <p>No — every doctor's real-time availability is visible on their profile. Pick a slot and confirm online in under two minutes.</p>
                        </div>
                    </div>
                    <div class="faq-item" :class="{ open: openFaqIndex === 1 }">
                        <button class="faq-q" @click="toggleFaq(1)">
                            <span>Can I reschedule or cancel an appointment?</span>
                            <span class="chev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
                        </button>
                        <div class="faq-a" :style="{ maxHeight: openFaqIndex === 1 ? '200px' : '0px' }">
                            <p>Yes, up to 2 hours before your scheduled time, directly from your dashboard — no need to call in.</p>
                        </div>
                    </div>
                    <div class="faq-item" :class="{ open: openFaqIndex === 2 }">
                        <button class="faq-q" @click="toggleFaq(2)">
                            <span>Will I be able to see my prescriptions and records?</span>
                            <span class="chev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
                        </button>
                        <div class="faq-a" :style="{ maxHeight: openFaqIndex === 2 ? '200px' : '0px' }">
                            <p>Every diagnosis, note and prescription from your visits is stored in your account and downloadable at any time.</p>
                        </div>
                    </div>
                    <div class="faq-item" :class="{ open: openFaqIndex === 3 }">
                        <button class="faq-q" @click="toggleFaq(3)">
                            <span>How do I know a doctor's consultation fee upfront?</span>
                            <span class="chev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
                        </button>
                        <div class="faq-a" :style="{ maxHeight: openFaqIndex === 3 ? '200px' : '0px' }">
                            <p>Every doctor profile lists their consultation fee clearly before you book — no surprises at checkout.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== LOCATION SYSTEM ===================== -->
        <section id="location">
            <div class="wrap">
                <div class="location-header-flex">
                    <div>
                        <span class="pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Visit Us</span>
                        <div class="section-head mb-0 mt-3">
                            <h2>Interactive Hospital Location System</h2>
                            <p>Find your nearest MediFlow medical center, check operating hours, and get live turn-by-turn directions.</p>
                        </div>
                    </div>
                    
                    <!-- Campus Selector Tabs -->
                    <div class="campus-selector-pills">
                        <button
                            v-for="campus in campuses"
                            :key="campus.id"
                            class="campus-pill-btn"
                            :class="{ active: activeCampus.id === campus.id }"
                            @click="selectCampus(campus)"
                        >
                            <span class="dot"></span>
                            {{ campus.badge }}
                        </button>
                    </div>
                </div>

                <div class="location-grid">
                    <div class="location-info">
                        <div class="campus-title-box">
                            <h4>{{ activeCampus.name }}</h4>
                            <span class="open-badge">● Open 24/7 Care</span>
                        </div>

                        <div class="location-row">
                            <div class="li-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
                            <div>
                                <h5>Address</h5>
                                <p>{{ activeCampus.address }}</p>
                            </div>
                        </div>

                        <div class="location-row">
                            <div class="li-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></div>
                            <div>
                                <h5>Hours of Operation</h5>
                                <p>{{ activeCampus.hours }}</p>
                            </div>
                        </div>

                        <div class="location-row">
                            <div class="li-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
                            <div>
                                <h5>Direct Phone Line</h5>
                                <p>{{ activeCampus.phone }}</p>
                            </div>
                        </div>

                        <div class="location-row">
                            <div class="li-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/></svg></div>
                            <div>
                                <h5>Front Desk Email</h5>
                                <p>reception@mediflow.org</p>
                            </div>
                        </div>

                        <div class="location-actions">
                            <a :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(activeCampus.address)}`" target="_blank" class="btn btn-primary btn-sm">
                                Get Directions
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Interactive Leaflet Map Container -->
                    <div class="map-wrapper">
                        <div ref="mapContainer" class="leaflet-map-frame"></div>
                        <div class="map-overlay-badge">
                            <span class="pulse-dot"></span>
                            Live Map View
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== NEWSLETTER ===================== -->
        <section>
            <div class="wrap">
                <div class="newsletter">
                    <div>
                        <h3>Get seasonal health tips in your inbox</h3>
                        <p>One short email a month. No spam, unsubscribe anytime.</p>
                    </div>
                    <form class="newsletter-form" @submit.prevent="handleNewsletterSubmit">
                        <input
                            type="email"
                            v-model="newsletterEmail"
                            :placeholder="newsletterSubscribed ? 'Thanks — you\'re subscribed!' : 'you@example.com'"
                            :disabled="newsletterSubscribed"
                            required
                            aria-label="Email address"
                        />
                        <button type="submit" class="btn btn-primary btn-sm" :disabled="newsletterSubscribed">
                            {{ newsletterSubscribed ? 'Subscribed ✓' : 'Subscribe' }}
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <!-- ===================== CLOSING CTA ===================== -->
        <section>
            <div class="wrap">
                <div class="closing">
                    <div>
                        <h2>Your next appointment is a couple of clicks away.</h2>
                        <p>Create a free account and book your first visit today.</p>
                    </div>
                    <Link href="/register" class="btn btn-primary">Create your account</Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; position: relative; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }

.pill { display: inline-flex; align-items: center; gap: 6px; background: #DDF15C; color: #3B4A12; font-size: 13px; font-weight: 600; padding: 7px 16px 7px 12px; border-radius: 999px; }
.pill svg { width: 14px; height: 14px; }
.tag { display: inline-flex; align-items: center; background: #FFFFFF; color: #16180F; font-size: 13px; font-weight: 500; padding: 8px 14px; border-radius: 999px; border: 1px solid rgba(22,24,15,0.08); }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 54px; padding: 0 28px; border-radius: 999px; font-size: 15.5px; font-weight: 600; text-decoration: none; transition: transform 150ms ease, background-color 150ms ease, box-shadow 150ms ease; cursor: pointer; border: 0; }
.btn:active { transform: scale(0.97); }
.btn-primary { background: #16301F; color: #fff; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.btn-primary:hover { background: #1E4029; box-shadow: 0 4px 10px rgba(22,24,15,0.06), 0 16px 36px rgba(22,24,15,0.10); }
.btn-outline { background: transparent; color: #16180F; border: 1.5px solid rgba(22,24,15,0.16); }
.btn-outline:hover { border-color: #16180F; }
.btn-sm { height: 46px; padding: 0 22px; font-size: 14px; }

.hero { position: relative; padding: 64px 0 48px; z-index: 1; }
.hero-grid { display: grid; grid-template-columns: 0.85fr 1.15fr; gap: 44px; align-items: stretch; }
@media (max-width: 980px) { .hero-grid { grid-template-columns: 1fr; } }
.hero-left { display: flex; flex-direction: column; }
.hero-left .pill { margin-bottom: 26px; width: fit-content; }
.hero h1 { font-size: clamp(2.4rem, 1.8rem + 2.4vw, 3.6rem); line-height: 1.1; letter-spacing: -0.02em; font-weight: 800; margin-bottom: 26px; }
.hl { background: #DDF15C; padding: 2px 8px; border-radius: 8px; display: inline; box-decoration-break: clone; -webkit-box-decoration-break: clone; }
.hero-left p.lead { font-size: 17px; color: #62655A; max-width: 44ch; margin-bottom: 34px; }
.hero-cta { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 38px; }
.hero-proof { display: flex; align-items: center; gap: 16px; margin-top: auto; }
.avatar-stack { display: flex; align-items: center; }
.avatar-stack .av { width: 46px; height: 46px; border-radius: 50%; border: 3px solid #F8F6EF; margin-left: -13px; background: linear-gradient(135deg, #16301F, #3a6b4c); }
.avatar-stack .av:first-child { margin-left: 0; }
.avatar-stack .count { width: 46px; height: 46px; border-radius: 50%; border: 3px solid #F8F6EF; margin-left: -13px; background: #DDF15C; color: #3B4A12; font-size: 12.5px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
.hero-proof p { font-size: 13.5px; color: #62655A; max-width: 22ch; }

.hero-photo { position: relative; border-radius: 32px; overflow: hidden; min-height: 480px; background: #16301F; display: flex; align-items: flex-end; justify-content: center; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.hero-img-bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; }
.hero-img-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(22,48,31,0.7) 0%, rgba(22,48,31,0.15) 50%, rgba(0,0,0,0.1) 100%); pointer-events: none; }
.hero-photo .ph-caption { position: absolute; top: 22px; left: 22px; z-index: 2; font-size: 11.5px; font-weight: 600; letter-spacing: 0.02em; color: rgba(255,255,255,0.95); background: rgba(22,48,31,0.65); padding: 6px 14px; border-radius: 999px; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15); }
.rating-float { position: absolute; bottom: 22px; right: 22px; z-index: 2; background: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 10px rgba(22,24,15,0.06), 0 16px 36px rgba(22,24,15,0.10); padding: 15px 19px; display: flex; align-items: center; gap: 10px; }
.rating-float .pin { width: 36px; height: 36px; border-radius: 50%; background: #EEF7C4; color: #3B4A12; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.rating-float .pin svg { width: 18px; height: 18px; }
.rating-float b { font-size: 15px; display: block; }
.rating-float .stars { color: #16301F; font-size: 12px; letter-spacing: 1px; }
.rating-float span.src { font-size: 11px; color: #62655A; }

.info-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 18px; padding: 28px 0 72px; position: relative; z-index: 1; }
@media (max-width: 900px) { .info-row { grid-template-columns: 1fr; } }
.info-card { border-radius: 24px; padding: 26px; min-height: 210px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.info-card.photo { background: #FFFFFF; display: flex; flex-direction: column; }
.info-card.lime { background: #DDF15C; }
.info-card.gray { background: #EFEDE4; }
.mini-photo { border-radius: 16px; height: 104px; margin-bottom: 16px; background: linear-gradient(135deg, #DDEBC4, #A9C08A); display: flex; align-items: center; justify-content: center; }
.mini-photo svg { width: 32px; height: 32px; color: #fff; opacity: 0.6; }
.info-card h3 { font-size: 17.5px; font-weight: 700; margin-bottom: 6px; line-height: 1.3; }
.info-card p { font-size: 13.5px; color: #62655A; }
.info-card.lime p { color: #3B4A12; opacity: 0.85; }
.checklist-tags { display: flex; flex-wrap: wrap; gap: 8px; margin: 14px 0 16px; }
.checklist-tags .tag { background: rgba(255,255,255,0.55); border-color: transparent; font-size: 12.5px; padding: 6px 12px; }
.info-link { font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; color: #16301F; text-decoration: none; }
.info-link svg { width: 14px; height: 14px; }
.mini-chart { display: flex; align-items: flex-end; gap: 6px; height: 62px; margin-top: 16px; }
.mini-chart span { flex: 1; background: #16301F; border-radius: 4px 4px 0 0; opacity: 0.85; }

section { padding: 80px 0; position: relative; z-index: 1; }
@media (max-width: 640px) { section { padding: 56px 0; } }
.section-head { max-width: 580px; margin-bottom: 44px; }
.section-head h2 { font-size: 36px; font-weight: 800; letter-spacing: -0.017em; margin: 14px 0 10px; }
.section-head p { font-size: 16px; color: #62655A; }
.section-alt { background: #F0EEE3; }

.stats-band { border-top: 1px solid #E7E3D3; border-bottom: 1px solid #E7E3D3; padding: 44px 0; }
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; text-align: center; }
@media (max-width: 720px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
.stat-item b { display: block; font-size: 40px; font-weight: 800; letter-spacing: -0.02em; font-family: 'JetBrains Mono', monospace; }
.stat-item span { font-size: 13.5px; color: #62655A; }

.dept-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
@media (max-width: 960px) { .dept-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .dept-grid { grid-template-columns: 1fr; } }
.dept-card { background: #FFFFFF; border-radius: 24px; padding: 26px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); transition: box-shadow 150ms ease, transform 150ms ease; }
.dept-card:hover { box-shadow: 0 4px 10px rgba(22,24,15,0.06), 0 16px 36px rgba(22,24,15,0.10); transform: translateY(-3px); }
.dept-icon { width: 48px; height: 48px; border-radius: 14px; background: #EEF7C4; color: #3B4A12; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; }
.dept-icon svg { width: 23px; height: 23px; }
.dept-card h4 { font-size: 16px; font-weight: 700; margin-bottom: 6px; }
.dept-card p { font-size: 13.5px; color: #62655A; margin-bottom: 16px; }

.services-grid { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 24px; align-items: stretch; }
@media (max-width: 900px) { .services-grid { grid-template-columns: 1fr; } }
.service-cards { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
@media (max-width: 480px) { .service-cards { grid-template-columns: 1fr; } }
.service-card { background: #FFFFFF; border-radius: 16px; padding: 20px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); transition: box-shadow 150ms ease, transform 150ms ease; position: relative; }
.service-card:hover { box-shadow: 0 4px 10px rgba(22,24,15,0.06), 0 16px 36px rgba(22,24,15,0.10); transform: translateY(-2px); }
.service-card.dark { background: #16301F; color: #fff; }
.service-card.dark .service-icon { background: rgba(255,255,255,0.12); color: #DDF15C; }
.service-icon { width: 42px; height: 42px; border-radius: 12px; background: #EEF7C4; color: #3B4A12; display: flex; align-items: center; justify-content: center; margin-bottom: 28px; }
.service-icon svg { width: 20px; height: 20px; }
.service-card .arrow { position: absolute; top: 18px; right: 18px; width: 28px; height: 28px; border-radius: 50%; background: rgba(22,24,15,0.06); display: flex; align-items: center; justify-content: center; }
.service-card.dark .arrow { background: rgba(255,255,255,0.12); }
.service-card .arrow svg { width: 13px; height: 13px; }
.service-card h4 { font-size: 14.5px; font-weight: 600; line-height: 1.35; }
.services-photo { border-radius: 32px; background: #16301F; min-height: 360px; display: flex; align-items: flex-end; justify-content: center; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); position: relative; overflow: hidden; }
.services-img-bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; }
.services-img-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(22,48,31,0.65) 0%, transparent 60%); pointer-events: none; }
.services-photo .ph-caption { position: absolute; top: 20px; left: 20px; z-index: 2; font-size: 11.5px; font-weight: 600; color: rgba(255,255,255,0.95); background: rgba(22,48,31,0.65); padding: 6px 14px; border-radius: 999px; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15); }

.why-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
@media (max-width: 960px) { .why-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .why-grid { grid-template-columns: 1fr; } }
.why-card { padding: 8px 4px; }
.why-icon { width: 50px; height: 50px; border-radius: 50%; background: #16301F; color: #DDF15C; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; }
.why-icon svg { width: 22px; height: 22px; }
.why-card h4 { font-size: 16.5px; font-weight: 700; margin-bottom: 8px; }
.why-card p { font-size: 14px; color: #62655A; line-height: 1.6; }

.process-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
@media (max-width: 800px) { .process-grid { grid-template-columns: 1fr; } }
.process-card { background: #FFFFFF; border-radius: 24px; padding: 32px 28px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); position: relative; }
.process-num { position: absolute; top: 24px; right: 28px; font-family: 'JetBrains Mono', monospace; font-size: 13px; color: #62655A; font-weight: 600; }
.process-icon { width: 48px; height: 48px; border-radius: 14px; background: #EEF7C4; color: #3B4A12; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; }
.process-icon svg { width: 22px; height: 22px; }
.process-card h4 { font-size: 18px; font-weight: 700; margin-bottom: 10px; }
.process-card p { font-size: 14px; color: #62655A; line-height: 1.6; }

.lab-grid { display: grid; grid-template-columns: 0.8fr 1fr 0.9fr; gap: 16px; align-items: stretch; }
@media (max-width: 900px) { .lab-grid { grid-template-columns: 1fr; } }
.lab-photo { border-radius: 24px; min-height: 230px; background: #16301F; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
.lab-img-bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; }
.lab-img-overlay { position: absolute; inset: 0; background: rgba(22,48,31,0.2); pointer-events: none; }
.lab-main { background: #FFFFFF; border-radius: 24px; padding: 30px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); display: flex; flex-direction: column; justify-content: center; }
.lab-main h3 { font-size: 23px; font-weight: 700; margin-bottom: 10px; }
.lab-main p { font-size: 14.5px; color: #62655A; margin-bottom: 22px; }
.lab-tags { background: #DDF15C; border-radius: 24px; padding: 26px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.lab-tags .tag-list { display: flex; flex-wrap: wrap; gap: 8px; }
.lab-tags .tag { background: rgba(255,255,255,0.55); border-color: transparent; }
.lab-tags .tag.more { background: #16301F; color: #fff; }

.doctor-scroll { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; }
@media (max-width: 1080px) { .doctor-scroll { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px) { .doctor-scroll { grid-template-columns: repeat(2, 1fr); } }
.doctor-photo { border-radius: 24px; aspect-ratio: 3/4; background: linear-gradient(150deg, #16301F, #3a6b4c); box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); display: flex; align-items: center; justify-content: center; margin-bottom: 12px; position: relative; overflow: hidden; }
.doctor-photo img { width: 100%; height: 100%; object-fit: cover; object-position: top center; border-radius: 24px; }
.doctor-photo svg { width: 44px; height: 44px; color: #fff; opacity: 0.55; }
.doctor-photo .spec-tag { position: absolute; bottom: 12px; left: 12px; background: #FFFFFF; font-size: 11.5px; font-weight: 600; padding: 6px 12px; border-radius: 999px; }
.doctor-card h4 { font-size: 14.5px; font-weight: 600; line-height: 1.35; }

.testimonial-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
@media (max-width: 900px) { .testimonial-grid { grid-template-columns: 1fr; } }
.review-card { background: #FFFFFF; border-radius: 24px; padding: 28px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); display: flex; flex-direction: column; }
.review-head { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.review-avatar { width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0; }
.review-name { font-size: 14.5px; font-weight: 600; }
.review-stars { color: #16301F; font-size: 12px; letter-spacing: 1px; }
.review-card p.body { font-size: 14px; color: #62655A; line-height: 1.65; margin-bottom: 14px; flex: 1; }

.faq-list { max-width: 780px; }
.faq-item { border-bottom: 1px solid #E7E3D3; }
.faq-q { width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 22px 4px; text-align: left; font-size: 16px; font-weight: 600; background: none; border: 0; cursor: pointer; color: inherit; }
.faq-q .chev { width: 32px; height: 32px; border-radius: 50%; background: #EFEDE4; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: transform 200ms ease, background-color 200ms ease; }
.faq-q .chev svg { width: 14px; height: 14px; }
.faq-item.open .faq-q .chev { background: #DDF15C; transform: rotate(180deg); }
.faq-a { max-height: 0; overflow: hidden; transition: max-height 250ms ease; }
.faq-a p { font-size: 14.5px; color: #62655A; line-height: 1.65; padding: 0 4px 22px; max-width: 60ch; }

.location-header-flex { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; flex-wrap: wrap; margin-bottom: 28px; }
.campus-selector-pills { display: flex; gap: 10px; flex-wrap: wrap; }
.campus-pill-btn { display: inline-flex; align-items: center; gap: 8px; background: #FFFFFF; border: 1.5px solid #E7E3D3; border-radius: 999px; padding: 10px 18px; font-size: 13.5px; font-weight: 600; color: #62655A; cursor: pointer; transition: all 150ms ease; }
.campus-pill-btn:hover { border-color: #16301F; color: #16301F; }
.campus-pill-btn.active { background: #16301F; border-color: #16301F; color: #FFFFFF; box-shadow: 0 4px 12px rgba(22,48,31,0.15); }
.campus-pill-btn .dot { width: 8px; height: 8px; border-radius: 50%; background: #9BB37E; }
.campus-pill-btn.active .dot { background: #DDF15C; }

.campus-title-box { margin-bottom: 16px; border-bottom: 1.5px solid #E7E3D3; padding-bottom: 14px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
.campus-title-box h4 { font-size: 18px; font-weight: 800; color: #16301F; }
.open-badge { font-size: 12px; font-weight: 600; color: #3B4A12; background: #EEF7C4; padding: 4px 12px; border-radius: 999px; }

.location-grid { display: grid; grid-template-columns: 0.9fr 1.1fr; gap: 24px; align-items: stretch; }
@media (max-width: 900px) { .location-grid { grid-template-columns: 1fr; } }
.location-info { background: #FFFFFF; border-radius: 24px; padding: 30px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); display: flex; flex-direction: column; justify-content: space-between; }
.location-row { display: flex; gap: 14px; padding: 12px 0; border-bottom: 1px solid #E7E3D3; }
.location-row:last-child { border-bottom: none; }
.location-row .li-icon { width: 40px; height: 40px; border-radius: 12px; background: #EEF7C4; color: #3B4A12; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.location-row .li-icon svg { width: 18px; height: 18px; }
.location-row h5 { font-size: 14.5px; font-weight: 700; margin-bottom: 3px; }
.location-row p { font-size: 13.5px; color: #62655A; }
.location-actions { margin-top: 18px; }

.map-wrapper { position: relative; border-radius: 24px; overflow: hidden; min-height: 380px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); border: 1px solid #E7E3D3; }
.leaflet-map-frame { width: 100%; height: 100%; min-height: 380px; z-index: 1; }
.map-overlay-badge { position: absolute; top: 16px; right: 16px; z-index: 500; background: rgba(255,255,255,0.92); backdrop-filter: blur(8px); border-radius: 999px; padding: 6px 14px; font-size: 12px; font-weight: 600; color: #16301F; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.pulse-dot { width: 8px; height: 8px; border-radius: 50%; background: #22C55E; box-shadow: 0 0 0 4px rgba(34,197,94,0.25); }

:deep(.mediflow-map-pin) { background: transparent; border: none; }
:deep(.pin-wrap) { position: relative; display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; }
:deep(.pin-body) { width: 40px; height: 40px; border-radius: 50% 50% 50% 0; background: #16301F; color: #DDF15C; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(22,48,31,0.4); border: 2px solid #FFFFFF; }
:deep(.pin-body svg) { transform: rotate(45deg); }
:deep(.pin-pulse) { position: absolute; width: 44px; height: 44px; border-radius: 50%; background: rgba(221,241,92,0.6); animation: pinPulse 2s infinite ease-in-out; }
@keyframes pinPulse { 0% { transform: scale(0.6); opacity: 1; } 100% { transform: scale(1.5); opacity: 0; } }

.newsletter { background: #DDF15C; border-radius: 32px; padding: 52px 44px; display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap; }
.newsletter h3 { font-size: 24px; font-weight: 800; letter-spacing: -0.015em; max-width: 22ch; }
.newsletter p { font-size: 14px; color: #3B4A12; opacity: 0.8; margin-top: 6px; }
.newsletter-form { display: flex; gap: 8px; flex: 0 0 auto; }
.newsletter-form input { height: 52px; border-radius: 999px; border: none; padding: 0 20px; font-size: 14.5px; width: 260px; outline: none; }
@media (max-width: 560px) { .newsletter-form { flex-direction: column; width: 100%; } .newsletter-form input { width: 100%; } }

.closing { background: #16301F; border-radius: 32px; padding: 60px 44px; display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap; color: #fff; position: relative; overflow: hidden; }
.closing::before { content: ""; position: absolute; top: -80px; right: -60px; width: 260px; height: 260px; border-radius: 50%; background: #DDF15C; opacity: 0.15; filter: blur(10px); }
.closing h2 { font-size: 29px; font-weight: 800; letter-spacing: -0.015em; max-width: 24ch; position: relative; }
.closing p { color: rgba(255,255,255,0.65); font-size: 14.5px; margin-top: 8px; position: relative; }
.closing .btn-primary { background: #DDF15C; color: #3B4A12; position: relative; border: 0; }
.closing .btn-primary:hover { background: #ecf99c; }
</style>
