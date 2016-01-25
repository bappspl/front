/* ==================================================================
                		PRELOADER
================================================================== */
Pace.on('done', function() {
  jQuery("#loader").fadeOut(200);
  $('body').addClass('loaded');
});


$(document).ready(function(){
	"use strict";

/* ==================================================================
                FOR SCROLL UP BUTTON
================================================================== */
	$.scrollUp({
		scrollName: 'scrollUp', // Element ID
		scrollDistance: 0, // Distance from top/bottom before showing element (px)
		scrollFrom: 'top', // 'top' or 'bottom'
		scrollSpeed: 300, // Speed back to top (ms)
		easingType: 'linear', // Scroll to top easing (see http://easings.net/)
		animation: 'fade', // Fade, slide, none
		animationInSpeed: 200, // Animation in speed (ms)
		animationOutSpeed: 200, // Animation out speed (ms)
		scrollText: '<i class="pe-7s-angle-up"></i>Do góry', // Text for element, can contain HTML
		scrollTitle: true, // Set a custom <a> title if required. Defaults to scrollText
		scrollImg: false, // Set true to use image
		activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
		zIndex: 10001 // Z-Index for the overlay
	});


/* ==================================================================
                USED FOR CLICK TO HIDE MENU
================================================================== */
jQuery(".nav a").on("click", function () {
    jQuery("#nav-menu").removeClass("in").addClass("collapse")
});




/* ==================================================================
                		Homepage Slider
================================================================== */
	$( '#az-hero-slider' ).sliderPro({
		width: '100%',
		// height: '100%',
	    fade: true,
	    forceSize: 'fullWindow',
	    arrows: true,
	    waitForLayers: true,
	    buttons: false,
	    autoplay: false,
	    autoplayDelay: 6000,
	    autoScaleLayers: false,
	    slideAnimationDuration: 1500
	});

	$('.sp-mask').css({'height':($(window).height() - 120 ) +'px'});
	$('.sp-slide').css({'height':($(window).height() - 120 ) +'px'});


/* ==================================================================
                		SKILLBAR
================================================================== */
$('.az-skillbar').waypoint(function(direction) {
	$('.az-skillbar[data-percent]').each(function () {
		var skillbarWrapper = $(this);
	    var progress = $(this).find('span.skill-bar-percent');
	    var percentage = Math.ceil($(this).attr('data-percent'));
		    $({countNum: 0}).animate({countNum: percentage}, {
		      duration: 4000,
		      step: function() {
		        // What todo on every count
		      var pct = '';
		      if(percentage == 0){
		        pct = Math.floor(this.countNum) + '%';
		      }else{
		        pct = Math.floor(this.countNum+1) + '%';
		      }
		      progress.text(pct) && skillbarWrapper.find('.az-skillbar-bar').css('width',pct);
		      }
		    });
		  });
		this.destroy()
	}, {
	offset: 'bottom-in-view'
});


/* ==================================================================
                		PARALLAX REFRESH COMMAND
================================================================== */
if(!Modernizr.touch){
	$(window).stellar({
		responsive: true,
	    positionProperty: 'position',
	    horizontalScrolling: false
	});

}

/* ==================================================================
                		COUNTER PLUGIN
================================================================== */
$('.az-counter-section-wrapper').waypoint(function(direction) {
	$('.az-counter').countTo({
        speed: 3500
    });
	this.destroy()
}, {
	offset: 'bottom-in-view'
});


$(function(){
    $(window).on("counter.resize", function() {
    var windowsize = $(window).width();
    if(windowsize <= 767) {
    	$('.az-counter-section-wrapper').waypoint(function(direction) {
			$('.az-counter').countTo({
		        speed: 3500
		    });
			this.destroy()
		}, {
			offset: '25%'
		});
      }
    }).trigger("counter.resize")
});

/* ==================================================================
                		MASONRY PORTFOLIO GRID
================================================================== */
	var $portfolioContainer	= $('.az-works'),
		$portfolioMasonryContainer	= $('.az-portfolio-masonry-section .az-works');

		// filter items on button click
		$portfolioContainer.isotope({
		  filter: '*',
		  itemSelector: '.az-work-item',
		  animationOptions: {
		      duration: 750,
		      easing: 'linear',
		      queue: false
		  },
		  masonry: {
		    // use outer width of grid-sizer for columnWidth
		    columnWidth: '.az-work-item',

			  gutterWidth: 20
		  }
		});

		$portfolioContainer.infinitescroll({
	    navSelector  : '#az-portfolio-next-page-nav',    // selector for the paged navigation
	    nextSelector : '#az-portfolio-next-page-nav a',  // selector for the NEXT link (to page 2)
	    itemSelector : '.az-work-item ',     // selector for all items you'll retrieve
		dataType: 'json',
		appendCallback: false,
	    loading: {
	        finishedMsg: '',
	        img: 'images-front/controls/loader.GIF'
	      }
	    },
	    // call Isotope as a callback
			function( json ) {

				var $items = $(json.html);
				// append items to grid
				$portfolioContainer.append( $items )
					// add and lay out newly appended items
					.isotope( 'appended', $items );

			$('.az-lightbox').magnificPopup({
					type: 'image',
					gallery:{
						enabled:true
					}
				});
		}
		);


		$('#az-portfolio-filter a').on('click',function(){
			      var selector = $(this).attr('data-filter');
			      $portfolioContainer.isotope({
			          filter: selector,
			          animationOptions: {
			              duration: 650,
			              easing: 'linear',
			              queue: false
			          }
			      });
			    return false;
			  });


		var $optionSets = $('#az-portfolio-filter .az-portfolio-filter-btn-group'),
			     $optionLinks = $optionSets.find('a');

			     $optionLinks.on('click',function(){
			        var $this = $(this);
			    // don't proceed if already selected
			    if ( $this.hasClass('selected') ) {
			        return false;
			    }
			 var $optionSet = $this.parents('#az-portfolio-filter .az-portfolio-filter-btn-group');
			 $optionSet.find('.selected').removeClass('selected');
			 $this.addClass('selected');
		});





/* ==================================================================
                		TESTIMONIAL SLIDER
================================================================== */
$('#az-testimonial-carousal').owlCarousel({
	items: 1,
	autoplay: true,
	loop: true
});

/* ==================================================================
                		CLIENT SLIDER
================================================================== */
$('.az-clients-carousal').owlCarousel({
	items: 3,
	autoplay: true,
	loop: true,
	dots: false,
	responsiveClass:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:3
        }
    }
});



