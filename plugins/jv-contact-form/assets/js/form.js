(function($){
    $(document).on('submit', '.jvcf-form', function(e){
      e.preventDefault();
      const $form = $(this);
      const $btn  = $form.find('.jvcf-submit');
      const $status = $form.find('.jvcf-status');
  
      const data = $form.serializeArray();
      $status.text('');
      $btn.prop('disabled', true).text('Đang gửi...');
  
      $.ajax({
        url: JVCF.ajaxUrl,
        method: 'POST',
        data: data,
        dataType: 'json'
      })
      .done(function(res){
        if(res && res.success){
          $status.css('color', '#0a8').text(JVCF.success);
          $form[0].reset();
        } else {
          $status.css('color', '#d33').text(res?.data?.msg || JVCF.error);
        }
      })
      .fail(function(){
        $status.css('color', '#d33').text(JVCF.error);
      })
      .always(function(){
        $btn.prop('disabled', false).text('Gửi liên hệ');
      });
    });
  })(jQuery);