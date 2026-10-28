---
name: Warm Artisanal POS
colors:
  surface: '#fff8f6'
  surface-dim: '#e1d8d6'
  surface-bright: '#fff8f6'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#fbf2f0'
  surface-container: '#f5ecea'
  surface-container-high: '#efe6e4'
  surface-container-highest: '#e9e1df'
  on-surface: '#1e1b1a'
  on-surface-variant: '#504440'
  inverse-surface: '#342f2e'
  inverse-on-surface: '#f8efed'
  outline: '#827470'
  outline-variant: '#d4c3be'
  surface-tint: '#79564b'
  primary: '#331910'
  on-primary: '#ffffff'
  primary-container: '#4b2e24'
  on-primary-container: '#be9587'
  inverse-primary: '#eabdae'
  secondary: '#7a5649'
  on-secondary: '#ffffff'
  secondary-container: '#fdcdbc'
  on-secondary-container: '#795548'
  tertiary: '#331a00'
  on-tertiary: '#ffffff'
  tertiary-container: '#512c00'
  on-tertiary-container: '#cc9158'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffdbcf'
  primary-fixed-dim: '#eabdae'
  on-primary-fixed: '#2d150c'
  on-primary-fixed-variant: '#5f3f34'
  secondary-fixed: '#ffdbcf'
  secondary-fixed-dim: '#ebbcac'
  on-secondary-fixed: '#2e150b'
  on-secondary-fixed-variant: '#603f33'
  tertiary-fixed: '#ffdcbf'
  tertiary-fixed-dim: '#fab97c'
  on-tertiary-fixed: '#2d1600'
  on-tertiary-fixed-variant: '#683c08'
  background: '#fff8f6'
  on-background: '#1e1b1a'
  surface-variant: '#e9e1df'
typography:
  headline-xl:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
  headline-lg:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
  headline-md:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  headline-sm:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '600'
    lineHeight: 24px
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
  label-lg:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.02em
  label-md:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.04em
  label-sm:
    fontFamily: Inter
    fontSize: 10px
    fontWeight: '700'
    lineHeight: 14px
    letterSpacing: 0.06em
  numeric-hero:
    fontFamily: Inter
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 36px
  numeric-summary:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '700'
    lineHeight: 24px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1rem
  margin: 1.25rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 0.75rem
  space-lg: 1.25rem
  space-xl: 2rem
---

## Brand & Style

This design system establishes a warm, artisanal, yet operationally sharp interface tailored for modern coffee house points-of-sale and order management. It reconciles the earthy, aromatic hospitality of specialty coffee with the zero-latency, high-ergonomics demands of fast-paced floor operations.

The aesthetic direction blends **Warm Modernity** with **Tactile Functionalism**:
- **Atmosphere:** Grounded, welcoming, and sensory, reflecting slow-drip craftsmanship while functioning with rapid register velocity.
- **Ergonomics:** Designed for high-stress cafe environments (cashiers under pressure, baristas checking tickets with wet hands, dim or high-glare ambient cafe lighting). Touch targets are deliberately generous, text hierarchies are instantaneous to parse, and status tokens eliminate cognitive overhead.
- **Visual Tone:** Natural cream undertones prevent screen glare during long shifts, replacing clinical stark whites with comforting bone porcelain and deep roasted espresso hues.

## Colors

The color palette centers around deeply roasted coffee notes, soft dairy froth, and warm caramel accents, supported by explicit semantic indicators for order lifecycle states.

### Core Swatches
- **Primary (`#4B2E24` - Dark Espresso):** High-priority UI anchors, persistent navigation rails, active selection borders, and authoritative action buttons.
- **Secondary (`#795548` - Roasted Bean):** Category chips, auxiliary headers, and secondary action wrappers.
- **Tertiary (`#C58B52` - Golden Crema / Caramel):** Accent focal points, total calculation highlights, active tab underscores, and badge highlights.
- **Neutral Primary (`#292524` - Roasted Charcoal):** All high-contrast primary typography, line-item pricing, and system titles.
- **Neutral Surface Light (`#FFFCF7` - Bone Porcelain):** Primary canvas for menu cards, ticket surfaces, and active modal sheets.
- **Neutral Background (`#F5EFE6` - Steamed Cream):** The underlying viewport background providing warm comfort and reduced eye fatigue under fluorescent or low light.
- **Border / Divider (`#E8DFD5` - Oat Latte):** Structural 1px separation lines keeping dense layouts breathable without heavy contrast.

### Operational State Tokens
- **Success / Lunas (`#1E7E4E` on `#EBF7EE`):** Paid orders, completed tickets, inventory restocked.
- **Info / Diproses (`#1D64B3` on `#EBF3FC`):** Kitchen preparation underway, barista brewing in progress.
- **Warning / Menunggu (`#B45309` on `#FEF3C7`):** Unpaid tables, queued orders awaiting fulfillment.
- **Danger / Batal (`#C2410C` on `#FEE2E2`):** Cancelled transactions, voided items, low stock alerts.

## Typography

The design system exclusively adopts **Inter** to maximize data legibility across POS terminals, high-density order matrices, and thermal receipt print-outs.

- **Tabular Figures:** All numeric displays (pricing, quantities, tax calculations, table numbers) must use `font-variant-numeric: tabular-nums` to guarantee straight vertical decimal alignment in checkout calculations.
- **Letter Spacing:** Tighter negative tracking (`-0.01em`) on large headlines preserves card space; extended tracking (`0.04em` to `0.06em`) on badge labels ensures readability on small badges viewed from distance.
- **Weight Strategy:** Strictly limits weights to Regular (400) for standard metadata, Semi-Bold (600) for interactive labels, and Bold (700) for prices, total calculations, and item names.

