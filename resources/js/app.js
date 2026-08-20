import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

/* Chat widget — must be registered before Alpine starts */
document.addEventListener('alpine:init', () => {
    Alpine.data('chatWidget', () => ({
        open: false,
        name: '',
        email: '',
        message: '',
        messages: [],
        lastId: 0,
        started: false,
        sending: false,
        conversationId: null,
        agentJoined: false,
        visitorTyping: false,
        agentTyping: false,
        typingTimer: null,
        init() {
            this.poll();
            this._pollInterval = setInterval(() => this.poll(), 2000);
        },
        async poll() {
            if (!this.conversationId) return;
            try {
                const res = await fetch(`/chat/messages/${this.conversationId}?last_id=${this.lastId}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.ok) {
                    if (data.messages.length) {
                        data.messages.forEach(m => this.messages.push(m));
                        this.lastId = data.messages[data.messages.length - 1].id;
                        this.$nextTick(() => {
                            const el = document.getElementById('chat-log');
                            if (el) el.scrollTop = el.scrollHeight;
                        });
                    }
                    if (data.agent_joined !== undefined) this.agentJoined = data.agent_joined;
                    if (data.agent_typing !== undefined) this.agentTyping = data.agent_typing;
                }
            } catch (e) {}
        },
        async send() {
            if (!this.message.trim()) return;
            this.sending = true;
            const payload = { message: this.message };
            let url = '/chat/send';
            if (!this.started) {
                if (!this.name.trim()) {
                    alert('Please enter your name to start the chat.');
                    this.sending = false;
                    return;
                }
                url = '/chat/start';
                payload.name = this.name;
                payload.email = this.email;
            } else if (this.conversationId) {
                payload.conversation_id = this.conversationId;
            }
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.ok) {
                    this.started = true;
                    this.conversationId = data.conversation_id || this.conversationId;
                    this.message = '';
                    this.visitorTyping = false;
                    this.poll();
                }
            } catch (e) {}
            this.sending = false;
        },
        onTyping() {
            if (!this.conversationId) return;
            this.visitorTyping = true;
            fetch('/chat/typing', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ conversation_id: this.conversationId, sender: 'visitor' })
            }).catch(() => {});
            clearTimeout(this.typingTimer);
            this.typingTimer = setTimeout(() => {
                this.visitorTyping = false;
                fetch('/chat/typing', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ conversation_id: this.conversationId, sender: 'visitor', clear: true })
                }).catch(() => {});
            }, 1500);
        }
    }));
});

Alpine.start();

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
        hide();
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

initPreloader();
initReveal();
initParallax();
