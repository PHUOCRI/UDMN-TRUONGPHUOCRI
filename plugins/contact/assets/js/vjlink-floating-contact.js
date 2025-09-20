jQuery(document).ready(function($) {
    'use strict';

    // Add hover effect for the phone button
    $('.vjlink-contact-phone').hover(
        function() {
            $(this).find('i').css('transform', 'rotate(15deg)');
        },
        function() {
            $(this).find('i').css('transform', 'rotate(0)');
        }
    );

    // Add click animation
    $('.vjlink-contact-phone, .vjlink-contact-whatsapp').on('click', function() {
        $(this).addClass('clicked');
        setTimeout(() => {
            $(this).removeClass('clicked');
        }, 300);
    });

    // Add scroll effect - show/hide buttons on scroll
    let lastScrollTop = 0;
    const $floatingContact = $('.vjlink-floating-contact');
    const floatingHeight = $floatingContact.outerHeight();
    
    $(window).scroll(function() {
        const st = $(this).scrollTop();
        
        // Show/hide on scroll down/up
        if (st > lastScrollTop && st > 100) {
            // Scrolling down
            $floatingContact.css('transform', 'translateY(100px)');
        } else if (st < lastScrollTop) {
            // Scrolling up
            $floatingContact.css('transform', 'translateY(0)');
        }
        
        lastScrollTop = st <= 0 ? 0 : st; // For Mobile or negative scrolling
    });

    // Add touch support for mobile devices
    let touchStartY = 0;
    let touchEndY = 0;
    
    document.addEventListener('touchstart', function(event) {
        touchStartY = event.changedTouches[0].screenY;
    }, false);

    document.addEventListener('touchend', function(event) {
        touchEndY = event.changedTouches[0].screenY;
        handleSwipe();
    }, false);
    
    function handleSwipe() {
        if (touchEndY < touchStartY) {
            // Swipe up
            $floatingContact.css('transform', 'translateY(100px)');
        }
        if (touchEndY > touchStartY) {
            // Swipe down
            $floatingContact.css('transform', 'translateY(0)');
        }
    }
});
