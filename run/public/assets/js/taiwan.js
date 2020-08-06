$("#Hsinchu-City, #Hsinchu-City, #Taichung-City, #Miaoli-County, #Nantou-County, #New-Taipei-City, #Taipei-City, #Keelung-City, #Chiayi-City, #Taoyuan-City, #Hsinchu-County, #Chiayi-County, #Kaohsiung-City, #Pingtung-County, #Taitung-County, #Yilan-County, #Hualien-County, #Changhua-County, #Tainan-City, #Yunlin-County").bind('mouseover',function(event){
    $(this).removeClass("cls-1").addClass("cls-2");
    hideTaiwanCityLabel($(this).attr('id'));

}).bind('mouseleave',function(){
    $(this).removeClass("cls-2").addClass("cls-1");
    hideTaiwanCityLabel();
}).bind('click',function(){
    window.location.href = "review.php?sort=time&city="+encodeURIComponent($(this).data("city"))+"#review-unit";
    return false;
});

$("#Penghu-County, #Lianjiang-County, #Kinmen-County").bind('mouseover',function(event){
    $(this).find('polygon, path').removeClass("cls-1").addClass("cls-2");
    hideTaiwanCityLabel($(this).attr('id'));
}).bind('mouseleave',function(){
    $(this).find('polygon, path').removeClass("cls-2").addClass("cls-1");
    hideTaiwanCityLabel();
}).bind('click',function(){
    window.location.href = "review.php?sort=time&city="+encodeURIComponent($(this).data("city"))+"#review-unit";
    return false;
});

function hideTaiwanCityLabel(a)
{
    $("#Hsinchu-City-Label, #Hsinchu-City-Label, #Taichung-City-Label, #Miaoli-County-Label, #Nantou-County-Label, #New-Taipei-City-Label, #Taipei-City-Label, #Keelung-City-Label, #Chiayi-City-Label, #Taoyuan-City-Label, #Hsinchu-County-Label, #Chiayi-County-Label, #Kaohsiung-City-Label, #Pingtung-County-Label, #Taitung-County-Label, #Yilan-County-Label, #Hualien-County-Label, #Changhua-County-Label, #Tainan-City-Label, #Yunlin-County-Label, #Penghu-County-Label, #Lianjiang-County-Label, #Kinmen-County-Label").hide();
    if(a!="")
    {
        $("#"+a+"-Label").show();
    }
}



