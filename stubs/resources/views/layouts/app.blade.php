<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'WatEtenWeVandaag')</title>
  <meta name="description" content="@yield('meta_description', 'Recepten, weekplanners en meer — Wat eten we vandaag?')">
  <link rel="canonical" href="@yield('canonical', url()->current())">
  @hasSection('amphtml')
    <link rel="amphtml" href="@yield('amphtml')">
  @endif
  @yield('meta')
  @yield('jsonld')

  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
  <meta name="theme-color" content="#2fb344">
  <link rel="icon" href="{{ asset('favicon.ico') }}">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <style>body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:0;color:#111}</style>

  <script defer src="{{ asset('js/consent.js') }}"></script>

  {{-- Analytics (GA4/GTM) via consent gating: script wordt pas geladen door consent.js --}}
  @if (env('GTM_ID'))
    <script type="text/plain" data-consent="analytics" data-src="https://www.googletagmanager.com/gtag/js?id={{ env('GTM_ID') }}"></script>
    <script type="text/plain" data-consent="analytics">
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);} 
      gtag('js', new Date());
      gtag('config', '{{ env('GTM_ID') }}', { anonymize_ip: true });
    </script>
  @endif

  {{-- AdSense (alleen in production). Script en slots worden pas geactiveerd na consent.js --}}
  @if (app()->environment('production') && env('ADSENSE_CLIENT'))
    <script type="text/plain" data-consent="ads" data-src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ env('ADSENSE_CLIENT') }}" crossorigin="anonymous"></script>
    @if (env('ADSENSE_AUTO_ADS', false))
      <script type="text/plain" data-consent="ads">(adsbygoogle=window.adsbygoogle||[]).push({});</script>
    @endif
  @endif

  @stack('head')
</head>
<body>
  @include('partials.cookie-banner')
  @include('partials.cookie-preferences')

  <header class="container" style="padding:12px 16px;border-bottom:1px solid #eee">
    <a href="/" style="text-decoration:none;color:inherit;font-weight:700">WatEtenWeVandaag</a>
  </header>

  <main class="container" style="max-width:980px;margin:0 auto;padding:16px">
    @yield('content')
  </main>

  <footer class="container" style="padding:16px;border-top:1px solid #eee">
    <a href="#" data-consent-footer-link>Cookievoorkeuren aanpassen</a>
  </footer>

  @stack('scripts')
</body>
</html>