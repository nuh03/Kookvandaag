# Deploy handleiding (Shared Hosting)

Deze gids helpt je WatEtenWeVandaag te deployen op een standaard shared hosting omgeving met `public_html` en cron-toegang.

## 1. Mappenstructuur
- Document root moet wijzen naar `public/` van Laravel.
- Indien host `public_html/` vereist:
  - Optie A (aanbevolen): stel document root om naar `.../current/public`
  - Optie B: kopieer inhoud van `public/` naar `public_html/` en verwijs `index.php` naar de juiste paden:
    - `require __DIR__.'/../vendor/autoload.php';`
    - `$app = require_once __DIR__.'/../bootstrap/app.php';`
  - Houd `storage/` en `bootstrap/cache/` schrijfbaar.

## 2. PHP/Composer opties
- Met SSH/Composer: voer `composer install --no-dev --optimize-autoloader` uit
- Zonder Composer: upload vooraf gebouwde ZIP met `vendor/` en gecompileerde assets

## 3. Environment (.env)
- Kopieer `.env.example` naar `.env`
- Vul `APP_KEY` (genereer met `php artisan key:generate` of gebruik lokaal gegenereerde)
- Vul database, mail en 3rd-party keys (OpenAI, Stripe, Mollie, AdSense, GTM)

## 4. Migrations & seeds
- SSH: `php artisan migrate --force --seed`
- Geen SSH: voer migrations lokaal en upload DB dump (alleen als toegestaan en veilig)

## 5. Cron & Queue
- Cron elke minuut:
  - `* * * * * php /pad/naar/artisan schedule:run >> /dev/null 2>&1`
- Queue (database driver) op shared hosting:
  - `* * * * * php /pad/naar/artisan queue:work --once --queue=default >> /dev/null 2>&1`

## 6. Optimalisaties
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`
- Schakel debug uit: `APP_ENV=production`, `APP_DEBUG=false`

## 7. Webhooks
- Stripe: stel webhook in naar `/webhooks/stripe`
- Mollie: stel webhook in naar `/webhooks/mollie`
- Zorg dat HTTPS actief is

## 8. SEO & AMP checks
- Sitemap: `/sitemap.xml` bevat AMP URL’s
- Robots: `/robots.txt` met sitemap
- Recept-AMP: `/amp/recepten/{slug}` valideert via AMP validator
- Recipe JSON-LD valideert in Rich Results Test

## 9. Consent/Privacy
- Cookiebanner en voorkeurenpaneel tonen op eerste bezoek
- Consent Mode v2: default denied bij first paint; update naar granted na akkoord
- AMP-consent actief vóór `<amp-ad>`

## 10. AdSense
- `.env`: `ADSENSE_CLIENT=ca-pub-xxxxxxxx` en `ADSENSE_AUTO_ADS=true|false`
- Script injectie alleen in production en na consent `ads=true`
- AMP-ads via `<amp-ad>`; `ads.txt` toegankelijk op `https://domein.nl/ads.txt`

## 11. PWA
- `manifest.webmanifest` correct gekoppeld
- Service worker geregistreerd; offline receptdetail functioneert

## 12. Troubleshooting
- 500 na deploy: leeg `bootstrap/cache/*`, run caches opnieuw
- 404’s: check `.htaccess` in `public/` (Laravel default)
- AMP validator errors: check inline CSS limieten en geldige AMP componenten

## 13. Rollback-strategie
- Gebruik symlink-achtig pattern: `releases/{timestamp}`, `current -> releases/{timestamp}`
- Bij probleem: symlink terugzetten naar vorige release