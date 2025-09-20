/**
 * Main JavaScript file for Du Lịch Việt Nhật Theme
 */

document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainMenu = document.querySelector('.main-menu');
    
    if (mobileMenuToggle && mainMenu) {
        mobileMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.toggle('active');
            mainMenu.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });
    }
    
    // Search Toggle
    const searchToggle = document.querySelector('.search-toggle');
    const searchForm = document.querySelector('.search-form');
    
    if (searchToggle && searchForm) {
        searchToggle.addEventListener('click', function(e) {
            e.preventDefault();
            searchForm.classList.toggle('active');
            
            // If search form is being shown, focus on the search input
            if (searchForm.classList.contains('active')) {
                const searchInput = searchForm.querySelector('input[type="search"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            // Don't prevent default if it's just a # link
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                
                // Close mobile menu if open
                if (mainMenu && mainMenu.classList.contains('active')) {
                    mainMenu.classList.remove('active');
                    document.body.classList.remove('menu-open');
                    if (mobileMenuToggle) {
                        mobileMenuToggle.classList.remove('active');
                    }
                }
                
                // Calculate the header height for proper scrolling
                const header = document.querySelector('header');
                const headerHeight = header ? header.offsetHeight : 0;
                const offset = 20; // Additional offset in pixels
                
                // Get the target element's position
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - (headerHeight + offset);
                
                // Smooth scroll to the target
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Back to Top Button
    const backToTopBtn = document.querySelector('.back-to-top');
    
    if (backToTopBtn) {
        // Show/hide the button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });
        
        // Smooth scroll to top when clicked
        backToTopBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Initialize Testimonial Slider
    initTestimonialSlider();
    
    // Initialize Partner Logos Slider
    initPartnerSlider();
    
    // Add animation classes when elements come into view
    setupScrollAnimations();
});

/**
 * Initialize Testimonial Slider
 */
function initTestimonialSlider() {
    const testimonialSlider = document.querySelector('.testimonials-slider');
    if (!testimonialSlider) return;
    
    let currentSlide = 0;
    const slides = testimonialSlider.querySelectorAll('.testimonial-item');
    const totalSlides = slides.length;
    
    // Only proceed if there are slides
    if (totalSlides === 0) return;
    
    // Show first slide
    slides[0].classList.add('active');
    
    // Auto-advance slides every 5 seconds
    setInterval(() => {
        // Hide current slide
        slides[currentSlide].classList.remove('active');
        
        // Move to next slide
        currentSlide = (currentSlide + 1) % totalSlides;
        
        // Show new slide
        slides[currentSlide].classList.add('active');
        
        // Scroll the slider to show the active slide
        testimonialSlider.scrollTo({
            left: slides[currentSlide].offsetLeft,
            behavior: 'smooth'
        });
    }, 5000);
}

/**
 * Initialize Partner Logos Slider
 */
function initPartnerSlider() {
    const partnerSlider = document.querySelector('.partners-slider');
    if (!partnerSlider) return;
    
    // For now, we're using CSS for the partner slider
    // This function is a placeholder for any future JavaScript functionality
    // that might be needed for the partner slider
}

/**
 * Set up scroll animations for elements with animation classes
 */
function setupScrollAnimations() {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    // Function to check if element is in viewport
    const isInViewport = (element) => {
        const rect = element.getBoundingClientRect();
        return (
            rect.top <= (window.innerHeight * 0.8) &&
            rect.bottom >= 0
        );
    };
    
    // Function to handle scroll events
    const handleScroll = () => {
        animatedElements.forEach(element => {
            if (isInViewport(element)) {
                element.classList.add('animate-fadeInUp');
                // Remove the animation class after it's done to prevent re-animating
                setTimeout(() => {
                    element.classList.remove('animate-on-scroll');
                }, 1000);
            }
        });
    };
    
    // Initial check on page load
    handleScroll();
    
    // Check on scroll
    window.addEventListener('scroll', handleScroll);
}

/**
 * Debounce function to limit how often a function is called
 */
function debounce(func, wait = 10, immediate = true) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}
