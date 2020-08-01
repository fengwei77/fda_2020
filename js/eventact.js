
$(document).ready(function() {


  TweenMax.to($('.bbg,.popup'), 0, {autoAlpha:0,ease:Quad.easeOut});



  $('body').jpreLoader({
    splashID: "#jSplash",
    loaderVPos: '50%',
    autoClose: true
    
  }, function() { 
     gameBanimate();
  }); 
 

});



/*第二之一關*/
function gameBanimate(){
  TweenMax.to($('.niuup'), 0.8, {rotation:5,transformOrigin:"bottom center", repeat:-1, yoyo:true,ease:Power0.easeNone});
  TweenMax.to($('.hanboyup'), 0.8, {rotation:-5,transformOrigin:"bottom center", repeat:-1, yoyo:true,ease:Power0.easeNone});




}

