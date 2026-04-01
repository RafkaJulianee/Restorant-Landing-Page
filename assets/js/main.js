// =========================================
// Navbar Scroll Effect (Glassmorphism)
// =========================================
const navbar = document.getElementById('navbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('shadow-md');
            navbar.style.background = 'rgba(254, 248, 240, 0.95)';
        } else {
            navbar.classList.remove('shadow-md');
            navbar.style.background = 'rgba(254, 248, 240, 0.7)';
        }
    });
}

// =========================================
// Mobile Menu Toggle
// =========================================
const mobileBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
if (mobileBtn && mobileMenu) {
    const icon = mobileBtn.querySelector('i');

    mobileBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        if (mobileMenu.classList.contains('hidden')) {
            icon.classList.replace('ph-x', 'ph-list');
        } else {
            icon.classList.replace('ph-list', 'ph-x');
        }
    });

    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            icon.classList.replace('ph-x', 'ph-list');
        });
    });
}

// =========================================
// Live Review System
// =========================================
document.addEventListener('DOMContentLoaded', () => {

    // --- Star Rating Logic ---
    const starContainer = document.getElementById('star-rating-container');
    const ratingLabel = document.getElementById('rating-label');
    let currentRating = 0;

    const ratingTexts = ['', 'Sangat Buruk 😞', 'Kurang Memuaskan 😕', 'Cukup Baik 😊', 'Sangat Baik 😃', 'Luar Biasa! 🤩'];
    const avatarColors = [
        { bg: 'bg-pink-100', text: 'text-pink-600' },
        { bg: 'bg-blue-100', text: 'text-blue-600' },
        { bg: 'bg-purple-100', text: 'text-purple-600' },
        { bg: 'bg-green-100', text: 'text-green-600' },
        { bg: 'bg-orange-100', text: 'text-orange-600' },
        { bg: 'bg-teal-100', text: 'text-teal-600' },
        { bg: 'bg-yellow-100', text: 'text-yellow-700' },
    ];

    if (starContainer) {
        const stars = starContainer.querySelectorAll('.star-btn');

        function highlightStars(count) {
            stars.forEach((star, idx) => {
                if (idx < count) {
                    star.classList.remove('text-gray-200');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-200');
                }
            });
            if (ratingLabel) {
                ratingLabel.textContent = count > 0 ? ratingTexts[count] : 'Pilih bintang...';
                ratingLabel.style.color = count > 0 ? '#F97316' : '';
            }
        }

        stars.forEach(star => {
            const val = parseInt(star.dataset.value);

            star.addEventListener('mouseover', () => highlightStars(val));
            star.addEventListener('mouseout', () => highlightStars(currentRating));
            star.addEventListener('click', () => {
                currentRating = val;
                highlightStars(currentRating);
                star.style.transform = 'scale(1.3)';
                setTimeout(() => star.style.transform = '', 200);
            });
        });
    }

    // --- Submit Review Logic ---
    const submitBtn = document.getElementById('submit-review-btn');
    const reviewAlert = document.getElementById('review-alert');

    function showAlert(type, message) {
        if (!reviewAlert) return;
        reviewAlert.className = '';
        reviewAlert.classList.add('rounded-2xl', 'px-5', 'py-4', 'text-sm', 'font-medium', 'flex', 'items-center', 'gap-3', 'animate-fade-in');
        if (type === 'error') {
            reviewAlert.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            reviewAlert.innerHTML = `<i class="ph-fill ph-warning-circle text-xl shrink-0"></i> ${message}`;
        } else {
            reviewAlert.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            reviewAlert.innerHTML = `<i class="ph-fill ph-check-circle text-xl shrink-0"></i> ${message}`;
        }
    }

    function buildStarHTML(rating) {
        let html = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                html += `<i class="ph-fill ph-star"></i>`;
            } else {
                html += `<i class="ph ph-star"></i>`;
            }
        }
        return html;
    }

    function getRandomAvatarColor() {
        return avatarColors[Math.floor(Math.random() * avatarColors.length)];
    }

    function createReviewCard(name, rating, comment) {
        const initials = name.trim().charAt(0).toUpperCase();
        const color = getRandomAvatarColor();
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des"];
        const month = monthNames[now.getMonth()];
        const year = now.getFullYear();
        const formattedDate = `${hours}:${minutes}, ${day} ${month} ${year}`;

        const card = document.createElement('div');
        card.className = 'testimonial-card bg-white p-8 rounded-3xl shadow-sm border border-orange-50 hover:-translate-y-2 transition-transform duration-300 relative new-review-entry';
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px) scale(0.95)';

        card.innerHTML = `
            <div class="absolute top-4 left-4 bg-brand-red text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Baru</div>
            <i class="ph-fill ph-quotes text-5xl text-orange-100 absolute top-6 right-6"></i>
            <div class="flex gap-1 text-yellow-400 mb-6 text-xl mt-2">
                ${buildStarHTML(rating)}
            </div>
            <p class="text-brand-gray mb-8 leading-relaxed">"${comment}"</p>
            <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
                <div class="w-12 h-12 rounded-xl ${color.bg} ${color.text} flex items-center justify-center font-bold text-xl shrink-0">${initials}</div>
                <div>
                    <h4 class="font-bold text-brand-dark">${name}</h4>
                    <p class="text-[10px] font-bold text-brand-red uppercase tracking-widest mt-1 opacity-70">
                        <i class="ph-bold ph-clock"></i> ${formattedDate}
                    </p>
                </div>
            </div>
        `;
        return card;
    }

    function injectReviewCard(card) {
        const grid = document.getElementById('testimonials-grid');
        if (!grid) return;

        // Prepend the new card before the first existing card
        grid.insertBefore(card, grid.firstChild);

        // Animate in
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Scroll to testimonials section
        setTimeout(() => {
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 150);
    }

    if (submitBtn) {
        submitBtn.addEventListener('click', () => {
            const nameInput = document.getElementById('review-name');
            const commentInput = document.getElementById('review-comment');

            const name = nameInput ? nameInput.value.trim() : '';
            const comment = commentInput ? commentInput.value.trim() : '';

            // Validation
            if (!name) {
                showAlert('error', 'Harap masukkan nama lengkap Anda.');
                nameInput && nameInput.focus();
                return;
            }
            if (currentRating === 0) {
                showAlert('error', 'Harap pilih minimal 1 bintang untuk rating.');
                return;
            }
            if (comment.length < 20) {
                showAlert('error', 'Komentar minimal 20 karakter. Ceritakan pengalaman Anda lebih detail!');
                commentInput && commentInput.focus();
                return;
            }

            // Real Backend Submission
            const formData = new FormData();
            formData.append('action', 'add_review');
            formData.append('name', name);
            formData.append('rating', currentRating);
            formData.append('comment', comment);

            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Create and inject the new review card
                    const newCard = createReviewCard(name, currentRating, comment);
                    injectReviewCard(newCard);

                    // Show success
                    showAlert('success', `Terima kasih, <strong>${name}</strong>! Ulasan Anda berhasil ditambahkan.`);

                    // Reset form
                    if (nameInput) nameInput.value = '';
                    if (commentInput) commentInput.value = '';
                    currentRating = 0;
                    if (starContainer) {
                        starContainer.querySelectorAll('.star-btn').forEach(s => {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-200');
                        });
                    }
                    if (ratingLabel) {
                        ratingLabel.textContent = 'Pilih bintang...';
                        ratingLabel.style.color = '';
                    }
                } else {
                    showAlert('error', data.message || 'Gagal mengirim ulasan.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan sistem. Silakan coba lagi nanti.');
            })
            .finally(() => {
                // Restore button
                submitBtn.disabled = false;
                submitBtn.innerHTML = `Kirim Ulasan`;

                // Auto-hide alert after 5s
                setTimeout(() => {
                    if (reviewAlert) reviewAlert.className = 'hidden';
                }, 5000);
            });
        });
    }

});
