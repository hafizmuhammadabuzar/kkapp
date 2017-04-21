$(document).ready(function(){

<<<<<<< HEAD
    var availableTags = [
        "ActionScript",
        "AppleScript",
        "Asp",
        "BASIC",
        "C",
        "C++",
        "Clojure",
        "COBOL",
        "ColdFusion",
        "Erlang",
        "Fortran",
        "Groovy",
        "Haskell",
        "Java",
        "JavaScript",
        "Lisp",
        "Perl",
        "PHP",
        "Python",
        "Ruby",
        "Scala",
        "Scheme"
    ];
    $( ".bootstrap-tagsinput input" ).autocomplete({
        source: availableTags
    });

=======
>>>>>>> dc9d7b82fd179895379f2bd7d465a153584eb58f
    $(document).keypress(function(e) {
        if(e.which == 13) {
            if($(".bootstrap-tagsinput input").is(":focus")){
                return false;
            }
        }
    });

    var currentTime = new Date()
    var month = currentTime.getMonth() + 1;
    var day = currentTime.getDate();
    var year = currentTime.getFullYear();

    $(".start-input, .end-input").datepicker({
        dateFormat: "dd-mm-yy",
        minDate: day,
        yearRange: year+":"+(year+1),
        dateFormat: "yy-mm-dd",
        defaultDate: year+'-'+month+'-'+day
        
    })

    $(".add-location").on("click", function(e){
        e.preventDefault();
        $(this).modal( {
            modalClass: "popup"
        });
    });
})