
$(document).ready(function(){
    $('.banner-carousel').owlCarousel({
        loop:true,
        responsiveClass:true,
        autoplay:true,
        items: 1,
        autoplayTimeout:4000,
        autoplayHoverPause:true,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        nav: true,
        dots: false,
         navText:[
        "<i class='fa fa-angle-left'></i>",
        "<i class='fa fa-angle-right'></i>"
        ],
    })
});

$(document).ready(function(){
    $('.categories-carousel').owlCarousel({
        loop:true,
        responsiveClass:true,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            500: {
                items: 2
            },
            767: {
                items: 3
            },
            992: {
                items: 6
            },
        }
    })
});

$(document).ready(function(){
    $('.video-carousel').owlCarousel({
        loop:false,
        responsiveClass:true,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        nav: true,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            767: {
                items: 3
            },
            992: {
                items: 4
            },
        }
    })
});

$(document).ready(function(){
    $('.pdf-carousel').owlCarousel({
        loop:false,
        responsiveClass:true,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        nav: true,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            767: {
                items: 3
            },
            992: {
                items: 4
            },
        }
    })
});

$(document).ready(function() {

    $('.video').magnificPopup({
        type: 'iframe',
        delegate: '.video-play',
        gallery: {
            enabled: true
        }

    });

});

