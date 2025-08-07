/**
 * SBS Portal Theme JavaScript
 * 
 * @package SBS_Portal
 * @version 1.0.0
 */

(function ($) {
    'use strict';

    // DOM Ready
    $(document).ready(function () {
        initFAQAccordion();
        initLanguageDropdown();
        initFloatButtons();
        initPopup();
        initMobileMenu();
        initScrollEffects();
        initPortalEffects();
        initFloatingAnimation();
        initFormValidation();
        initBannerCarousel();
    });

    /**
     * Initialize FAQ Accordion
     */
    function initFAQAccordion() {
        // Initialize expanded groups on page load (headers are expanded by default)
        $('.faq-group.expanded').each(function () {
            const $group = $(this);
            const $content = $group.find('.faq-group-content');

            // Show content for expanded groups
            $content.show();
        });

        // Initialize collapsed items on page load (items are collapsed by default)
        $('.faq-item:not(.expanded)').each(function () {
            const $item = $(this);
            const $answer = $item.find('.faq-answer');

            // Hide answer for collapsed items
            $answer.hide();
        });

        // FAQ Group Toggle
        $('.faq-group-header').on('click', function () {
            const $group = $(this).closest('.faq-group');
            const $content = $group.find('.faq-group-content');
            const $iconContainer = $group.find('.group-toggle .icon-container');

            // Toggle expanded state
            $group.toggleClass('expanded');

            // Show/hide content
            $content.slideToggle(300);

            // Update icon container
            if ($group.hasClass('expanded')) {
                $iconContainer.removeClass('collapsed').addClass('expanded');
                $iconContainer.html('<img src="' + getTemplateDirectoryUri() + '/assets/images/icons/icon-minus.svg" alt="Minus" />');
            } else {
                $iconContainer.removeClass('expanded').addClass('collapsed');
                $iconContainer.html('<img src="' + getTemplateDirectoryUri() + '/assets/images/icons/icon-plus.svg" alt="Plus" />');
            }
        });

        // FAQ Item Toggle
        $('.faq-question').on('click', function () {
            const $item = $(this).closest('.faq-item');
            const $answer = $item.find('.faq-answer');
            const $iconContainer = $item.find('.question-toggle .icon-container');

            // Toggle expanded state
            $item.toggleClass('expanded');

            // Show/hide answer
            $answer.slideToggle(300);

            // Update icon container
            if ($item.hasClass('expanded')) {
                $iconContainer.removeClass('collapsed').addClass('expanded');
                $iconContainer.html('<img src="' + getTemplateDirectoryUri() + '/assets/images/icons/icon-minus.svg" alt="Minus" />');
            } else {
                $iconContainer.removeClass('expanded').addClass('collapsed');
                $iconContainer.html('<img src="' + getTemplateDirectoryUri() + '/assets/images/icons/icon-plus.svg" alt="Plus" />');
            }
        });
    }

    /**
     * Initialize Language Dropdown
     */
    function initLanguageDropdown() {
        $('.language-dropdown').on('click', function (e) {
            e.stopPropagation();
            $(this).toggleClass('active');

            if ($(this).hasClass('active')) {
                $('.language-options').slideDown(200);
            } else {
                $('.language-options').slideUp(200);
            }
        });

        $('.language-option').on('click', function (e) {
            e.preventDefault();
            const selectedLang = $(this).text();
            $('.current-language').text(selectedLang);
            $('.language-dropdown').removeClass('active');
            $('.language-options').slideUp(200);
        });

        // Close dropdown when clicking outside
        $(document).on('click', function () {
            $('.language-dropdown').removeClass('active');
            $('.language-options').slideUp(200);
        });
    }

    /**
     * Initialize Float Buttons
     */
    function initFloatButtons() {
        // Back to top button
        $('#back-to-top, .back-to-top').on('click', function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 800);
        });

        // Show/hide back to top based on scroll
        $(window).on('scroll', function () {
            if ($(this).scrollTop() > 500) {
                $('.back-to-top').fadeIn();
            } else {
                $('.back-to-top').fadeOut();
            }
        });

        // Float chat button
        $('.float-chat').on('click', function () {
            // Add chat functionality here
            console.log('Chat button clicked');
        });

        // Float contact button
        $('.float-contact').on('click', function () {
            // Add contact functionality here
            window.location.href = '/contact';
        });
    }

    /**
     * Initialize Popup/Modal
     */
    function initPopup() {
        // Show popup (you can trigger this based on conditions)
        function showPopup() {
            $('#sbs-popup').fadeIn(300);
            $('body').addClass('popup-open');
        }

        // Close popup
        $('#popup-close, .sbs-popup-overlay').on('click', function (e) {
            if (e.target === this) {
                $('#sbs-popup').fadeOut(300);
                $('body').removeClass('popup-open');
            }
        });

        // Escape key to close popup
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') {
                $('#sbs-popup').fadeOut(300);
                $('body').removeClass('popup-open');
            }
        });

        // Auto show popup after 5 seconds (example)
        // setTimeout(showPopup, 5000);
    }

    /**
     * Initialize Mobile Menu
     */
    function initMobileMenu() {
        $('.mobile-menu-button, .menu-button').on('click', function () {
            $(this).toggleClass('active');
            $('.nav-links').slideToggle(300);
        });

        // Close mobile menu when window is resized
        $(window).on('resize', function () {
            if ($(window).width() > 768) {
                $('.nav-links').show();
                $('.mobile-menu-button, .menu-button').removeClass('active');
            }
        });
    }

    /**
     * Initialize Portal Effects
     */
    function initPortalEffects() {
        // Portal box hover effects
        $('.portal-box').hover(
            function () {
                $(this).addClass('hovered');
            },
            function () {
                $(this).removeClass('hovered');
            }
        );

        // Menu button effects
        $('.menu-button').on('click', function () {
            // Add menu functionality here
            console.log('Menu clicked');
        });

        // Portal box click effects
        $('.portal-box').on('click', function () {
            const title = $(this).find('.box-title').text();
            console.log('Portal box clicked:', title);
            // Add navigation logic here
        });
    }

    /**
     * Initialize Floating Animation
     */
    function initFloatingAnimation() {
        // Enhanced floating animation for hero circle
        const $heroCircle = $('.hero-circle-image');

        // Mouse movement parallax effect
        $(window).on('mousemove', function (e) {
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;

            const translateX = (mouseX - 0.5) * 20;
            const translateY = (mouseY - 0.5) * 20;

            $heroCircle.css({
                'transform': `translate(${translateX}px, ${translateY}px)`
            });
        });

        // Add rotation on scroll
        $(window).on('scroll', function () {
            const scrollTop = $(this).scrollTop();
            const rotation = scrollTop * 0.1;

            $heroCircle.find('img').css({
                'transform': `rotate(${rotation}deg)`
            });
        });

        // Add scale effect on window resize
        $(window).on('resize', function () {
            const windowWidth = $(this).width();
            const scale = Math.min(1, windowWidth / 1440);

            if (windowWidth < 768) {
                $heroCircle.css({
                    'transform': `scale(${scale * 0.6})`
                });
            } else {
                $heroCircle.css({
                    'transform': 'scale(1)'
                });
            }
        });
    }

    /**
     * Initialize Scroll Effects
     */
    function initScrollEffects() {
        // Smooth scrolling for anchor links
        $('a[href^="#"]').on('click', function (e) {
            e.preventDefault();
            const target = $(this.getAttribute('href'));
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 1000, 'easeInOutCubic');
            }
        });

        // Enhanced scroll events with throttling
        let scrollTimeout;
        $(window).on('scroll', function () {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }

            scrollTimeout = setTimeout(function () {
                handleScrollEffects();
            }, 10);
        });

        function handleScrollEffects() {
            const scrollTop = $(window).scrollTop();

            // Remove parallax effect to prevent section overlapping
            // $('.sbs-hero-section').css({
            //     'transform': `translateY(${scrollTop * parallaxSpeed}px)`
            // });

            // Portal boxes entrance animation
            $('.portal-box:not(.in-view)').each(function () {
                if (isElementInViewport($(this), 0.3)) {
                    $(this).addClass('in-view');
                }
            });

            // Sections entrance animation
            $('.sbs-gallery-section:not(.in-view), .sbs-blog-section:not(.in-view), .sbs-faq-section:not(.in-view)').each(function () {
                if (isElementInViewport($(this), 0.2)) {
                    $(this).addClass('in-view');
                }
            });

            // Gallery items staggered animation
            $('.gallery-item:not(.in-view)').each(function () {
                if (isElementInViewport($(this), 0.4)) {
                    $(this).addClass('in-view');
                }
            });
        }

        // Helper function to check if element is in viewport
        function isElementInViewport($element, threshold = 0.3) {
            const elementTop = $element.offset().top;
            const elementBottom = elementTop + $element.outerHeight();
            const viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();
            const triggerPoint = viewportBottom - ($(window).height() * threshold);

            return elementTop < triggerPoint && elementBottom > viewportTop;
        }

        // Fade in animation for elements
        function checkFadeIn() {
            $('.fade-in').each(function () {
                const elementTop = $(this).offset().top;
                const elementBottom = elementTop + $(this).outerHeight();
                const viewportTop = $(window).scrollTop();
                const viewportBottom = viewportTop + $(window).height();

                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $(this).addClass('fade-in-active');
                }
            });
        }

        $(window).on('scroll', checkFadeIn);
        checkFadeIn(); // Check on load
    }

    /**
     * Get Template Directory URI
     */
    function getTemplateDirectoryUri() {
        return window.sbsThemeData ? window.sbsThemeData.templateDirectoryUri : '/wp-content/themes/sbs-portal';
    }

    /**
     * Get SVG icon
     */
    function getIcon(iconName) {
        const icons = {
            plus: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 6V18M6 12H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
            minus: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M6 12H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
            'chevron-down': '<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1"/></svg>'
        };

        return icons[iconName] || '';
    }

    /**
     * AJAX Blog Loading (if needed)
     */
    function loadMoreBlogs() {
        const $loadMoreBtn = $('.load-more-blogs');

        $loadMoreBtn.on('click', function (e) {
            e.preventDefault();

            const page = $(this).data('page') || 1;
            const nextPage = page + 1;

            $.ajax({
                url: sbs_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'load_more_blogs',
                    page: nextPage,
                    nonce: sbs_ajax.nonce
                },
                beforeSend: function () {
                    $loadMoreBtn.text('読み込み中...');
                },
                success: function (response) {
                    if (response.success) {
                        $('.blog-posts-grid').append(response.data.html);
                        $loadMoreBtn.data('page', nextPage);

                        if (!response.data.has_more) {
                            $loadMoreBtn.hide();
                        }
                    }
                    $loadMoreBtn.text('もっと見る');
                },
                error: function () {
                    $loadMoreBtn.text('エラーが発生しました');
                }
            });
        });
    }

    /**
     * Initialize Banner Carousel
     */
    function initBannerCarousel() {
        const $carousel = $('.banner-carousel-track');
        const $bannerItems = $('.banner-item');

        if ($carousel.length === 0) return;

        // Pause animation on hover
        $carousel.on('mouseenter', function () {
            $(this).css('animation-play-state', 'paused');
        });

        $carousel.on('mouseleave', function () {
            $(this).css('animation-play-state', 'running');
        });

        // Banner item click handlers
        $bannerItems.on('click', function () {
            const $item = $(this);

            // Add click animation
            $item.addClass('clicked');
            setTimeout(() => {
                $item.removeClass('clicked');
            }, 200);

            // Handle different banner actions
            const bannerIndex = $item.index() % 3; // Get original banner index (0, 1, or 2)

            switch (bannerIndex) {
                case 0: // Gallery Image 1
                    console.log('Opening gallery image 1 details');
                    // Add your gallery popup or navigation logic here
                    break;
                case 1: // Gallery Image 2
                    console.log('Opening gallery image 2 details');
                    // Add navigation to gallery image 2 page
                    break;
                case 2: // Gallery Image 3
                    console.log('Opening gallery image 3 details');
                    // Add navigation to gallery image 3 page
                    break;
            }
        });

        // Add touch/swipe support for mobile
        let startX = 0;
        let currentX = 0;
        let isDragging = false;

        $carousel.on('touchstart', function (e) {
            startX = e.originalEvent.touches[0].clientX;
            isDragging = true;
            $(this).css('animation-play-state', 'paused');
        });

        $carousel.on('touchmove', function (e) {
            if (!isDragging) return;
            currentX = e.originalEvent.touches[0].clientX;
            const diff = startX - currentX;

            if (Math.abs(diff) > 50) {
                // Swipe detected
                if (diff > 0) {
                    // Swipe left - speed up animation
                    $(this).css('animation-duration', '15s');
                } else {
                    // Swipe right - slow down animation
                    $(this).css('animation-duration', '45s');
                }
            }
        });

        $carousel.on('touchend', function () {
            isDragging = false;
            setTimeout(() => {
                $(this).css('animation-play-state', 'running');
                $(this).css('animation-duration', '30s');
            }, 1000);
        });

        // Add keyboard navigation
        $(document).on('keydown', function (e) {
            if (e.key === 'ArrowLeft') {
                $carousel.css('animation-play-state', 'paused');
                setTimeout(() => {
                    $carousel.css('animation-play-state', 'running');
                }, 2000);
            } else if (e.key === 'ArrowRight') {
                $carousel.css('animation-duration', '15s');
                setTimeout(() => {
                    $carousel.css('animation-duration', '30s');
                }, 1000);
            }
        });

        // Performance optimization: Reduce animation on mobile
        if (window.innerWidth <= 768) {
            $carousel.css('animation-duration', '45s');
        }

        // Add intersection observer for performance
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                } else {
                    entry.target.style.animationPlayState = 'paused';
                }
            });
        }, { threshold: 0.1 });

        observer.observe($carousel[0]);
    }

    /**
     * Form Validation
     */
    function initFormValidation() {
        $('form').on('submit', function (e) {
            let isValid = true;

            // Remove previous error messages
            $('.field-error').remove();

            // Check required fields
            $(this).find('[required]').each(function () {
                const $field = $(this);
                const value = $field.val().trim();

                if (!value) {
                    isValid = false;
                    $field.after('<span class="field-error">この項目は必須です</span>');
                }
            });

            // Email validation
            $(this).find('[type="email"]').each(function () {
                const $field = $(this);
                const email = $field.val();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email && !emailRegex.test(email)) {
                    isValid = false;
                    $field.after('<span class="field-error">有効なメールアドレスを入力してください</span>');
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

})(jQuery);