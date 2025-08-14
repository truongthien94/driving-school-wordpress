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
        initLanguageSwitcher();
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
        // Normalize initial visibility for all groups based on .expanded
        $('.faq-group').each(function () {
            const $group = $(this);
            const $content = $group.children('.faq-group-content');
            const isExpanded = $group.hasClass('expanded');
            if (isExpanded) {
                $content.show();
            } else {
                $content.hide();
            }
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
            const $content = $group.children('.faq-group-content');
            const $iconContainer = $group.find('.group-toggle .icon-container');

            const willExpand = !$group.hasClass('expanded');
            $group.toggleClass('expanded', willExpand);
            $(this).attr('aria-expanded', willExpand ? 'true' : 'false');
            $content.attr('aria-hidden', willExpand ? 'false' : 'true');

            // Stop queued animations and toggle
            $content.stop(true, true).slideToggle(300);

            // Update icon container
            if (willExpand) {
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
     * Initialize Language Switcher
     */
    function initLanguageSwitcher() {
        // Handle language option clicks
        $('.language-option').on('click', function (e) {
            e.preventDefault();

            const languageCode = $(this).data('language');
            const languageName = $(this).find('.lang-name').text();
            const languageFlag = $(this).find('.flag-icon').text();

            // Don't switch if same language
            if ($(this).hasClass('active')) {
                return;
            }

            // Show loading state
            const originalText = $(this).text();
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');

            // Send AJAX request to switch language
            $.ajax({
                url: sbsLanguage.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'switch_language',
                    language: languageCode,
                    nonce: sbsLanguage.nonce
                },
                success: function (response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.success) {
                            // Update current language display
                            $('.language-switcher .lang-name').text(languageName);
                            $('.language-switcher .flag-icon').text(languageFlag);
                            $('.language-switcher-mobile .lang-name').text(languageName);
                            $('.language-switcher-mobile .flag-icon').text(languageFlag);

                            // Update active states
                            $('.language-option').removeClass('active');
                            $('.language-option[data-language="' + languageCode + '"]').addClass('active');

                            // Close dropdowns
                            $('.dropdown-menu').removeClass('show');

                            // Reload page to show new language content
                            setTimeout(function () {
                                window.location.reload();
                            }, 300);

                        } else {
                            console.error('Language switch failed:', data.message);
                            alert('Language switch failed. Please try again.');
                        }
                    } catch (e) {
                        console.error('Invalid JSON response:', e);
                        alert('Language switch failed. Please try again.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                    alert('Language switch failed. Please try again.');
                },
                complete: function () {
                    // Restore original text if still on page
                    setTimeout(function () {
                        $('.language-option').each(function () {
                            const $option = $(this);
                            if ($option.find('.spinner-border').length > 0) {
                                const flag = $option.data('flag');
                                const name = $option.data('name');
                                $option.html('<span class="flag-icon">' + flag + '</span><span class="lang-name">' + name + '</span>');
                            }
                        });
                    }, 1000);
                }
            });
        });

        // Close dropdown when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu').removeClass('show');
            }
        });
    }

    /**
     * Initialize Float Buttons
     */
    function initFloatButtons() {
        const $backToTop = $('#back-to-top');
        const $floatButtons = $('.float-buttons');
        const $floatChat = $('#float-chat');
        const $floatContact = $('#float-contact');

        // Show/hide back to top and adjust float buttons and popup position
        $(window).on('scroll', function () {
            const scrollTop = $(this).scrollTop();

            if (scrollTop > 300) {
                // Show back to top and push float buttons up
                $backToTop.addClass('visible');
                $floatButtons.addClass('back-to-top-visible').removeClass('back-to-top-hidden');
                $('#sbs-popup').addClass('back-to-top-visible').removeClass('back-to-top-hidden');
            } else {
                // Hide back to top and push float buttons down
                $backToTop.removeClass('visible');
                $floatButtons.addClass('back-to-top-hidden').removeClass('back-to-top-visible');
                $('#sbs-popup').addClass('back-to-top-hidden').removeClass('back-to-top-visible');
            }
        });

        // Back to top functionality
        $backToTop.on('click', function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 600);
        });

        // Float chat button
        $floatChat.on('click', function () {
            // Add chat functionality here
            console.log('Chat button clicked');
            // You can open a chat widget or redirect to chat page
        });

        // Float contact button
        $floatContact.on('click', function () {
            // Add contact functionality here
            console.log('Contact button clicked');
            // You can open contact form or redirect to contact page
        });
    }

    /**
 * Initialize Popup
 */
    function initPopup() {
        const $popup = $('#sbs-popup');
        const $popupContent = $('#draggable-popup');
        const $popupClose = $('#popup-close');
        const $popupTabs = $('.popup-tab');

        let isDragging = false;
        let startX, startY, startLeft, startTop;

        // Show popup after 3 seconds
        setTimeout(function () {
            $popup.fadeIn(300);
        }, 3000);

        // Draggable functionality
        $popupContent.on('mousedown', function (e) {
            if (e.target.closest('.popup-close, .popup-tab')) return;

            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            startLeft = parseInt($popupContent.css('left')) || 0;
            startTop = parseInt($popupContent.css('top')) || 0;

            $popupContent.addClass('dragging');
            e.preventDefault();
        });

        $(document).on('mousemove', function (e) {
            if (!isDragging) return;

            const deltaX = e.clientX - startX;
            const deltaY = e.clientY - startY;

            $popupContent.css({
                left: startLeft + deltaX + 'px',
                top: startTop + deltaY + 'px'
            });
        });

        $(document).on('mouseup', function () {
            if (isDragging) {
                isDragging = false;
                $popupContent.removeClass('dragging');
            }
        });

        // Close popup
        $popupClose.on('click', function () {
            $popup.fadeOut(300);
        });

        // Tab switching
        $popupTabs.on('click', function () {
            $popupTabs.removeClass('active');
            $(this).addClass('active');
        });
    }

    /**
     * Initialize Mobile Menu and Mega Menu
     */
    function initMobileMenu() {
        // Handle mega menu close button only (Bootstrap handles opening)
        $('.mega-menu-close').on('click', function () {
            $('.menu-button').removeClass('active');
            // Close the offcanvas using Bootstrap method
            const offcanvasElement = document.getElementById('offcanvasNavbar');
            const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
            if (offcanvas) {
                offcanvas.hide();
            }
        });

        // Handle mega menu language switcher
        $('.language-switcher-mega').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const $dropdown = $(this).closest('.mega-language-dropdown');
            $dropdown.find('.dropdown-menu').toggleClass('show');
        });

        // Close language dropdown when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.mega-language-dropdown').length) {
                $('.language-dropdown-menu-mega').removeClass('show');
            }
        });

        // Handle mega menu navigation links
        $('.mega-nav-link, .mega-nav-sublink, .mega-footer-link').on('click', function (e) {
            // Add any navigation handling here
            console.log('Navigation link clicked:', $(this).text());
        });

        // Handle mega menu show/hide events
        $('#offcanvasNavbar').on('show.bs.offcanvas', function () {
            $('body').addClass('mega-menu-open');
            $('.menu-button').addClass('active');
        });

        $('#offcanvasNavbar').on('shown.bs.offcanvas', function () {
            $(this).addClass('show');
        });

        $('#offcanvasNavbar').on('hide.bs.offcanvas', function () {
            $(this).removeClass('show');
        });

        $('#offcanvasNavbar').on('hidden.bs.offcanvas', function () {
            $('.menu-button').removeClass('active');
            $('body').removeClass('mega-menu-open');
        });

        // Handle responsive behavior
        handleMegaMenuResponsive();
    }

    /**
     * Handle Mega Menu Responsive Behavior
     */
    function handleMegaMenuResponsive() {
        function adjustMegaMenuLayout() {
            const $megaMenu = $('.sbs-mega-menu');
            const windowWidth = $(window).width();

            if (windowWidth <= 768) {
                $megaMenu.addClass('mobile-layout');
            } else if (windowWidth <= 1024) {
                $megaMenu.removeClass('mobile-layout').addClass('tablet-layout');
            } else {
                $megaMenu.removeClass('mobile-layout tablet-layout');
            }
        }

        // Initial adjustment
        adjustMegaMenuLayout();

        // Adjust on window resize
        $(window).on('resize', debounce(adjustMegaMenuLayout, 250));
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

        // Click navigation handled via anchors in template

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

    // Blog List Page Specific Functionality
    if ($('.sbs-blog-list').length > 0) {
        // Float buttons functionality
        $('.float-chat').on('click', function () {
            // Add chat functionality here
            console.log('Chat button clicked');
        });

        $('.float-contact').on('click', function () {
            // Add contact functionality here  
            console.log('Contact button clicked');
        });

        // Enhanced back to top for blog list
        $('.blog-list-back-to-top').on('click', function () {
            $('html, body').animate({
                scrollTop: 0
            }, 500);
        });

        // Pagination button hover effects
        $('.pagination-btn:not(.disabled)').hover(
            function () {
                $(this).css('transform', 'scale(1.05)');
            },
            function () {
                $(this).css('transform', 'scale(1)');
            }
        );

        // Blog card enhanced hover effects
        $('.blog-card-large').hover(
            function () {
                $(this).css({
                    'transform': 'translateY(-4px)',
                    'box-shadow': '0px 60px 100px -50px rgba(15, 15, 15, 0.2)'
                });
            },
            function () {
                $(this).css({
                    'transform': 'translateY(-2px)',
                    'box-shadow': '0px 50px 80px -40px rgba(15, 15, 15, 0.15)'
                });
            }
        );

        // Show/hide floating elements on scroll
        $(window).scroll(function () {
            const scrollTop = $(this).scrollTop();

            if (scrollTop > 300) {
                $('.blog-list-floating-elements').addClass('visible');
            } else {
                $('.blog-list-floating-elements').removeClass('visible');
            }
        });

        // Smooth scroll for breadcrumb links
        $('.breadcrumb-link').on('click', function (e) {
            const href = $(this).attr('href');
            if (href === '/' || href.includes('home')) {
                // Let it navigate normally to home page
                return true;
            }
        });

        // Banner carousel functionality for blog list
        $('.banner-item').on('click', function () {
            $(this).addClass('clicked');
            setTimeout(() => {
                $(this).removeClass('clicked');
            }, 100);
        });

        // Pause carousel on hover
        $('.banner-carousel-track').hover(
            function () {
                $(this).css('animation-play-state', 'paused');
            },
            function () {
                $(this).css('animation-play-state', 'running');
            }
        );
    }

    /**
     * Utility function to debounce function calls
     * @param {Function} func - Function to debounce
     * @param {number} wait - Wait time in milliseconds
     * @returns {Function} - Debounced function
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Helper function to get template directory URI
     * @returns {string} - Template directory URI
     */
    function getTemplateDirectoryUri() {
        // Fallback for template directory URI
        return window.sbsPortalData?.templateUrl || '/wp-content/themes/sbs-portal';
    }

})(jQuery);