/* assets/js/main.js */

document.addEventListener('DOMContentLoaded', () => {
    // Reveal animations on scroll
    const animateElements = document.querySelectorAll('.animate-fade-in');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    animateElements.forEach(el => observer.observe(el));

    // Form Validation (Example for Login/Register)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Dynamic Price Update in Cart/Product Details
    const qtyInput = document.querySelector('input[name="quantity"]');
    const priceDisplay = document.querySelector('.price-tag');
    
    if (qtyInput && priceDisplay) {
        const basePrice = parseFloat(priceDisplay.textContent.replace('$', '').replace(',', ''));
        
        qtyInput.addEventListener('input', () => {
            const qty = parseInt(qtyInput.value) || 1;
            const newPrice = (basePrice * qty).toFixed(2);
            priceDisplay.textContent = `$${newPrice}`;
        });
    }

    // Glass Card Hover Effect Enhancements (Optional)
    const glassCards = document.querySelectorAll('.glass-card');
    glassCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.borderColor = 'rgba(99, 102, 241, 0.4)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.borderColor = 'rgba(255, 255, 255, 0.1)';
        });
    });
});
