$(document).ready(function() {
    // $("#menu-toggle").click(function(e) {
    //     e.preventDefault();
    //     $("#wrapper").toggleClass("expanded");
    //     $("#main_icon").toggleClass("collapsed");
    //     $("#sidebar--menu__collapsed").toggleClass("block__hide");
    //     $(".sidebarfooter__block").toggleClass("collapsed");
    // });
    $(".open-config").click(function(e) {
        e.preventDefault();
        $(".sidebar--painel").toggleClass("block__slide");
    });
    $(".close-painel").click(function(e) {
        e.preventDefault();
        $(".sidebar--painel").toggleClass("block__slide");
    });

    // script apenas para ilustrar adicionar novo filtro
    $("#add-atribute").click(function(e) {
        e.preventDefault();
        $("#filter-list__atribute02").slideToggle();
    });
    // $(".remove-atribute").click(function(e) {
    //     e.preventDefault();
    //     $("#filter-list__atribute02").toggleClass("block__hide");
    // });
});