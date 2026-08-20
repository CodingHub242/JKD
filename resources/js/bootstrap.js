import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Initialize Laravel Echo with Reverb (Pusher-compatible) only when config is available
const reverbConfig = window.reverbConfig || {};

if (reverbConfig.key) {
    import('laravel-echo').then(({ default: Echo }) => {
        import('pusher-js').then(() => {
            window.Echo = new Echo({
                broadcaster: 'reverb',
                key: reverbConfig.key,
                wsHost: reverbConfig.host || window.location.hostname,
                wsPort: reverbConfig.port || 8080,
                wssPort: reverbConfig.port || 8080,
                forceTLS: reverbConfig.scheme === 'https',
                enabledTransports: ['ws', 'wss'],
                authEndpoint: '/broadcasting/auth',
            });
        });
    });
}
