$(document).ready(function () {

    $(".infoForm").on("click", ".plus", function () {

        var parUl = $(this).parents("ul"),
            parLi = $(this).parent("li"),
            clOn  = $(parLi).clone();

        $(clOn).find("input").val('');
        $(parUl).append(clOn);

        return false;
    });
    $(".infoForm").on("click", ".minus", function () {

        var parUl = $(this).parents("ul"),
            parLi = $(this).parent("li");

        $(parLi).remove();

        return false;
    });

    $(".js-tymbler").on("click", function () {

        var parLi = $(this).parents("li"),
            row2  = $(parLi).find(".row2");


       if ($(this).hasClass("active")){
           $(row2).removeClass("active");
           $(this).removeClass("active");
       }
       else
       {
           $(row2).addClass("active");
           $(this).addClass("active");

       }
        
            return false;
        
    });


}); //Конец Ready