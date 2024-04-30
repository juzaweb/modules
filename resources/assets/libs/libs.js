import $ from "jquery";
//import Echo from 'laravel-echo';

window.$ = window.jQuery = $;

window.Popper = require('popper.js').default;

import 'bootstrap';
import Chart from 'chart.js/auto';
window.Chart = Chart;

// window.Pusher = require('pusher-js');

// const CMSEcho = new Echo({
//     broadcaster: 'pusher',
//     key: 'xxx',
//     cluster: 'mt1',
//     forceTLS: true,
// });

// const channel = CMSEcho.channel('notification');
// channel.listen('.new-notification', function (data) {
//     alert(data?.message);
// });
