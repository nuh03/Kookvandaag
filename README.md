# WatEtenWeVandaag

Een snel NL/BE receptenplatform (Laravel, PHP 8.2+) gericht op shared hosting. Focus op SEO, AMP, AI, memberships, betalingen, Admin, Ads en volledige GDPR/Consent Mode v2.

## Features (MVP)
- Recepten (CRUD), filters, Recipe JSON-LD
- “Wat eten we vandaag?” (dagelijks via scheduler)
- AI restjes-generator (OpenAI, NL) met caching en rate limits
- Membership (guest/free/premium) met premium gate
- Betalingen: Stripe + Mollie (iDEAL/Apple Pay), webhooks (subscriptions + one-off)
- Weekplanner (7 dagen), boodschappenlijst (merge; export CSV/PDF)
- Account: login/registratie/profiel/abonnement; data export/delete (DSAR)
- Admin: dashboard; CRUD recipes/tags/featured/seizoenen; reviews moderatie; users; payment logs; CSV import/export
- Reviews, voedingswaarden/allergenen, kostenindicatie, seizoenspagina’s, favorieten
- PWA (offline receptdetail), Social sharing, Logging, Feature flags
- Ads & Google AdSense (DB-gestuurd, placements, stats), ads.txt
- Cookie/Privacy (GDPR, Consent Mode v2 + AMP-consent)

## Stack
- Laravel 10/11 (PHP 8.2+), MySQL/MariaDB
- Blade + Breeze (auth)
- Queue: database driver (cron)
- Caching: file/database (geen Redis vereist)

## Vereisten voor hosting
- PHP 8.2+ met ext-s: pdo_mysql, mbstring, tokenizer, openssl, ctype, json, xml, curl, gd/imagick
- MySQL/MariaDB database
- Cron beschikbaar (minuut)
- Schrijfbare mappen: `storage/`, `bootstrap/cache/`, uploads
- Document root naar `public/` (of map `public` naar `public_html`)

## Installatie-opties
1) SSH + Composer beschikbaar (aanbevolen)
   - composer create-project laravel/laravel
   - `composer install --no-dev --prefer-dist` (productie)
   - `php artisan key:generate`
   - `php artisan migrate --seed`
   - Cron: `* * * * * php /pad/naar/artisan schedule:run >> /dev/null 2>&1`
   - Queue (shared hosting): `* * * * * php /pad/naar/artisan queue:work --once --queue=default >> /dev/null 2>&1`

2) Geen SSH/Composer (shared hosting)
   - Upload een vooraf-gebouwde ZIP met `vendor/` en gecompileerde assets
   - Stel `.env` in (zie hieronder)
   - Zorg dat document root `public/` is (of deploy inhoud van `public/` naar `public_html/` + update paden)

Let op: Deze repository bevat (nog) geen `vendor/` en geen gecompileerde assets. Bij gebrek aan SSH/Composer zullen we een build-artefact aanleveren (ZIP) in een volgende stap.

## .env keys (voorbeeld)
- APP_NAME=WatEtenWeVandaag
- APP_ENV=production
- APP_URL=https://voorbeeld.nl
- APP_KEY=base64:...
- DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- OPENAI_API_KEY
- STRIPE_KEY, STRIPE_SECRET
- MOLLIE_API_KEY
- MAIL_MAILER=smtp, MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM_ADDRESS, MAIL_FROM_NAME
- ADSENSE_CLIENT=ca-pub-xxxxxxxxxxxxxxx
- ADSENSE_AUTO_ADS=true|false
- GTM_ID=GTM-XXXXXXX (optioneel)
- FEATURE_FLAGS=PWA,REVIEWS,EMAIL_ALERTS,ADS (comma-separated)

## SEO & AMP
- Receptdetail heeft AMP-versie: `/amp/recepten/{slug}`
- `<link rel="amphtml">` op normale pagina
- Sitemap (incl. AMP-URL’s) via scheduler
- Robots.txt met sitemap

## Consent (GDPR) – hoofdlijnen
- Categorieën: `strict_necessary`, `analytics`, `ads`, `marketing`
- Cookiebanner + voorkeurenpaneel (toggle per categorie); opslag in first-party cookie (12 maanden)
- Consent Mode v2: default denied bij eerste paint; granted na akkoord
- AMP: `<amp-consent>` gating + `<amp-ad>` pas na akkoord

## Ads
- DB-gestuurde placements en statistieken
- AdSense script alleen in production en na consent (ads=true)
- AMP-ads via `<amp-ad>`
- `ads.txt` endpoint/bestand

## PWA
- Manifest + service worker (offline receptdetail)

## Tests (rook)
- Core routes + admin auth
- SEO endpoints (sitemap/robots) + JSON-LD smoke
- Ads rendering + stats
- Consent gating (scripts geblokkeerd tot akkoord)
- AMP validator smoke (structuur)

## Lokale ontwikkeling (indicatief)
- PHP 8.2+, Composer, Node 20+
- `composer install` / `npm ci`
- `php artisan migrate --seed`
- `php artisan serve`

## Next steps
- Bevestig hostingdetails (Composer/SSH, public path, DB)
- Daarna initialiseren we het Laravel project, Breeze, packages en de eerste migrations/controllers/blades