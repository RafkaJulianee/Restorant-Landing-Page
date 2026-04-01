// Navbar Scroll Effect (Glassmorphism becomes more opaque)
const navbar = document.getElementById('navbar');
if(navbar) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('shadow-md');
            navbar.style.background = 'rgba(254, 248, 240, 0.9)';
        } else {
            navbar.classList.remove('shadow-md');
            navbar.style.background = 'rgba(254, 248, 240, 0.7)';
        }
    });
}

// Mobile Menu Toggle
const btn = document.getElementById('mobile-menu-btn');
const menu = document.getElementById('mobile-menu');
if(btn && menu) {
    const icon = btn.querySelector('i');
    
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
        if(menu.classList.contains('hidden')){
            icon.classList.replace('ph-x', 'ph-list');
        } else {
            icon.classList.replace('ph-list', 'ph-x');
        }
    });

    // Close mobile menu when link is clicked
    const mobileLinks = menu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            menu.classList.add('hidden');
            icon.classList.replace('ph-x', 'ph-list');
        });
    });
}

// Star Rating Interactive Logic
document.addEventListener('DOMContentLoaded', () => {
    // Select the container by traversing or ID. Since it's in a form, we'll find the specific star group.
    const starContainer = document.querySelector('.flex.gap-2.text-3xl.cursor-pointer');
                            
    if (starContainer) {
        const stars = starContainer.querySelectorAll('i');
        let currentRating = 0;
        
        // Ensure initial classes are manageable (all solid for visual, but gray for unselected)
        stars.forEach(star => {
            star.classList.add('ph-fill');
            star.classList.remove('ph');
        });

        // Set data values for easier calculation
        stars.forEach((star, index) => {
            star.dataset.index = index + 1;
            
            // Add hover and click functionality
            star.addEventListener('mouseover', function() {
                const hoverValue = parseInt(this.dataset.index);
                highlightStars(hoverValue);
            });
            
            star.addEventListener('mouseout', function() {
                highlightStars(currentRating);
            });
            
            star.addEventListener('click', function() {
                currentRating = parseInt(this.dataset.index);
                highlightStars(currentRating);
                // Add a bounce animation class briefly
                this.style.transform = 'scale(1.2)';
                setTimeout(() => this.style.transform = 'scale(1)', 150);
            });
        });
        
        function highlightStars(count) {
            stars.forEach((star, index) => {
                if (index < count) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }
    }
});
