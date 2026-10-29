// ============================================================
// Pasora Solution Ltd - site interactions
// ============================================================

// Current year in footer
document.getElementById('year').textContent = new Date().getFullYear();

// Sticky header shadow
const header = document.querySelector('.site-header');
const backToTop = document.getElementById('backToTop');
const scrollProgress = document.getElementById('scrollProgress');
window.addEventListener('scroll', () => {
  header.classList.toggle('scrolled', window.scrollY > 10);
  backToTop.classList.toggle('visible', window.scrollY > 500);
  const scrollable = document.documentElement.scrollHeight - window.innerHeight;
  scrollProgress.style.width = scrollable > 0 ? (window.scrollY / scrollable) * 100 + '%' : '0';
}, { passive: true });

// Mobile nav toggle
const navToggle = document.getElementById('navToggle');
const siteNav = document.getElementById('siteNav');
navToggle.addEventListener('click', () => {
  const open = siteNav.classList.toggle('open');
  navToggle.classList.toggle('open', open);
  navToggle.setAttribute('aria-expanded', open);
});
siteNav.querySelectorAll('a').forEach(link => {
  link.addEventListener('click', () => {
    siteNav.classList.remove('open');
    navToggle.classList.remove('open');
    navToggle.setAttribute('aria-expanded', 'false');
  });
});

// Scroll reveal animations
const revealObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('in');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.05, rootMargin: '0px 0px 80px 0px' });
document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

// Animated stat counters
const statObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (!entry.isIntersecting) return;
    const el = entry.target;
    const target = parseInt(el.dataset.count, 10);
    const duration = 1400;
    const start = performance.now();
    const tick = now => {
      const progress = Math.min((now - start) / duration, 1);
      el.textContent = Math.round(target * (1 - Math.pow(1 - progress, 3)));
      if (progress < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
    statObserver.unobserve(el);
  });
}, { threshold: 0.5 });
document.querySelectorAll('.stat-num').forEach(el => statObserver.observe(el));

// Active nav link highlighting
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.site-nav a[href^="#"]');
const sectionObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (!entry.isIntersecting) return;
    navLinks.forEach(link => {
      link.classList.toggle('active', link.getAttribute('href') === `#${entry.target.id}`);
    });
  });
}, { rootMargin: '-40% 0px -55% 0px' });
sections.forEach(section => sectionObserver.observe(section));

// Contact form (front-end only, opens the user's mail client)
const form = document.getElementById('contactForm');
const formNote = document.getElementById('formNote');
let lastSubmit = 0;
form.addEventListener('submit', e => {
  e.preventDefault();
  // Honeypot filled → almost certainly a bot; drop silently
  if (document.getElementById('cf-hp').value) return;
  // Throttle rapid repeat submissions
  if (Date.now() - lastSubmit < 5000) return;
  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }
  lastSubmit = Date.now();
  const data = new FormData(form);
  const subject = encodeURIComponent(`Website enquiry: ${data.get('service')}`);
  const body = encodeURIComponent(
    `Name: ${data.get('name')}\n` +
    `Email: ${data.get('email')}\n` +
    `Phone: ${data.get('phone') || 'not provided'}\n` +
    `Service: ${data.get('service')}\n\n` +
    `${data.get('message')}`
  );
  const to = (form.dataset.contactEmail || 'info@pasorasolutionltd.com').trim();
  window.location.href = `mailto:${to}?subject=${subject}&body=${body}`;
  formNote.textContent = `Opening your email app now. You can also write to us directly at ${to}`;
  form.reset();
});
