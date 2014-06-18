(function($){
    $(document).ready(function(){
     $('form.checkoutform').submit( function(ev) {
                    
                // confirm email validation
                    $email_confirms = $(this).find('input.js-confirm-email');
                    var validated = true;
                    var $first_error = false;
                    if ($email_confirms.length > 0) {
                        $email_confirms.each(function(){
                                $email_confirm = $(this);	
                                $corresponding_email = $('#'+$email_confirm.attr('id').replace('Confirm',''));
                                if ($corresponding_email.val() != $email_confirm.val()) {
                                        ev.preventDefault();
                                        $email_confirm.closest('div.js-confirm-email').addClass('error');
                                        $email_confirm.closest('div').find('span.error').remove();
                                        $email_confirm.closest('div').append('<span class="error">Email and Confirm Email fields must match.</span>');
                                        validated = false;
                                        if (!$first_error) {
                                                $first_error = $email_confirm;
                                        }
                                }
                        });
                    }
                    if ($first_error) {
                            $('html, body').animate({ scrollTop: $first_error.offset().top - 140 }, 1000);
                            //$first_error.effect("pulsate");
                    }
                    return validated;
                });
                
                // Third party select2 initialisation
                    var preventSearch = false;
                    var minResults = 12;
                    if (jQuery().select2) {
                      $('select').not('[name=PerPage]').select2({
                              allowClear: true,
                              width: 'resolve',
                              minimumResultsForSearch: minResults,
                              preventSearchInput: preventSearch
                      });
                    }
    });
})(jQuery);