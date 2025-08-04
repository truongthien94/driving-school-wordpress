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
    });

    /**
     * Initialize FAQ Accordion
     */
    function initFAQAccordion() {
        // FAQ Group Toggle
        $('.faq-group-header').on('click', function () {
            const $group = $(this).closest('.faq-group');
            const $content = $group.find('.faq-group-content');
            const $icon = $group.find('.group-toggle .toggle-icon');

            // Toggle expanded state
            $group.toggleClass('expanded');

            // Update icon
            if ($group.hasClass('expanded')) {
                $icon.html(getIcon('minus'));
            } else {
                $icon.html(getIcon('plus'));
            }
        });

        // FAQ Item Toggle
        $('.faq-question').on('click', function () {
            const $item = $(this).closest('.faq-item');
            const $answer = $item.find('.faq-answer');
            const $icon = $item.find('.question-toggle');

            // Toggle expanded state
            $item.toggleClass('expanded');

            // Show/hide answer
            $answer.slideToggle(300);

            // Update icon
            if ($item.hasClass('expanded')) {
                $icon.html(getIcon('minus'));
            } else {
                $icon.html(getIcon('plus'));
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
                }, 800);
            }
        });

        // Parallax effect for hero section
        $(window).on('scroll', function () {
            const scrollTop = $(this).scrollTop();
            const parallaxSpeed = 0.5;

            $('.sbs-hero-section').css({
                'transform': `translateY(${scrollTop * parallaxSpeed}px)`
            });

            // Portal boxes entrance animation
            $('.portal-box').each(function () {
                const elementTop = $(this).offset().top;
                const elementBottom = elementTop + $(this).outerHeight();
                const viewportTop = $(window).scrollTop();
                const viewportBottom = viewportTop + $(window).height();

                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $(this).addClass('in-view');
                }
            });
        });

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