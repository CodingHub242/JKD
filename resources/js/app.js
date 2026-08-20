import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initPreloader();
    initReveal();
    initParallax();
});

/* Live Chat Widget */
document.addEventListener('alpine:init', () => {
    Alpine.data('liveChat', () => ({
        open: false,
        name: '',
        email: '',
        message: '',
        messages: [],
        conversationId: null,
        sending: false,
        polling: null,
        init() {
            const saved = sessionStorage.getItem('jkd_chat_conversation_id');
            if (saved) {
                this.conversationId = saved;
            }
        },
        async send() {
            if (!this.message.trim()) return;

            if (!this.conversationId) {
                if (!this.name.trim() || !this.email.trim()) {
                    alert('Please enter your name and email to start chatting.');
                    return;
                }
                await this.startConversation();
            }

            this.sending = true;
            try {
                const res = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ message: this.message })
                });
                const data = await res.json();
                if (data.ok) {
                    this.messages.push({
                        id: Date.now(),
                        body: this.message,
                        sender_type: 'visitor',
                        created_at: new Date().toLocaleTimeString()
                    });
                    this.message = '';
                    this.$nextTick(() => this.scrollToBottom());
                    this.startPolling();
                }
            } catch (e) {}
            this.sending = false;
        },
        async startConversation() {
            try {
                const res = await fetch('/chat/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        name: this.name,
                        email: this.email,
                        message: this.message
                    })
                });
                const data = await res.json();
                if (data.ok) {
                    this.conversationId = data.conversation_id;
                    sessionStorage.setItem('jkd_chat_conversation_id', data.conversation_id);
                }
            } catch (e) {}
        },
        startPolling() {
            if (this.polling) clearInterval(this.polling);
            this.polling = setInterval(() => this.poll(), 3000);
        },
        async poll() {
            if (!this.conversationId) return;
            try {
                const res = await fetch('/chat/messages?last_id=0', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.ok && data.messages.length) {
                    this.messages = data.messages.map(m => ({
                        ...m,
                        created_at: new Date(m.created_at).toLocaleTimeString()
                    }));
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (e) {}
        },
        scrollToBottom() {
            const el = document.getElementById('live-chat-messages');
            if (el) el.scrollTop = el.scrollHeight;
        }
    }));
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
