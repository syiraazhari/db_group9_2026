/* =====================================================
   FreshMart - Main JavaScript
   File: assets/js/main.js
   ===================================================== */

document.addEventListener('DOMContentLoaded', function() {
    // Flash messages auto-dismiss
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    // Rating stars
    const stars = document.querySelectorAll('.rating-stars .star');
    const ratingInput = document.getElementById('rating');

    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            const rating = index + 1;
            if (ratingInput) ratingInput.value = rating;
            stars.forEach((s, i) => {
                s.classList.toggle('active', i < rating);
            });
        });

        star.addEventListener('mouseenter', () => {
            stars.forEach((s, i) => {
                s.style.color = i <= index ? '#fbbf24' : '#d1d5db';
            });
        });
    });

    document.querySelector('.rating-stars')?.addEventListener('mouseleave', () => {
        const currentRating = ratingInput ? parseInt(ratingInput.value) : 0;
        stars.forEach((s, i) => {
            s.style.color = i < currentRating ? '#fbbf24' : '#d1d5db';
        });
    });

    // Quantity controls in cart
    document.querySelectorAll('.qty-control').forEach(control => {
        const minusBtn = control.querySelector('.qty-minus');
        const plusBtn = control.querySelector('.qty-plus');
        const qtySpan = control.querySelector('.qty-value');
        const form = control.closest('form');
        const input = form?.querySelector('input[name="quantity"]');

        minusBtn?.addEventListener('click', () => {
            let val = parseInt(qtySpan.textContent);
            if (val > 1) {
                val--;
                qtySpan.textContent = val;
                if (input) input.value = val;
                form?.submit();
            }
        });

        plusBtn?.addEventListener('click', () => {
            let val = parseInt(qtySpan.textContent);
            val++;
            qtySpan.textContent = val;
            if (input) input.value = val;
            form?.submit();
        });
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            target?.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Navbar scroll effect
    let lastScroll = 0;
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        if (currentScroll > 100) {
            navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
        } else {
            navbar.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
        }
        lastScroll = currentScroll;
    });

    // Add to cart animation
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('button');
            const originalText = btn.textContent;
            btn.textContent = '✓ Added!';
            btn.style.background = '#16a34a';

            setTimeout(() => {
                btn.textContent = originalText;
                btn.style.background = '';
            }, 1500);
        });
    });
});
