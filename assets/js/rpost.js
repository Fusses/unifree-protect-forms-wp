jQuery(document).ready(function() {
  var data = {
    'action': 'rpost_ajax_handler'
  };
  jQuery.post( rpostajaxhandler.ajaxurl, data, function( response ){
    jQuery('.wpcf7-submit, #comment').before(response);
   }, 'json');    
});

(function ($) {
  $('form').on('change',function(){
    $('.rpost').val('rpost');
  })
})(jQuery);
