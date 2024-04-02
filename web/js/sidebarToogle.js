$(document).ready(function() {
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("expanded");
        $("#main_icon").toggleClass("collapsed");
        $("#sidebar--menu__collapsed").toggleClass("block__hide");
        $(".sidebarfooter__block").toggleClass("collapsed");
    });
});