$(document).ready(function() {
    
    var menu = "close";
    $(".mobile-menu .menu-toggle").click(function() {
        if (menu === "close") {
            $('.mask').show();
            $(this).parent().next(".mobile-nav").css("transform", "translate(0, 0)");
            
            menu = "open";
        } else {
            $('.mask').hide();
            $(this).parent().next(".mobile-nav").css("transform", "translate(-100%, 0)");
            menu = "close";
        }
    });
    $(".mask").click(function() {
        $(this).hide();
        menu = "close";
        $('.mobile-menu .menu-toggle').parent().next(".mobile-nav").css("transform", "translate(-100%, 0)");
    });
    $("#btn-close").click(function() {
        $('.alert').hide();
    });
    
});
$(document).on('click','.mobile-menu .mobile-nav li.main > a',function(){
    var has = $('.mobile-menu .mobile-nav li.main');
    if(has.hasClass('active')){
        has.removeClass('active');
    }
    $(this).parent().addClass('active');
});