/* ==================================================================
                		LIGHT BOX
================================================================== */
$('.az-lightbox, .lightbox').magnificPopup({
	type: 'image',
	gallery:{
		enabled:true
	}
});

$('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
	disableOn: 700,
	type: 'iframe',
	mainClass: 'mfp-fade',
	removalDelay: 160,
	preloader: false,

	fixedContentPos: false
});



/* ==================================================================
                		Flickr Feed
================================================================== */
//$('#flickr-gallery').jflickrfeed({
//	limit: 8,
//	qstrings: {
//		id: '35653055@N04'   /* Add your Flickr ID here. You can find your flickr id from http://idgettr.com/ */
//	},
//	itemTemplate:
//	'<li>' +
//		'<a class="flickr-gallery-img" href="{{image}}" title="{{title}}">' +
//			'<img src="{{image_s}}" alt="{{title}}" />' +
//		'</a>' +
//	'</li>'
//}, function(data) {
//	$('.flickr-gallery-img').magnificPopup({
//		type: 'image',
//		  gallery:{
//		    enabled:true
//		  }
//	});
//});


/* ==================================================================
                		BLOG MASONRY
================================================================== */
var $blogContainer = $('.az-blog-masonry-section .az-blog-wrapper');

	$blogContainer.imagesLoaded(function(){
	    $blogContainer.isotope({
	        itemSelector : '.az-blog-post',
	        masonry: {
			    columnWidth: '.az-blog-post',
				itemSelector : '.box',
				percentPosition: true
			  }
	    });
	});

      $blogContainer.infinitescroll({
        navSelector  : '#az-blog-next-page-nav',    // selector for the paged navigation
        nextSelector : '#az-blog-next-page-nav a',  // selector for the NEXT link (to page 2)
        itemSelector : '.az-blog-post',     // selector for all items you'll retrieve
        //animate: true,
        //prefill: true,
        //pixelsFromNavToBottom: 600, // CHANGE THIS NUMBER IF YOU WANT TO TRIGGER THE NEXT POST VERY SOON
        //bufferPx: 400,
	  	dataType: 'json',
	  	appendCallback: false,
        loading: {
        	msgText: "",
        	speed: 1000,
        	animate: false,
            finishedMsg: ''
          }
        },
        // trigger Masonry as a callback
		function( json ) {

			var $items = $(json.html);
			// append items to grid
			$blogContainer.append( $items )
				// add and lay out newly appended items
				.isotope( 'appended', $items );
		}
      );

	$(function(){
	    $(window).on("trigger.resize", function() {
	    var windowsize = $(window).width();
	    if(windowsize <= 767) {
	    	$blogContainer.infinitescroll({
		        pixelsFromNavToBottom: 1250 // CHANGE THIS NUMBER IF YOU WANT TO TRIGGER THE NEXT POST VERY SOON
		    	}
		    );
	      }
	    }).trigger("trigger.resize")
	});




