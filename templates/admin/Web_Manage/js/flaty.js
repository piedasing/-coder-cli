var isnicescroll=true;
$(function() {
	$.Window=$(window);
    //Handel user layout settings using cookie
    function handleUserLayoutSetting() {
        if (typeof cookie_not_handle_user_settings != 'undefined' && cookie_not_handle_user_settings == true) {
            return;
        }
        //Collapsed sidebar
        if ($.cookie('sidebar-collapsed') == 'true') {
            $('#sidebar').addClass('sidebar-collapsed');
        }

        //Fixed sidebar
        if ($.cookie('sidebar-fixed') == 'true') {
            $('#sidebar').addClass('sidebar-fixed');
        }

        //Fixed navbar
        if ($.cookie('navbar-fixed') == 'true') {
            $('#navbar').addClass('navbar-fixed');
        }

        var color_skin = $.cookie('skin-color');
        var color_sidebar = $.cookie('sidebar-color');
        var color_navbar = $.cookie('navbar-color');

        //Skin color
        if (color_skin !== undefined) {
            $('body').addClass('skin-' + color_skin);
        }

        //Sidebar color
        if (color_sidebar !== undefined) {
            $('#main-container').addClass('sidebar-' + color_sidebar);
        }

        //Navbar color
        if (color_navbar !== undefined) {
            $('#navbar').addClass('navbar-' + color_navbar);
        }
    }
    function getNowTime(){//取得現在時間
        var timeDate= new Date();
        var tMonth = (timeDate.getMonth()+1) > 9 ? (timeDate.getMonth()+1) : '0'+(timeDate.getMonth()+1);
        var tDate = timeDate.getDate() > 9 ? timeDate.getDate() : '0'+timeDate.getDate();
        var tHours = timeDate.getHours() > 9 ? timeDate.getHours() : '0'+timeDate.getHours();
        var tMinutes = timeDate.getMinutes() > 9 ? timeDate.getMinutes() : '0'+timeDate.getMinutes();
        var tSeconds = timeDate.getSeconds() > 9 ? timeDate.getSeconds() : '0'+timeDate.getSeconds();
        return timeDate= timeDate.getFullYear()+'-'+ tMonth +'-'+ tDate +' '+ tHours +':'+ tMinutes +':'+ tSeconds;
    }

    //If you want to handle skin color by server-side code, don't forget to comment next line
    handleUserLayoutSetting();

    //Disable certain links
    $('a[href^=#]').click(function (e) {
        e.preventDefault()
    });

    //Add animation to notification and messages icon, if they have any new item
    var badge = $('.flaty-nav .dropdown-toggle > .icon-bell-alt + .badge')
    if ($(badge).length > 0 && parseInt($(badge).html()) > 0) {
        $('.flaty-nav .dropdown-toggle > .icon-bell-alt').addClass('anim-swing');
    }
    badge = $('.flaty-nav .dropdown-toggle > .icon-envelope + .badge')
    if ($(badge).length > 0 && parseInt($(badge).html()) > 0) {
        $('.flaty-nav .dropdown-toggle > .icon-envelope').addClass('anim-top-down');
    }

    //---------------- Nice Scroll --------------------//
    // try{
    //     $('html').niceScroll({zindex: 999, cursorwidth: 8});
    //     $('.nice-scroll').niceScroll({railoffset: {left: -3}});
    //     var resizeNicescroll = function() { 
    //       //  if ($('#sidebar').height() > $('#main-content').height()) {
    //            // $('#main-content').height(($('#sidebar').height() - 40) + 'px');
    //        // }
    //         $("html").getNiceScroll().resize();
    //     }
    //     resizeNicescroll();
    //     isnicescroll=true;
    // }
    // catch (e) {
    //     isnicescroll=false;
    // }
//	$.Events = {
//  		resizeNicescroll   : 'resiz eNicescroll'
// 	}
//
//	$.Window.bind($.Events.resizeNicescroll, resizeNicescroll);

    //---------------- Tooltip & Popover --------------------//
    $('.show-tooltip').tooltip({animation:true,container: 'body', delay: {show:500}});
    $('.show-popover').popover();

    //---------------- Syntax Highlighter --------------------//
    window.prettyPrint && prettyPrint();

    //---------------- Sidebar -------------------------------//
    //Scrollable fixed sidebar
    var scrollableSidebar = function(doScroll) {
        doScroll = typeof doScroll !== 'undefined' ? doScroll : true;
        if ($('#sidebar.sidebar-fixed').size() == 0) {
            $('#sidebar .nav').css('height', 'auto');
            return;
        }
        if ($('#sidebar.sidebar-fixed.sidebar-collapsed').size() > 0) {
            $('#sidebar .nav').css('height', 'auto');
            return;
        }
        var winHeight = $(window).height() - 90;
        $('#sidebar.sidebar-fixed .nav').css('height', winHeight + 'px').niceScroll({railalign: 'left', railoffset: {left: 3}, cursoropacitymax: 0.7});
        if (doScroll) {
            setTimeout(function(){
                $("#sidebar.sidebar-fixed .nav").getNiceScroll().doScrollPos(0, $('#sidebar .nav').scrollTop() + 40, 900);
                },
                9
            );
        }
    }
    scrollableSidebar(false);
    //Submenu dropdown
    $('#sidebar a.dropdown-toggle').click(function() {
        var submenu = $(this).next('.submenu');
        var arrow = $(this).children('.arrow');
        if (arrow.hasClass('icon-angle-right')) {
            arrow.addClass('anim-turn90');
        }
        else {
            arrow.addClass('anim-turn-90');
        }
        submenu.slideToggle(400, function(){
            if($(this).is(":hidden")) {
                arrow.attr('class', 'arrow icon-angle-right');
                $("#sidebar.sidebar-fixed .nav").getNiceScroll().resize();
            } else {
                arrow.attr('class', 'arrow icon-angle-down');
                scrollableSidebar();
            }
            arrow.removeClass('anim-turn90').removeClass('anim-turn-90');
            resizeNicescroll();
        });
    });

    //Collapse button
    $('#sidebar.sidebar-collapsed #sidebar-collapse > i').attr('class', 'icon-double-angle-right');
    $('#sidebar-collapse').click(function(){
        $('#sidebar').toggleClass('sidebar-collapsed');
        if ($('#sidebar').hasClass('sidebar-collapsed')) {
            $('#sidebar-collapse > i').attr('class', 'icon-double-angle-right');
            $('#sidebar.sidebar-collapsed .nav-list > li').mouseenter(function(){
                resizeNicescroll();
            });
            $.cookie('sidebar-collapsed', 'true');
        } else {
            $('#sidebar-collapse > i').attr('class', 'icon-double-angle-left');
            $.cookie('sidebar-collapsed', 'false');
        }
        $(".nice-scroll").getNiceScroll().resize();
        scrollableSidebar();
    });

    $('#sidebar').on('show.bs.collapse', function () {
        if ($(this).hasClass('sidebar-collapsed')) {
            $(this).removeClass('sidebar-collapsed');
        }
        resizeNicescroll();
    });

    //Search Form
    $('#sidebar .search-form').click(function(){
        $('#sidebar .search-form input[type="text"]').focus();
    });
    $('#sidebar .nav > li.active > a > .arrow').removeClass('icon-angle-right').addClass('icon-angle-down');


    //---------------- Horizontal Menu -------------------------------//
    if ($('#nav-horizontal')) {
        var horizontalNavHandler = function() {
            var w = $(window).width();
            if (w > 979) {
                $('#nav-horizontal').removeClass('nav-xs');
                $('#nav-horizontal .arrow').removeClass('icon-angle-right').removeClass('icon-angle-down').addClass('icon-caret-down');
            }
            else {
                $('#nav-horizontal').addClass('nav-xs');
                $('#nav-horizontal .arrow').removeClass('icon-caret-down').addClass('icon-angle-right');
                resizeNicescroll();
            }
        }
        $(window).resize(function(){
            horizontalNavHandler();
        });
        horizontalNavHandler();
    }

    //Horizontal menu dropdown
    $('#nav-horizontal a.dropdown-toggle').click(function() {
        var submenu = $(this).next('.dropdown-menu');
        var arrow = $(this).children('.arrow');
        if ($('#nav-horizontal.nav-xs').size() > 0) {
            if (arrow.hasClass('icon-angle-right')) {
                arrow.addClass('anim-turn90');
            }
            else {
                arrow.addClass('anim-turn-90');
            }
        }
        if ($('#nav-horizontal.nav-xs').size() == 0) {
            $('#nav-horizontal > li > .dropdown-menu').not(submenu).slideUp(400);
        }
        submenu.slideToggle(400, function(){
            if ($('#nav-horizontal.nav-xs').size() > 0) {
                if($(this).is(":hidden")) {
                    arrow.attr('class', 'arrow icon-angle-right');
                } else {
                    arrow.attr('class', 'arrow icon-angle-down');
                }
                arrow.removeClass('anim-turn90').removeClass('anim-turn-90');
                resizeNicescroll();
            }
        });
    });

    $('#nav-horizontal').on('shown.bs.collapse', function () {
        resizeNicescroll();
    });

    //------------------ Theme Setting --------------------//
    //Toggle showing theme setting box
    $('#theme-setting > a').click(function(){
        $(this).next().animate({width:'toggle'}, 500, function(){
            if($(this).is(":hidden")) {
                $('#theme-setting > a > i').attr('class', 'icon-gears');
            } else {
                $('#theme-setting > a > i').attr('class', 'icon-remove');
            }
        });
        $(this).next().css('display', 'inline-block');
    });
    //Change skin and colors
    $('#theme-setting ul.colors a').click(function(){
        var parent_li = $(this).parent().get(0);
        var parent_ul = $(parent_li).parent().get(0);
        var target = $(parent_ul).data('target');
        var prefix = $(parent_ul).data('prefix');
        var color = $(this).attr('class');
        var regx = new RegExp('\\b' + prefix + '.*\\b', 'g');
        $(parent_ul).children('li').removeClass('active');
        $(parent_li).addClass('active');
        //Remove current skin class if it has
        if ($(target).attr('class') != undefined) {
            $(target).attr('class', $(target).attr('class').replace(regx, '').trim());
        }
        $(target).addClass(prefix + color)
        if (target == 'body') {
            var parent_ul_li = $(parent_ul).parent().get(0);
            var next_li = $(parent_ul_li).nextAll('li:lt(2)');
            $(next_li).find('li.active').removeClass('active');
            $(next_li).find('a.' + color).parent().addClass('active');
            //Remove static color class from Navbar & Sidebar
            $('#navbar').attr('class', $('#navbar').attr('class').replace(/\bnavbar-.*\b/g, '').trim());
            $('#main-container').attr('class', $('#main-container').attr('class').replace(/\bsidebar-.*\b/g, '').trim());
			$('body').attr('class',prefix+color);
        }
		var date = new Date();
        date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
        $.cookie(prefix + 'color', color, {expires: date });
    });
    //Handel selected color
    var theme_colors = ["blue", "red", "green", "orange", "yellow", "pink", "magenta", "gray", "black"];
    $.each(theme_colors, function(k, v) {
        if ($('body').hasClass('skin-' + v)) {
            $('#theme-setting ul.colors > li').removeClass('active');
            $('#theme-setting ul.colors > li:has(a.'+ v +')').addClass('active');
        }
    });

    $.each(theme_colors, function(k, v) {
        if ($('#navbar').hasClass('navbar-' + v)) {
            $('#theme-setting ul[data-prefix="navbar-"] > li').removeClass('active');
            $('#theme-setting ul[data-prefix="navbar-"] > li:has(a.'+ v +')').addClass('active');
        }

        if ($('#main-container').hasClass('sidebar-' + v)) {
            $('#theme-setting ul[data-prefix="sidebar-"] > li').removeClass('active');
            $('#theme-setting ul[data-prefix="sidebar-"] > li:has(a.'+ v +')').addClass('active');
        }
    });
    //Handle fixed navbar & sidebar
    if ($('#sidebar').hasClass('sidebar-fixed')) {
        $('#theme-setting > ul > li > a[data-target="sidebar"] > i').attr('class', 'icon-check green')
    }
    if ($('#navbar').hasClass('navbar-fixed')) {
        $('#theme-setting > ul > li > a[data-target="navbar"] > i').attr('class', 'icon-check green')
    }
    $('#theme-setting > ul > li > a').click(function(){
        var target = $(this).data('target');
        var check = $(this).children('i');
        if (check.hasClass('icon-check-empty')) {
            check.attr('class', 'icon-check green');
            $('#' + target).addClass(target + '-fixed');
            $.cookie(target + '-fixed', 'true');
        } else {
            check.attr('class', 'icon-check-empty');
            $('#' + target).removeClass(target + '-fixed');
            $.cookie(target + '-fixed', 'false');
        }
        if (target == "sidebar") {
            scrollableSidebar();
        }
    });

    //-------------------------- Boxes -----------------------------//
    $('.box .box-tool > a').click(function(e) {
        if ($(this).data('action') == undefined) {
            return;
        }
        var action = $(this).data('action');
        var btn = $(this);
        switch (action) {
            case 'collapse':
                $(btn).children('i').addClass('anim-turn180');
                $(this).parents('.box').children('.box-content').slideToggle(500, function(){
                    if($(this).is(":hidden")) {
                        $(btn).children('i').attr('class', 'icon-chevron-down');
                    } else {
                        $(btn).children('i').attr('class', 'icon-chevron-up');
                    }
                });
                break;
            case 'close':
                $(this).parents('.box').fadeOut(500, function(){
                    $(this).parent().remove();
                })
                break;
            case 'config':
                $('#' + $(this).data('modal')).modal('show');
                break;
        }
        setTimeout(function(){ resizeNicescroll();},600);
        e.preventDefault();
    });

    //-------------------------- Mail Page -----------------------------//
    //Collapse and Uncollapse
    $('.mail-messages .msg-collapse > a').click(function(e){
        $(this).children('i').addClass('anim-turn180');
        $(this).parents('li').find('.mail-msg-container').slideToggle(500, function(){
            var i = $(this).parents('li').find('.msg-collapse > a').children('i');
            if($(this).is(':hidden')) {
                $(i).attr('class', 'icon-chevron-down');
            } else {
                $(i).attr('class', 'icon-chevron-up');
            }
        });
    });

    //Star and Unstar
    $('.mail-content i.icon-star').click(function(){
        $(this).toggleClass('starred');
    });

    //Check All and Uncheck All message in mail list
    $('.mail-toolbar > li:first-child > input[type="checkbox"]').change(function() {
        var check = false;
        if ($(this).is(':checked')) {
            check = true;
        }
        $(this).parents('.mail-content').find('.mail-list .ml-left > input[type="checkbox"]').prop('checked', check);
        var li = $(this).parents('.mail-content').find('.mail-list > li');
        if (check) {
            $(li).addClass('checked');
        }
        else {
            $(li).removeClass('checked');
        }
    });

    //Add .checked class to selected rows
    $('.mail-list .ml-left > input[type="checkbox"]').change(function(){
        if ($(this).is(':checked')) {
            $(this).parents('li').addClass('checked');
        }
        else {
            $(this).parents('li').removeClass('checked');
        }
    })

    //--------------------- Go Top Button ---------------------//
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('#btn-scrollup').fadeIn();
        } else {
            $('#btn-scrollup').fadeOut();
        }
    });
    $('#btn-scrollup').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });

    //---------------- Active Tile --------------------//
    if ($('.tile-active').size() > 0) {
        var tileMoveDuration = 1500;
        var tileDefaultStop = 5000;

        var tileGoUp = function(el, stop1, stop2, height) {
            $(el).children('.tile').animate({top: '-='+ height +'px'}, tileMoveDuration);
            setTimeout(function(){ tileGoDown(el, stop1, stop2, height); }, stop2 + tileMoveDuration);
        }

        var tileGoDown = function(el, stop1, stop2, height) {
            $(el).children('.tile').animate({top: '+='+ height +'px'}, tileMoveDuration);
            setTimeout(function(){ tileGoUp(el, stop1, stop2, height); }, stop1 + tileMoveDuration);
        }

        $('.tile-active').each(function(index, el){
            var tile1, tile2, stop1, stop2, height;

            tile1 = $(this).children('.tile').first();
            tile2 = $(this).children('.tile').last();
            stop1 = $(tile1).data('stop');
            stop2 = $(tile2).data('stop');
            height = $(tile1).outerHeight();

            if (stop1 == undefined) {
                stop1 = tileDefaultStop;
            }
            if (stop2 == undefined) {
                stop2 = tileDefaultStop;
            }

            setTimeout(function(){ tileGoUp(el, stop1, stop2, height); }, stop1);
        });
    }

    //------------------------- Table --------------------------//
    //Check all and uncheck all table rows
    $('.table > thead > tr > th:first-child > input[type="checkbox"]').change(function() {
        var check = false;
        if ($(this).is(':checked')) {
            check = true;
        }
        $(this).parents('thead').next().find('tr > td:first-child > input[type="checkbox"]').prop('checked', check);
    })

    //------------------------ Data Table -----------------------//

    if (jQuery().dataTable) {
        $('#table1').dataTable({
            "fnDrawCallback" : function () {
                if (jQuery().getNiceScroll) {
                    $("html").getNiceScroll().resize();
                }
            },
            "aLengthMenu": [
                [10, 15, 25, 50, 100, -1],
                [10, 15, 25, 50, 100, "All"]
            ],
            "iDisplayLength": 10,
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sInfo": "_START_ - _END_ of _TOTAL_",
                "sInfoEmpty": "0 - 0 of 0",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0]
            }]
        });
    }

    //----------------------- Chosen Select ---------------------//
    if (jQuery().chosen) {
        $(".chosen").chosen({no_results_text: "Oops, nothing found!"});

        $(".chosen-with-diselect").chosen({
            allow_single_deselect: true,
            search_contains: true,
            width: 'auto'
        });
    }

    //--------------- Password Strength Indicator ----------------//
    if (jQuery().pwstrength) {
        $('input[data-action="pwindicator"]').pwstrength();
    }

    //----------------- Bootstrap Dual Listbox -------------------//
    if (jQuery().bootstrapDualListbox) {
        $('select[data-action="duallistbox"]').bootstrapDualListbox();
    }

    //----------------------- Colorpicker -------------------------//
    if (jQuery().colorpicker) {
        $('.colorpicker-default').colorpicker({
            format: 'hex'
        });
        $('.colorpicker-rgba').colorpicker();
    }

    //----------------------- Time Picker -------------------------//
    if (jQuery().timepicker) {
        $('.timepicker-default').timepicker();
        $('.timepicker-24').timepicker({
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false
        });
    }
    //------------------------ DateTime Picker ------------------------//
    if (jQuery().datetimepicker) {
        $('.datetimepicker').datetimepicker({
            //startDate: getNowTime(),
			minuteStep: 30
        });
    }
    //------------------------ Date Picker ------------------------//
    if (jQuery().datepicker) {
        // $('.date-picker').datepicker();
        $('.date-picker').each(function () {
            const format = $(this).attr('data-date-format') || 'yyyy-mm-dd';
            $(this).attr('autocomplete', 'off');
            $(this).datepicker({
                format: format,
                language: 'zh-tw'
            });
            $(this).on('keydown paste', function (evt) {
                evt.preventDefault();
                return false;
            });
        });
    }

    //------------------------ Date Range Picker ------------------------//
    if (jQuery().daterangepicker) {
        //Date Range Picker
        $('.date-range').daterangepicker();
    }

    //------------------------ Bootstrap WYSIWYG Editor -----------------//
    if (jQuery().wysihtml5) {
        $('.wysihtml5').wysihtml5();
    }

    //------------------------------ Form validation --------------------------//
    if (jQuery().validate) {
        var removeSuccessClass = function(e) {
            $(e).closest('.form-group').removeClass('has-success');
        }
        $('#validation-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",

            invalidHandler: function (event, validator) { //display error alert on form submit

            },

            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change dony by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                setTimeout(function(){removeSuccessClass(element);}, 3000);
            },

            success: function (label) {
                label.closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
            }
        });
    }

    //---------------------------- prettyPhoto -------------------------------//
    if (jQuery().prettyPhoto) {
        $(".gallery a[rel^='prettyPhoto']").prettyPhoto({social_tools:'', hideflash: true});
    }

	//------------------modify by coder-------------
	$.fn.getType = function(){ return this[0].tagName == "INPUT" ? $(this[0]).attr("type").toLowerCase() : this[0].tagName.toLowerCase(); }
});



$( document ).ready(function() {
	if( typeof CKEDITOR !== 'undefined' ){
        ckeditorScrollFix();
    }
	setTimeout(function(){ resizeNicescroll()},1000);

});

$(document).on('keypress', 'form', function (evt) {
    if (evt.which === 13) {
        evt.preventDefault();
    }
})
