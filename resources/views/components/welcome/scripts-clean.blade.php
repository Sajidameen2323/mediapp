<!-- Enhanced JavaScript for interactivity -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle functionality
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                menuIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });

            // Close mobile menu when clicking on a link
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                });
            });
        }

        // Add smooth scrolling to anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Enhanced star rating interaction for feedback form
        document.querySelectorAll('.text-3xl').forEach((star, index) => {
            star.addEventListener('click', function() {
                const stars = Array.from(this.parentElement.children);
                stars.forEach((s, i) => {
                    if (i <= index) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
            });

            star.addEventListener('mouseover', function() {
                const stars = Array.from(this.parentElement.children);
                stars.forEach((s, i) => {
                    if (i <= index) {
                        s.style.transform = 'scale(1.1)';
                    } else {
                        s.style.transform = 'scale(1)';
                    }
                });
            });

            star.addEventListener('mouseout', function() {
                const stars = Array.from(this.parentElement.children);
                stars.forEach(s => {
                    s.style.transform = 'scale(1)';
                });
            });
        });

        // Add animation to feature cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe service cards for animation
        document.querySelectorAll('.group').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });

        // Add typing animation to AI chat
        const aiMessages = document.querySelectorAll('.bg-white\\/30');
        if (aiMessages.length > 0) {
            let delay = 0;
            aiMessages.forEach(message => {
                setTimeout(() => {
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(10px)';
                    message.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    
                    setTimeout(() => {
                        message.style.opacity = '1';
                        message.style.transform = 'translateY(0)';
                    }, 100);
                }, delay);
                delay += 1000;
            });
        }

        // Add floating animation to statistics cards
        const statsCards = document.querySelectorAll('.bg-white\\/10');
        statsCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.2}s`;
            card.classList.add('animate-float');
        });

        // Add hover effects to service buttons
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
            });

            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '';
            });
        });

        // Add simple form validation for feedback form
        const feedbackForm = document.querySelector('form');
        if (feedbackForm) {
            feedbackForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const name = this.querySelector('input[type="text"]').value;
                const feedback = this.querySelector('textarea').value;
                const selectedStars = this.querySelectorAll('.text-yellow-400').length;
                
                if (!name || !feedback || selectedStars === 0) {
                    alert('Please fill in all fields and provide a rating.');
                    return;
                }
                
                // Simulate form submission
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Submitting...';
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    alert('Thank you for your feedback!');
                    this.reset();
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    
                    // Reset stars
                    this.querySelectorAll('.text-3xl').forEach(star => {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    });
                }, 2000);
            });
        }

        // Add scroll to top functionality
        const scrollToTopBtn = document.createElement('button');
        scrollToTopBtn.innerHTML = '↑';
        scrollToTopBtn.className = 'fixed bottom-8 right-8 bg-blue-600 text-white w-12 h-12 rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 opacity-0 pointer-events-none z-50';
        scrollToTopBtn.style.fontSize = '20px';
        scrollToTopBtn.style.fontWeight = 'bold';
        document.body.appendChild(scrollToTopBtn);

        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Show/hide scroll to top button
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.style.opacity = '1';
                scrollToTopBtn.style.pointerEvents = 'auto';
            } else {
                scrollToTopBtn.style.opacity = '0';
                scrollToTopBtn.style.pointerEvents = 'none';
            }
        });

        // Add loading animation for buttons
        document.querySelectorAll('button').forEach(button => {
            if (button.textContent.includes('Book') || button.textContent.includes('Schedule') || button.textContent.includes('Order')) {
                button.addEventListener('click', function(e) {
                    if (this.getAttribute('type') !== 'submit') {
                        e.preventDefault();
                        const originalText = this.textContent;
                        this.innerHTML = '<span class="inline-block animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>Loading...';
                        this.disabled = true;
                        
                        // Navigate to booking page
                        // setTimeout(() => {
                        //     this.textContent = originalText;
                        //     this.disabled = false;
                        //     alert('Feature coming soon! This will redirect to the booking system.');
                        // }, 1500);
                    }
                });
            }
        });

        // Add navigation highlight on scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('nav a[href^="#"]');
        
        window.addEventListener('scroll', function() {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('text-blue-600');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('text-blue-600');
                }
            });
        });

        // ==== DOCTOR SEARCH FUNCTIONALITY ====
        
        // Get form elements
        const searchForm = document.getElementById('doctorSearchForm');
        const doctorSearchInput = document.getElementById('doctorSearchInput');
        const specialtyFilter = document.getElementById('specialtyFilter');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const resetFiltersBtn = document.getElementById('resetFiltersBtn');
        const doctorsGrid = document.getElementById('doctorsGrid');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const noResultsMessage = document.getElementById('noResultsMessage');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        const resetSearchBtn = document.getElementById('resetSearchBtn');
        const retrySearchBtn = document.getElementById('retrySearchBtn');
        const showGeneralDoctorsBtn = document.getElementById('showGeneralDoctorsBtn');
        const searchFeedback = document.getElementById('searchFeedback');
        const searchFeedbackText = document.getElementById('searchFeedbackText');

        // Symptom keywords for intelligent search feedback
        const symptomKeywords = [
            'headache', 'migraine', 'stomach pain', 'chest pain', 'back pain', 'knee pain',
            'cough', 'fever', 'nausea', 'dizziness', 'shortness of breath', 'anxiety',
            'depression', 'skin rash', 'acne', 'eye problems', 'ear pain', 'sore throat',
            'joint pain', 'fatigue', 'bloating', 'constipation', 'diarrhea', 'diabetes',
            'hypertension', 'heart problems', 'allergies', 'asthma', 'pregnancy', 'weight loss'
        ];

        // Color palettes for doctor cards
        const colorPalettes = [
            { gradient: 'from-blue-500 to-purple-600', color: 'blue-600' },
            { gradient: 'from-green-500 to-blue-600', color: 'green-600' },
            { gradient: 'from-purple-500 to-pink-600', color: 'purple-600' },
            { gradient: 'from-red-500 to-orange-600', color: 'red-600' },
            { gradient: 'from-indigo-500 to-purple-600', color: 'indigo-600' },
            { gradient: 'from-teal-500 to-blue-600', color: 'teal-600' }
        ];

        // Show/hide clear button on input (without triggering search)
        if (doctorSearchInput) {
            doctorSearchInput.addEventListener('input', function() {
                const query = this.value.trim();
                // Show/hide clear button
                if (query) {
                    clearSearchBtn.classList.remove('hidden');
                } else {
                    clearSearchBtn.classList.add('hidden');
                }
            });
        }

        // Clear search functionality
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                doctorSearchInput.value = '';
                clearSearchBtn.classList.add('hidden');
                searchDoctors('');
            });
        }

        // Utility functions for UI management
        function showElement(element) {
            if (element) element.classList.remove('hidden');
        }

        function hideElement(element) {
            if (element) element.classList.add('hidden');
        }

        function showLoading() {
            hideElement(doctorsGrid);
            hideElement(noResultsMessage);
            hideElement(errorMessage);
            showElement(loadingIndicator);
        }

        function hideLoading() {
            hideElement(loadingIndicator);
        }

        function showError(message) {
            hideLoading();
            hideElement(doctorsGrid);
            hideElement(noResultsMessage);
            if (errorText) errorText.textContent = message;
            showElement(errorMessage);
        }

        function showNoResults() {
            hideLoading();
            hideElement(doctorsGrid);
            hideElement(errorMessage);
            showElement(noResultsMessage);
        }

        function showResults() {
            hideLoading();
            hideElement(noResultsMessage);
            hideElement(errorMessage);
            showElement(doctorsGrid);
        }

        // Enhanced doctor search with Algolia integration
        async function searchDoctors(query = '') {
            showLoading();
            hideSearchFeedback();

            try {
                const params = new URLSearchParams();
                
                // Add query parameter
                if (query && query.trim()) {
                    params.append('q', query.trim());
                }
                
                // Add specialty filter
                const specialty = specialtyFilter.value;
                if (specialty && specialty !== '') {
                    params.append('specialization', specialty);
                }

                const response = await fetch(`/api/appointments/search-doctors?${params.toString()}`);
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to search doctors');
                }

                if (data.success) {
                    if (data.doctors && data.doctors.length > 0) {
                        displayDoctors(data.doctors);
                        showSearchFeedback(query, data.doctors.length, data.search_type);
                        showResults();
                    } else {
                        showNoResults();
                        showSearchFeedback(query, 0, data.search_type);
                    }
                } else {
                    throw new Error(data.message || 'Search failed');
                }
            } catch (error) {
                console.error('Error searching doctors:', error);
                showError('Unable to search doctors. Please try again.');
            }
        }

        // Display doctors in grid
        function displayDoctors(doctors) {
            doctorsGrid.innerHTML = doctors
                .map((doctor, index) => createDoctorCard(doctor, index))
                .join('');
        }

        // Show search feedback with intelligent messaging
        function showSearchFeedback(query, resultCount, searchType) {
            if (!query && !specialtyFilter.value) {
                hideSearchFeedback();
                return;
            }

            let feedbackMessage = '';
            
            if (query && query.trim()) {
                const isSymptomSearch = symptomKeywords.some(symptom => 
                    query.toLowerCase().includes(symptom.toLowerCase())
                );

                if (isSymptomSearch) {
                    if (resultCount > 0) {
                        feedbackMessage = `<i class="fas fa-stethoscope text-green-600 mr-2"></i>Found ${resultCount} doctor(s) specializing in treating "${query}". Specialists are prioritized, with general practitioners as additional options.`;
                    } else {
                        feedbackMessage = `<i class="fas fa-info-circle text-blue-600 mr-2"></i>No specific specialists found for "${query}". Try searching for general practitioners or contact our support for assistance.`;
                    }
                } else {
                    feedbackMessage = `<i class="fas fa-search text-blue-600 mr-2"></i>Found ${resultCount} doctor(s) matching "${query}".`;
                }
            } else if (specialtyFilter.value) {
                feedbackMessage = `<i class="fas fa-filter text-blue-600 mr-2"></i>Showing ${resultCount} ${specialtyFilter.value.toLowerCase()} specialist(s).`;
            }

            if (feedbackMessage) {
                searchFeedbackText.innerHTML = feedbackMessage;
                searchFeedback.classList.remove('hidden');
            }
        }

        function hideSearchFeedback() {
            if (searchFeedback) searchFeedback.classList.add('hidden');
        }        // Create doctor card HTML with enhanced data structure
        function createDoctorCard(doctor, index) {
            const palette = colorPalettes[index % colorPalettes.length];
            const availability = doctor.is_available ? 'Available Now' : 'Next available: Soon';
            const rating = doctor?.rating ? Number(doctor.rating).toFixed(1) : 0;
            const reviewCount = doctor?.rating_count ?? 0; 
            
            // Handle both direct doctor data and nested user data
            const doctorName = doctor.name || (doctor.user ? doctor.user.name : 'Unknown Doctor');
            const doctorId = doctor.id;
            
            // Generate initials from name
            const initials = doctorName.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);

            // Generate star rating display based on actual rating
            function generateStarRating(rating) {
                const fullStars = Math.floor(rating);
                const hasHalfStar = rating % 1 >= 0.5;
                const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
                
                let stars = '';
                
                // Full stars
                for (let i = 0; i < fullStars; i++) {
                    stars += '<i class="fas fa-star text-yellow-400"></i>';
                }
                
                // Half star
                if (hasHalfStar) {
                    stars += '<i class="fas fa-star-half-alt text-yellow-400"></i>';
                }
                
                // Empty stars
                for (let i = 0; i < emptyStars; i++) {
                    stars += '<i class="far fa-star text-gray-300 dark:text-gray-600"></i>';
                }
                
                return stars;
            }

            const starRating = generateStarRating(Number(rating));

            const bookUrl = `{{ route('patient.appointments.create', ['doctor_id' => 'doctor_id_placeholder']) }}`;
            const bookUrlWithId = bookUrl.replace('doctor_id_placeholder', doctorId);

            return `
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg hover:shadow-xl transition-shadow overflow-hidden">
                    <div class="bg-gradient-to-r ${palette.gradient} h-32"></div>
                    <div class="p-6 -mt-16">
                        <div class="bg-white dark:bg-gray-800 w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg">
                            <span class="text-2xl font-bold text-${palette.color}">${initials}</span>
                        </div>
                        <div class="text-center">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">${doctorName}</h3>
                            <p class="text-${palette.color} dark:text-${palette.color.replace('600', '400')} font-medium">${doctor.specialization || 'General Practice'}</p>
                            ${doctor.experience_years ? `<p class="text-gray-600 dark:text-gray-400 text-sm mt-2">${doctor.experience_years} years experience</p>` : ''}
                            <div class="flex items-center justify-center mt-2 space-x-1">
                                <div class="flex items-center space-x-0.5">
                                    ${starRating}
                                </div>
                                <span class="text-gray-600 dark:text-gray-400 text-sm ml-2">${rating} ${reviewCount > 0 ? `(${reviewCount} reviews)` : '(No reviews yet)'}</span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">${availability}</p>
                            ${doctor.consultation_fee ? `<p class="text-gray-600 dark:text-gray-400 text-sm">Consultation: $${doctor.consultation_fee}</p>` : ''}
                            <a href="${bookUrlWithId}" class="block">
                                <button class="mt-4 w-full bg-${palette.color} text-white py-2 rounded-lg hover:bg-${palette.color.replace('600', '700')} transition-colors">
                                    Book Appointment
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }

        // Search form submission - only trigger search when "Find Doctors" button is clicked
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const query = doctorSearchInput.value.trim();
                searchDoctors(query);
            });
        }

        // Reset filters
        if (resetFiltersBtn) {
            resetFiltersBtn.addEventListener('click', function() {
                doctorSearchInput.value = '';
                specialtyFilter.value = '';
                clearSearchBtn.classList.add('hidden');
                searchDoctors('');
            });
        }

        // Reset search (from no results section)
        if (resetSearchBtn) {
            resetSearchBtn.addEventListener('click', function() {
                doctorSearchInput.value = '';
                specialtyFilter.value = '';
                clearSearchBtn.classList.add('hidden');
                searchDoctors('');
            });
        }

        // Retry search
        if (retrySearchBtn) {
            retrySearchBtn.addEventListener('click', function() {
                const query = doctorSearchInput.value.trim();
                searchDoctors(query);
            });
        }

        // Show general practitioners
        if (showGeneralDoctorsBtn) {
            showGeneralDoctorsBtn.addEventListener('click', function() {
                specialtyFilter.value = 'General Practice';
                doctorSearchInput.value = '';
                clearSearchBtn.classList.add('hidden');
                searchDoctors('');
            });
        }

        // No real-time search on specialty change - only search on button click
        if (specialtyFilter) {
            specialtyFilter.addEventListener('change', function() {
                // No automatic search - user must click "Find Doctors" button
            });
        }

        // Initialize with default search (load all available doctors on page load)
        searchDoctors('');

        console.log('🏥 MediCare platform loaded successfully!');
    }); // End of DOMContentLoaded
</script>
