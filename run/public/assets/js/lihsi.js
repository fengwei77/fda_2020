/**
*
* @authors Your Name (you@example.org)
* @date    2017-09-06 16:28:10
* @version $Id$
*/
// if the image in the window of browser when the page is loaded, show that image
var orangeWidth = $(window).width();
var currentWidth = $(window).width();

$(document).ready(function() {
    resetMobileMenu();

    var kkWidth = $(window).width();
    // $("#showWidth").text(kkWidth);
});

$(window).resize(function() {

    currentWidth = $(window).width();

    if(orangeWidth!=currentWidth)
    {
        resetMobileMenu();
    }

    kkWidth = $(window).width();
    // $("#showWidth").text(kkWidth);
});

$(window).scroll(function() {
    var scrollVal = $(this).scrollTop();

    if((scrollVal > 20) && (currentWidth <=960))
    {
        $("header").css("background-color", "#8c1025");
    }
    else
    {
        $("header").css("background-color", "transparent");
    }
});

$(function(){

    $('header .switch i').click(function(){
        if($(this).hasClass("la-bars"))
        {
            $('nav').show();
            $(this).removeClass("la-bars");
            $(this).addClass("la-times");
        }
        else
        {
            $('nav').hide();
            $(this).addClass("la-bars");
            $(this).removeClass("la-times");
        }
    });
});

function gotop(){
    $("html,body").animate({scrollTop:0},1000,"easeOutExpo");
    return false;
}

function resetMobileMenu()
{
    $('header .switch i').addClass("la-bars");
    $('header .switch i').removeClass("la-times");

    if(currentWidth > 960) {
        $('nav').show();
    }
    else
    {
        $('nav').hide();
    }
}

function openMask(a)
{
    $("."+a).show();
}

function closeMask(a)
{
    $("."+a).hide();
}

function chsme(){
    $('*[class^="chsme chs-"]').hide();
    $('*[class^="chsme-mobile chs-"]').hide();

    var chs_me_arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];//原有陣列放全部數字
    var ranNum = 3;

    for (var i = 0; i < ranNum; i++) {
        var ran = Math.floor(Math.random() * chs_me_arr.length);
        $('.chsme.chs-' + ran).show();
    };

    var chs_me_mobile_arr = [1, 2, 3, 4];//原有陣列放全部數字
    var ranNum_mobile = 1;

    for (var m = 0; m < ranNum_mobile; m++) {
        var ran_mobile = Math.floor(Math.random() * chs_me_mobile_arr.length);
        $('.chsme-mobile.chs-' + ran_mobile).show();
        console.log(ran_mobile);
    };

    setTimeout(chsme, 1000);
}

//image over change image
var sourceSwap = function () {
    var $this = $(this);
    var newSource = $this.data('alt-src');
    $this.data('alt-src', $this.attr('src'));
    $this.attr('src', newSource);
}

$(function() {
    $('img[data-alt-src]').each(function() {
        new Image().src = $(this).data('alt-src');
    }).hover(sourceSwap, sourceSwap);
});

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};
