jQuery(document).ready(function ($) {

    var items = $('.item');

    // FIRST OPEN THE LINK WITH HASH
    function OpenDemo(el) {
        var loading = $('#demo-loading');
        var theme_url = $(el).data('link');
        var purchase_url = $(el).data('purchase');
        var theme_name = $(el).data('name');
        $('.remove_frame a').attr('href', theme_url);

        loading.show();
        $('#iframe').attr('src', theme_url).one('load',function(){
            loading.fadeOut(300);
        });

        $('#iframe').attr('src', theme_url);
        window.location.href='#'+theme_name;

        items.removeClass('active');
        $(el).addClass('active');
    }

    var getDemoByHash = function(){
        var selector = location.hash && location.hash.replace(/\#/g, '');
        var selectDemo = $(selector ? '.item[data-name="'+selector+'"]':'.item:first');
        return selectDemo;
    }

    OpenDemo(getDemoByHash());

    $(window).bind('hashchange',function(){
        OpenDemo(getDemoByHash());
    })

    //ADJUST HEIGHT FOR SWITCHER
    function fixHeight () {
        var headerHeight = $('#container').height();
        var jquerybar = $('.jquery-bar').height();
        var window_height = $(window).height();
        $('#iframe').attr('height', ((window_height - 0) - headerHeight - jquerybar) + 'px');
        $('#iframe-wrap').height(window_height - headerHeight - jquerybar);
    }

    $(window).resize(function () {
        fixHeight();
    }).resize();

    //LIST ITEM SWITCH
    $('ul.items li').click(function () {
        if(!($(this).hasClass('active'))){
            OpenDemo(this);
        }
        return false;
    });

    //RESPONSIVE TRIGGERS
    $('.desktop').click(function () {
        $('#iframe-wrap').removeClass().addClass('desktop-resize');
        $('.ipad-portrait,.ipad-landscape,.iphone,.desktop,.iphone-landscape').removeClass('active');
        $(this).addClass('active');
        $('body').addClass('full');
        return false;
    });

    $('.ipad-portrait').click(function () {
        $('#iframe-wrap').removeClass().addClass('ipad-portrait-resize');
        $('.ipad-portrait,.ipad-landscape,.iphone,.desktop,.iphone-landscape').removeClass('active');
        $(this).addClass('active');
        $('body').removeClass('full');
        return false;
    });

    $('.ipad-landscape').click(function () {
        $('#iframe-wrap').removeClass().addClass('ipad-landscape-resize');
        $('.ipad-portrait,.ipad-landscape,.iphone,.desktop,.iphone-landscape').removeClass('active');
        $(this).addClass('active');
        $('body').removeClass('full');
        return false;
    });

    $('.iphone').click(function () {
        $('#iframe-wrap').removeClass().addClass('iphone-resize');
        $('.ipad-portrait,.ipad-landscape,.iphone,.desktop,.iphone-landscape').removeClass('active');
        $(this).addClass('active');
        $('body').removeClass('full');
        return false;
    });
    $('.iphone-landscape').click(function () {
        $('#iframe-wrap').removeClass().addClass('iphone-landscape-resize');
        $('.ipad-portrait,.ipad-landscape,.iphone,.desktop,.iphone-landscape').removeClass('active');
        $(this).addClass('active');
        $('body').removeClass('full');
        return false;
    });

    $('.desktop').addClass('active');
});