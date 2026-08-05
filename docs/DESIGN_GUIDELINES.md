# Design Guidelines
## Hospital Appointment Management System (HAMS)

| | |
|---|---|
| **Document Type** | UI/UX Design System & Guidelines |
| **Project Name** | Hospital Appointment Management System |
| **Version** | 1.0 |
| **Audience** | Frontend engineers, designers, QA |
| **Stack Reference** | Vue 3 + TypeScript + Inertia.js, Tailwind CSS, Shadcn UI |

---

## Table of Contents

1. [Design Philosophy](#design-philosophy)
2. [Brand Personality](#brand-personality)
3. [Color System](#color-system)
4. [Typography](#typography)
5. [Spacing System](#spacing-system)
6. [Border Radius](#border-radius)
7. [Shadows](#shadows)
8. [Icons](#icons)
9. [Buttons](#buttons)
10. [Forms](#forms)
11. [Cards](#cards)
12. [Tables](#tables)
13. [Charts](#charts)
14. [Dashboard Layout](#dashboard-layout)
15. [Public Website Design](#public-website-design)
16. [Patient Dashboard Design](#patient-dashboard-design)
17. [Doctor Dashboard Design](#doctor-dashboard-design)
18. [Admin Dashboard Design](#admin-dashboard-design)
19. [Calendar Design](#calendar-design)
20. [Appointment Cards](#appointment-cards)
21. [Modal Design](#modal-design)
22. [Empty States](#empty-states)
23. [Loading Skeletons](#loading-skeletons)
24. [Toast Notifications](#toast-notifications)
25. [Responsive Rules](#responsive-rules)
26. [Accessibility Guidelines](#accessibility-guidelines)
27. [Animation Guidelines](#animation-guidelines)
28. [UI Component Naming Convention](#ui-component-naming-convention)
29. [Design Do's](#design-dos)
30. [Design Don'ts](#design-donts)

---

## Design Philosophy

HAMS is designed to feel like a **premium operational tool**, not a hospital brochure. The guiding principle: **calm precision**. Every screen should feel quiet, controlled, and trustworthy — the visual language of a product that handles important, sensitive work (patient health data, scheduling, clinical records) without ever feeling clinical-cold or bureaucratic.

We borrow directly from the design instincts of **Linear** (restraint, sharp typography, purposeful motion), **Notion** (structured content density without clutter), **Stripe Dashboard** (data-dense but legible, confident use of color for status), and **Vercel Dashboard** (dark-mode-first polish, monospace accents for technical data, generous whitespace).

Three non-negotiables:

1. **Clarity over decoration.** Every visual element must earn its place. No gradients, illustrations, or iconography that exist purely for "hospital vibes" (no stethoscopes, no crosses, no stock photos of smiling doctors in lab coats holding clipboards).
2. **Hierarchy through typography and spacing, not color.** Color is reserved for status and action — never used decoratively to differentiate sections.
3. **Consistency is the feature.** A patient booking an appointment and an admin reviewing analytics should feel like they are using the same product family, just scoped to different permissions.

---

## Brand Personality

| Trait | Expression in UI |
|---|---|
| **Trustworthy** | Muted, confident palette; no alarming reds except for genuine destructive/critical states; consistent spacing that never feels rushed. |
| **Precise** | Tight alignment grids, tabular numerals for data, exact spacing multiples — nothing "eyeballed." |
| **Calm** | Generous whitespace, soft shadows, no flashing or aggressive motion, muted status colors rather than saturated alarm tones. |
| **Modern / Premium** | Dark-mode-first design sensibility even in light mode (crisp neutrals, not beige/gray-blue hospital tones), Inter/Geist-style typography, subtle borders instead of heavy drop shadows. |
| **Efficient** | Dense, scannable data tables and dashboards for power users (Doctors, Admins) without sacrificing breathing room on public/patient-facing pages. |
| **Human** | Despite the SaaS polish, patient-facing copy and empty states retain warmth — this is still healthcare, and tone should never feel like a billing platform. |

**Avoid entirely:** medical clip-art, blue-and-white "hospital corporate" gradients, Comic Sans-adjacent friendliness, rounded mascot illustrations, ambulance-red alert colors used decoratively.

---

## Color System

Colors are defined as design tokens (CSS variables) with Light and Dark mode pairs. All colors must meet **WCAG 2.1 AA contrast** at their intended usage (text vs. background).

### Primary

The primary brand color — used for primary actions, active states, links, and key data highlights. A deep, confident indigo/blue rather than a generic "hospital blue."

| Token | Light Mode | Dark Mode | Usage |
|---|---|---|---|
| `--primary-50` | `#EEF2FF` | `#1A1B2E` | Subtle backgrounds, hover tints |
| `--primary-100` | `#E0E7FF` | `#242542` | Selected row backgrounds |
| `--primary-300` | `#A5B4FC` | `#5B5FE8` | Disabled/muted primary |
| `--primary-500` | `#4F46E5` | `#6366F1` | **Base primary** — buttons, links, active nav |
| `--primary-600` | `#4338CA` | `#818CF8` | Hover state |
| `--primary-700` | `#3730A3` | `#A5B4FC` | Active/pressed state |

### Secondary

Used for secondary actions and supporting UI elements — a neutral slate tone that pairs cleanly with primary without competing for attention.

| Token | Light Mode | Dark Mode | Usage |
|---|---|---|---|
| `--secondary-100` | `#F1F5F9` | `#1E293B` | Secondary button background |
| `--secondary-500` | `#64748B` | `#94A3B8` | Secondary button text/icon |
| `--secondary-700` | `#334155` | `#CBD5E1` | Secondary button hover text |

### Accent

Reserved for highlights, badges, and moments that need a distinct pop without implying status (e.g., "New," "Featured Doctor," onboarding highlights). A restrained teal/cyan.

| Token | Light Mode | Dark Mode |
|---|---|---|
| `--accent-100` | `#CFFAFE` | `#083344` |
| `--accent-500` | `#06B6D4` | `#22D3EE` |
| `--accent-700` | `#0E7490` | `#67E8F9` |

### Success

| Token | Light Mode | Dark Mode | Usage |
|---|---|---|---|
| `--success-100` | `#DCFCE7` | `#052e16` | Success banners, badge backgrounds |
| `--success-500` | `#16A34A` | `#22C55E` | Confirmed appointment, completed status |
| `--success-700` | `#15803D` | `#4ADE80` | Text on light success backgrounds |

### Warning

| Token | Light Mode | Dark Mode | Usage |
|---|---|---|---|
| `--warning-100` | `#FEF3C7` | `#451A03` | Pending status backgrounds |
| `--warning-500` | `#D97706` | `#F59E0B` | Pending, "action needed" indicators |
| `--warning-700` | `#B45309` | `#FBBF24` | Text on light warning backgrounds |

### Danger

| Token | Light Mode | Dark Mode | Usage |
|---|---|---|---|
| `--danger-100` | `#FEE2E2` | `#450A0A` | Cancelled/error backgrounds |
| `--danger-500` | `#DC2626` | `#EF4444` | Destructive buttons, cancelled status, form errors |
| `--danger-700` | `#B91C1C` | `#F87171` | Text on light danger backgrounds |

### Info

| Token | Light Mode | Dark Mode | Usage |
|---|---|---|---|
| `--info-100` | `#DBEAFE` | `#0C2340` | Informational banners |
| `--info-500` | `#2563EB` | `#3B82F6` | Info badges, tooltips |
| `--info-700` | `#1D4ED8` | `#60A5FA` | Text on light info backgrounds |

### Neutral

The workhorse palette — backgrounds, borders, text. Cool-neutral gray (slate-based), never warm/beige, to keep the product feeling technical and clean rather than "medical office."

| Token | Light Mode | Dark Mode | Usage |
|---|---|---|---|
| `--neutral-0` | `#FFFFFF` | `#0B0D12` | Page/card background |
| `--neutral-50` | `#F8FAFC` | `#11141A` | Subtle section backgrounds |
| `--neutral-100` | `#F1F5F9` | `#171A21` | Sidebar background, hover backgrounds |
| `--neutral-200` | `#E2E8F0` | `#1F232C` | Borders, dividers |
| `--neutral-400` | `#94A3B8` | `#4B5563` | Placeholder text, disabled text |
| `--neutral-600` | `#475569` | `#9CA3AF` | Secondary body text |
| `--neutral-900` | `#0F172A` | `#F1F5F9` | Primary text/headings |

### Light Mode

Default surface: `--neutral-0` background, `--neutral-900` text, `--neutral-200` borders. Cards sit on `--neutral-50` or `--neutral-0` with a 1px `--neutral-200` border rather than a heavy shadow — the "Stripe Dashboard" feel of flat, bordered surfaces.

### Dark Mode

Default surface: `--neutral-0` (dark) background at `#0B0D12` — deliberately near-black rather than dark gray, matching Linear/Vercel's high-contrast dark aesthetic. Cards use `--neutral-50` (dark) at `#11141A` with a subtle `--neutral-200` (dark) border. Status colors are boosted in saturation/lightness slightly in dark mode (as reflected in the tables above) to maintain contrast against dark backgrounds without looking neon.

---

## Typography

### Fonts

- **Primary UI Font:** `Inter` — used for all UI text, body copy, forms, navigation. Excellent legibility at small sizes, wide language support, free and self-hostable.
- **Display/Heading Accent (optional):** `Inter` at higher weights is sufficient; no separate display font is introduced, keeping the type system minimal (a Linear/Vercel convention — one font family, weight and size do the work).
- **Monospace (data/technical):** `JetBrains Mono` or `Geist Mono` — used exclusively for appointment codes, patient IDs, timestamps in tables, and any tabular numeric data where digit alignment matters.

### Font Sizes

A restrained modular scale, mapped to Tailwind's default type scale for consistency:

| Token | Size | Usage |
|---|---|---|
| `text-xs` | 12px | Badges, table meta text, timestamps |
| `text-sm` | 14px | Body text (default UI density), form labels |
| `text-base` | 16px | Public-facing body copy, primary reading text |
| `text-lg` | 18px | Card titles, section sub-headings |
| `text-xl` | 20px | Page section headings |
| `text-2xl` | 24px | Dashboard page titles |
| `text-3xl` | 30px | Public page section headers |
| `text-4xl` | 36px | Homepage hero secondary line |
| `text-5xl` | 48px | Homepage hero primary headline |

**Dashboard UI defaults to `text-sm` as the base body size** — denser than a marketing site, matching Linear/Notion's information-dense working surfaces. Public-facing pages use `text-base` as the reading default for comfort.

### Line Heights

| Context | Line Height |
|---|---|
| Headings | `1.2` (tight, confident) |
| Body/UI text | `1.5` (comfortable reading) |
| Dense table cells | `1.35` |
| Long-form public content (About, policies) | `1.65` |

### Weights

| Weight | Value | Usage |
|---|---|---|
| Regular | 400 | Body text, table content |
| Medium | 500 | Form labels, nav items, card titles |
| Semibold | 600 | Section headings, button text, active states |
| Bold | 700 | Page titles, hero headlines, statistic figures |

**Rule:** Never use more than 3 weights on a single screen. Avoid `font-light` entirely — it reads as fragile on data-dense dashboards.

---

## Spacing System

### 4px / 8px System

All spacing (padding, margin, gap) is derived from a **4px base unit**, expressed via Tailwind's default spacing scale (`1 = 4px`). Components primarily use **8px increments** for a cleaner rhythm, dropping to 4px only for tight internal component spacing (icon-to-label gaps, badge padding).

| Token | Value | Typical Use |
|---|---|---|
| `space-1` | 4px | Icon-to-text gap, badge internal padding |
| `space-2` | 8px | Compact stacking (form label to input) |
| `space-3` | 12px | Button internal padding (vertical) |
| `space-4` | 16px | Standard component padding, card internal padding (mobile) |
| `space-6` | 24px | Card internal padding (desktop), section gaps |
| `space-8` | 32px | Section-to-section spacing |
| `space-12` | 48px | Major layout block separation |
| `space-16` | 64px | Public page section separation |
| `space-24` | 96px | Homepage hero vertical breathing room |

### Container Width

| Context | Max Width |
|---|---|
| Dashboard content area | `1440px` (fluid within sidebar-offset layout) |
| Public marketing pages | `1280px` |
| Reading content (About, policy pages) | `768px` for text blocks within the wider page |
| Auth screens (login/register) | `420px` centered card |
| Modals (standard) | `560px` |
| Modals (large, e.g., appointment detail) | `720px` |

### Grid

- Base grid: **12-column**, `24px` gutter on desktop, `16px` on tablet, `16px` on mobile.
- Dashboard widget grids default to a **4/8** or **6/6** split for primary content vs. sidebar widgets; statistic cards use **3 or 4 equal columns** on desktop, collapsing to **2 columns** on tablet, **1 column** on mobile.

### Breakpoints

| Name | Width | Notes |
|---|---|---|
| `sm` | 640px | Large phones (landscape) |
| `md` | 768px | Tablets |
| `lg` | 1024px | Small laptops — **sidebar becomes persistent from here up** |
| `xl` | 1280px | Standard desktop |
| `2xl` | 1536px | Large monitors — content area gains max-width, doesn't stretch edge-to-edge |

---

## Border Radius

A consistent, moderate radius scale — rounded enough to feel modern and approachable, never "bubbly."

| Token | Value | Usage |
|---|---|---|
| `radius-sm` | 6px | Badges, small buttons, checkboxes |
| `radius-md` | 8px | Inputs, standard buttons, table rows (on hover highlight) |
| `radius-lg` | 12px | Cards, dropdowns, popovers |
| `radius-xl` | 16px | Modals, large feature cards |
| `radius-full` | 9999px | Avatars, pills/badges, switches |

**Rule:** Never mix more than two radius sizes within a single component (e.g., a card with `radius-lg` should use `radius-md` or `radius-sm` for nested buttons/inputs, not `radius-xl`).

---

## Shadows

Shadows are used **sparingly** — the primary depth cue is a 1px border, not elevation. Shadows are reserved for elements that truly float above content (modals, dropdowns, toasts, popovers).

| Token | Definition (Light Mode) | Usage |
|---|---|---|
| `shadow-xs` | `0 1px 2px rgba(15, 23, 42, 0.04)` | Buttons on hover, subtle card lift |
| `shadow-sm` | `0 1px 3px rgba(15, 23, 42, 0.06), 0 1px 2px rgba(15,23,42,0.04)` | Dropdown menus, tooltips |
| `shadow-md` | `0 4px 8px rgba(15, 23, 42, 0.08)` | Popovers, date pickers |
| `shadow-lg` | `0 12px 24px rgba(15, 23, 42, 0.10)` | Modals |
| `shadow-xl` | `0 20px 40px rgba(15, 23, 42, 0.12)` | Command palette / large overlays |

**Dark mode:** shadows are reduced in opacity and supplemented with a subtle `1px` lighter border (`--neutral-200` dark), since dark shadows read poorly on near-black backgrounds — elevation in dark mode is communicated primarily through background lightness steps (`--neutral-50` vs `--neutral-100`), not shadow depth.

---

## Icons

- **Icon set:** `lucide-vue-next` (Lucide) — matches the Shadcn UI ecosystem, consistent 24×24 grid, stroke-based (not filled), reads as precise and modern rather than playful.
- **Stroke width:** `1.5px` – `2px` standard; never mix stroke weights within the same view.
- **Sizes:** `16px` (inline with `text-sm` labels), `20px` (default UI icon, nav items, buttons), `24px` (empty states, feature highlights), `32px+` reserved for illustrated empty states only.
- **Color:** Icons inherit `currentColor` by default — they take on the text color of their context (neutral-600 for default UI, primary-500 for active states, status colors for status indicators) rather than being independently colored.
- **No custom illustrated icon sets.** No flat-design medical icon packs (syringes, pill bottles, heartbeats) used decoratively — icons communicate function (calendar, clock, user, file), not "hospital theme."

---

## Buttons

### Variants

| Variant | Usage | Visual Treatment |
|---|---|---|
| **Primary** | Main call-to-action per screen (Book Appointment, Save, Confirm) | Solid `primary-500` background, white text |
| **Secondary** | Alternative actions alongside a primary action | `neutral-0` background, `neutral-200` border, `neutral-900` text |
| **Outline** | Lower-emphasis actions, filter toggles | Transparent background, `neutral-200` border, hover fills `neutral-50` |
| **Ghost** | Tertiary/icon-only actions, table row actions | No background/border by default, `neutral-100` background on hover |
| **Destructive** | Cancel appointment, delete user, remove record | Solid `danger-500` background, white text |
| **Link** | Inline text actions (e.g., "View all," "Forgot password?") | No background/border, `primary-500` text, underline on hover |

### States

| State | Treatment |
|---|---|
| **Default** | Base variant styling |
| **Hover** | Background/text shifts one step darker (e.g., `primary-500` → `primary-600`); `150ms ease` transition |
| **Active/Pressed** | Background shifts a further step darker (`primary-700`); slight scale (`0.98`) optional for tactile feedback |
| **Focus-visible** | 2px `primary-500` focus ring with 2px offset — always visible on keyboard navigation, never suppressed |
| **Disabled** | 40% opacity, `cursor-not-allowed`, no hover/active transitions |
| **Loading** | Inline spinner replaces leading icon (or appears left of label); label may change to a progressive state (e.g., "Booking..."); button is disabled during load to prevent double-submit |

### Sizes

| Size | Height | Padding (horizontal) | Usage |
|---|---|---|---|
| `sm` | 32px | 12px | Table row actions, compact toolbars |
| `md` | 40px | 16px | **Default** — forms, cards, standard actions |
| `lg` | 48px | 24px | Primary page CTAs, hero sections, auth submit buttons |
| `icon` | 36px × 36px | — | Icon-only buttons (square, centered icon) |

---

## Forms

### Inputs

- Height matches button `md` (40px) for visual alignment when inputs and buttons sit inline.
- `radius-md` (8px), 1px `neutral-200` border, `neutral-0` background.
- **Focus state:** border shifts to `primary-500`, plus a soft 3px `primary-100` ring (glow) around the field — no harsh browser default outline.
- Placeholder text uses `neutral-400`, never used as a substitute for a label.
- Every input has a persistent, visible label (`text-sm font-medium`) above the field — labels are never removed in favor of placeholder-only patterns (accessibility requirement).
- Helper text (`text-xs text-neutral-600`) sits below the field when additional context is needed.

### Select

- Visually matches text input styling (same height, border, radius) with a trailing chevron icon (`ChevronDown`, 16px, `neutral-400`).
- Dropdown panel: `shadow-md`, `radius-lg`, `neutral-0` background, options use `radius-sm` hover highlight in `neutral-100`.
- Selected option indicated with a checkmark icon and `primary-500` text, not just a background fill, for accessibility redundancy.
- Searchable/combobox variant (for doctor/department selection with many options) follows the same visual language with an inline search input pinned to the top of the dropdown panel.

### Textarea

- Same border/radius/focus treatment as text inputs.
- Minimum 3 visible rows by default (e.g., "Reason for visit" field); resizable vertically only (`resize-y`), never horizontally.
- Character counter (`text-xs text-neutral-400`, bottom-right) shown when a max length is enforced (e.g., 1000 characters for appointment reason).

### Checkbox

- 18px × 18px, `radius-sm`, 1.5px `neutral-300` border, unchecked = transparent fill.
- Checked state: `primary-500` fill with white checkmark icon, `150ms ease` transition.
- Indeterminate state supported (partial selection in bulk admin tables) with a horizontal dash icon.
- Label is always clickable (large hit target extends across the label text, not just the box).

### Switch

- Used for binary settings (notification toggles, active/inactive status) rather than checkboxes when the action takes effect immediately (no separate "Save" step).
- Track: `radius-full`, 44px × 24px; thumb: `radius-full`, white, `shadow-xs`, animates position on toggle (`200ms ease`).
- On = `primary-500` track; Off = `neutral-200` track. Never uses red/green alone to indicate on/off (color-blind consideration) — position and (where relevant) an accompanying label communicate state.

### Validation

- **Inline, real-time validation** on blur (not on every keystroke, which feels aggressive) for most fields; exceptions: password strength and confirmation-match feedback may update live.
- Error state: border shifts to `danger-500`, a `danger-500` helper text replaces the default helper text, prefixed with an alert-circle icon (16px).
- Success state (optional, for critical fields like email availability): border shifts to `success-500` with a check icon — used sparingly, only where positive confirmation adds real value (not on every valid field).
- Form-level submission errors (e.g., "This slot was just booked by another patient") appear as a dismissible inline banner (`danger-100` background, `danger-700` text) at the top of the form, in addition to any field-level errors — never as a silent failure.
- Required fields are marked with a subtle asterisk in `danger-500`; a "* Required" legend is included once per form, not repeated per field.

---

## Cards

- Base card: `neutral-0` background, 1px `neutral-200` border, `radius-lg` (12px), `space-6` (24px) internal padding on desktop / `space-4` (16px) on mobile.
- No default shadow on static content cards — the border provides sufficient separation (Stripe/Linear convention). `shadow-xs` may be added only on interactive/hoverable cards (e.g., a clickable doctor card) to reinforce affordance.
- **Interactive cards** (doctor profile card, clickable dashboard widgets) gain `shadow-sm` and a 1px `primary-100` border tint on hover, with a `150ms ease` transition — never a jarring scale/lift effect.
- Card header (title + optional action button/menu) is visually separated from card body via spacing alone, not a divider line, unless the card contains multiple distinct sections (e.g., a card with a table inside uses a divider between header and table).
- Statistic cards (see Dashboard Layout) are a specialized card variant — see below.

---

## Tables

- Row height: 48px default (dashboard data tables), 40px in compact/dense mode (available as a user-toggleable density option for power users — Doctor/Admin appointment lists).
- Header row: `text-xs font-medium text-neutral-600`, uppercase tracking optional, `neutral-50` background, sticky on scroll for long tables.
- Row borders: 1px `neutral-200` bottom border only (no vertical column borders — keeps the table feeling light, not spreadsheet-heavy).
- **Row hover:** `neutral-50` background shift, `100ms ease` — signals interactivity without being distracting.
- **Zebra striping is not used** — relies on hover and clear row-height rhythm instead, consistent with the minimal aesthetic.
- Status is always shown via a **status badge** (pill, `radius-full`, colored per status token) in its own column, never via full-row background coloring.
- Numeric/tabular data (fees, counts, dates) uses the monospace font and is right-aligned; text data is left-aligned.
- Row actions (edit, cancel, view) are right-aligned, revealed as icon buttons — visible by default on touch/tablet, and either always-visible or hover-revealed on desktop depending on table density needs (dense admin tables show icons on hover to reduce visual noise; lower-density patient tables show them persistently).
- Empty and loading states follow the [Empty States](#empty-states) and [Loading Skeletons](#loading-skeletons) sections respectively — a table is never simply blank.
- Pagination sits below the table: page size selector (left), page navigation (right), consistent across every paginated table in the system.

---

## Charts

- Chart library: `recharts` (per stack), styled to match the design system rather than using default chart-library colors.
- Chart color usage draws from the core palette: `primary-500` for the primary data series, `accent-500` and `neutral-400` for secondary/comparison series, status colors only when a chart is explicitly status-based (e.g., appointments by status donut chart).
- Gridlines: single-direction, `neutral-200`, low-opacity — never a heavy crosshatch grid.
- Axis labels: `text-xs text-neutral-600`.
- Tooltips: match the popover style (`neutral-0` background, `shadow-md`, `radius-md`, 1px border), show exact values with the monospace font for numbers.
- Line charts use smooth-but-not-overly-curved interpolation (`monotone`), 2px stroke weight, with an optional subtle gradient area fill (`primary-500` at 8% opacity fading to transparent) for trend emphasis — no heavy drop shadows on chart lines.
- Bar charts use `radius-sm` rounded top corners only, consistent gap ratio (roughly 40% bar / 60% gap) for a clean, non-cluttered look.
- Every chart has a clear title and, where relevant, a date-range control — charts are never presented without context or the ability to adjust scope.

---

## Dashboard Layout

### Sidebar

- Fixed-width **260px** on desktop (`lg` and above), collapsible to a 72px icon-only rail (toggle persisted per user via local preference).
- Background: `neutral-50`/`neutral-100` (a step darker than the main content area) with a 1px right border — visually distinct from content without competing with it.
- Structure top-to-bottom: logo/product mark → primary navigation (grouped by section with subtle `text-xs uppercase text-neutral-400` group labels) → spacer → secondary items (Settings, Help) → user account menu (avatar + name + role badge) pinned to the bottom.
- Active nav item: `primary-50` background, `primary-700` text, `radius-md`, with a 2px `primary-500` left indicator bar. Inactive items: `neutral-600` text, `neutral-100` hover background.
- Role-specific navigation: Patient, Doctor, and Admin each see a distinct item set — the sidebar is generated from the user's role/permissions, not manually hidden via CSS.
- On tablet (`md`) and below, the sidebar collapses into a slide-over drawer triggered by a hamburger icon in the top navigation, overlaying content with a scrim rather than pushing content.

### Top Navigation

- Height: 64px, `neutral-0` background, 1px bottom border, sticky on scroll.
- Contains: page title/breadcrumb (left), global search trigger (center or left-adjacent, `⌘K` command-palette style), and a right-aligned cluster of notification bell (with unread badge), theme toggle (light/dark), and user avatar menu.
- Breadcrumbs use `text-sm text-neutral-600` with `text-neutral-900` for the current page, separated by a subtle chevron — used on nested admin views (e.g., Departments > Cardiology > Doctors).

### Content Area

- Max content width `1440px`, centered, with `space-6`–`space-8` outer padding (responsive).
- Consistent page header pattern: page title (`text-2xl font-semibold`) + optional description line + right-aligned primary action button, followed by `space-6` before main content begins.
- Content is organized in a responsive grid (see Grid section) — widgets and cards snap to the 12-column system, never arbitrarily positioned.

### Widgets

- Dashboard widgets (upcoming appointments list, quick actions, recent activity feed) are cards with a consistent header pattern: title + optional "View all" link (top-right, `text-sm`, `primary-500`).
- Widgets support their own internal empty/loading states independently — one widget loading does not block the rendering of others (progressive loading, not an all-or-nothing dashboard spinner).

### Statistics Cards

- Compact card variant used for KPI display (e.g., "Today's Appointments: 24").
- Layout: small label (`text-xs text-neutral-600 uppercase`) → large figure (`text-3xl font-bold`, monospace for the number) → optional trend indicator (small colored badge with arrow icon: `success-500` for positive trend, `danger-500` for negative, `neutral-400` for flat) → optional sparkline mini-chart along the bottom edge.
- Statistic cards are grouped in rows of 3–4 on desktop, always equal-width, equal-height, with consistent internal padding regardless of content length (shorter labels don't produce shorter cards).

---

## Public Website Design

The public site must read as a **modern SaaS product homepage**, not a hospital brochure — closer to a Linear/Vercel marketing page than a typical healthcare site.

### Homepage

- Hero: large, confident headline (`text-5xl font-bold`), one-line supporting copy (`text-lg text-neutral-600`), primary CTA ("Book an Appointment") + secondary CTA ("Browse Doctors"). No stock photography of doctors — instead, use a clean product-style visual: an abstract illustration of the booking flow, a stylized calendar/schedule graphic, or a soft gradient/mesh background consistent with the primary/accent palette.
- Departments section: a clean grid of department cards (icon + name + short description), consistent card treatment per the Cards section.
- "How it Works" section: 3–4 step horizontal process (Search → Select Doctor → Pick a Slot → Confirm), using numbered steps with connecting lines, not clipart icons.
- Featured/Top Doctors section: horizontally scrollable or grid of doctor cards (see Doctor Profile below for card anatomy).
- Trust section: statistics (e.g., "10,000+ appointments booked," "50+ specialists") displayed in the statistic-card style, reinforcing product credibility the way a SaaS site would show usage metrics.
- Footer: standard multi-column SaaS footer (Product, Company, Legal, Contact), `neutral-900` background in light mode for contrast (a common premium-SaaS footer treatment), with muted `neutral-400` text.

### Doctors (Directory)

- Left-side (or top, on mobile) filter panel: department, specialization, availability, search — using the Select/Search input patterns defined above, filters apply without a full page reload (Inertia partial reloads).
- Doctor cards in a responsive grid (3 columns desktop, 2 tablet, 1 mobile): avatar (circular, `radius-full`), name, specialization, department badge, star rating (if reviews enabled), consultation fee, "View Profile" ghost button.
- Empty filter results show the standard [Empty State](#empty-states) pattern with a "Clear filters" action.

### Doctor Profile

- Two-column layout on desktop: left column (sticky) shows doctor photo, name, credentials, department, rating, and a prominent "Book Appointment" primary button; right column shows tabbed content — About/Bio, Qualifications, Availability preview, Reviews.
- Availability preview shows the next few open slots directly on the profile (read-only teaser) to reduce friction before entering the full booking flow.
- Mobile: single column, photo/CTA block first, tabs below, with the "Book Appointment" button also available as a sticky bottom bar for easy thumb access.

### Booking

- A focused, **distraction-free flow** — sidebar/top nav may be simplified or hidden to reduce drop-off, replaced with a minimal progress indicator (Step 1 of 3: Select Slot → Confirm Details → Review).
- Slot selection: calendar date picker (see Calendar Design) paired with a time-slot grid (pill buttons per available time, `radius-full`, disabled styling for unavailable slots — never simply hidden, so patients understand the doctor's full schedule).
- Confirmation step: clear summary card (doctor, department, date/time, fee) before final submission — no destructive/irreversible action without an explicit review step.
- Success state: a dedicated confirmation screen (not just a toast) with appointment code, calendar-add action, and clear next steps — booking confirmation is a moment worth a full, calm screen, not a fleeting notification.

### About

- Long-form content page using the constrained `768px` reading width, `line-height 1.65`, standard heading hierarchy — a content page, not a dashboard, so it can afford more traditional editorial spacing.

### Contact

- Simple two-column layout: contact form (left) using standard form components, hospital contact details/map/hours (right) in a card. Form submission uses the same validation and toast patterns as the rest of the product for consistency.

### Authentication

- Centered card (`420px` max-width) on a subtly branded background (soft gradient or muted pattern using `primary-50`/`accent-50` tones — never a busy photo background).
- Consistent structure across Login, Register, Forgot Password, Reset Password: logo mark top-center, form title, form fields, primary submit button (full-width), secondary link below (e.g., "Don't have an account? Register").
- Password fields include a visibility toggle (eye icon) and, on registration, a live strength indicator (segmented bar using neutral/warning/success colors, not a scary red-heavy meter).
- Role selection is never exposed at registration — all self-registration produces a Patient account per business rules; Doctor/Admin accounts are provisioned internally, so no role picker appears on this screen.

---

## Patient Dashboard Design

- Landing view on login: greeting header ("Welcome back, [Name]") + primary "Book New Appointment" CTA prominently placed top-right.
- Primary widget: **Upcoming Appointments** — card list (not a dense table, since patients typically have few active appointments) showing doctor avatar, name, specialization, date/time, status badge, and quick actions (Reschedule, Cancel, View Details).
- Secondary widgets: Recent Prescriptions (compact list, downloadable), Notifications feed, Quick Actions (Find a Doctor, View Medical History).
- Appointment History accessed via a dedicated nav item, using a filterable table (date range, status, doctor) with the standard table component.
- Profile section uses a settings-page pattern: left-side vertical tab list (Profile, Security, Notifications) + right-side form panel, consistent with the Admin settings pattern for product-wide familiarity.

---

## Doctor Dashboard Design

- Landing view: **Today's Schedule** as the hero widget — a timeline-style list of today's appointments in chronological order, current/next appointment visually emphasized (subtle `primary-50` highlight background).
- Statistics row: Today's Appointments, Pending Requests, Completed This Week, Average Rating — using the Statistics Card pattern.
- Appointment management view: table-first (higher data density expected for a working clinician), with inline status-update actions and a slide-over panel (not full navigation) for viewing patient history/adding consultation notes — minimizing context-switching during a busy schedule.
- Schedule management: a dedicated visual weekly-availability editor (grid of days × time blocks, click-and-drag to define working hours) paired with an exceptions/leave list below it.
- Patient history access is presented as a searchable list scoped only to the doctor's own patients, reinforcing the permission boundary visually as well as functionally (no "browse all patients" option ever appears in this role's UI).

---

## Admin Dashboard Design

- Landing view: system-wide statistics row (Total Appointments Today, Active Doctors, New Patients This Week, Revenue Snapshot) followed by a two-column layout: Appointment Volume trend chart (left, larger) + Recent Activity feed (right, narrower, pulling from `activity_logs`).
- Management sections (Doctors, Patients, Departments, Appointments, Settings) live under clearly grouped sidebar sections, each following the same list-page pattern: page header with primary action (e.g., "Add Doctor") → filter/search bar → data table → pagination.
- Bulk actions (e.g., deactivate multiple doctors) are supported via row checkboxes and a contextual action bar that slides in above the table when selections are made — never a permanently visible bulk-action toolbar cluttering the default view.
- Reports/Analytics section is chart-forward, using the Charts guidelines, with export actions (CSV/PDF) as secondary buttons near each report's header.
- Settings follow the vertical-tab settings pattern (General, Scheduling, Notifications, Roles & Permissions, Billing) consistent with the Patient profile settings pattern, reinforcing the design system's internal consistency across roles.
- Destructive admin actions (delete department, suspend doctor) always route through a confirmation modal (see Modal Design) with the consequence explicitly stated in plain language, not just "Are you sure?".

---

## Calendar Design

- Two primary calendar contexts: **date picker** (for booking/scheduling forms) and **full calendar view** (Doctor's schedule overview, Admin's appointment calendar).
- Date picker: compact month grid, `radius-lg` container, `shadow-md`, current day indicated with a `primary-100` ring, selected day filled solid `primary-500` with white text, unavailable/past days shown at reduced opacity and non-interactive (not hidden — visible-but-disabled preserves spatial consistency of the grid).
- Days with available slots show a small dot indicator beneath the date number; fully booked days show no dot (visually distinct from "not yet loaded").
- Full calendar view (Doctor/Admin): supports Day, Week, and Month views (toggle top-right), appointments rendered as compact colored blocks (color = status), time-axis on the left in the monospace font, current-time indicator as a thin `danger-500` horizontal line in Day/Week view.
- Clicking/tapping an appointment block opens a popover (not a full navigation) with a quick summary and a "View Full Details" link — keeps the calendar the primary working surface.
- Calendar respects the hospital's configured business hours by default (collapsing/graying non-working hours) with an option to expand and view the full 24-hour range if needed.

---

## Appointment Cards

The appointment card is one of the system's most-reused components (patient dashboard, doctor schedule, admin lists) and must be visually consistent everywhere it appears.

**Anatomy:**
- Left: participant avatar (the *other* party — doctor's avatar shown to the patient, and vice versa), `radius-full`, 40–48px.
- Center: primary line (doctor/patient name, `font-medium`), secondary line (specialization/department or reason for visit, `text-sm text-neutral-600`), date/time line (`text-sm`, monospace for the time).
- Right: status badge (`radius-full` pill, colored per status token — `pending` = warning, `confirmed` = info, `completed` = success, `cancelled`/`no_show` = danger, `rescheduled` = neutral) stacked above a compact action menu (kebab icon → dropdown: Reschedule, Cancel, View Details) or inline quick-action buttons where space allows.
- Hover (in list contexts): subtle `neutral-50` background and `shadow-xs`, entire card clickable to open details, with the action menu independently clickable (event propagation stopped) to avoid accidental navigation when managing the appointment directly.
- Compact variant (used in dense dashboard widgets): collapses the secondary line and reduces avatar to 32px, retaining the same color/status language.

---

## Modal Design

- Container: `radius-xl` (16px), `shadow-lg`, `neutral-0` background, max-width per content type (`560px` standard, `720px` for content-heavy modals like appointment details or medical record entry).
- Structure: header (title `text-lg font-semibold` + close icon button top-right) → 1px divider → body (`space-6` padding) → 1px divider → footer (right-aligned action buttons: secondary/cancel on the left of the pair, primary action on the right, following platform convention).
- Overlay: `neutral-900` at 40% opacity (light mode) / 60% opacity (dark mode), with a subtle backdrop blur for a premium feel — click-outside-to-close enabled for non-destructive modals, disabled (must use explicit Cancel) for destructive/data-entry modals to prevent accidental data loss.
- Destructive confirmation modals use a compact variant (`420px` max-width), icon (danger-colored, circular tinted background) + concise headline + one explanatory sentence + Cancel/Confirm button pair, with the destructive action styled as the `Destructive` button variant, never accidentally styled as Primary.
- Entrance animation: fade + slight scale-up (`0.96 → 1`) over `180ms ease-out`; exit is the reverse at `120ms ease-in` (faster out than in, standard motion practice).
- Focus is trapped within the modal while open, and returns to the triggering element on close (accessibility requirement, not optional polish).

---

## Empty States

Every list, table, or widget that can be empty must have a **designed empty state** — never a blank white space or a raw "No data" string.

**Anatomy:**
- Centered content within the container: a simple line-art icon (24–32px, `neutral-300`, from the Lucide set — e.g., `CalendarX` for no appointments, `Users` for no patients) inside a subtle circular `neutral-100` background.
- Headline (`text-base font-medium text-neutral-900`) stating the state plainly (e.g., "No upcoming appointments").
- Supporting line (`text-sm text-neutral-600`) offering context or next step (e.g., "Book your first appointment to see it here.").
- Primary action button where relevant ("Book Appointment," "Add Doctor," "Clear Filters") — empty states are treated as an opportunity to guide the next action, not a dead end.
- Distinguish clearly between **"no data yet"** (e.g., new user, encouraging first action) and **"no results for this filter"** (encouraging filter adjustment) — copy and CTA differ between these two cases even though the visual pattern is shared.

---

## Loading Skeletons

- Skeleton screens are used for all primary content loads (dashboard widgets, tables, cards) instead of centered spinners, matching the perceived-performance conventions of Linear/Notion/Stripe.
- Skeleton blocks use a `neutral-100` (light) / `neutral-100` (dark) base with a subtle left-to-right shimmer animation (`1.5s ease-in-out infinite`), respecting `prefers-reduced-motion` (falls back to a static pulse-opacity animation instead of a moving shimmer).
- Skeletons mirror the actual layout of the content they're replacing (e.g., a table skeleton shows the correct number of columns and approximate row heights) rather than a generic gray box, so the transition from skeleton to real content feels seamless, not jarring.
- Small, secondary UI updates (e.g., a status badge updating after an action) use a subtle inline spinner or opacity-dim-on-the-existing-content approach instead of a full skeleton replacement — skeletons are reserved for initial/first content loads, not every re-fetch.
- Global, full-page spinners are avoided entirely except for the very first authenticated page load (session bootstrapping); all subsequent navigation within the app uses Inertia's progress indicator (a slim top-of-page loading bar, `primary-500`) plus local skeletons.

---

## Toast Notifications

- Position: bottom-right on desktop/tablet, bottom-center (full-width minus margin) on mobile — never top-center, which competes with the sticky top navigation.
- Anatomy: leading status icon (colored per type), message text (`text-sm`), optional inline action link (e.g., "Undo"), close icon (top-right of the toast, always available even on auto-dismiss toasts).
- Types map directly to the status color tokens: Success (`success-500` icon/accent), Error (`danger-500`), Warning (`warning-500`), Info (`info-500`) — background stays `neutral-0`/card-style with a colored left border accent (4px) rather than a fully colored toast body, keeping it legible and on-brand rather than looking like a generic alert banner.
- Auto-dismiss after 5 seconds for informational/success toasts; error toasts persist until manually dismissed (errors should never silently disappear before the user has processed them).
- Multiple toasts stack vertically with `space-2` gap, newest on top (or bottom, consistently chosen — newest-on-top is recommended to avoid layout jump for the user's eye line), capped at 3 visible at once with additional toasts queued.
- Entrance: slide-in + fade from the edge (`right` on desktop, `bottom` on mobile), `200ms ease-out`; exit: fade + slight slide, `150ms ease-in`.

---

## Responsive Rules

### Desktop (`lg` and above, ≥1024px)

- Persistent sidebar (full 260px or collapsed 72px rail), top navigation, multi-column dashboard grids (3–4 statistic cards per row, 2-column widget layouts).
- Tables show full column sets; hover-revealed row actions are acceptable.
- Modals centered with generous surrounding overlay space.

### Tablet (`md`, 768px–1023px)

- Sidebar collapses to an off-canvas drawer (hamburger-triggered), top navigation remains persistent.
- Statistic card grids drop to 2 columns; widget layouts stack to a single column in most cases, with select two-column layouts retained only where content genuinely fits (e.g., a compact stat pair).
- Tables either scroll horizontally within their container (preserving column integrity) or switch to a condensed column set (hide least-critical columns, e.g., "Created Date") depending on the specific table's priority columns — decided per-table, not a single blanket rule.
- Touch targets increase slightly (minimum 40px height on interactive elements, up from 36px minimum on desktop) to account for touch input.

### Mobile (below `md`, <768px)

- Sidebar fully replaced by the off-canvas drawer; top navigation simplifies to logo/menu-icon (left) + essential icons only (notifications, avatar) on the right — search may move into the drawer or a dedicated icon-triggered overlay.
- All multi-column grids (statistics, doctor cards, department cards) collapse to a single column.
- Tables convert to a **stacked card list pattern** (each row becomes a compact card showing the 2–3 most important fields + a "View Details" tap target) rather than forcing horizontal scroll, which is a poor mobile pattern for this kind of data.
- Modals become full-screen sheets (slide up from bottom) rather than centered floating dialogs, maximizing usable space on small viewports.
- The Booking flow's floating "Book Appointment" CTA becomes a sticky bottom action bar on doctor profile pages for easy thumb reach.
- Minimum touch target size: 44px × 44px on all interactive elements, per platform accessibility conventions.

---

## Accessibility Guidelines

- **Color contrast:** All text meets WCAG 2.1 AA (4.5:1 for normal text, 3:1 for large text/UI components) in both light and dark mode — verified per token pairing, not assumed.
- **Never color-alone:** Status is always communicated with color **plus** text/icon (status badges include a label, not just a colored dot; form errors include icon + text, not just a red border).
- **Keyboard navigation:** Every interactive element (including custom Shadcn components — Select, Switch, Dialog) is fully operable via keyboard (Tab, Shift+Tab, Enter, Space, Escape, Arrow keys where applicable). Focus order follows visual/logical reading order.
- **Visible focus states:** A clear, consistent focus ring (`primary-500`, 2px, 2px offset) is present on every focusable element — focus outlines are never removed (`outline: none` without a replacement is prohibited).
- **Semantic HTML first:** Use native elements (`<button>`, `<label>`, `<table>`, headings in order) before reaching for ARIA; ARIA attributes supplement, not replace, semantic structure.
- **Form accessibility:** Every input has a programmatically associated `<label>`; error messages are linked via `aria-describedby`; required fields use `aria-required`.
- **Screen reader support:** Dynamic content changes (toast notifications, form errors, slot availability updates) are announced via `aria-live` regions; icon-only buttons always include an accessible name (`aria-label` or visually-hidden text).
- **Motion sensitivity:** All non-essential animation respects `prefers-reduced-motion: reduce` — shimmer effects, page transitions, and hover micro-interactions fall back to instant or minimal-motion equivalents.
- **Target sizing:** Minimum interactive target size of 44×44px on touch contexts, 36px minimum on pointer/desktop contexts, with adequate spacing between adjacent targets to prevent mis-taps (particularly important in dense admin tables).
- **Text resizing:** Layouts remain functional and non-clipping when browser text is scaled up to 200%.
- **Alt text:** All meaningful images (doctor photos, department icons where informative) include descriptive `alt` text; purely decorative graphics use empty `alt=""` to avoid screen-reader noise.

---

## Animation Guidelines

Motion in HAMS is **functional, not decorative** — it exists to clarify state changes and spatial relationships, never to impress. Default philosophy: fast, subtle, purposeful.

### Micro Interactions

- Button press: slight scale-down (`0.98`) on active state, `100ms ease`.
- Checkbox/switch toggle: fill/position transition, `150–200ms ease`.
- Badge/status changes: brief `150ms` color crossfade rather than an abrupt snap, so status updates (e.g., appointment confirmed) feel acknowledged rather than jarring.
- Form field focus: border color + ring fade-in, `120ms ease`.

### Hover Effects

- Cards/list rows: background tint and/or `shadow-xs` appears over `150ms ease` — no movement/lift/scale on hover for list items (movement is reserved for genuinely elevated, floating elements like modals and dropdowns, not inline content).
- Buttons: background/text color shift, `150ms ease`, no scale on hover (scale is reserved for the active/press state only, to avoid a "bouncy" feel).
- Links: underline fades in (`120ms ease`) rather than appearing instantly, for a refined feel consistent with the premium aesthetic.

### Transitions

- Page-level transitions (Inertia navigations): content cross-fades subtly (`150ms`) rather than a hard cut, paired with the slim top-loading progress bar; no full-page slide/zoom transitions, which feel heavy on a data-dense product.
- Sidebar collapse/expand: width transition `200ms ease-in-out`, with nested label text fading out before the width fully collapses (staggered, not simultaneous, to avoid visual mangling of text during the transition).
- Accordion/collapsible sections (e.g., FAQ, filter groups): height auto-transition `200ms ease-in-out`, content fades in slightly after the height transition begins.

### Loading

- Skeleton shimmer: `1.5s ease-in-out infinite`, as defined in the Loading Skeletons section.
- Button loading spinner: continuous `800ms linear infinite` rotation, standard spinner icon, no easing pauses (loading motion should read as "in progress," not decorative).
- Full route-level loading: the slim top progress bar (`primary-500`, 2–3px height) animates its width using an eased-but-continuous progression, completing with a quick fade-out (`200ms`) on load finish — never a modal-blocking spinner for standard navigation.

**General rule of thumb:** if an animation's purpose can't be stated in one sentence ("this confirms the toggle changed state," "this shows new content is arriving"), it doesn't belong in the product.

---

## UI Component Naming Convention

Component naming follows a consistent, discoverable pattern aligned with the Vue + Shadcn UI structure, to keep the frontend codebase as disciplined as the visual system.

- **Base/primitive components** (from Shadcn UI, lightly themed): kept under their original names in a dedicated primitives directory (e.g., `Button`, `Input`, `Select`, `Dialog`, `Switch`) — not renamed, so upstream updates remain easy to track.
- **Domain/composite components**: `PascalCase`, prefixed by domain/module for discoverability, pattern: `{Domain}{ComponentPurpose}`.
  - Examples: `AppointmentCard`, `AppointmentStatusBadge`, `AppointmentBookingForm`, `DoctorProfileCard`, `DoctorAvailabilityEditor`, `PatientProfileForm`, `DepartmentFilterPanel`, `DashboardStatCard`, `NotificationBell`, `ActivityLogItem`.
- **Layout components**: prefixed `Layout` or `App`, describing their structural role — `AppSidebar`, `AppTopNav`, `DashboardLayout`, `PublicLayout`, `AuthLayout`.
- **Page-level components** (Inertia page components): `PascalCase` matching the route/module structure, suffixed `Page` or `Index`/`Show`/`Create`/`Edit` per Laravel resourceful convention mirrored on the frontend — e.g., `Appointments/Index.vue`, `Appointments/Show.vue`, `Doctors/Create.vue`.
- **Shared/utility UI components**: prefixed `Ui` only when disambiguation from a domain component is genuinely needed (e.g., `UiEmptyState`, `UiLoadingSkeleton`, `UiConfirmDialog`) — otherwise kept simple (`EmptyState`, `Toast`, `Pagination`).
- **Props/variants naming**: consistent `variant` and `size` prop names across all components that support them (matching the Buttons section's variant/size vocabulary), so `variant="destructive"` or `size="sm"` behaves predictably regardless of which component it's applied to.
- **File/folder structure mirrors this taxonomy**: `components/ui/` (primitives), `components/domain/{module}/` (composite domain components), `components/layout/` (structural), `pages/{Module}/` (Inertia pages) — ensuring any engineer can predict a component's location from its name alone.

---

## Design Do's

- **Do** use whitespace generously on public/marketing pages and moderately-but-deliberately on dashboards — density is a tool, not a default.
- **Do** keep color usage functional: primary for action, status colors for state, neutral for structure.
- **Do** maintain one typeface family system-wide (Inter + JetBrains Mono for data) for a cohesive, premium feel.
- **Do** design every state of every component: default, hover, focus, active, disabled, loading, error, empty.
- **Do** use borders over shadows as the default depth cue; reserve shadows for genuinely floating elements.
- **Do** keep destructive actions behind explicit confirmation with plain-language consequences.
- **Do** ensure the product feels identical in spirit (same components, same spacing, same interaction patterns) across Patient, Doctor, and Admin experiences — only the content and permissions differ.
- **Do** test every screen in both light and dark mode before considering it complete.
- **Do** design mobile layouts as first-class experiences for the public site and Patient dashboard specifically, since patients are highly likely to book from a phone.

---

## Design Don'ts

- **Don't** use medical clip-art, stock photography of doctors/patients, or literal iconography (crosses, stethoscopes, pill bottles) as decorative elements.
- **Don't** use saturated "alarm" red for anything other than genuine destructive/critical actions — avoid red creeping into decorative or neutral contexts.
- **Don't** introduce a second display font, script font, or decorative typeface anywhere in the product.
- **Don't** rely on color alone to communicate meaning (status, validation, selection).
- **Don't** use heavy drop shadows, skeuomorphic gradients, or glossy button effects — this is not the visual language of this product.
- **Don't** stack more than two elevation cues (e.g., border + shadow is fine; border + shadow + glow + gradient is not).
- **Don't** hide critical actions behind ambiguous icon-only buttons without accessible labels or tooltips.
- **Don't** use full-page blocking spinners for routine navigation — always prefer skeletons and the slim progress bar.
- **Don't** allow inconsistent spacing values outside the defined 4px/8px scale ("just this once" spacing exceptions compound quickly across a large product).
- **Don't** design admin-only power-user screens with the same generous, low-density spacing used on public marketing pages — respect the different working contexts of each audience.
- **Don't** ship a component in only one theme (light or dark) — every component must be verified in both before merge.

---

**End of Document**