/* ==================================================================
                			GOOGLE MAP
================================================================== */
function b() {
	var lat = $('#lat').val();
	var lng = $('#lng').val();

    var a = {
            zoom: 8,
            scrollwheel: false,
            center: new google.maps.LatLng(lat, lng),
            styles: [{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"stylers":[{"hue":"#000"},{"saturation":-100},{"gamma":2.15},{"lightness":-50}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"lightness":24}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":12}]}]
        },
        b = document.getElementById("az-map"),
        c = new google.maps.Map(b, a);
    new google.maps.Marker({
        position: new google.maps.LatLng(lat, lng),
        map: c,
        title: "Lombard"
    })
}
google.maps.event.addDomListener(window, "load", b);

});


/* ==================================================================
                        YOUTUBE API
================================================================== */
var tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;

var videoCode = $('#player').data('url');

if(videoCode) {
	videoCode = youtube_parser(videoCode);
}

    function onYouTubePlayerAPIReady() {
        player = new YT.Player('player', {
            playerVars: {
                'autoplay': 1,
                'controls': 0,
                'autohide': 1,
                'wmode': 'opaque',
                'showinfo': 0,
                'loop': 1,
                'rel': 0,
                'mute': 1
            },
            videoId: videoCode,            // ADD YOUR YOUTUBE VIDEO ID HERE
            events: {
                'onReady': onPlayerReady
            }
        });

    }

    function onPlayerReady(event) {
        event.target.mute();
        $('#text').fadeIn(400);
        //why this? Well, if you want to overlay text on top of your video, you
        //will have to fade it in once your video has loaded in order for this
        //to work in Safari, or your will get an origin error.
    }

    function youtube_parser(url){
        var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
        var match = url.match(regExp);
        return (match&&match[7].length==11)? match[7] : false;
    }

