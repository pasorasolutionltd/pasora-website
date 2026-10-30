<?php
require __DIR__ . '/content.php';

/** Render one service card from a services_list() item. */
function render_service_card(array $s, int $i): void
{
    $d = $i % 3;
    $cls = $d === 1 ? ' delay-1' : ($d === 2 ? ' delay-2' : '');
    echo '<article class="service-card reveal' . $cls . '">';
    echo '<div class="service-icon">' . svc_icon((string) ($s['icon'] ?? 'star')) . '</div>';
    echo '<h3>' . htmlspecialchars((string) $s['title'], ENT_QUOTES, 'UTF-8') . '</h3>';
    echo '<p>' . htmlspecialchars((string) $s['desc'], ENT_QUOTES, 'UTF-8') . '</p>';
    if (!empty($s['bullets'])) {
        echo '<ul>';
        foreach ($s['bullets'] as $b) {
            echo '<li>' . htmlspecialchars((string) $b, ENT_QUOTES, 'UTF-8') . '</li>';
        }
        echo '</ul>';
    }
    echo '</article>';
}

$BASE = 'https://pasorasolutionltd.com';

$ld = [
    '@context' => 'https://schema.org',
    '@type'    => 'ProfessionalService',
    '@id'      => $BASE . '/#organization',
    'name'     => c('org_name'),
    'slogan'   => c('slogan'),
    'description' => c('site_description'),
    'url'      => $BASE . '/',
    'logo'     => $BASE . '/assets/og-image.png',
    'image'    => $BASE . '/assets/og-image.png',
    'email'    => c('email'),
    'telephone'=> c('phone_href'),
    'address'  => ['@type' => 'PostalAddress', 'addressLocality' => 'Accra', 'addressCountry' => 'GH'],
    'openingHoursSpecification' => [
        '@type' => 'OpeningHoursSpecification',
        'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        'opens' => '08:00', 'closes' => '18:00',
    ],
    'hasOfferCatalog' => [
        '@type' => 'OfferCatalog', 'name' => 'Services',
        'itemListElement' => array_map(static fn($t) => [
            '@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $t],
        ], array_merge(
            array_column(services_list('it'), 'title'),
            array_column(services_list('travel'), 'title')
        )),
    ],
    'sameAs' => array_values(array_filter([
        c('social_facebook'), c('social_instagram'), c('social_x'),
        c('social_tiktok'), c('social_youtube'), c('social_whatsapp'),
    ], static fn($u) => $u !== '')),
];
if (empty($ld['sameAs'])) {
    unset($ld['sameAs']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= ch('site_title') ?></title>
  <meta name="description" content="<?= ch('site_description') ?>">
  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#0d1533">
  <link rel="canonical" href="<?= $BASE ?>/">

  <!-- Open Graph (Facebook, LinkedIn, WhatsApp) -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?= ch('org_name') ?>">
  <meta property="og:title" content="<?= ch('org_name') ?>: <?= ch('slogan') ?>">
  <meta property="og:description" content="<?= ch('site_description') ?>">
  <meta property="og:url" content="<?= $BASE ?>/">
  <meta property="og:image" content="<?= $BASE ?>/assets/og-image.png">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="1200">
  <meta property="og:locale" content="en_GB">

  <!-- Twitter / X card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= ch('org_name') ?>: <?= ch('slogan') ?>">
  <meta name="twitter:description" content="<?= ch('site_description') ?>">
  <meta name="twitter:image" content="<?= $BASE ?>/assets/og-image.png">
  <!-- Security: CSP fallback for hosts that don't send headers (frame-ancestors/X-Frame-Options must still come from .htaccess) -->
  <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; img-src 'self' data:; base-uri 'self'; form-action 'self' mailto:; object-src 'none'">
  <meta name="referrer" content="strict-origin-when-cross-origin">
  <noscript><style>.reveal{opacity:1;transform:none}</style></noscript>
  <link rel="icon" type="image/svg+xml" href="<?= ch('logo') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .footer-social{display:flex;gap:12px;margin-top:16px;flex-wrap:wrap}
    .footer-social a{display:inline-flex;width:36px;height:36px;align-items:center;justify-content:center;border-radius:9px;background:rgba(255,255,255,.08);color:#fff;transition:background .2s ease,transform .2s ease}
    .footer-social a:hover{background:rgba(255,255,255,.18);transform:translateY(-2px)}
    .footer-social svg{width:18px;height:18px;display:block}
    .services-subhead{font-family:var(--font-head);font-weight:800;font-size:clamp(22px,2.6vw,30px);color:var(--ink);text-align:center;letter-spacing:-.01em;margin:0 0 30px}
    .services-subhead::after{content:"";display:block;width:56px;height:3px;margin:14px auto 0;border-radius:3px;background:var(--grad)}
    .services-grid + .services-subhead{margin-top:52px}
  </style>

  <!-- Structured data for rich search results -->
  <script type="application/ld+json">
  <?= json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>

  </script>
</head>
<body>

  <div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

  <!-- ============ HEADER ============ -->
  <header class="site-header" id="top">
    <div class="container header-inner">
      <a href="#top" class="brand">
        <img src="<?= ch('logo') ?>" alt="<?= ch('org_name') ?> logo" class="brand-logo">
        <span class="brand-text">
          <span class="brand-name"><?= ch('brand_name') ?></span>
          <span class="brand-sub"><?= ch('brand_sub') ?></span>
        </span>
      </a>

      <nav class="site-nav" id="siteNav">
        <a href="#home">Home</a>
        <a href="#services">Services</a>
        <a href="#about">About</a>
        <a href="#why-us">Why Us</a>
        <a href="#contact" class="nav-cta">Get a Quote</a>
      </nav>

      <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>

  <!-- ============ HERO ============ -->
  <section class="hero" id="home">
    <div class="hero-pixels" aria-hidden="true"></div>
    <div class="aurora" aria-hidden="true">
      <span class="aurora-blob aurora-1"></span>
      <span class="aurora-blob aurora-2"></span>
      <span class="aurora-blob aurora-3"></span>
    </div>
    <div class="container hero-inner">
      <div class="hero-content reveal">
        <p class="hero-kicker"><?= ch('hero_kicker') ?></p>
        <h1><?= ch('hero_title_1') ?><br><span class="grad-text"><?= ch('hero_title_2') ?></span></h1>
        <p class="hero-lead">
          <?= ch('hero_lead') ?>
        </p>
        <div class="hero-actions">
          <a href="#services" class="btn btn-primary"><?= ch('hero_btn1') ?></a>
          <a href="#contact" class="btn btn-ghost"><?= ch('hero_btn2') ?></a>
        </div>
        <ul class="hero-badges">
          <li><span class="dot"></span><?= ch('hero_badge1') ?></li>
          <li><span class="dot"></span><?= ch('hero_badge2') ?></li>
          <li><span class="dot"></span><?= ch('hero_badge3') ?></li>
        </ul>
      </div>
      <div class="hero-art reveal delay-1">
        <img src="<?= ch('logo') ?>" alt="" class="hero-logo">
      </div>
    </div>
  </section>

  <!-- ============ TECH MARQUEE ============ -->
  <div class="marquee" aria-label="Technologies we work with">
    <div class="marquee-track">
      <?php for ($i = 0; $i < 2; $i++): foreach (clist('marquee') as $tech): ?>
      <span><?= htmlspecialchars($tech, ENT_QUOTES, 'UTF-8') ?></span><i>&#9670;</i>
      <?php endforeach; endfor; ?>
    </div>
  </div>

  <!-- ============ SERVICES ============ -->
  <section class="section services" id="services">
    <div class="container">
      <div class="section-head reveal">
        <p class="section-kicker"><?= ch('services_kicker') ?></p>
        <h2><?= ch('services_head_1') ?> <span class="grad-text"><?= ch('services_head_2') ?></span></h2>
        <p class="section-lead"><?= ch('services_lead') ?></p>
      </div>

      <h3 class="services-subhead reveal"><?= ch('services_it_head') ?></h3>
      <div class="services-grid">
        <?php foreach (services_list('it') as $i => $s) { render_service_card($s, $i); } ?>
      </div>

      <h3 class="services-subhead reveal"><?= ch('services_travel_head') ?></h3>
      <div class="services-grid">
        <?php foreach (services_list('travel') as $i => $s) { render_service_card($s, $i); } ?>

        <article class="service-card service-card-cta reveal delay-3">
          <h3><?= ch('services_cta_title') ?></h3>
          <p><?= ch('services_cta_text') ?></p>
          <a href="#contact" class="btn btn-primary"><?= ch('services_cta_btn') ?></a>
        </article>
      </div>
    </div>
  </section>

  <!-- ============ ABOUT ============ -->
  <section class="section about" id="about">
    <div class="container about-inner">
      <div class="about-art reveal">
        <div class="about-card">
          <img src="<?= ch('logo') ?>" alt="<?= ch('org_name') ?> logo">
        </div>
      </div>
      <div class="about-content reveal delay-1">
        <p class="section-kicker"><?= ch('about_kicker') ?></p>
        <h2><?= ch('about_head_1') ?> <span class="grad-text"><?= ch('about_head_2') ?></span></h2>
        <p><?= ch('about_p1') ?></p>
        <p><?= ch('about_p2') ?></p>
        <div class="about-points">
          <div class="point"><strong>Mission</strong><span><?= ch('about_mission') ?></span></div>
          <div class="point"><strong>Vision</strong><span><?= ch('about_vision') ?></span></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ STATS ============ -->
  <section class="stats">
    <div class="container stats-grid">
      <div class="stat reveal"><span class="stat-num" data-count="<?= ch('stat1_num') ?>">0</span><span class="stat-plus"><?= ch('stat1_suf') ?></span><p><?= ch('stat1_lbl') ?></p></div>
      <div class="stat reveal delay-1"><span class="stat-num" data-count="<?= ch('stat2_num') ?>">0</span><span class="stat-plus"><?= ch('stat2_suf') ?></span><p><?= ch('stat2_lbl') ?></p></div>
      <div class="stat reveal delay-2"><span class="stat-num" data-count="<?= ch('stat3_num') ?>">0</span><span class="stat-plus"><?= ch('stat3_suf') ?></span><p><?= ch('stat3_lbl') ?></p></div>
      <div class="stat reveal delay-3"><span class="stat-num" data-count="<?= ch('stat4_num') ?>">0</span><span class="stat-plus"><?= ch('stat4_suf') ?></span><p><?= ch('stat4_lbl') ?></p></div>
    </div>
  </section>

  <!-- ============ WHY US ============ -->
  <section class="section why-us" id="why-us">
    <div class="container">
      <div class="section-head reveal">
        <p class="section-kicker"><?= ch('why_kicker') ?></p>
        <h2><?= ch('why_head_1') ?> <span class="grad-text"><?= ch('why_head_2') ?></span></h2>
      </div>
      <div class="why-grid">
        <div class="why-item reveal">
          <span class="why-num">01</span>
          <h3><?= ch('why1_title') ?></h3>
          <p><?= ch('why1_desc') ?></p>
        </div>
        <div class="why-item reveal delay-1">
          <span class="why-num">02</span>
          <h3><?= ch('why2_title') ?></h3>
          <p><?= ch('why2_desc') ?></p>
        </div>
        <div class="why-item reveal delay-2">
          <span class="why-num">03</span>
          <h3><?= ch('why3_title') ?></h3>
          <p><?= ch('why3_desc') ?></p>
        </div>
        <div class="why-item reveal delay-3">
          <span class="why-num">04</span>
          <h3><?= ch('why4_title') ?></h3>
          <p><?= ch('why4_desc') ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ CTA BAND ============ -->
  <section class="cta-band">
    <div class="container cta-inner reveal">
      <h2><?= ch('cta_heading') ?></h2>
      <p><?= ch('cta_text') ?></p>
      <a href="#contact" class="btn btn-light"><?= ch('cta_btn') ?></a>
    </div>
  </section>

  <!-- ============ CONTACT ============ -->
  <section class="section contact" id="contact">
    <div class="container contact-inner">
      <div class="contact-info reveal">
        <p class="section-kicker"><?= ch('contact_kicker') ?></p>
        <h2><?= ch('contact_head_1') ?> <span class="grad-text"><?= ch('contact_head_2') ?></span></h2>
        <p class="section-lead"><?= ch('contact_lead') ?></p>
        <ul class="contact-list">
          <li>
            <span class="contact-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
            <div><strong>Phone</strong><a href="tel:<?= ch('phone_href') ?>"><?= ch('phone_display') ?></a></div>
          </li>
          <li>
            <span class="contact-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg></span>
            <div><strong>Email</strong><a href="mailto:<?= ch('email') ?>"><?= ch('email') ?></a></div>
          </li>
          <li>
            <span class="contact-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
            <div><strong>Office</strong><span><?= ch('office') ?></span></div>
          </li>
          <li>
            <span class="contact-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
            <div><strong>Hours</strong><span><?= ch('hours') ?><br><?= ch('hours_emergency') ?></span></div>
          </li>
        </ul>
      </div>

      <form class="contact-form reveal delay-1" id="contactForm" data-contact-email="<?= ch('email') ?>" novalidate>
        <!-- honeypot: hidden from humans, bots that fill it are ignored -->
        <input type="text" name="company_website" id="cf-hp" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
        <div class="form-row">
          <div class="form-field">
            <label for="cf-name">Full Name</label>
            <input type="text" id="cf-name" name="name" placeholder="Your name" maxlength="100" autocomplete="name" required>
          </div>
          <div class="form-field">
            <label for="cf-email">Email Address</label>
            <input type="email" id="cf-email" name="email" placeholder="you@company.com" maxlength="120" autocomplete="email" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-field">
            <label for="cf-phone">Phone (optional)</label>
            <input type="tel" id="cf-phone" name="phone" placeholder="+233 ..." maxlength="30" autocomplete="tel">
          </div>
          <div class="form-field">
            <label for="cf-service">Service Needed</label>
            <select id="cf-service" name="service">
              <option><?= ch('service1_title') ?></option>
              <option><?= ch('service2_title') ?></option>
              <option><?= ch('service3_title') ?></option>
              <option><?= ch('service4_title') ?></option>
              <option><?= ch('service5_title') ?></option>
              <option>Other / Not sure yet</option>
            </select>
          </div>
        </div>
        <div class="form-field">
          <label for="cf-message">Project Details</label>
          <textarea id="cf-message" name="message" rows="5" placeholder="Tell us a little about what you need..." maxlength="2000" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Send Message</button>
        <p class="form-note" id="formNote" role="status"></p>
      </form>
    </div>
  </section>

  <!-- ============ FOOTER ============ -->
  <footer class="site-footer">
    <div class="container footer-grid">
      <div class="footer-brand">
        <a href="#top" class="brand">
          <img src="<?= ch('logo') ?>" alt="<?= ch('org_name') ?> logo" class="brand-logo">
          <span class="brand-text">
            <span class="brand-name light"><?= ch('brand_name') ?></span>
            <span class="brand-sub"><?= ch('brand_sub') ?></span>
          </span>
        </a>
        <p class="footer-tag"><?= ch('footer_tagline') ?></p>
        <?php
        $socials = [
            'social_facebook'  => ['Facebook',  '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07c0 6 4.39 10.97 10.13 11.87v-8.4H7.08v-3.47h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.5c-1.49 0-1.95.93-1.95 1.88v2.26h3.33l-.53 3.47h-2.8v8.4C19.61 23.04 24 18.07 24 12.07z"/></svg>'],
            'social_instagram' => ['Instagram', '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 3.25.15 4.77 1.69 4.92 4.92.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.15 3.23-1.66 4.77-4.92 4.92-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-3.26-.15-4.77-1.7-4.92-4.92C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85C2.38 3.92 3.9 2.38 7.15 2.23 8.42 2.17 8.8 2.16 12 2.16zM12 0C8.74 0 8.33.01 7.05.07 2.7.27.27 2.69.07 7.05.01 8.33 0 8.74 0 12s.01 3.67.07 4.95c.2 4.36 2.62 6.78 6.98 6.98C8.33 23.99 8.74 24 12 24s3.67-.01 4.95-.07c4.35-.2 6.78-2.62 6.98-6.98.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95c-.2-4.35-2.62-6.78-6.98-6.98C15.67.01 15.26 0 12 0zm0 5.84A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84zM12 16a4 4 0 1 1 4-4 4 4 0 0 1-4 4zm6.41-10.85a1.44 1.44 0 1 0 1.44 1.44 1.44 1.44 0 0 0-1.44-1.44z"/></svg>'],
            'social_x'         => ['X',         '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 1.15h3.68l-8.04 9.19L24 22.85h-7.41l-5.8-7.58-6.64 7.58H.46l8.6-9.83L0 1.15h7.6l5.24 6.93zM17.6 20.64h2.04L6.49 3.25H4.3z"/></svg>'],
            'social_tiktok'    => ['TikTok',    '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M16.6 5.82A4.28 4.28 0 0 1 15.54 3h-3.09v12.4a2.59 2.59 0 1 1-2.59-2.59c.27 0 .53.04.78.12v-3.2a5.78 5.78 0 1 0 4.9 5.71V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3a4.28 4.28 0 0 1-3.24-1.48z"/></svg>'],
            'social_youtube'   => ['YouTube',   '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.19a3.02 3.02 0 0 0-2.12-2.14C19.5 3.55 12 3.55 12 3.55s-7.5 0-9.38.5A3.02 3.02 0 0 0 .5 6.19 31.5 31.5 0 0 0 0 12a31.5 31.5 0 0 0 .5 5.81 3.02 3.02 0 0 0 2.12 2.14c1.88.5 9.38.5 9.38.5s7.5 0 9.38-.5a3.02 3.02 0 0 0 2.12-2.14A31.5 31.5 0 0 0 24 12a31.5 31.5 0 0 0-.5-5.81zM9.55 15.57V8.43L15.82 12z"/></svg>'],
            'social_whatsapp'  => ['WhatsApp',  '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M.06 24l1.68-6.15a11.87 11.87 0 0 1-1.59-5.95C.15 5.33 5.5 0 12.06 0a11.82 11.82 0 0 1 8.41 3.49 11.76 11.76 0 0 1 3.48 8.4c0 6.56-5.34 11.9-11.9 11.9a11.9 11.9 0 0 1-5.69-1.45zm6.6-3.8c1.68.99 3.28 1.59 5.4 1.59 5.45 0 9.89-4.43 9.9-9.88a9.86 9.86 0 0 0-9.9-9.9C6.6 1.99 2.17 6.42 2.17 11.88a9.82 9.82 0 0 0 1.51 5.26l-.99 3.62 3.97-1.04zm11.39-5.56c-.07-.12-.27-.2-.56-.34-.29-.15-1.73-.86-2-.95-.27-.1-.46-.15-.66.15-.2.29-.76.95-.93 1.15-.17.2-.34.22-.63.07-.29-.15-1.24-.46-2.36-1.46-.87-.78-1.46-1.73-1.63-2.02-.17-.29-.02-.45.13-.6.13-.13.29-.34.44-.51.15-.17.2-.29.29-.49.1-.2.05-.37-.02-.51-.07-.15-.66-1.6-.91-2.19-.24-.57-.48-.49-.66-.5l-.56-.01c-.2 0-.51.07-.78.37-.27.29-1.02 1-1.02 2.43s1.05 2.82 1.19 3.02c.15.2 2.06 3.15 4.99 4.42.7.3 1.24.48 1.67.62.7.22 1.34.19 1.84.12.56-.08 1.73-.71 1.97-1.39.24-.69.24-1.28.17-1.4z"/></svg>'],
        ];
        $hasSocial = false;
        foreach ($socials as $sk => $sv) { if (c($sk) !== '') { $hasSocial = true; break; } }
        if ($hasSocial): ?>
        <div class="footer-social">
          <?php foreach ($socials as $sk => $sv): if (c($sk) === '') continue; ?>
          <a href="<?= ch($sk) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= $sv[0] ?>"><?= $sv[1] ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="footer-col">
        <h4>Services</h4>
        <a href="#services"><?= ch('service1_title') ?></a>
        <a href="#services"><?= ch('service2_title') ?></a>
        <a href="#services"><?= ch('service3_title') ?></a>
        <a href="#services"><?= ch('service4_title') ?></a>
        <a href="#services"><?= ch('service5_title') ?></a>
      </div>
      <div class="footer-col">
        <h4>Company</h4>
        <a href="#about">About Us</a>
        <a href="#why-us">Why Choose Us</a>
        <a href="#contact">Contact</a>
      </div>
      <div class="footer-col">
        <h4>Get in Touch</h4>
        <a href="mailto:<?= ch('email') ?>"><?= ch('email') ?></a>
        <a href="tel:<?= ch('phone_href') ?>"><?= ch('phone_display') ?></a>
        <span><?= ch('office') ?></span>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <p>&copy; <span id="year"></span> <?= ch('org_name') ?>. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <a href="#top" class="back-to-top" id="backToTop" aria-label="Back to top">&uarr;</a>

  <script src="js/script.js"></script>
</body>
</html>
