$(function () {
    "use strict";
    $("nav ul li").on("click", function () {
        
        // Get The ID Of a When I Clicked
        var myID = $(this).attr("id");
        
        // Remove Class Inactive When I clicked And Add It In Siblings In Ul
        $(this).addClass("inactive").siblings().removeClass("inactive");
        
        // Hide The Div When i Clicked
        $(".stepper_cosdfntainer div").hide();
        
        // When Clicked In Li Get Div Same ID
        
        $("#" + myID + "-content").fadeIn(1000);
    });
});