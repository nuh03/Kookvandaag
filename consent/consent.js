(function () {
  'use strict';

  var CONSENT_COOKIE_NAME = 'wev_consent';
  var CONSENT_COOKIE_VERSION = 1;
  var CONSENT_TTL_DAYS = 365;

  var DEFAULT_CONSENT = {
    version: CONSENT_COOKIE_VERSION,
    timestamp: null,
    categories: {
      strict_necessary: true,
      analytics: false,
      ads: false,
      marketing: false
    }
  };

  function daysToExpires(days) {
    var date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    return date.toUTCString();
  }

  function setCookie(name, value, days) {
    var expires = 'expires=' + daysToExpires(days);
    document.cookie = name + '=' + encodeURIComponent(value) + ';' + expires + ';path=/;SameSite=Lax';
  }

  function getCookie(name) {
    var nameEQ = name + '=';
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i].trim();
      if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length));
    }
    return null;
  }

  function readConsent() {
    try {
      var stored = getCookie(CONSENT_COOKIE_NAME);
      if (!stored) return null;
      var parsed = JSON.parse(stored);
      if (!parsed || parsed.version !== CONSENT_COOKIE_VERSION) return null;
      return parsed;
    } catch (e) {
      return null;
    }
  }

  function writeConsent(consent) {
    consent.timestamp = new Date().toISOString();
    setCookie(CONSENT_COOKIE_NAME, JSON.stringify(consent), CONSENT_TTL_DAYS);
  }

  function deepClone(obj) {
    return JSON.parse(JSON.stringify(obj));
  }

  function ensureConsent() {
    var current = readConsent();
    if (current) return current;
    var fresh = deepClone(DEFAULT_CONSENT);
    writeConsent(fresh);
    return fresh;
  }

  function dispatchConsentChanged(consent) {
    try {
      var event = new CustomEvent('consent:changed', { detail: consent });
      window.dispatchEvent(event);
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({ event: 'consent_update', consent: consent });
    } catch (e) {}
  }

  function gtag() {
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push(arguments);
  }

  function applyConsentMode(consent) {
    // Default denied for non-essential at first paint; essentials granted
    var analyticsGranted = !!consent.categories.analytics;
    var adsGranted = !!consent.categories.ads;
    var marketingGranted = !!consent.categories.marketing; // may map to ad_user_data/person.

    var update = {
      ad_storage: adsGranted ? 'granted' : 'denied',
      analytics_storage: analyticsGranted ? 'granted' : 'denied',
      ad_user_data: marketingGranted ? 'granted' : 'denied',
      ad_personalization: marketingGranted ? 'granted' : 'denied',
      functionality_storage: 'granted',
      security_storage: 'granted'
    };

    if (typeof window.gtag === 'function') {
      window.gtag('consent', 'update', update);
    } else {
      gtag('consent', 'update', update);
    }
  }

  function setDefaultConsentModeDenied() {
    var update = {
      ad_storage: 'denied',
      analytics_storage: 'denied',
      ad_user_data: 'denied',
      ad_personalization: 'denied',
      functionality_storage: 'granted',
      security_storage: 'granted'
    };
    if (typeof window.gtag === 'function') {
      window.gtag('consent', 'default', update);
    } else {
      gtag('consent', 'default', update);
    }
  }

  function loadScript(src, attrs) {
    return new Promise(function (resolve, reject) {
      var s = document.createElement('script');
      s.src = src;
      s.async = true;
      if (attrs) {
        Object.keys(attrs).forEach(function (k) {
          s.setAttribute(k, attrs[k]);
        });
      }
      s.onload = resolve;
      s.onerror = reject;
      document.head.appendChild(s);
    });
  }

  function enableDeferredScripts(consent) {
    var nodes = document.querySelectorAll('script[type="text/plain"][data-consent]');
    nodes.forEach(function (node) {
      var category = node.getAttribute('data-consent');
      if (!category) return;
      var allowed = !!consent.categories[category];
      if (!allowed) return;
      var src = node.getAttribute('data-src');
      var inline = node.textContent && node.textContent.trim().length > 0;
      if (src) {
        loadScript(src).catch(function () {});
      } else if (inline) {
        try {
          var exec = document.createElement('script');
          exec.text = node.textContent;
          document.head.appendChild(exec);
        } catch (e) {}
      }
      node.parentNode && node.parentNode.removeChild(node);
    });
  }

  function showBanner() {
    var el = document.getElementById('wev-cookie-banner');
    if (el) el.hidden = false;
  }

  function hideBanner() {
    var el = document.getElementById('wev-cookie-banner');
    if (el) el.hidden = true;
  }

  function openPreferences() {
    var el = document.getElementById('wev-cookie-modal');
    if (el) el.hidden = false;
  }

  function closePreferences() {
    var el = document.getElementById('wev-cookie-modal');
    if (el) el.hidden = true;
  }

  function bindUI(consent) {
    var acceptAllBtn = document.querySelector('[data-consent-accept-all]');
    var rejectAllBtn = document.querySelector('[data-consent-reject-all]');
    var prefsBtn = document.querySelector('[data-consent-open-prefs]');
    var savePrefsBtn = document.querySelector('[data-consent-save-prefs]');
    var closePrefsBtn = document.querySelector('[data-consent-close-prefs]');

    if (acceptAllBtn) acceptAllBtn.addEventListener('click', function () {
      consent.categories.analytics = true;
      consent.categories.ads = true;
      consent.categories.marketing = true;
      writeConsent(consent);
      hideBanner();
      applyConsentMode(consent);
      enableDeferredScripts(consent);
      dispatchConsentChanged(consent);
    });

    if (rejectAllBtn) rejectAllBtn.addEventListener('click', function () {
      consent.categories.analytics = false;
      consent.categories.ads = false;
      consent.categories.marketing = false;
      writeConsent(consent);
      hideBanner();
      applyConsentMode(consent);
      dispatchConsentChanged(consent);
    });

    if (prefsBtn) prefsBtn.addEventListener('click', function () {
      openPreferences();
    });

    if (closePrefsBtn) closePrefsBtn.addEventListener('click', function () {
      closePreferences();
    });

    if (savePrefsBtn) savePrefsBtn.addEventListener('click', function () {
      var analyticsToggle = document.querySelector('#wev-toggle-analytics');
      var adsToggle = document.querySelector('#wev-toggle-ads');
      var marketingToggle = document.querySelector('#wev-toggle-marketing');
      consent.categories.analytics = !!(analyticsToggle && analyticsToggle.checked);
      consent.categories.ads = !!(adsToggle && adsToggle.checked);
      consent.categories.marketing = !!(marketingToggle && marketingToggle.checked);
      writeConsent(consent);
      hideBanner();
      closePreferences();
      applyConsentMode(consent);
      enableDeferredScripts(consent);
      dispatchConsentChanged(consent);
    });

    // Footer link: open preferences
    var footerLink = document.querySelector('[data-consent-footer-link]');
    if (footerLink) footerLink.addEventListener('click', function (e) {
      e.preventDefault();
      openPreferences();
    });
  }

  function init() {
    setDefaultConsentModeDenied();

    var consent = ensureConsent();

    var isFirstVisit = !readConsent();
    if (isFirstVisit) {
      showBanner();
    } else {
      // Apply mode based on stored consent and enable any deferred scripts
      applyConsentMode(consent);
      enableDeferredScripts(consent);
    }

    bindUI(consent);

    // Expose minimal API
    window.ConsentManager = {
      get: function () { return readConsent() || deepClone(DEFAULT_CONSENT); },
      set: function (updates) {
        var current = ensureConsent();
        current.categories.analytics = !!updates.categories.analytics;
        current.categories.ads = !!updates.categories.ads;
        current.categories.marketing = !!updates.categories.marketing;
        writeConsent(current);
        applyConsentMode(current);
        enableDeferredScripts(current);
        dispatchConsentChanged(current);
      },
      acceptAll: function () {
        var c = ensureConsent();
        c.categories.analytics = true;
        c.categories.ads = true;
        c.categories.marketing = true;
        writeConsent(c);
        applyConsentMode(c);
        enableDeferredScripts(c);
        dispatchConsentChanged(c);
      },
      rejectAll: function () {
        var c = ensureConsent();
        c.categories.analytics = false;
        c.categories.ads = false;
        c.categories.marketing = false;
        writeConsent(c);
        applyConsentMode(c);
        dispatchConsentChanged(c);
      },
      openPreferences: openPreferences
    };
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();