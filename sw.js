// sw.js

const CACHE_NAME = 'torquetrend-cache-v1';
const urlsToCache = [
    '/',
    '/index.php',
    '/article.php',
    '/styles.css',
    '/scripts.js',
    '/manifest.json',
    '/images/M5.jpg',
    '/images/car%20charging.jpg',
    '/images/eg80.jpg',
    '/images/lithium-mine.jpg',
    '/images/model-s.jpg',
    '/images/waymo.jpg',
    '/admin/login.php',
    // Add other assets you want to cache
];

// Install the service worker and cache resources
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

// Listen for fetch events and serve cached content when offline
self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                // Cache hit - return response
                if (response) {
                    return response;
                }
                return fetch(event.request);
            }
        )
    );
});

// Update the service worker and remove old caches
self.addEventListener('activate', function(event) {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
