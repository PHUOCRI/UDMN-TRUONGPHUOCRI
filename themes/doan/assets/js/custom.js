/**
 * Custom JavaScript for Du Lịch Việt Nhật Theme
 */

jQuery(document).ready(function($) {
    'use strict';

    // Back to Top Button
    var $backToTop = $('#back-to-top');
    
    // Show/hide back to top button on scroll
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 300) {
            $backToTop.addClass('visible');
        } else {
            $backToTop.removeClass('visible');
        }
    });
    
    // Smooth scroll to top
    $backToTop.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 800, 'easeInOutExpo');
        return false;
    });

    // Add animation class to elements when they come into view
    function animateOnScroll() {
        $('.animate-on-scroll').each(function() {
            var elementPos = $(this).offset().top;
            var topOfWindow = $(window).scrollTop();
            var windowHeight = $(window).height();
            
            if (elementPos < (topOfWindow + windowHeight - 100)) {
                $(this).addClass('animated fadeInUp');
            }
        });
    }
    
    // Run once on page load
    animateOnScroll();
    
    // Run on scroll
    $(window).on('scroll', function() {
        animateOnScroll();
    });

    // Mobile menu toggle
    $('.menu-toggle').on('click', function() {
        $('.main-navigation').slideToggle(300);
        $(this).toggleClass('active');
    });

    // Close mobile menu when clicking outside
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.site-header').length) {
            $('.main-navigation').slideUp(300);
            $('.menu-toggle').removeClass('active');
        }
    });

    // Add dropdown toggle for mobile
    $('.menu-item-has-children > a').after('<span class="dropdown-toggle"><i class="fas fa-chevron-down"></i></span>');
    
    $('.dropdown-toggle').on('click', function() {
        $(this).toggleClass('active').siblings('.sub-menu').slideToggle(200);
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Add smooth scrolling to all links
    $('a[href*="#"]:not([href="#"])').on('click', function() {
        if (location.pathname.replace(/^\//,'') === this.pathname.replace(/^\//,'') && 
            location.hostname === this.hostname) {
            
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 1000, 'easeInOutExpo');
                return false;
            }
        }
    });

    // Add active class to current menu item
    var currentLocation = location.href;
    $('.main-navigation a').each(function() {
        if (this.href === currentLocation) {
            $(this).addClass('current-menu-item');
        }
    });

    // Initialize any sliders
    if ($.fn.slick) {
        $('.testimonial-slider').slick({
            dots: true,
            arrows: false,
            autoplay: true,
            autoplaySpeed: 5000,
            pauseOnHover: true,
            adaptiveHeight: true
        });
    }

    // Lazy load images
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('img.lazyload');
        images.forEach(img => {
            img.src = img.dataset.src;
        });
    } else {
        // Fallback for browsers that don't support lazy loading
        let script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
        document.body.appendChild(script);
    }

    // Add no-touch class to body if device doesn't support touch
    if (!('ontouchstart' in window || navigator.msMaxTouchPoints)) {
        $('body').addClass('no-touch');
    }

    // Handle preloader
    $(window).on('load', function() {
        $('.preloader').fadeOut('slow');
    });

    // Add focus class to form elements
    $('input, textarea, select').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });
});
