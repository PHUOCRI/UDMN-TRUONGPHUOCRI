/**
 * Modern Header JavaScript
 * Handles mobile menu, search overlay, and smooth interactions
 */

(function($) {
    'use strict';

    // Wait for DOM to be ready
    $(document).ready(function() {
        
        // Mobile Menu Toggle
        const menuToggle = $('.menu-toggle');
        const mobileMenu = $('.mobile-menu');
        const mobileMenuOverlay = $('.mobile-menu-overlay');
        const mobileMenuClose = $('.mobile-menu-close');
        const hamburger = $('.hamburger');

        // Open mobile menu
        menuToggle.on('click', function(e) {
            e.preventDefault();
            mobileMenu.addClass('active');
            mobileMenuOverlay.addClass('active');
            $('body').addClass('menu-open');
            hamburger.addClass('active');
        });

        // Close mobile menu
        function closeMobileMenu() {
            mobileMenu.removeClass('active');
            mobileMenuOverlay.removeClass('active');
            $('body').removeClass('menu-open');
            hamburger.removeClass('active');
        }

        mobileMenuClose.on('click', closeMobileMenu);
        mobileMenuOverlay.on('click', closeMobileMenu);

        // Close mobile menu when clicking on menu links
        $('.mobile-menu-items a').on('click', function() {
            closeMobileMenu();
        });

        // Search Toggle
        const searchToggle = $('.search-toggle');
        const searchOverlay = $('.search-overlay');
        const searchClose = $('.search-close');

        // Open search overlay
        searchToggle.on('click', function(e) {
            e.preventDefault();
            searchOverlay.addClass('active');
            $('body').addClass('search-open');
            // Focus on search input after animation
            setTimeout(function() {
                searchOverlay.find('input[type="search"]').focus();
            }, 300);
        });

        // Close search overlay
        function closeSearchOverlay() {
            searchOverlay.removeClass('active');
            $('body').removeClass('search-open');
        }

        searchClose.on('click', closeSearchOverlay);
        searchOverlay.on('click', function(e) {
            if (e.target === this) {
                closeSearchOverlay();
            }
        });

        // Close search on Escape key
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27) { // Escape key
                closeSearchOverlay();
                closeMobileMenu();
            }
        });

        // Sticky Header
        const header = $('.site-header');
        let lastScrollTop = 0;
        let ticking = false;

        function updateHeader() {
            const scrollTop = $(window).scrollTop();
            
            if (scrollTop > 100) {
                header.addClass('sticky');
            } else {
                header.removeClass('sticky');
            }
            
            lastScrollTop = scrollTop;
            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateHeader);
                ticking = true;
            }
        }

        $(window).on('scroll', requestTick);

        // Smooth scrolling for anchor links
        $('a[href*="#"]:not([href="#"])').on('click', function(e) {
            const target = $(this.hash);
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 800, 'easeInOutCubic');
            }
        });

        // Add easing function
        $.easing.easeInOutCubic = function (x, t, b, c, d) {
            if ((t/=d/2) < 1) return c/2*t*t*t + b;
            return c/2*((t-=2)*t*t + 2) + b;
        };

        // Header Actions Hover Effects
        $('.header-actions .search-toggle, .header-actions .menu-toggle').hover(
            function() {
                $(this).addClass('hover');
            },
            function() {
                $(this).removeClass('hover');
            }
        );

        // Cart Animation
        $('.cart-contents').on('click', function() {
            $(this).addClass('clicked');
            setTimeout(() => {
                $(this).removeClass('clicked');
            }, 200);
        });

        // Mobile Menu Item Animation
        $('.mobile-menu-items li').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
        });

        // Dropdown Menu Functionality - Hover based
        $('.primary-menu li.has-dropdown').hover(
            function() {
                // Mouse enter
                $(this).addClass('hover');
            },
            function() {
                // Mouse leave
                $(this).removeClass('hover');
            }
        );
        
        // Click functionality for mobile/touch devices
        $('.primary-menu li.has-dropdown > a').on('click', function(e) {
            if ($(window).width() <= 768) {
                e.preventDefault();
                const $parent = $(this).closest('li');
                const $submenu = $parent.find('.sub-menu');
                const $icon = $(this).find('i');
                
                // Close other open dropdowns
                $('.primary-menu > li').not($parent).removeClass('active');
                $('.primary-menu .sub-menu').not($submenu).removeClass('active');
                $('.primary-menu .dropdown-icon').not($icon).removeClass('rotated');
                
                // Toggle current dropdown
                $parent.toggleClass('active');
                $submenu.toggleClass('active');
                $icon.toggleClass('rotated');
            }
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.primary-menu > li').length) {
                $('.primary-menu > li').removeClass('active');
                $('.primary-menu .sub-menu').removeClass('active');
                $('.primary-menu .dropdown-icon').removeClass('rotated');
            }
        });
        
        // Keyboard navigation for dropdown
        $('.primary-menu > li').on('keydown', function(e) {
            if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                e.preventDefault();
                const $parent = $(this);
                const $submenu = $parent.find('.sub-menu');
                const $icon = $parent.find('.dropdown-icon');
                
                // Close other open dropdowns
                $('.primary-menu > li').not($parent).removeClass('active');
                $('.primary-menu .sub-menu').not($submenu).removeClass('active');
                $('.primary-menu .dropdown-icon').not($icon).removeClass('rotated');
                
                // Toggle current dropdown
                $parent.toggleClass('active');
                $submenu.toggleClass('active');
                $icon.toggleClass('rotated');
            }
        });

        // Search Form Enhancement
        const searchForm = $('.search-form-wrapper form');
        if (searchForm.length) {
            searchForm.on('submit', function(e) {
                const searchInput = $(this).find('input[type="search"]');
                if (searchInput.val().trim() === '') {
                    e.preventDefault();
                    searchInput.focus();
                    return false;
                }
            });
        }

        // Keyboard Navigation for Mobile Menu
        $('.mobile-menu-items a').on('keydown', function(e) {
            const items = $('.mobile-menu-items a');
            const currentIndex = items.index(this);
            
            switch(e.keyCode) {
                case 38: // Up arrow
                    e.preventDefault();
                    if (currentIndex > 0) {
                        items.eq(currentIndex - 1).focus();
                    }
                    break;
                case 40: // Down arrow
                    e.preventDefault();
                    if (currentIndex < items.length - 1) {
                        items.eq(currentIndex + 1).focus();
                    }
                    break;
                case 9: // Tab
                    if (e.shiftKey && currentIndex === 0) {
                        e.preventDefault();
                        mobileMenuClose.focus();
                    } else if (!e.shiftKey && currentIndex === items.length - 1) {
                        e.preventDefault();
                        // Focus on first focusable element in mobile menu
                        $('.mobile-menu a, .mobile-menu button').first().focus();
                    }
                    break;
            }
        });

        // Initialize animations on load
        setTimeout(function() {
            $('.site-header').addClass('loaded');
        }, 100);

        // Handle window resize
        $(window).on('resize', function() {
            // Close mobile menu on desktop
            if ($(window).width() > 768) {
                closeMobileMenu();
            }
        });

        // Add loading class to body
        $('body').addClass('header-loaded');
        
        // VJLINK Image Slider Functionality
        let currentSlideIndex = 0;
        const slides = $('.slide');
        const totalSlides = slides.length;
        let slideInterval;
        
        // Auto slide function
        function autoSlide() {
            if (totalSlides > 1) {
                slideInterval = setInterval(() => {
                    changeSlide(1);
                }, 5000); // Change slide every 5 seconds
            }
        }
        
        // Change slide function
        window.changeSlide = function(direction) {
            if (totalSlides <= 1) return;
            
            // Clear existing interval
            clearInterval(slideInterval);
            
            // Remove active class from current slide
            slides.eq(currentSlideIndex).removeClass('active');
            
            // Calculate new slide index
            currentSlideIndex += direction;
            
            // Handle slide boundaries
            if (currentSlideIndex >= totalSlides) {
                currentSlideIndex = 0;
            } else if (currentSlideIndex < 0) {
                currentSlideIndex = totalSlides - 1;
            }
            
            // Add active class to new slide
            slides.eq(currentSlideIndex).addClass('active');
            
            // Update dots
            $('.dot').removeClass('active');
            $('.dot').eq(currentSlideIndex).addClass('active');
            
            // Restart auto slide
            autoSlide();
        };
        
        // Go to specific slide
        window.currentSlide = function(slideNumber) {
            if (totalSlides <= 1) return;
            
            clearInterval(slideInterval);
            
            // Remove active class from current slide
            slides.eq(currentSlideIndex).removeClass('active');
            
            // Set new slide index
            currentSlideIndex = slideNumber - 1;
            
            // Add active class to new slide
            slides.eq(currentSlideIndex).addClass('active');
            
            // Update dots
            $('.dot').removeClass('active');
            $('.dot').eq(currentSlideIndex).addClass('active');
            
            // Restart auto slide
            autoSlide();
        };
        
        // Pause auto slide on hover
        $('.image-slider-section').hover(
            function() {
                clearInterval(slideInterval);
            },
            function() {
                autoSlide();
            }
        );
        
        // Start auto slide
        autoSlide();
        
        // Touch/swipe support for mobile
        let startX = 0;
        let endX = 0;
        
        $('.slider-wrapper').on('touchstart', function(e) {
            startX = e.originalEvent.touches[0].clientX;
        });
        
        $('.slider-wrapper').on('touchend', function(e) {
            endX = e.originalEvent.changedTouches[0].clientX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const threshold = 50; // Minimum swipe distance
            const diff = startX - endX;
            
            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    // Swipe left - next slide
                    changeSlide(1);
                } else {
                    // Swipe right - previous slide
                    changeSlide(-1);
                }
            }
        }
 
    });

    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
        .site-header {
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .site-header.loaded {
            opacity: 1;
            transform: translateY(0);
        }
        
        .mobile-menu-items li {
            opacity: 0;
            transform: translateX(20px);
            animation: slideInRight 0.3s ease forwards;
        }
        
        @keyframes slideInRight {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .cart-contents.clicked {
            transform: scale(0.95);
            transition: transform 0.2s ease;
        }
        
        .hamburger.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .hamburger.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        
        .header-loaded .top-bar {
            animation: slideDown 0.6s ease;
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }
        
        body.menu-open {
            overflow: hidden;
        }
        
        body.search-open {
            overflow: hidden;
        }
    `;
    document.head.appendChild(style);

})(jQuery);