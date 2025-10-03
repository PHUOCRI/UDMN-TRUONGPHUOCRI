/**
 * Custom JavaScript for Du Lịch Việt Nhật Theme
 */

jQuery(document).ready(function($) {
    'use strict';

    // Back to Top Button
    var $backToTop = $('#back-to-top');
    
    // Show/hide back to top button on scroll (use rAF + passive)
    if ($backToTop.length) {
        var ticking = false;
        function updateBtn(){
            $backToTop.toggleClass('visible', window.pageYOffset > 300);
            ticking = false;
        }
        function onScroll(){ if (!ticking){ requestAnimationFrame(updateBtn); ticking = true; } }
        window.addEventListener('scroll', onScroll, { passive: true });
    }
    
    // Smooth scroll to top
    $backToTop.on('click', function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return false;
    });

    // Animate elements when they come into view - use IntersectionObserver
    (function(){
        var $els = $('.animate-on-scroll');
        if (!$els.length) return;
        if ('IntersectionObserver' in window) {
            var obs = new IntersectionObserver(function(entries, observer){
                entries.forEach(function(entry){
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated','fadeInUp');
                        entry.target.classList.remove('animate-on-scroll');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -5% 0px' });
            $els.each(function(){ obs.observe(this); });
        } else {
            // Fallback
            $els.addClass('animated fadeInUp').removeClass('animate-on-scroll');
        }
    })();

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
                $('html, body').animate({ scrollTop: target.offset().top - 100 }, 1000, 'easeInOutExpo');
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

    if ($.fn.slick) {
        $('.testimonial-slider').slick({
            dots: true,
            arrows: false,
            autoplay: true,
            autoplaySpeed: 5000,
            pauseOnHover: true,
            adaptiveHeight: true
        });

        var $news = $('.news-slider');
        if ($news.length && !$news.hasClass('slick-initialized')) {
            $news.slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: false,
                speed: 350,
                dots: true,
                arrows: true,
                prevArrow: '<button type="button" class="slick-prev" aria-label="Previous">‹</button>',
                nextArrow: '<button type="button" class="slick-next" aria-label="Next">›</button>',
                responsive: [
                    { breakpoint: 1200, settings: { slidesToShow: 3 } },
                    { breakpoint: 992,  settings: { slidesToShow: 2 } },
                    { breakpoint: 576,  settings: { slidesToShow: 1 } }
                ]
            });
        }
    }

    // Native lazy loading already handled elsewhere
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('img.lazyload');
        images.forEach(img => { img.src = img.dataset.src; });
    } else {
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

    (function(){
        var $wrap = $('.destinations-gallery');
        if (!$wrap.length) return;
        function setContainerHeight($g){
            var $container = $g.find('.gallery-panels');
            var $active = $container.find('.gallery-panel.active');
            if ($active.length){
                // batch write
                requestAnimationFrame(function(){
                    $container.height($active.outerHeight(true));
                });
            }
        }
        $wrap.each(function(){
            var $g = $(this);
            var $tabs = $g.find('.gallery-tab');
            var $panels = $g.find('.gallery-panel');
            if ($tabs.length && $panels.length){
                var $firstTab = $tabs.filter('.active').eq(0);
                if (!$firstTab.length) { $firstTab = $tabs.eq(0); }
                var firstTarget = $firstTab.data('target');
                $tabs.removeClass('active');
                $panels.stop(true,true).hide().removeClass('active');
                $firstTab.addClass('active');
                $g.find('#'+firstTarget).stop(true,true).show().addClass('active');
                var $firstPanel = $g.find('#'+firstTarget);
                setTimeout(function(){ setContainerHeight($g); }, 50);
                $firstPanel.find('img').each(function(){
                    if (this.complete) return;
                    $(this).one('load', function(){ setContainerHeight($g); });
                });
            }
        });

        $(document).on('click', '.gallery-tab', function(e){
            e.preventDefault();
            var $btn = $(this);
            var $g = $btn.closest('.destinations-gallery');
            var target = $btn.data('target');
            if (!target) return;
            var $container = $g.find('.gallery-panels');
            var $target = $g.find('#'+target);
            if (!$target.length) return;
            $g.find('.gallery-tab').removeClass('active');
            $btn.addClass('active');
            var $panels = $container.find('.gallery-panel');
            $panels.stop(true,true).hide().removeClass('active');
            $target.stop(true,true).fadeIn(250).addClass('active');
            setTimeout(function(){ setContainerHeight($g); }, 50);
            $target.find('img').each(function(){
                if (this.complete) return;
                $(this).one('load', function(){ setContainerHeight($g); });
            });
            return false;
        });

        $(window).on('resize', function(){
            $wrap.each(function(){ setContainerHeight($(this)); });
        });
    })();
});
