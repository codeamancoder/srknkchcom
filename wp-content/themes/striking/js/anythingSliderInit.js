jQuery(document).ready(function() {
	if(typeof slideShow == 'undefined') return;
	jQuery('#anything_slider').preloader({
		imgSelector:'img.slideimage',
		gradualDelay:false,
		afterShowAll:function(){
			var slider = jQuery(this);
			jQuery('#anything_slider_wrap').css('overflow','visible');
			
			slider.anythingSlider({
				expand:false,
				resizeContents:true,
				showMultiple:false,
				easing:slideShow['easing'],
				
				buildArrows:slideShow['buildArrows'],
				buildNavigation:slideShow['buildNavigation'],
				buildStartStop:false,
				
				toggleArrows:slideShow['toggleArrows'],
				toggleControls:slideShow['toggleControls'],
				
				// Function
				enableArrows:slideShow['enableArrows'],
				enableNavigation:slideShow['enableNavigation'],
				enableStartStop: false,
				enableKeyboard:slideShow['enableKeyboard'],
				
				// Navigation
				startPanel:1,
				changeBy:1,
				hashTags:false,
				infiniteSlides: true,
				navigationFormatter : null,
				navigationSize : false,
				
				// Slideshow options
				autoPlay:slideShow['autoPlay'],
				autoPlayLocked:slideShow['autoPlayLocked'],
				autoPlayDelayed:slideShow['autoPlayDelayed'],
				pauseOnHover:slideShow['pauseOnHover'],
				stopAtEnd:slideShow['stopAtEnd'],
				playRtl:slideShow['playRtl'],
				
				// Times
				delay:slideShow['delay'],
				resumeDelay:slideShow['resumeDelay'],
				animationTime:slideShow['animationTime'],
				
				// Callbacks
				onSlideInit:function(){
					base = slider.data('AnythingSlider');
					if(base.$currentPage.hasClass('stoped')){
						base.startStop(false);
					}else{
						base.startStop(base.options.autoPlay);
					}
				},
				// Video
				resumeOnVideoEnd:slideShow['resumeOnVideoEnd'],
				addWmodeToObject: 'transparent'
			});
			if(slider.find('.video_frame video')){
				if(typeof mejs != 'undefined'){
					jQuery('.video_frame video').mediaelementplayer();
				}
			}
			if(slider.find('li.panel:eq(1)').hasClass('stoped')){
				slider.data('AnythingSlider').startStop(false);
			}
			slider.find('li.panel.click_stoped').click(function(){
				slider.data('AnythingSlider').startStop(false);
			});
			
			slider.find('.anything_caption').css({ opacity: slideShow['captionOpacity'] });		
			jQuery('#anything_slider').anythingSliderVideo();
			if(slider.find('object[data-url*=youtube], embed[src*=youtube], iframe[src*=youtube], object[data-url*=vimeo], embed[src*=vimeo], iframe[src*=vimeo], video').length > 0){
				base = slider.data('AnythingSlider');
				base.startStop(false);
				jQuery('.anything_slider_loading').delay(5000).animate({opacity:0}, 2000,function(){jQuery(this).remove();base.startStop(base.options.autoPlay, true);});
			} else {
				jQuery('.anything_slider_loading').delay(300).animate({opacity:0}, 2000,function(){jQuery(this).remove();});
			}
		},
		beforeShowAll:function(){
			jQuery('<div id="anything_slider_loading" class="anything_slider_loading"></div>').insertBefore(this);
			jQuery(this).show();
		}
	})
});