## Layout & Spacing

The terminal uses a fixed multi-pane operational grid tailored for desktop touch displays (1024px minimum resolution, optimized for 1280px–1920px POS terminals and tablet registers).

### Workspace Layout Structure
- **Persistent Narrow Rail (80px – 96px width):** Houses high-level navigation (Register, Tables, Kitchen Display System, Inventory, Reports) with oversized icon-and-label touch buttons.
- **Central Catalog Surface (Fluid flex-grow):** 
  - Top category filter bar (sticky, scrollable, `space-sm` gap).
  - Product catalog structured as an adaptive 3-to-5 column fluid grid with `gutter` spacing (16px).
- **Persistent Order Ticket / Cart Panel (380px – 440px fixed width):**
  - Always visible on the right panel. Contains order meta (Table #, Customer Type), a fluid line-item list, quick surcharge/discount options, calculation summaries, and bottom-anchored action triggers.

### Touch Target Standard
- Minimum interactive touch area is **44px × 44px**, with the POS number pad and action buttons scaling to a minimum height of **54px** for single-tap register entry without misclicks.

## Elevation & Depth

Visual hierarchy employs **warm surface-container stacking** combined with **subtle low-opacity warm-tinted shadows**, eliminating stark floating elevation in favor of a cohesive physical desktop workstation.

- **Canvas Base (`#F5EFE6`):** The ground floor for outer frames and system dividers.
- **Level 1 Containers (`#FFFCF7`):** Elevated menu items, navigation backgrounds, and ticket panes. Uses a hairline perimeter border (`1px solid #E8DFD5`) and an ambient tinted drop shadow: `0 2px 4px rgba(75, 46, 36, 0.04)`.
- **Level 2 Interactive & Active States:** Selected menu cards, active category pills, and floating modals. Features `0 8px 16px -2px rgba(75, 46, 36, 0.08)` with a warmer ambient bloom.
- **Level 3 Sheets & Modals:** Overlay drawers (Modifier screens: "Ice Level", "Sugar", "Extra Shot") layered over backdrop blur (`backdrop-filter: blur(4px) rgba(41, 37, 36, 0.4)`), using shadow `0 20px 25px -5px rgba(75, 46, 36, 0.15)`.
- **Ghost Linework:** In dense line-item receipts, shadows are disabled; separation relies entirely on subtle borders (`#E8DFD5`) to maximize horizontal scanning clarity.

## Shapes

The design system applies a balanced **Rounded (`roundedness: 2`)** structure, translating to 8px (`0.5rem`) base radii, 16px (`1rem`) on outer containers, and full pills on contextual status badges.

- **Base Components (Inputs, Small Buttons, Add/Minus Steppers):** `8px` (`0.5rem`).
- **Product Tiles & Receipt Containers:** `16px` (`1rem`) for approachable, non-aggressive tactile surfaces.
- **Status Badges, Filter Chips, & Quick Modifiers:** Pill shape (`9999px`) to immediately communicate self-contained, selectable, or status-oriented data chunks.
- **Receipt Digital Cut:** The bottom boundary of digital order receipts uses a subtle geometric serrated cut or dashed boundary line to reflect physical counter receipts.

## Components

### Buttons
- **Primary POS Action ("Bayar / Charge", "Kirim ke Dapur"):** Full-width or oversized primary `#4B2E24` background, `#FFFCF7` text, font weight 700. Min-height 52px. Hover/Active tone transitions smoothly to `#38221B`.
- **Secondary / Utility ("Diskon", "Simpan"):** Cream container `#F5EFE6` with border `1px solid #E8DFD5`, text `#4B2E24`.
- **Destructive ("Batal Pesanan", "Void"):** Pale red base `#FEE2E2` with dark red text `#C2410C`. Requires confirmation popover.

### Status Badges
- Compact pill-shaped containers (`padding: 4px 10px`, `label-sm`).
- **Lunas / Selesai:** Background `#EBF7EE`, text and border `#1E7E4E`.
- **Diproses:** Background `#EBF3FC`, text and border `#1D64B3`.
- **Menunggu:** Background `#FEF3C7`, text and border `#B45309`.
- **Batal:** Background `#FEE2E2`, text and border `#C2410C`.

### Menu Product Cards
- **Structure:** Vertical layout consisting of top item thumbnail (or clean monogram fallback), item title (`headline-sm`, `#292524`), category subtitle, and bottom anchored price (`numeric-summary`, `#4B2E24`).
- **Interaction:** Hover/Press introduces a 2px active ring in `#C58B52` (Caramel) and an instant numeric badge counter in the top-right corner if the product is already in the cart.
- **Stock Depletion:** Disabled cards switch to 50% opacity with an overlay badge "Habis".

### Order Item Rows (The Cart)
- Dense, highly scannable horizontal rows.
- Left column: Quantity indicator with inline quick-tap increment/decrement pills (`-`, `+`).
- Center column: Item title, variant tags (e.g., *Less Ice, Oat Milk* in `body-sm`, `#795548`).
- Right column: Total item cost (`tabular-nums`), trash/remove icon on swipe or subtle tap.

### Modifiers & Add-On Sheets
- Bottom or slide-over drawer triggered when tapping customizable products.
- Uses multi-select and single-select chips (`rounded: 9999px`, height 40px) to rapidly select shot options, temperature, cup sizes, and sweetness levels without typing.

### Summary & Calculation Block
- Anchored to the bottom of the right pane.
- Lines for Subtotal, PPN (11%), Service Charge, and Discounts.
- Distinct elevated callout box for Grand Total displaying `numeric-hero` typography in `#4B2E24`.