import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Initialize Laravel Echo with Reverb (Pusher-compatible)
// Config is provided via window.reverbConfig from Blade templates
const reverbConfig = window.reverbConfig || {};

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
