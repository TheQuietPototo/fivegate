<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Lima Gerbang — Five Gates for coffee processing</title>
  <meta name="description" content="A plain-language guide for coffee farmers to check whether a change in processing is really cleaner, with an AI assistant powered by Groq.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  <a class="skip" href="#main" data-i18n="skip">Langsung ke isi halaman</a>

  <header class="site-header">
    <div class="wrap bar">
      <a class="brand" href="#top">
        <span class="brand-glyph" id="brandGlyph" aria-hidden="true"></span>
        <span class="brand-text">
          <strong data-i18n="brand_name">Lima Gerbang</strong>
          <small data-i18n="brand_sub">Panduan untuk petani kopi</small>
        </span>
      </a>

      <div class="tools">
        <div class="lang" role="group" aria-label="Bahasa / Language">
          <button type="button" class="lang-btn" data-lang="id" aria-pressed="true">ID</button>
          <button type="button" class="lang-btn" data-lang="en" aria-pressed="false">EN</button>
        </div>

      </div>
    </div>

    <nav class="wrap nav" aria-label="Menu">
      <a href="#gates" data-i18n="nav_gates">Lima gerbang</a>
      <a href="#check" data-i18n="nav_check">Cek ide Anda</a>
      <a href="#calc" data-i18n="nav_calc">Hitung beban</a>
      <a href="#actors" data-i18n="nav_actors">Siapa berperan</a>
      <a href="#ask" data-i18n="nav_ask">Tanya asisten</a>
    </nav>
  </header>

  <main id="main">
    <!-- Hero -->
    <section class="hero" id="top">
      <div class="wrap hero-grid">
        <div class="hero-copy">
          <h1 data-i18n="hero_title"></h1>
          <p class="lead" data-i18n="hero_text"></p>
          <div class="cta-row">
            <a class="btn btn-primary" href="#check" data-i18n="hero_cta1"></a>
            <a class="btn btn-ghost-light" href="#ask" data-i18n="hero_cta2"></a>
          </div>
          <p class="hero-note" data-i18n="hero_note"></p>
        </div>
        <ol class="hero-gates" id="heroGates" data-i18n-aria="hero_gates_label" aria-label="Lima gerbang"></ol>
      </div>
    </section>

    <!-- The five gates -->
    <section class="section" id="gates">
      <div class="wrap narrow">
        <h2 data-i18n="gates_title"></h2>
        <p class="section-intro" data-i18n="gates_intro"></p>
        <ol class="path" id="gatePath"></ol>
      </div>
    </section>

    <!-- Self-check wizard -->
    <section class="section band" id="check">
      <div class="wrap narrow">
        <h2 data-i18n="check_title"></h2>
        <p class="section-intro" data-i18n="check_intro"></p>
        <div class="panel" id="wizard"></div>
      </div>
    </section>

    <!-- Load calculator -->
    <section class="section" id="calc">
      <div class="wrap narrow">
        <h2 data-i18n="calc_title"></h2>
        <p class="section-intro" data-i18n="calc_intro"></p>

        <form class="panel" id="calcForm" novalidate>
          <div class="calc-grid">
            <fieldset class="calc-set">
              <legend data-i18n="c_before"></legend>
              <label for="flowB" data-i18n="c_flow"></label>
              <input id="flowB" type="number" inputmode="decimal" min="0" step="any">
              <label for="codB" data-i18n="c_cod"></label>
              <input id="codB" type="number" inputmode="decimal" min="0" step="any">
              <label for="errB" data-i18n="c_err"></label>
              <input id="errB" type="number" inputmode="decimal" min="0" step="any" value="10">
            </fieldset>

            <fieldset class="calc-set">
              <legend data-i18n="c_after"></legend>
              <label for="flowA" data-i18n="c_flow"></label>
              <input id="flowA" type="number" inputmode="decimal" min="0" step="any">
              <label for="codA" data-i18n="c_cod"></label>
              <input id="codA" type="number" inputmode="decimal" min="0" step="any">
              <label for="errA" data-i18n="c_err"></label>
              <input id="errA" type="number" inputmode="decimal" min="0" step="any" value="10">
            </fieldset>
          </div>

          <fieldset class="calc-set calc-energy">
            <legend data-i18n="c_energy_h"></legend>
            <div class="energy-grid">
              <div>
                <label for="kwhExtra" data-i18n="c_extra"></label>
                <input id="kwhExtra" type="number" inputmode="decimal" min="0" step="any" value="0">
              </div>
              <div>
                <label for="kwhSaved" data-i18n="c_saved"></label>
                <input id="kwhSaved" type="number" inputmode="decimal" min="0" step="any" value="0">
              </div>
              <div>
                <label for="ef" data-i18n="c_factor"></label>
                <input id="ef" type="number" inputmode="decimal" min="0" step="any" value="0.85">
              </div>
            </div>
            <p class="hint" data-i18n="c_factor_hint"></p>
          </fieldset>

          <div class="btn-row">
            <button type="submit" class="btn btn-primary" data-i18n="btn_calc"></button>
            <button type="button" class="btn btn-ghost" id="presetA" data-i18n="preset_a"></button>
            <button type="button" class="btn btn-ghost" id="presetB" data-i18n="preset_b"></button>
          </div>
          <p class="hint" data-i18n="preset_note"></p>

          <div class="calc-out" id="calcOut" aria-live="polite"></div>
        </form>
      </div>
    </section>

    <!-- Who does what -->
    <section class="section band" id="actors">
      <div class="wrap narrow">
        <h2 data-i18n="actors_title"></h2>
        <p class="section-intro" data-i18n="actors_intro"></p>
        <ul class="actors" id="actorList"></ul>
      </div>
    </section>

    <!-- AI assistant -->
    <section class="section" id="ask">
      <div class="wrap narrow">
        <h2 data-i18n="ask_title"></h2>
        <p class="section-intro" data-i18n="ask_intro"></p>

        <div class="panel chat">
          <div class="chat-log" id="chatLog" role="log" aria-live="polite" aria-relevant="additions text"></div>
          <div class="suggest" id="suggest"></div>

          <form class="chat-form" id="chatForm">
            <label class="sr-only" for="chatInput" data-i18n="ask_label"></label>
            <textarea id="chatInput" rows="2" maxlength="1500" data-i18n-placeholder="ask_ph" placeholder="Tulis pertanyaan Anda…"></textarea>
            <button type="submit" class="btn btn-primary" id="sendBtn" data-i18n="btn_send"></button>
          </form>

          <div class="chat-tools">
            <button type="button" class="link-btn" id="clearChat" data-i18n="btn_clear"></button>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="wrap narrow">
      <p data-i18n="footer_text"></p>
      <p><a href="{{ route('admin.login') }}" style="color: rgba(255,255,255,0.4); font-size: 0.8rem; text-decoration: underline;">Panel Admin</a></p>
    </div>
  </footer>



  <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
