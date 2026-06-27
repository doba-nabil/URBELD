importScripts('https://www.gstatic.com/firebasejs/12.9.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/12.9.0/firebase-messaging-compat.js');

const firebaseConfig = {
    apiKey: "AIzaSyB8M351dCYWxogC4H1AA5lS5A-Zr5hnkTM",
    authDomain: "asas-f2939.firebaseapp.com",
    projectId: "asas-f2939",
    storageBucket: "asas-f2939.firebasestorage.app",
    messagingSenderId: "753498308276",
    appId: "1:753498308276:web:93c385b47477936733e1e1",
    measurementId: "G-HKK96E4LXX"
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    
    const notificationTitle = payload.notification?.title || 'أسس العقارية';
    const notificationOptions = {
        body: payload.notification?.body || 'لديك إشعار جديد.',
        icon: '/website/assets/img/logo.png', // Add your actual icon here
        data: payload.data
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    if (event.notification.data && event.notification.data.url) {
        event.waitUntil(
            clients.openWindow(event.notification.data.url)
        );
    } else {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});
