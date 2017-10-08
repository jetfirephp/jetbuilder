$(window).load(function() {
    "use strict";
    $("#pageloader").delay(1200).fadeOut("slow"), $(".loader-item").delay(700).fadeOut();
	
	$('.right-content, #map-holder').height('auto');
	$('.right-content, #map-holder').equalHeights();
}), $(document).ready(function() {
    "use strict";

    $('#main-menu li').removeClass('active');
    $('#main-menu li a[href="'+window.location.href+'"]').parent().addClass('active');

	$(window).width() > 991 && $(function() {
       	$('.right-content, #map-holder').height('auto');
		$('.right-content, #map-holder').equalHeights();
    }),
	
	$(window).resize(function() {
		$('.right-content, #map-holder').height('auto');
		$('.right-content, #map-holder').equalHeights();
    });
	
    $(window).width() > 767 && $(function() {
        var e = $("#nav-wrap"),
            i = 0,
            o = 150;
        $(window).scroll(function() {
            i = $(window).scrollTop(), i >= o ? e.addClass("navbar-fixed-top animated fadeInDown") : e.removeClass("navbar-fixed-top animated fadeInDown")
        })
    }), $(window).width() < 767 && $(function() {
        var e = $(".style-2 header"),
            i = 0,
            o = 100;
        $(window).scroll(function() {
            i = $(window).scrollTop(), i >= o ? e.addClass("header-static") : e.removeClass("header-static")
        })
    }), jQuery("#main-menu").menuzord({
        align: "right",
        animation: "none",
        effect: "slide",
        indicatorFirstLevel: "<i class='fa fa-angle-down'></i>",
        indicatorSecondLevel: "<i class='fa fa-angle-right'></i>"
    }), jQuery("#left-menu").menuzord({
        animation: "none",
        effect: "slide",
        indicatorFirstLevel: "<i class='fa fa-angle-down'></i>",
        indicatorSecondLevel: "<i class='fa fa-angle-right'></i>"
    }), $(".counter").counterUp({
        delay: 10,
        time: 1e3
    });
    var e;
    e = jQuery(".bg-video").YTPlayer(), $(".collapse").on("shown.bs.collapse", function() {
        $(this).parent().find(".fa-plus").removeClass("fa-plus").addClass("fa-minus")
    }).on("hidden.bs.collapse", function() {
        $(this).parent().find(".fa-minus").removeClass("fa-minus").addClass("fa-plus")
    }), $(".skillbar").appear(), $(".skillbar").on("appear", function() {
        $(this).find(".skillbar-bar").animate({
            width: $(this).attr("data-percent")
        }, 3e3)
    }), jQuery(".play-video").on("click", function(e) {
        var i = jQuery(".video-box");
        i.prepend('<iframe src="https://www.youtube.com/embed/HOOepHfFBoY" width="500" height="281" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'), i.fadeIn(300), e.preventDefault()
    }), jQuery(".close-video").on("click", function(e) {
        jQuery(".video-box").fadeOut(400, function() {
            jQuery("iframe", this).remove().fadeOut(300)
        })
    }), $(".team-social ul li a, .demo-button a, .portfolio-buttons a, .social-icons li a").tooltip({
        placement: "top",
        animation: !0,
        delay: {
            show: 200,
            hide: 100
        }
    }), /*$(".tweet-stream").tweet({
        username: "envato",
        modpath: "twitter/",
        count: 1,
        template: "{text}{time}",
        loading_text: "loading twitter feed..."
    }), */$("#basicuse").jflickrfeed({
        limit: 9,
        qstrings: {
            id: "52617155@N08"
        },
        itemTemplate: '<li><a href="{{image_b}}"><img src="{{image_s}}" alt="{{title}}" /></a></li>'
    }), $(window).scroll(function() {
        $(this).scrollTop() > 100 ? $(".scrollToTop").fadeIn() : $(".scrollToTop").fadeOut()
    }), $(".scrollToTop").click(function() {
        return $("html, body").animate({
            scrollTop: 0
        }, 800), !1
    }), jQuery("#pie-charts").waypoint(function(e) {
        jQuery(".chart").easyPieChart({
            barColor: "#59585b",
            onStep: function(e, i, o) {
                jQuery(this.el).find(".percent").text(Math.round(o))
            }
        })
    }, {
        offset: function() {
            return jQuery.waypoints("viewportHeight") - jQuery(this).height() + 200
        }
    }), $("#home-testimonial").owlCarousel({
        items: 1,
        margin: 0,
        loop: !0,
        nav: !0,
        slideBy: 1,
        dots: !1,
        center: !1,
        autoplay: !1,
        autoheight: !0,
        navText: ["&#xf104;", "&#xf105"]
    }), $("#home-portfolio").owlCarousel({
        items: 1,
        margin: 0,
        loop: !0,
        nav: !0,
        slideBy: 1,
        dots: !1,
        center: !1,
        autoplay: !0,
        autoheight: !0,
        navText: ["&#xf104;", "&#xf105"],
        responsive: {
            320: {
                items: 1
            },
            480: {
                items: 2
            },
            600: {
                items: 3
            },
            1e3: {
                items: 5,
                loop: !0
            },
            1200: {
                items: 5,
                loop: !0
            }
        }
    }), $("#home-featured-sidebar").owlCarousel({
        items: 1,
        margin: 0,
        loop: !0,
        nav: !0,
        slideBy: 1,
        dots: !1,
        center: !1,
        autoplay: !0,
        autoheight: !0,
        navText: ["&#xf104;", "&#xf105"],
        responsive: {
            320: {
                items: 1
            },
            480: {
                items: 2
            },
            600: {
                items: 2
            },
            1e3: {
                items: 3,
                loop: !0
            },
            1200: {
                items: 3,
                loop: !0
            }
        }

    }), $("#shop-featured").owlCarousel({
        items: 1,
        margin: 20,
        loop: !0,
        nav: !0,
        slideBy: 1,
        dots: !1,
        center: !1,
        autoplay: !0,
        autoheight: !0,
        navText: ["&#xf104;", "&#xf105"],
        responsive: {
            320: {
                items: 1
            },
            480: {
                items: 2
            },
            600: {
                items: 3
            },
            1e3: {
                items: 4,
                loop: !0
            },
            1200: {
                items: 4,
                loop: !0
            }
        }
    }), $("#portfolio-single").owlCarousel({
        items: 1,
        margin: 0,
        loop: !0,
        nav: !0,
        slideBy: 1,
        dots: !1,
        center: !1,
        autoplay: !1,
        autoheight: !0,
        navText: ["&#xf104;", "&#xf105"]
    }), $("#post-slider").owlCarousel({
        items: 1,
        margin: 0,
        loop: !0,
        nav: !1,
        slideBy: 1,
        dots: !0,
        center: !0,
        autoplay: !1,
        autoheight: !0,
        navText: ["&#xf104;", "&#xf105"]
    }), $("#related-projects").owlCarousel({
        items: 1,
        margin: 30,
        loop: !0,
        nav: !0,
        slideBy: 1,
        dots: !1,
        center: !1,
        autoplay: !1,
        autoheight: !0,
        navText: ["&#xf104;", "&#xf105"],
        responsive: {
            320: {
                items: 1
            },
            480: {
                items: 2
            },
            600: {
                items: 3
            },
            1e3: {
                items: 3,
                loop: !0
            },
            1200: {
                items: 3,
                loop: !0
            }
        }
    }), $("#home-clients").owlCarousel({
        items: 1,
        margin: 30,
        loop: !0,
        nav: !1,
        slideBy: 1,
        dots: !0,
        center: !1,
        autoplay: !1,
        autoheight: !0,
        navText: ["&#xf104;", "&#xf105"],
        responsive: {
            320: {
                items: 2
            },
            480: {
                items: 2
            },
            600: {
                items: 4
            },
            1e3: {
                items: 5,
                loop: !0
            },
            1200: {
                items: 6,
                loop: !0
            }
        }
    })/*, $("#map_extended").gMap({
        markers: [{
            address: "",
            html: '<h4>Office</h4><address><div><div><b>Address:</b></div><div>Envato Pty Ltd, 13/2<br> Elizabeth St Melbourne VIC 3000,<br> Australia</div></div><div><div><b>Phone:</b></div><div>+1 (408) 786 - 5117</div></div><div><div><b>Fax:</b></div><div>+1 (408) 786 - 5227</div></div><div><div><b>Email:</b></div><div><a href="mailto:info@soar.com">info@info@soar.com</a></div></div></address>',
            latitude: -33.87695388579145,
            longitude: 151.22183918952942,
            icon: {
                image: "images/pin.png",
                iconsize: [35, 48],
                iconanchor: [17, 48]
            }
        }],
        icon: {
            image: "images/pin.png",
            iconsize: [35, 48],
            iconanchor: [17, 48]
        },
        latitude: -33.87695388579145,
        longitude: 151.22183918952942,
        zoom: 16
    }),*/ /*jQuery("#register_form").validate({
        meta: "validate",
        submitHandler: function (e) {
            var s = $("#society").val(),
                f = $("#first_name").val(),
                l = $("#last_name").val(),
                a = $("#address").val(),
                p = $("#postal_code").val(),
                c = $("#city").val(),
                t = $("#phone").val(),
                em = $("#email").val(),
                ps = $("#password").val(),
                cps = $("#confirm_pass").val(),
                cg = $("#cg").val();
            return $.post(api, {
                society: {
                    name: s
                },
                account: {
                    first_name: f,
                    last_name: l,
                    phone: t,
                    email: em,
                    password: ps,
                    confirm_pass: cps
                },
                address: {
                    address: a,
                    postal_code: p,
                    city: c
                },
                cg: cg
            }, function (response) {
                (response.status == 'error')
                    ? $("#responsemessage").addClass('alert-danger')
                    : $("#responsemessage").addClass('alert-theme');
                $("#responsemessage").append(response.message);
            }), $("#register_form").hide(), !1
        },
        rules: {
            society: {
                name: "required"
            },
            account: {
                first_name: "required",
                last_name: "required",
                phone: {
                    required: true,
                    minlength: 9
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_pass: {
                    required: true,
                    minlength: 5,
                    equalTo: '#password'
                }
            },
            address: {
                address: "required",
                postal_code: {
                    required: true,
                    number: true
                },
                city: "required"
            },
            cg: "required",
            _token: "required"
        },
        messages: {
            society: "Le nom de la société est requis",
            first_name: "Votre prénom est requis",
            last_name: "Votre nom est requis",
            phone: {
                required: "Le numéro de téléphone est requis",
                minlength: "Le format du numéro de téléphone n'est pas correct"
            },
            email: {
                required: "L'email est requis",
                email: "Le format du mail est incorrect"
            },
            password: {
                required: "Le mot de passe est requis",
                minlength: "Le mot de passe doit contenir minimum 5 caractères"
            },
            confirm_pass: {
                required: "La confirmation du mot de passe est requis",
                minlength: "Le mot de passe doit contenir minimum 5 caractères",
                equalTo: "Les mots de passe ne sont pas identiques"
            },
            address: "L'adresse est requis",
            postal_code: {
                required: "Le code postal est requis",
                number: "Le format du code postal est incorrect"
            },
            city: "La ville est requis",
            cg: "Veuillez accepter les CGU",
            _token: "Le token est requis"
        }
    }),*/ $("#js-grid-blog-posts").cubeportfolio({
        filters: "#js-filters-blog-posts",
        search: "#js-search-blog-posts",
        defaultFilter: "*",
        animationType: "3dflip",
        gapHorizontal: 50,
        gapVertical: 30,
        gridAdjustment: "responsive",
        mediaQueries: [{
            width: 1500,
            cols: 4
        }, {
            width: 1100,
            cols: 3
        }, {
            width: 800,
            cols: 3
        }, {
            width: 480,
            cols: 2
        }, {
            width: 320,
            cols: 1
        }],
        caption: "revealBottom",
        displayType: "fadeIn",
        displayTypeSpeed: 400
    }), $("#js-grid-awesome-work").cubeportfolio({
        filters: "#js-filters-awesome-work",
        loadMore: "#js-loadMore-awesome-work",
        loadMoreAction: "click",
        layoutMode: "grid",
        defaultFilter: "*",
        animationType: "scaleSides",
        gapHorizontal: 20,
        gapVertical: 20,
        gridAdjustment: "responsive",
        mediaQueries: [{
            width: 1500,
            cols: 4
        }, {
            width: 1100,
            cols: 3
        }, {
            width: 480,
            cols: 2
        }, {
            width: 320,
            cols: 1
        }],
        caption: "zoom",
        displayType: "fadeIn",
        displayTypeSpeed: 400
    }), $("#js-styl2-mosaic").cubeportfolio({
        filters: "#js-filters-styl2-mosaic",
        loadMore: "#js-loadMore-styl2-mosaic",
        loadMoreAction: "click",
        layoutMode: "mosaic",
        sortToPreventGaps: !0,
        defaultFilter: "*",
        animationType: "quicksand",
        gapHorizontal: 0,
        gapVertical: 0,
        gridAdjustment: "responsive",
        mediaQueries: [{
            width: 1500,
            cols: 5
        }, {
            width: 1100,
            cols: 4
        }, {
            width: 800,
            cols: 3
        }, {
            width: 480,
            cols: 2
        }, {
            width: 320,
            cols: 1
        }],
        caption: "zoom",
        displayType: "fadeIn",
        displayTypeSpeed: 400
    }), $("#js-styl2-mosaic-flat").cubeportfolio({
        filters: "#js-filters-styl2-mosaic-flat",
        loadMore: "#js-loadMore-styl2-mosaic-flat",
        loadMoreAction: "click",
        layoutMode: "mosaic",
        sortToPreventGaps: !0,
        defaultFilter: "*",
        animationType: "quicksand",
        gapHorizontal: 0,
        gapVertical: 0,
        gridAdjustment: "responsive",
        mediaQueries: [{
            width: 1500,
            cols: 6
        }, {
            width: 1100,
            cols: 5
        }, {
            width: 800,
            cols: 3
        }, {
            width: 480,
            cols: 2
        }, {
            width: 320,
            cols: 1
        }],
        caption: "zoom",
        displayType: "fadeIn",
        displayTypeSpeed: 400
    }), $("#js-grid-awesome-work-2col").cubeportfolio({
        filters: "#js-filters-awesome-work-2col",
        loadMore: "#js-loadMore-awesome-work-2col",
        loadMoreAction: "click",
        layoutMode: "grid",
        defaultFilter: "*",
        animationType: "scaleSides",
        gapHorizontal: 20,
        gapVertical: 20,
        gridAdjustment: "responsive",
        mediaQueries: [{
            width: 1500,
            cols: 2
        }, {
            width: 1100,
            cols: 2
        }, {
            width: 480,
            cols: 2
        }, {
            width: 320,
            cols: 1
        }],
        caption: "zoom",
        displayType: "fadeIn",
        displayTypeSpeed: 400
    }), $("#js-grid-mosaic-projects").cubeportfolio({
        filters: "#js-filters-awesome-work",
        loadMore: "#js-loadMore-awesome-work",
        loadMoreAction: "click",
        layoutMode: "mosaic",
        defaultFilter: "*",
        animationType: "quicksand",
        gapHorizontal: 20,
        gapVertical: 20,
        gridAdjustment: "responsive",
        mediaQueries: [{
            width: 1500,
            cols: 5
        }, {
            width: 1100,
            cols: 4
        }, {
            width: 800,
            cols: 3
        }, {
            width: 480,
            cols: 2
        }, {
            width: 320,
            cols: 1
        }],
        caption: "zoom",
        displayType: "sequentially",
        displayTypeSpeed: 80
    }), $("#js-grid-full-width").cubeportfolio({
        filters: "#js-filters-full-width",
        loadMore: "#js-loadMore-full-width",
        loadMoreAction: "click",
        layoutMode: "mosaic",
        sortToPreventGaps: !0,
        defaultFilter: "*",
        animationType: "fadeOutTop",
        gapHorizontal: 0,
        gapVertical: 0,
        gridAdjustment: "responsive",
        mediaQueries: [{
            width: 1500,
            cols: 5
        }, {
            width: 1100,
            cols: 4
        }, {
            width: 800,
            cols: 3
        }, {
            width: 480,
            cols: 2
        }, {
            width: 320,
            cols: 1
        }],
        caption: "zoom",
        displayType: "fadeIn",
        displayTypeSpeed: 100,
        lightboxDelegate: ".cbp-lightbox",
        lightboxGallery: !0,
        lightboxTitleSrc: "data-title",
        lightboxCounter: '<div class="cbp-popup-lightbox-counter">{{current}} of {{total}}</div>'
    }), new WOW({
        animateClass: "animated",
        offset: 100
    }).init(), $(".icon").on("click", function(e) {
        $(".search-box").toggleClass("expanded"), e.preventDefault()
    });
    var i, o = jQuery;
    o(document).ready(function() {
        void 0 == o("#rev_slider_4_1").revolution ? revslider_showDoubleJqueryError("#rev_slider_4_1") : i = o("#rev_slider_4_1").show().revolution({
            sliderType: "classic",
            jsFileLocation: "../revolution/js",
            sliderLayout: "auto",
            dottedOverlay: "none",
            delay: 9e3,
            navigation: {
                keyboardNavigation: "off",
                keyboard_direction: "horizontal",
                mouseScrollNavigation: "off",
                onHoverStop: "on",
                touch: {
                    touchenabled: "on",
                    swipe_threshold: 75,
                    swipe_min_touches: 1,
                    swipe_direction: "horizontal",
                    drag_block_vertical: !1
                },
                arrows: {
                    style: "zeus",
                    enable: !0,
                    hide_onmobile: !0,
                    hide_under: 600,
                    hide_onleave: !0,
                    hide_delay: 200,
                    hide_delay_mobile: 1200,
                    tmp: '<div class="tp-title-wrap">  	<div class="tp-arr-imgholder"></div> </div>',
                    left: {
                        h_align: "left",
                        v_align: "center",
                        h_offset: 30,
                        v_offset: 0
                    },
                    right: {
                        h_align: "right",
                        v_align: "center",
                        h_offset: 30,
                        v_offset: 0
                    }
                },
                bullets: {
                    enable: !0,
                    hide_onmobile: !0,
                    hide_under: 600,
                    style: "hermes",
                    hide_onleave: !0,
                    hide_delay: 200,
                    hide_delay_mobile: 1200,
                    direction: "horizontal",
                    h_align: "center",
                    v_align: "bottom",
                    h_offset: 0,
                    v_offset: 30,
                    space: 5,
                    tmp: ""
                }
            },
            viewPort: {
                enable: !0,
                outof: "pause",
                visible_area: "80%"
            },
            responsiveLevels: [1240, 1024, 778, 480],
            gridwidth: [1240, 1024, 778, 480],
            gridheight: [600, 600, 500, 400],
            lazyType: "none",
            parallax: {
                type: "mouse",
                origo: "slidercenter",
                speed: 2e3,
                levels: [2, 3, 4, 5, 6, 7, 12, 16, 10, 50]
            },
            shadow: 0,
            spinner: "off",
            stopLoop: "off",
            stopAfterLoops: -1,
            stopAtSlide: -1,
            shuffle: "off",
            autoHeight: "off",
            hideThumbsOnMobile: "off",
            hideSliderAtLimit: 0,
            hideCaptionAtLimit: 0,
            hideAllCaptionAtLilmit: 0,
            debugMode: !1,
            fallbacks: {
                simplifyAll: "off",
                nextSlideOnWindowFocus: "off",
                disableFocusListener: !1
            }
        })
    }), $(".btn-settings").on("click", function() {
        $(this).parent().toggleClass("active")
    }), $(".color-list div").on("click", function() {
        if ($(this).hasClass("active")) return !1;
        $("link.color-scheme-link").remove(), $(this).addClass("active").siblings().removeClass("active");
        var e = $(this).attr("data-src"),
            i = $('<link class="color-scheme-link" rel="stylesheet" />');
        i.attr("href", e).appendTo("head")
    }), $(".reset").on("click", function() {
        if ($(".color-list div").removeClass("active"), $(this).hasClass("active")) return !1;
        $("link.color-scheme-link").remove();
        var e = $(this).attr("data-src"),
            i = $('<link class="color-scheme-link" rel="stylesheet" />');
        i.attr("href", e).appendTo("head")
    }), $(".reset span").on("click", function() {
        var e = $(this).attr("class");
        $("body").attr("class", e)
    })
});