import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initPreloader();
    initReveal();
    initParallax();
});

/* Preloader — shown once per browser session */
function initPreloader() {
    const el = document.getElementById('preloader');
    if (!el) return;

    const seen = sessionStorage.getItem('jkd_preloaded');
    const hide = () => {
        el.classList.add('is-hidden');
        sessionStorage.setItem('jkd_preloaded', '1');
    };

    if (seen) {
        el.classList.add('is-hidden');
        return;
    }

    const minDelay = 900;
    const start = Date.now();
    const finish = () => {
        const waited = Date.now() - start;
        setTimeout(hide, Math.max(0, minDelay - waited));
    };

    if (document.readyState === 'complete') {
        finish();
    } else {
        window.addEventListener('load', finish, { once: true });
        // Safety net in case load never fires
        setTimeout(finish, 2500);
    }
}

/* Scroll reveal via IntersectionObserver */
function initReveal() {
    const items = document.querySelectorAll('.reveal');
    if (!items.length) return;

    if (!('IntersectionObserver' in window)) {
        items.forEach((i) => i.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    items.forEach((i) => observer.observe(i));
}

/* Lightweight parallax for [data-parallax] elements */
function initParallax() {
    const layers = document.querySelectorAll('[data-parallax]');
    if (!layers.length) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    let ticking = false;
    const update = () => {
        const y = window.scrollY;
        layers.forEach((layer) => {
            const speed = parseFloat(layer.dataset.parallax) || 0.2;
            layer.style.transform = `translate3d(0, ${y * speed}px, 0)`;
        });
        ticking = false;
    };

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    }, { passive: true });
}
