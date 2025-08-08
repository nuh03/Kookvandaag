# Consent Mode v2 (GA4/GTM)

- Bij eerste paint zetten we via `consent.js` de default op denied voor analytics/ads/marketing.
- Na akkoord updaten we naar granted en activeren we uitgestelde scripts (type="text/plain" + data-consent).
- Mapping:
  - analytics -> `analytics_storage`
  - ads -> `ad_storage`
  - marketing -> `ad_user_data` en `ad_personalization`
  - essentials -> `functionality_storage` en `security_storage` altijd granted
- DataLayer event: `consent_update` bij wijziging

## Niet-AMP
- Voeg `consent.js` in `<head>` toe
- Zet GA/AdSense scripts als `type="text/plain" data-consent="analytics|ads"`

## AMP
- Gebruik `<amp-consent>`; gate `<amp-analytics>` via `data-block-on-consent`