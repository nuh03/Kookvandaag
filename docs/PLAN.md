## Plan van aanpak (iteratief)

Dit plan volgt batches die aansluiten op shared hosting (geen Redis, cron beschikbaar) en de gevraagde features/acceptatiecriteria.

### Batch 1 — Init & Basis
- Laravel initialisatie + Breeze (Blade), NL locale
- Packages: openai-php/client, stripe/stripe-php, mollie/mollie-api-php, spatie/laravel-sitemap, honeypot/captcha
- Basis layout met slots (meta/OG/JSON-LD) en consent placeholders
- SEO routes (robots/sitemap/healthz placeholders)
- Docs: README/DEPLOY/ENV

Output: skeleton app, NL UI, auth, basis SEO endpoints

### Batch 2 — Datamodel & Migrations
- Migrations/Models: users (uit Breeze), recipes, recipe_reviews, plans, shopping_lists, ai_cache, redirects, seasons, ads, ad_stats, payment_logs, consent_logs
- Seeds: demo-plaatsen voor ads (placements), seizoenen, voorbeeldrecepten
- Admin stub (menu, auth-guard)

Output: database klaar voor core features

### Batch 3 — Controllers/Routes & Views (User)
- RecipesController (index/detail/filters + JSON-LD)
- TodayController (`/vandaag`) + scheduler (1×/dag wissel)
- Reviews (post/moderatie + drempel voor aggregateRating)
- Planner (7 dagen) + boodschappenlijst (merge; CSV/PDF export)
- Favorieten
- AMPRecipesController (`/amp/recepten/{slug}`) met valide AMP HTML en `<link rel="amphtml">`

Output: werkende user-flow, AMP receptdetail

### Batch 4 — AI & Memberships
- AI endpoints (OpenAI, NL chef prompt) + caching (24h) + throttles per rol
- Membership: guest/free/premium + gates op AI en planner export
- Response fallback bij >5s

Output: AI restjes-generator en role-based gating

### Batch 5 — Payments
- Stripe Cashier + Mollie API: abonnementen + one-off
- Webhooks: premium_until/status bijwerken
- E-mails bij succesvolle betaling

Output: betalingen end-to-end

### Batch 6 — Ads & Consent (volledig)
- DB-adsysteem: placements, stat logging (impressie/klik)
- Blade helper `ad($placement)` + partials (adsense/html/banner)
- AdSense injectie (prod + consent ads=true)
- `ads.txt` endpoint + beheer in Admin
- Consent module (banner + paneel + Consent Mode v2) en AMP-consent

Output: ads live, conform Consent Mode v2

### Batch 7 — SEO / PWA / Performance
- JSON-LD helpers (Recipe/Breadcrumb/FAQ/WebSite/Organization)
- Canonicals, noindex filters, sitemap scheduler, robots.txt
- PWA manifest + service worker (offline receptdetail)
- Response cache middleware, WebP/lazy-load/srcset, CLS-preventie

Output: sterke SEO + Core Web Vitals (LCP<2.5s, CLS<0.1)

### Batch 8 — DSAR & Admin uitbreidingen
- Account: data export (JSON/zip), account delete (beleid)
- Admin: reviews moderatie, users, payment logs, redirects, consent logs viewer, CSV import/export

Output: privacy-compleet + beheer

### Tests (doorlopend)
- Rooktests: core routes, admin auth
- SEO endpoints, JSON-LD smoke, ads rendering + stats
- Consent gating (scripts geblokkeerd tot akkoord), AMP-structuur validator smoke

### Deploy (shared hosting)
- Cron elke minuut (schedule + queue --once)
- Config/route/view caches
- Webhooks (Stripe/Mollie)

---

Volgende actie: Batch 1 uitvoeren zodra Composer/SSH beschikbaar is, of alternatief met prebuilt ZIP.