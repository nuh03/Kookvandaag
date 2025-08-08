# AMP voor recepten

- Route: `/amp/recepten/{slug}` dient een geldige AMP HTML te leveren.
- Canonical: op de normale receptpagina `<link rel="amphtml" href="/amp/recepten/{slug}">` opnemen.
- Consent: gebruik `<amp-consent>` met accept/weiger UI. Gate analytics en ads met `data-block-on-consent`.
- Ads: gebruik `<amp-ad type="adsense" data-ad-client=... data-ad-slot=...>`; laad pas na consent.

## Valideren
- Gebruik de AMP test: `https://search.google.com/test/amp`
- Of voeg `#development=1` toe in de URL en check DevTools console voor validator-fouten.

## JSON-LD Recipe
- Voeg `application/ld+json` toe met Recipe schema (naam, beschrijving, image, tijd, yield, ingrediënten, stappen)
- Test met Rich Results Test

## Voorbeeld
Zie `amp/recipe-amp.html` als referentie. Pas `data-ad-client`, `data-ad-slot`, `gtag_id` en canonical aan.