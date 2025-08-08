<div id="wev-cookie-modal" class="wev-modal" hidden role="dialog" aria-modal="true" aria-labelledby="wev-modal-title">
  <div class="wev-modal__dialog">
    <div class="wev-modal__header">
      <h3 id="wev-modal-title">Cookievoorkeuren</h3>
      <button class="wev-modal__close" data-consent-close-prefs aria-label="Sluiten">×</button>
    </div>
    <div class="wev-modal__body">
      <div class="wev-toggle">
        <div>
          <strong>Strikt noodzakelijke cookies</strong>
          <p>Altijd actief. Nodig voor basisfunctionaliteit en beveiliging.</p>
        </div>
        <div>
          <label class="wev-switch">
            <input type="checkbox" checked disabled>
            <span class="wev-slider"></span>
          </label>
        </div>
      </div>
      <div class="wev-toggle">
        <div>
          <strong>Analytics</strong>
          <p>Helpt ons de site te verbeteren (bijv. Google Analytics).</p>
        </div>
        <div>
          <label class="wev-switch">
            <input id="wev-toggle-analytics" type="checkbox">
            <span class="wev-slider"></span>
          </label>
        </div>
      </div>
      <div class="wev-toggle">
        <div>
          <strong>Advertenties</strong>
          <p>Toont niet-gepersonaliseerde of gepersonaliseerde advertenties (bijv. AdSense) afhankelijk van je keuze.</p>
        </div>
        <div>
          <label class="wev-switch">
            <input id="wev-toggle-ads" type="checkbox">
            <span class="wev-slider"></span>
          </label>
        </div>
      </div>
      <div class="wev-toggle">
        <div>
          <strong>Marketing</strong>
          <p>Voor remarketing en personalisatie (bijv. pixels).</p>
        </div>
        <div>
          <label class="wev-switch">
            <input id="wev-toggle-marketing" type="checkbox">
            <span class="wev-slider"></span>
          </label>
        </div>
      </div>
    </div>
    <div class="wev-modal__footer">
      <button class="wev-btn wev-btn--primary" data-consent-save-prefs>Opslaan</button>
    </div>
  </div>
</div>

<style>
.wev-modal { position: fixed; inset: 0; background: #0008; display: grid; place-items: center; z-index: 10000; }
.wev-modal__dialog { background: #fff; color: #111; width: min(720px, 92vw); border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,.25); overflow: hidden; }
.wev-modal__header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #eee; }
.wev-modal__body { padding: 16px 20px; display: grid; gap: 14px; max-height: min(65vh, 520px); overflow: auto; }
.wev-modal__footer { padding: 16px 20px; border-top: 1px solid #eee; display: flex; justify-content: flex-end; }
.wev-modal__close { background: transparent; border: 0; font-size: 22px; cursor: pointer; }
.wev-toggle { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 0; }
.wev-toggle p { margin: 4px 0 0 0; color: #444; font-size: 14px; }
.wev-switch { position: relative; display: inline-block; width: 44px; height: 26px; }
.wev-switch input { opacity: 0; width: 0; height: 0; }
.wev-slider { position: absolute; cursor: pointer; inset: 0; background: #ccc; border-radius: 26px; transition: .2s; }
.wev-slider:before { content: ""; position: absolute; height: 20px; width: 20px; left: 3px; top: 3px; background: white; border-radius: 50%; transition: .2s; }
.wev-switch input:checked + .wev-slider { background: #2fb344; }
.wev-switch input:checked + .wev-slider:before { transform: translateX(18px); }
.wev-btn { border: 0; border-radius: 6px; padding: 10px 14px; cursor: pointer; font-weight: 600; }
.wev-btn--primary { background: #2fb344; color: #fff; }
</style>