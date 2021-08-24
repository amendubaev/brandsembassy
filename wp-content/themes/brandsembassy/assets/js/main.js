(function() {
    var button, list;
    button = $(".languages-switcher");

    list = $(".languages-list");

    button.on("click", function() {
        list.toggle();
        return button.toggleClass("active");
    });

    list.children().on("click", function() {
        button.html($(this).html());
        button.css("color", "#72d2ff");
        list.toggle();
        return button.toggleClass("active");
    });
}.call(this));

$('.menu-nav--burger').on('click', function(){
    $(this).toggleClass('menu-nav--active');
    $('body').toggleClass('menu-open');
});

// Get params from url
function getURLParameters(sParam, multiple = true){
    if (multiple) {
        sParam += '%5B%5D';
    }

    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');

    var result = [];
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] == sParam) {
            result.push(sParameterName[1]);
        }
    }

    return result;
}

// Sort on industries page

$('.filter-selected').click(function () {
    $('#sorting').toggleClass('sort-active');
});
$(document).ready(function () {
    var selectedSort = $('.sort-selected').text();
    $('.filter-sort--link').text(selectedSort);
    
    // Sort branches
    $('.sort-icons .icon').click(function () {
        var sortType = $(this).data('view');
        $(this).addClass('sort-active').siblings().removeClass('sort-active');
        $('.grid-list[data-view="' + sortType + '"]').addClass('d-block').siblings('.section-sortable--view').removeClass('d-block');
    });
});

// $('#sorting').on('change', function (e) {
//     var url = window.location.href.split('?')[0];

//     // if url have query values, check if order exist and update value or add value
//     if (location.search.length) {
//         var order = getURLParameters('order', false);

//         if (order.length !== 0) {
//             // change order value
//             if (order[0] == this.value) {
//                 url = location.search
//             } else {

//                 url = location.search.replace(order[0], this.value);
//             }
//         } else {
//             // append order
//             url = location.search + "&order=" + this.value;
//         }

//     } else {
//         // add order for empty query string
//         url += "?order=" + this.value;
//     }

//     window.location.href = url;
// });

// Home sliders
$('.cards-slider').slick({
    dots: false,
    arrows: false,
    infinite: true,
    speed: 800,
    variableWidth: true,
    swipeToSlide: true,
    rows: 0,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
            }
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            }
        }
    ]
});

$('.cards-slider').on('afterChange', function (event, slick, currentSlide, nextSlide) {
    var currentCarousel = $(this).data('carousel');
    var currentCounter = $('.slider-counter[data-carousel="' + currentCarousel + '"]');
    $(currentCounter).find('.slider-counter--current').text(slick.currentSlide);
    $(currentCounter).find('.slider-counter--total').text(slick.slideCount);
});

$('.events-slider').slick({
    dots: false,
    arrows: false,
    infinite: true,
    rows: 0,
    speed: 300,
    swipeToSlide: true,
    variableWidth: true,
    slidesToShow: 1,
    slidesToScroll: 1,
});

$('.slick-prev').on('click', function () {
    var currentCarousel = $(this).data('carousel');
    $('.slick-slider[data-carousel="' + currentCarousel + '"]').slick('slickPrev');
});
  
$('.slick-next').on('click', function () {
    var currentCarousel = $(this).data('carousel');
    $('.slick-slider[data-carousel="' + currentCarousel + '"]').slick('slickNext');
});

// Social sharing
$('.icon-share').on('click', function () {
    $('.sharing-tooltip').toggleClass('sharing-tooltip--active');
});
$('.sharing-tooltip').jsSocials({
    shares: [
        { share: 'facebook', logo: 'icon icon-share--facebook' },
        { share: 'twitter', logo: 'icon icon-share--twitter' },
        { share: 'vkontakte', logo: 'icon icon-share--vk' },
        { share: 'linkedin', logo: 'icon icon-share--linkedin' },
    ],
    showLabel: false,
    showCount: true,
    shareIn: 'popup',
    on: {
        click: function(e) {},
        mouseenter: function(e) {},
        mouseleave: function(e) {},
    },
});

$('.filter-button').on('click', function () {
    $(this).toggleClass('filter-button--active');
    $('.filters').toggleClass('d-block');
    if ($(this).hasClass('filter-button--active')) {
        $(this).text('Скрыть фильтры');
    } else {
        $(this).text('Показать фильтры');
    }
});

// Popup settings
$('.button-popup').on('click', function (e) {
	e.preventDefault();
    var popup = $(this).data('popup');
    $('.popup[data-popup="' + popup + '"]').toggleClass('popup-active');
    $('body').addClass('popup-open');
    if(popup == 'video-promo') {
        $('video').get(0).play();
    }
});

$('.popup').on('click', function(e) {
	if( $(e.target).is('.popup-close') || $(e.target).is('.popup') ) {
		e.preventDefault();
        var popup = $(this).data('popup');
		$(this).removeClass('popup-active');
		$('body').removeClass('popup-open');
        if(popup == 'video-promo') {
            $('video').get(0).pause();
	    }
    }
});
$(document).keyup(function(event){
    if(event.which == '27') {
        $('.popup').removeClass('popup-active');
		$('body').removeClass('popup-open');
        $('video').get(0).pause();
    }
});

// Show card info
$('.card-item--controllers').on('click', function (e) {
    e.preventDefault();
    $(this).parent().parent().toggleClass('card-hovered');
});