# AdSense integratie

## Web (niet-AMP)
- `.env`: `ADSENSE_CLIENT=ca-pub-xxxxxxxx`, `ADSENSE_AUTO_ADS=true|false`
- Script alleen injecteren in production én wanneer consent `ads=true` is
- Handmatige slots via `<ins class="adsbygoogle" data-ad-client data-ad-slot>`

Consent gating voorbeelden (niet-AMP):
- Laad AdSense script met `type="text/plain" data-consent="ads" data-src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-..."` en laat `consent.js` dit activeren bij akkoord

## AMP
- Gebruik `<amp-ad type="adsense" data-ad-client="ca-pub-..." data-ad-slot="...">`
- Gate met `<amp-consent>` en `data-block-on-consent` voor analytics

## ads.txt
- Publiceer `public/ads.txt` met je publisher ID