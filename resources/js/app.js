require('./bootstrap');

window._ = require('lodash');
window.$ = window.jQuery = require('jquery');

var notifications = [];
const NOTIFICATION_TYPES = {
    Payment: 'App\\Notifications\\designerNotifications',
    
    User: 'App\\Notifications\\UserNotifications',

    CompanyUser: 'App\\Notifications\\CompanyUserNotifications'
};
 
//pusher
window.Pusher = require('pusher-js');

import Echo from "laravel-echo";

window.Echo = new Echo({
    broadcaster: 'pusher',
    key:process.env.MIX_PUSHER_APP_KEY,
    cluster: 'eu',
    encrypted: false
});
