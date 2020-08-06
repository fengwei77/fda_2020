$(document).ready(function(){
	window.sr = ScrollReveal({ 
		reset: false
	});
		
	// Customizing a reveal set
	sr.reveal('.foo', { duration: 1000 });
	//speical ani
	sr.reveal('.smooth', { delay:1000, scale: 2, easing: 'ease-in', distance: '-100px' });
	sr.reveal('.smoothde', { delay:1500, scale: 3, easing: 'ease-in' });
	//spec middle
	sr.reveal('.refrommiddle-spec', { duration: 1000,delay: 0, scale: 1, origin: '', distance: '0px', viewFactor:0.6});

	/*delay0*/
	//bottom
	sr.reveal('.refrombottom', { duration: 1000,delay: 0, scale: 1, origin: 'bottom', distance: '100px'});
	//top
	sr.reveal('.refromtop', { duration: 1000,delay: 0, scale: 1, origin: 'top', distance: '100px'});
	//middle
	sr.reveal('.refrommiddle', { duration: 1000,delay: 0, scale: 1, origin: '', distance: '0px'});
	//left
	sr.reveal('.refromleft', { duration: 1000,delay: 0, scale: 1, origin: 'left', distance: '50px'});
	//right
	sr.reveal('.refromright', { duration: 1000,delay: 0, scale: 1, origin: 'right', distance: '50px'});
	
	/*delay100*/
	//bottom
	sr.reveal('.refrombottomde', { duration: 1000,delay: 250, scale: 1, origin: 'bottom', distance: '100px'});
	//top
	sr.reveal('.refromtopde', { duration: 1000,delay: 250, scale: 1, origin: 'top', distance: '100px'});
	//middle
	sr.reveal('.refrommiddlede', { duration: 1000,delay: 250, scale: 1, origin: '', distance: '0px'});
	//left
	sr.reveal('.refromleftde', { duration: 1000,delay: 250, scale: 1, origin: 'left', distance: '100px'});
	//right
	sr.reveal('.refromrightde', { duration: 1000,delay: 250, scale: 1, origin: 'right', distance: '100px'});
	
	/*delay200*/
	//bottom
	sr.reveal('.refrombottomdex', { duration: 1000,delay: 500, scale: 1, origin: 'bottom', distance: '100px'});
	//top
	sr.reveal('.refromtopdex', { duration: 1000,delay: 500, scale: 1, origin: 'top', distance: '100px'});
	//middle
	sr.reveal('.refrommiddledex', { duration: 1000,delay: 500, scale: 1, origin: '', distance: '0px'});
	//left
	sr.reveal('.refromleftdex', { duration: 1000,delay: 500, scale: 1, origin: 'left', distance: '100px'});
	//right
	sr.reveal('.refromrightdex', { duration: 1000,delay: 500, scale: 1, origin: 'right', distance: '100px'});
});
