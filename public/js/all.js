$(document).ready(function(){
    $(".arrow").click(function(e) {
            e.preventDefault();
            var elm = $(this).attr("href");
        $('html, body').animate({
            scrollTop: parseInt($(elm).offset().top)
        }, 800);
    });
})