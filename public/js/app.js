$(document).ready(function() {

    var height = $(window).height() - $('#footer-wp').outerHeight(true) - $('#header-wp').outerHeight(true);
    $('#content').css('min-height', height);

    //  CHECK ALL
    $("input[name='checkAll']").click(function() {
        var status = $(this).prop('checked');
        $('.table-checkall tbody tr td input[type="checkbox"]').prop("checked", status);
    });

    // EVENT SIDEBAR MENU
    $('#sidebar-menu .nav-item .nav-link .title').after('<span class="fa fa-angle-right arrow"></span>');
    var sidebar_menu = $('#sidebar-menu > .nav-item > .nav-link > .fa-angle-right');
    sidebar_menu.on('click', function() {
        if (!$(this).parent('a').parent('li').hasClass('active')) {
            $('.sub-menu').slideUp();
            $(this).parent('a').parent('li').find('.sub-menu').slideDown();
            $('#sidebar-menu > .nav-item').removeClass('active');
            $(this).parent('a').parent('li').addClass('active');
            return false;
        } else {
            $('.sub-menu').slideUp();
            $('#sidebar-menu > .nav-item').removeClass('active');
            return false;
        }
    });


    $(".nav-link.active .sub-menu").slideDown();
    // $("p").slideUp();

    $("#sidebar-menu .arrow").click(function() {
        $(this).parents("li").children(".sub-menu").slideToggle();
        $(this).toggleClass("fa-angle-right fa-angle-down");
    });

    //  CHECK ALL
    $('input[name="checkAll"]').click(function() {
        var status = $(this).prop("checked");
        $('.list-table-wp tbody tr td input[type="checkbox"]').prop(
            "checked",
            status
        );
    });

});