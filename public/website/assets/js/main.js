(function ($) {
    "use strict";

    // Check direction
    var isRTL = document.documentElement.dir === 'rtl';

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();


    // Initiate the wowjs
    new WOW().init();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 45) {
            $('.nav-bar').addClass('sticky-top');
        } else {
            $('.nav-bar').removeClass('sticky-top');
        }
    });

    // Mobile Menu Popup Management
    const navbarToggler = document.getElementById('navbarToggler');
    const navbarCollapse = document.getElementById('navbarCollapse');
    const navbarOverlay = document.getElementById('navbarOverlay');

    if (navbarToggler && navbarCollapse && navbarOverlay) {
        // Open menu
        navbarToggler.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const isOpen = navbarCollapse.classList.contains('show');

            if (isOpen) {
                closeNavMenu();
            } else {
                openNavMenu();
            }
        });

        function openNavMenu() {
            navbarCollapse.classList.add('show');
            navbarOverlay.classList.add('show');
            navbarToggler.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeNavMenu() {
            navbarCollapse.classList.remove('show');
            navbarOverlay.classList.remove('show');
            navbarToggler.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close menu when clicking overlay
        navbarOverlay.addEventListener('click', function () {
            closeNavMenu();
        });

        // Close menu when clicking close button (::before)
        navbarCollapse.addEventListener('click', function (e) {
            if (e.target === this && window.innerWidth <= 991.98) {
                const rect = this.getBoundingClientRect();
                const isRTL = document.documentElement.dir === 'rtl';
                const closeButtonArea = isRTL ?
                    (e.clientX > rect.right - 60 && e.clientY < rect.top + 60) :
                    (e.clientX < rect.left + 60 && e.clientY < rect.top + 60);

                if (closeButtonArea) {
                    closeNavMenu();
                }
            }
        });

        // Close menu when clicking nav links
        const navLinks = navbarCollapse.querySelectorAll('.nav-link:not(.dropdown-toggle)');
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 991.98) {
                    setTimeout(() => closeNavMenu(), 300);
                }
            });
        });

        // Close with ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && navbarCollapse.classList.contains('show')) {
                closeNavMenu();
            }
        });
    }


    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        return false;
    });


    // Header carousel
    $(".header-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        items: 1,
        dots: true,
        loop: true,
        rtl: isRTL,
        nav: true,
        navText: [
            '<i class="bi bi-chevron-left"></i>',
            '<i class="bi bi-chevron-right"></i>'
        ]
    });


    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        margin: 24,
        dots: false,
        loop: true,
        rtl: isRTL,
        nav: true,
        navText: [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsive: {
            0: {
                items: 1
            },
            992: {
                items: 2
            }
        }
    });

    // About carousel with custom dots
    $(".about-carousel").owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        smartSpeed: 1000,
        dots: true,
        nav: false,
        rtl: isRTL,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        dotsContainer: false,
        mouseDrag: true,
        touchDrag: true,
        onInitialized: function () {
            // Add custom animation class on init
            $('.about-carousel .owl-item.active').find('.about-slide').addClass('animated');
        },
        onChanged: function () {
            // Re-trigger animations on slide change
            $('.about-carousel .owl-item').find('.about-slide').removeClass('animated');
        },
        onTranslate: function () {
            // Add animation to new active slide
            setTimeout(function () {
                $('.about-carousel .owl-item.active').find('.about-slide').addClass('animated');
            }, 100);
        }
    });

    // Video Popup Functionality
    const playButton = document.getElementById('playButton');
    const videoPopup = document.getElementById('videoPopup');
    const closeVideo = document.getElementById('closeVideo');
    const videoPlayer = document.getElementById('videoPlayer');
    const videoWrapper = document.getElementById('videoShowcase');

    // Open video popup when clicking play button or video wrapper
    if (playButton && videoPopup && videoPlayer) {
        playButton.addEventListener('click', function (e) {
            e.stopPropagation();
            openVideoPopup();
        });

        videoWrapper.addEventListener('click', function () {
            openVideoPopup();
        });

        // Close video popup
        closeVideo.addEventListener('click', function () {
            closeVideoPopup();
        });

        // Close video when clicking outside
        videoPopup.addEventListener('click', function (e) {
            if (e.target === videoPopup) {
                closeVideoPopup();
            }
        });

        // Close video with ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && videoPopup.classList.contains('active')) {
                closeVideoPopup();
            }
        });

        function openVideoPopup() {
            videoPopup.classList.add('active');
            videoPlayer.play();
            document.body.style.overflow = 'hidden';
        }

        function closeVideoPopup() {
            videoPopup.classList.remove('active');
            videoPlayer.pause();
            videoPlayer.currentTime = 0;
            document.body.style.overflow = '';
        }
    }

    // Brands Carousel
    $(".brands-carousel").owlCarousel({
        items: 6,
        loop: true,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        smartSpeed: 800,
        dots: false,
        nav: false,
        rtl: isRTL,
        margin: 30,
        center: false,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        responsive: {
            0: {
                items: 3,
                margin: 15,
                nav: false
            },
            576: {
                items: 4,
                margin: 20
            },
            768: {
                items: 5,
                margin: 25
            },
            992: {
                items: 6,
                margin: 30
            }
        }
    });

    // Contact Form Handling
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Get form button
            const submitBtn = this.querySelector('.btn-call-back');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.innerHTML = '<span>Sending...</span>';
            submitBtn.disabled = true;

            // Simulate form submission (replace with actual AJAX call)
            setTimeout(function () {
                // Success message
                submitBtn.innerHTML = '<span>✓ Message Sent!</span>';
                submitBtn.style.background = '#28a745';

                // Reset form
                contactForm.reset();

                // Reset button after 3 seconds
                setTimeout(function () {
                    submitBtn.innerHTML = originalText;
                    submitBtn.style.background = '';
                    submitBtn.disabled = false;
                }, 3000);
            }, 1500);
        });

        // Add focus animation to inputs
        const inputs = contactForm.querySelectorAll('.contact-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function () {
                this.parentElement.style.transform = 'translateY(-2px)';
            });

            input.addEventListener('blur', function () {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    }

})(jQuery);
$(document).ready(function () {
    $('.select2').select2();
    $('.select2').next('.select2-container').find('.select2-selection__arrow')
        .addClass('custom-arrow')
        .empty()
        .append('<i class="bi bi-arrow-down-left"></i>');
});

