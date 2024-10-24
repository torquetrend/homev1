// scripts.js

document.addEventListener('DOMContentLoaded', function() {
    // Handle Contact Form Submission
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const responseDiv = document.getElementById('contact-response');
            const formData = new FormData(contactForm);

            fetch('backend/contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                responseDiv.textContent = data.message;
                responseDiv.className = 'response-message ' + data.status;
                if(data.status === 'success') {
                    contactForm.reset();
                }
            })
            .catch(error => {
                responseDiv.textContent = 'An error occurred. Please try again.';
                responseDiv.className = 'response-message error';
            });
        });
    }

    // Handle Subscribe Form Submission
    const subscribeForm = document.getElementById('subscribe-form');
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const responseDiv = document.getElementById('subscribe-response');
            const formData = new FormData(subscribeForm);

            fetch('backend/subscribe.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                responseDiv.textContent = data.message;
                responseDiv.className = 'response-message ' + data.status;
                if(data.status === 'success') {
                    subscribeForm.reset();
                }
            })
            .catch(error => {
                responseDiv.textContent = 'An error occurred. Please try again.';
                responseDiv.className = 'response-message error';
            });
        });
    }
});
