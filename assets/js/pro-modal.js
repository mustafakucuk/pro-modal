'use strict'
jQuery(function($){

  $(".pro-modal").each(function(){
    var modalElementID = $(this).attr('id');
    var modalID = $(this).data('modalid');
    var modalTrigger = $(this).data('modaltrigger');
    var modalTriggerElement = $(this).data('modaltriggerelement');
    var modalCookie = $(this).data('modalcookie');
    if( modalCookie == 2 && modalTrigger == 1 ){
      document.cookie = "pro_modal-"+modalID+"=2;path=/";
    }
    if( modalTrigger == 2){
      $(modalTriggerElement).click(function() {
        document.cookie = "pro_modal-"+modalID+"=2;path=/";
        $('#'+modalElementID+' .modal-wrapper').addClass('open');
        return false;
      });
    }
    $('.btn-close').click(function() {
       $('.modal-wrapper').removeClass('open');
       return false;
    });
  })
  
});