/* ==================================================================
                        SIDE MENU
================================================================== */
(function() {

	$('#zamowienie').click(function(e){
		e.preventDefault();

		$.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate"));

		var date = $("#datepicker").datepicker( 'getDate' );

		var day = date.getDate();
		var monthIndex = date.getMonth();
		var year = date.getFullYear();

		date = day + '.' + monthIndex + '.' + year;

		var imie = $('#imie-nazwisko');
		var miejscowosc = $('#miejscowosc');
		var tel = $('#nr-telefonu');
		var kod = $('#kod-pocztowy');
		var dodatki = $('#dodatkowe-zalozenia');
		var email = $('#email');

		var imieVal = imie.val();
		var miejscowoscVal = miejscowosc.val();
		var telVal = tel.val();
		var kodVal = kod.val();
		var dodatkiVal = dodatki.val();
		var emailVal = email.val();

		var error = 0;

		if(imieVal.length == 0) {
			imie.addClass('form-error');
			error = 1;
		} else {
			imie.removeClass('form-error');
			error = 0;
		}

		if(miejscowoscVal.length == 0) {
			miejscowosc.addClass('form-error');
			error = 1;
		} else {
			miejscowosc.removeClass('form-error');
			error = 0;
		}

		if(telVal.length == 0) {
			tel.addClass('form-error');
			error = 1;
		} else {
			tel.removeClass('form-error');
			error = 0;
		}

		if(kodVal.length == 0) {
			kod.addClass('form-error');
			error = 1;
		} else {
			kod.removeClass('form-error');
			error = 0;
		}

		if(dodatkiVal.length == 0) {
			dodatki.addClass('form-error');
			error = 1;
		} else {
			dodatki.removeClass('form-error');
			error = 0;
		}

		if(emailVal.length == 0) {
			email.addClass('form-error');
			error = 1;
		} else {
			email.removeClass('form-error');
			error = 0;
		}

		if(error == 1)
			return;

		$.ajax({
			type: "post",
			url: "/performance-form",
			dataType : 'json',
			data: {
				imie: imieVal,
				miejscowosc: miejscowoscVal,
				tel: telVal,
				kod: kodVal,
				dodatki: dodatkiVal,
				email: emailVal,
				data: date
			},
			success: function(data)
			{
				$('#zamowienie').attr('disabled', 'disabled').text('Pomyślnie wysłano');

				imie.val('');
				email.val('');
				miejscowosc.val('');
				tel.val('');
				kod.val('');
				dodatki.val('');
				dodatki.val('');
			}
		});

	});

	//contact-form
	$('#wyslij').click(function(e){
	    e.preventDefault();

	    var imie = $('#az-input-name');
	    var email = $('#az-input-email');
	    var temat = $('#az-input-subject');
	    var wiadomosc = $('#az-textarea-message');

	    var imieVal = imie.val();
	    var emailVal = email.val();
	    var tematVal = temat.val();
	    var wiadomoscVal = wiadomosc.val();

	    var error = 0;

	    if(imieVal.length == 0) {
		imie.addClass('form-error');
		error = 1;
	    } else {
		imie.removeClass('form-error');
		error = 0;
	    }

	    if(emailVal.length == 0) {
		email.addClass('form-error');
		error = 1;
	    } else {
		email.removeClass('form-error');
		error = 0;
	    }

	    if(tematVal.length == 0) {
		temat.addClass('form-error');
		error = 1;
	    } else {
		temat.removeClass('form-error');
		error = 0;
	    }

	    if(wiadomoscVal.length == 0) {
		wiadomosc.addClass('form-error');
		error = 1;
	    } else {
		wiadomosc.removeClass('form-error');
		error = 0;
	    }

	    if(error == 1)
		return;

	    $.ajax({
			type: "post",
			url: "/contact-form",
			dataType : 'json',
			data: {
				imie: imieVal,
				email: emailVal,
				temat: tematVal,
				wiadomosc: wiadomoscVal
			},
			success: function(data)
			{
				$('#wyslij').attr('disabled', 'disabled').val('Pomyślnie wysłano');

				imie.val('');
				email.val('');
				temat.val('');
				wiadomosc.val('');
			}
	    });

	});

	//datepicker

    //console.log(dates);

	var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;

	if(width > 768) {
		$("#datepicker").datepicker({
            beforeShowDay: function(date){
                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [ dates.indexOf(string) == -1 ]
            },
			numberOfMonths: 3
		});
	} else {
		$("#datepicker").datepicker({
            beforeShowDay: function(date){
                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [ dates.indexOf(string) == -1 ]
            }
        });
	}

	$('.zamow-koncert').click(function(e){
		e.preventDefault();
		//$('#hidden-content').fadeIn('slow');
		$('#hidden-content').fadeToggle('slow');
	});

	var bodyEl = document.body,
		content = document.querySelector( 'body' ),
		openbtn = document.getElementById( 'az-side-menu-open-button' ),
		closebtn = document.getElementById( 'az-side-menu-close-button' ),
		isOpen = false;

	function init() {
		initEvents();
	}

	function initEvents() {
		openbtn.addEventListener( 'click', toggleMenu );
		if( closebtn ) {
			closebtn.addEventListener( 'click', toggleMenu );
		}

		// close the menu element if the target it´s not the menu element or one of its descendants..
		content.addEventListener( 'click', function(ev) {
			var target = ev.target;
			if( isOpen && target !== openbtn ) {
				toggleMenu();
			}
		} );
	}

	function toggleMenu() {
		if( isOpen ) {
			classie.remove( bodyEl, 'az-show-menu' );
		}
		else {
			classie.add( bodyEl, 'az-show-menu' );
		}
		isOpen = !isOpen;
	}

	init();

})();
$(function() {
	// This is a functions that scrolls to #{blah}link
	function goToByScroll(href){
		// Remove "link" from the ID
		id = href.replace("#", "");
		console.log(id);
		// Scroll
		$('html,body').animate({
				scrollTop: $("#"+id).offset().top
			}, 'slow');
	}

	$("a.scroll-to-div").click(function(e) {
		// Prevent a page reload when a link is pressed
		e.preventDefault();
		// Call the scroll function
		goToByScroll($(this).attr('href'));
	});


	var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;

	if(width > 768) {
		new AnimOnScroll( document.getElementById( 'grid' ), {
			minDuration : 0.4,
			maxDuration : 0.7,
			viewportFactor : 0.2
		} );
	}

});