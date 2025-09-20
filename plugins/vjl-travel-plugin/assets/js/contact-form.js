/**
 * VJL Travel Contact Form JavaScript
 * Based on JV Contact Form plugin
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initContactForm();
    });

    function initContactForm() {
        $('.vjl-contact-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $btn = $form.find('.btn[type="submit"]');
            const $status = $form.find('.alert');
            const $loading = $btn.find('.btn-loading');
            const $text = $btn.find('.btn-text');
            
            // Clear previous messages
            $status.hide().removeClass('alert-success alert-danger');
            
            // Validate form
            if (!validateForm($form)) {
                return;
            }
            
            // Show loading state
            $btn.addClass('loading');
            $text.hide();
            $loading.show();
            
            // Prepare form data
            const formData = $form.serializeArray();
            formData.push({
                name: 'action',
                value: 'vjl_contact_form_submit'
            });
            formData.push({
                name: 'nonce',
                value: vjlContactForm.nonce
            });
            
            // Submit form via AJAX
            $.ajax({
                url: vjlContactForm.ajaxUrl,
                method: 'POST',
                data: formData,
                dataType: 'json',
                timeout: 30000
            })
            .done(function(response) {
                if (response && response.success) {
                    showMessage($status, response.data.message, 'success');
                    $form[0].reset();
                    
                    // Track form submission (if analytics is available)
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'form_submit', {
                            'event_category': 'contact',
                            'event_label': 'contact_form'
                        });
                    }
                } else {
                    const errorMsg = response && response.data && response.data.message 
                        ? response.data.message 
                        : vjlContactForm.messages.error;
                    showMessage($status, errorMsg, 'danger');
                }
            })
            .fail(function(xhr, status, error) {
                let errorMsg = vjlContactForm.messages.error;
                
                if (status === 'timeout') {
                    errorMsg = 'Yêu cầu hết thời gian chờ. Vui lòng thử lại.';
                } else if (xhr.status === 0) {
                    errorMsg = 'Không thể kết nối đến server. Vui lòng kiểm tra kết nối internet.';
                }
                
                showMessage($status, errorMsg, 'danger');
            })
            .always(function() {
                // Hide loading state
                $btn.removeClass('loading');
                $loading.hide();
                $text.show();
            });
        });
        
        // Real-time validation
        $('.vjl-contact-form .form-control').on('blur', function() {
            validateField($(this));
        });
        
        // Phone number formatting
        $('.vjl-contact-form input[name="phone"]').on('input', function() {
            formatPhoneNumber($(this));
        });
    }
    
    function validateForm($form) {
        let isValid = true;
        
        // Validate required fields
        $form.find('[required]').each(function() {
            if (!validateField($(this))) {
                isValid = false;
            }
        });
        
        // Validate email
        const $email = $form.find('input[name="email"]');
        if ($email.length && $email.val()) {
            if (!validateField($email)) {
                isValid = false;
            }
        }
        
        // Validate phone
        const $phone = $form.find('input[name="phone"]');
        if ($phone.length && $phone.val()) {
            if (!validateField($phone)) {
                isValid = false;
            }
        }
        
        return isValid;
    }
    
    function validateField($field) {
        const fieldName = $field.attr('name');
        const fieldValue = $field.val().trim();
        let isValid = true;
        let errorMessage = '';
        
        // Remove previous validation classes
        $field.removeClass('is-valid is-invalid');
        $field.siblings('.invalid-feedback, .valid-feedback').remove();
        
        // Required field validation
        if ($field.prop('required') && !fieldValue) {
            isValid = false;
            errorMessage = 'Trường này là bắt buộc.';
        }
        
        // Email validation
        if (fieldName === 'email' && fieldValue) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(fieldValue)) {
                isValid = false;
                errorMessage = vjlContactForm.messages.email_invalid;
            }
        }
        
        // Phone validation
        if (fieldName === 'phone' && fieldValue) {
            const phoneRegex = /^[0-9+\-\s()]+$/;
            if (!phoneRegex.test(fieldValue) || fieldValue.length < 10) {
                isValid = false;
                errorMessage = vjlContactForm.messages.phone_invalid;
            }
        }
        
        // Name validation
        if (fieldName === 'name' && fieldValue) {
            if (fieldValue.length < 2) {
                isValid = false;
                errorMessage = 'Tên phải có ít nhất 2 ký tự.';
            }
        }
        
        // Message validation
        if (fieldName === 'message' && fieldValue) {
            if (fieldValue.length < 10) {
                isValid = false;
                errorMessage = 'Tin nhắn phải có ít nhất 10 ký tự.';
            }
        }
        
        // Apply validation result
        if (fieldValue) { // Only show validation if field has value
            if (isValid) {
                $field.addClass('is-valid');
                $field.after('<div class="valid-feedback">✓ Hợp lệ</div>');
            } else {
                $field.addClass('is-invalid');
                $field.after('<div class="invalid-feedback">' + errorMessage + '</div>');
            }
        }
        
        return isValid;
    }
    
    function formatPhoneNumber($input) {
        let value = $input.val().replace(/\D/g, ''); // Remove non-digits
        
        // Format Vietnamese phone number
        if (value.length > 0) {
            if (value.startsWith('84')) {
                value = '+' + value;
            } else if (value.startsWith('0')) {
                value = '+84' + value.substring(1);
            }
        }
        
        $input.val(value);
    }
    
    function showMessage($status, message, type) {
        $status
            .removeClass('alert-success alert-danger')
            .addClass('alert-' + type)
            .html(message)
            .fadeIn();
        
        // Scroll to message
        $('html, body').animate({
            scrollTop: $status.offset().top - 100
        }, 500);
        
        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(function() {
                $status.fadeOut();
            }, 5000);
        }
    }
    
    // Utility function to check if element is in viewport
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
    
    // Animate form elements on scroll
    $(window).on('scroll', function() {
        $('.vjl-contact-form-wrapper').each(function() {
            if (isInViewport(this) && !$(this).hasClass('animated')) {
                $(this).addClass('animated');
                $(this).find('.vjl-contact-form-title').addClass('fade-in-up');
                $(this).find('.form-control, .form-select').each(function(index) {
                    $(this).css('animation-delay', (index * 0.1) + 's');
                    $(this).addClass('fade-in-up');
                });
            }
        });
    });
    
})(jQuery);

// CSS animations (injected via JavaScript)
const style = document.createElement('style');
style.textContent = `
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .vjl-contact-form-wrapper.animated {
        animation: slideInUp 0.8s ease-out;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
