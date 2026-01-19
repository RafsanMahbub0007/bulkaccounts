## Overview

* Ensure all public-facing pages render correctly on mobile, tablet, and desktop, using the existing Tailwind CSS setup

* Target views under [layouts](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/layouts), [livewire](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/livewire), and [partials](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/partials)

* Keep Filament admin as-is (already responsive); focus on storefront pages

## Baseline Setup

* Confirm Tailwind is active via Vite in [app.blade.php](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/layouts/app.blade.php#L26-L33)

* Establish consistent container widths and spacing using `max-w-*` and `px-*` at `sm`, `md`, `lg`, `xl`, `2xl` breakpoints

* Normalize media: images with `w-full h-auto`, responsive `aspect-video` where appropriate, and lazy loading

## Layout & Navigation

* Topbar/Offer/Nav in [partials](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/partials):

  * Implement mobile hamburger menu and collapse large nav items into drawers

  * Ensure sticky header behavior doesn’t overlap content and uses responsive paddings

* Footer: stack columns on mobile, grid on larger screens

## Home & Marketing Sections

* Update sections in [home.blade.php](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/livewire/home.blade.php) and partials (hero, featured-categories, testimonials, why-us):

  * Use responsive grid (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-3/4`) for cards

  * Ensure text sizes scale (`text-base sm:text-lg lg:text-xl`) and spacing adapts

## Product Browsing

* Category pages [categories.blade.php](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/livewire/categories.blade.php), [products.blade.php](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/livewire/products.blade.php), [product-details.blade.php](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/livewire/product-details.blade.php):

  * Responsive product grid with safe min card width on mobile

  * Details page: stack image and content on mobile; two-column at `md+`

  * Price/CTA block uses sticky on desktop, inline on mobile

## Cart & Checkout

* [cart.blade.php](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/livewire/cart.blade.php):

  * Convert data tables to card stacks on mobile using `hidden md:table` pattern

* [checkout.blade.php](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/livewire/checkout.blade.php):

  * Two-column layout at `lg+`, single-column on mobile

  * Large touch targets and form field spacing; error messages wrap correctly

## User Pages

* Orders/Payments/Dashboard [user-orders.blade.php](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/livewire/user-orders.blade.php), [user-payments.blade.php](file:///e:/rafsan%20pc/Rafsan/Rafsan/New%20folder/bulkaccounts/resources/views/livewire/user-payments.blade.php):

  * Make tables responsive with stacked rows on mobile

  * Ensure download buttons are visible and accessible

## Content Pages

* Blog/FAQ/Guidelines/Privacy/Terms: enforce readable line length and spacing; images scale fluidly; code blocks and lists wrap

## Accessibility & Quality

* Maintain contrast and focus states; ensure semantic headings order

* Test breakpoints: 360px, 768px, 1024px, 1280px, 1536px

* Cross-browser check: Chrome, Firefox, Safari (mobile/webkit)

## Performance

* Defer heavy media, use `loading="lazy"` and `decoding="async"`

* Ensure Vite bundles remain optimized; avoid inline large styles

## Deliverables

* Responsive updates across targeted Blade views without comments

* No backend logic changes; CSS/markup only

* Verification via responsive preview and manual checks at predefined breakpoints

## Next Step

* After approval, I will implement changes page-by-page, verify visually, and provide a summary of updated files with Code References for review.

