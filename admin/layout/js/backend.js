
$(function (){
   
    'use strict';
    
    //Hides placeholder on form focus then shows it again on blur.
    
    $('[placeholder]').focus(function (){
        
        $(this).attr('data-text', $(this).attr('placeholder'));
        
        $(this).attr('placeholder', '');
        
    }).blur(function(){
        
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    // Add asterisk on required fields.

    $('input').each(function (){

        if($(this).attr('required') === 'required'){

            $(this).after('<span class="asterisk">*</span>');
        }
    });

    //Convert password field to tet field on hover.

    var passField = $('.password');

    $('.show-pass').hover(function(){

        passField.attr('type', 'text');

    }, function(){

        passField.attr('type', 'password');

    });

    //Confirmation Message On Button.
    $('.confirm').click(function () {

        return confirm('Are you sure?');
    });

});