<!doctype html>
<html amp lang="nl">
<head>
  <meta charset="utf-8">
  <script async src="https://cdn.ampproject.org/v0.js"></script>
  <script async custom-element="amp-consent" src="https://cdn.ampproject.org/v0/amp-consent-0.1.js"></script>
  <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
  <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
  <link rel="canonical" href="{{ $canonical ?? url('/recepten/' . ($recipe['slug'] ?? '')) }}">
  <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
  <title>{{ $recipe['title'] ?? 'Recept' }} – WatEtenWeVandaag</title>
  <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
  <style amp-custom>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:#111;margin:0}
    header,main,footer{max-width:760px;margin:0 auto;padding:16px}
    header h1{font-size:28px;margin:12px 0}
    .meta{color:#666;font-size:14px}
    .hero{aspect-ratio:16/9;background:#f2f2f2;border-radius:8px;overflow:hidden}
    .section{margin:18px 0}
    .ingredients ul{padding-left:18px}
    .steps ol{padding-left:18px}
    .ad-container{margin:20px 0;min-height:100px}
    .consent-prompt{background:#0b0f19;color:#fff;padding:14px;border-radius:8px}
    .consent-actions{display:flex;gap:8px;margin-top:10px}
    .btn{background:#2fb344;color:#fff;border:0;border-radius:6px;padding:8px 12px}
    .btn--ghost{background:transparent;outline:1px solid #fff3;color:#fff}
  </style>
  <script type="application/ld+json">
  {!! json_encode($jsonld ?? []) !!}
  </script>
</head>
<body>
  <amp-consent id="wev-consent">
    <script type="application/json">
    {
      "consentInstanceId": "wev",
      "consentRequired": true,
      "promptUI": "consent-ui",
      "postPromptUI": "post-prompt"
    }
    </script>
    <div id="consent-ui" class="consent-prompt" hidden>
      <strong>Cookies op WatEtenWeVandaag</strong>
      <p>We gebruiken cookies voor essentiële functies en, met jouw toestemming, voor analyses en advertenties.</p>
      <div class="consent-actions">
        <button class="btn" on="tap:wev-consent.accept">Alles accepteren</button>
        <button class="btn btn--ghost" on="tap:wev-consent.reject">Alles weigeren</button>
      </div>
    </div>
    <div id="post-prompt" hidden></div>
  </amp-consent>

  <header>
    <nav><a href="{{ url('/') }}">WatEtenWeVandaag</a></nav>
    <h1>{{ $recipe['title'] ?? 'Recept' }}</h1>
    <div class="meta">{{ $recipe['meta'] ?? '' }}</div>
  </header>

  <main>
    @if(!empty($recipe['image']))
    <div class="hero">
      <amp-img src="{{ $recipe['image'] }}" width="800" height="450" layout="responsive" alt="{{ $recipe['title'] ?? 'Recept' }}"></amp-img>
    </div>
    @endif

    <section class="section ingredients">
      <h2>Ingrediënten</h2>
      <ul>
        @foreach(($recipe['ingredients'] ?? []) as $ing)
          <li>{{ $ing }}</li>
        @endforeach
      </ul>
    </section>

    <section class="section steps">
      <h2>Bereiding</h2>
      <ol>
        @foreach(($recipe['steps'] ?? []) as $step)
          <li>{{ $step }}</li>
        @endforeach
      </ol>
    </section>

    @if(env('ADSENSE_CLIENT'))
    <div class="ad-container">
      <amp-ad width="300" height="250"
              type="adsense"
              data-ad-client="{{ env('ADSENSE_CLIENT') }}"
              data-ad-slot="{{ $ad_slot ?? '1234567890' }}"
              data-auto-format="rspv"
              data-full-width>
        <div overflow></div>
      </amp-ad>
    </div>
    @endif
  </main>

  <footer>
    <small>© WatEtenWeVandaag</small>
  </footer>

  @if(env('GTM_ID'))
  <amp-analytics type="gtag" data-credentials="include" data-block-on-consent>
    <script type="application/json">
    {
      "vars" : {
        "gtag_id": "{{ env('GTM_ID') }}",
        "config" : {
          "{{ env('GTM_ID') }}": { "groups": "default" }
        }
      }
    }
    </script>
  </amp-analytics>
  @endif
</body>
</html>