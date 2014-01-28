;(function(){
	'use strict';

	// PLUGINS \\
	
	// FOLDABLE
	(function( $ ) {

		var self;
		$.fn.foldable = function( options ) {

			//var options = args;

			var defaults = {
				title_class: 'block-title',
				content_class: 'block-content'
			}

			var settings = $.extend( {}, defaults, options );

			self = this; //store original jQuery object
			return self.each(function() {

				var block = $(this);
				var title = block.find('.' + settings.title_class);
				var content = block.find('.' + settings.content_class);

				if(!$(this).hasClass('open')){
					content.hide();
				}

				title.on('click', function(){
					content.toggle();
					block.toggleClass('open');
				});

			});

			function close() {
				content.hide();
				block.removeClass('open');
				console.log('close');
			}

			function open() {
				content.show();
				block.addClass('open');
				console.log('open');

			}

		};

		$.fn.foldable.toggle = function( action ) {

			// console.log('hop');
			console.log(self);
			console.log(this);


			// return this.each(function() {
			// 	console.log(this);
			// 	// if(action === 'close') {

			// 	// }
			// });
		};
	}( jQuery ));



})()