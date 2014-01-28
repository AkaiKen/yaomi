;(function(){
	'use strict';

	// PLUGINS \\
	
	// FOLDABLE
	(function( $ ) {

		var self;

		var defaults = {
			title_class: 'block-title',
			content_class: 'block-content'
		}

		var settings;

		var methods = {
	        init : function(options) {
	        	settings = $.extend( {}, defaults, options );

				self = this; //store original jQuery object
				return self.each(function() {

					var block = $(this);

					block.addClass('foldable-triggered');

					var title = block.find('.' + settings.title_class);
					var content = block.find('.' + settings.content_class);

					if(!block.hasClass('open')){
						methods.close();
					}

					title.on('click', function(){
						if(!block.hasClass('open')) {
							methods.open(this);
						}
						else {
							methods.close(this);
						}
					});

				});
	        },
	        open : function( arg ) { 

	        	arg = (typeof(arg) === 'undefined') ? this : arg;
	        	methods.toggle('open', arg);

	        },
	        close : function( arg ) {

	        	arg = (typeof(arg) === 'undefined') ? this : arg;
	        	methods.toggle('close', arg);

	        },
	        toggle : function (action, arg) {

	        	var block = jQuery(arg).closest('.foldable-triggered');

	        	if(action === 'close') {
	        		block.removeClass('open').addClass('closed');
	        		block.find('.' + settings.content_class).hide();
	        	}
	        	else {
	        		block.removeClass('closed').addClass('open');
	        		block.find('.' + settings.content_class).show();
	        	}

	        }
	    };

		$.fn.foldable = function( args ) {

			if ( methods[args] ) {
	            return methods[ args ].apply( this, Array.prototype.slice.call( arguments, 1 ));
	        } else if ( typeof args === 'object' || ! args ) {
	            // Default to "init"
	            return methods.init.apply( this, arguments );
	        } else {
	            $.error( 'Method ' +  args + ' does not exist on jQuery.foldable' );
	        } 
			
		};
	}( jQuery ));
	// FOLDABLE - END
	

	// FILTER BY DATA
	(function($) {
 
	/* by Elijah Manor with collaboration from Doug Neiner
	* Filter results by html5 data attributes either at
	* design or at runtime
	*
	* Usages:
	* $( "p" ).filterByData( "mytype" );
	* $( "p" ).filterByData( "mytype, "mydata" );
	*/
	$.fn.filter_by_data = function( type, value, strict ) {

		return this.filter( function() {

			if(strict === null) {
				strict = true;
			}

			var $this = $( this );

			if(value !== null) {
				if(strict){
					return ($this.data( type ) === value);
				}
				else {
					return ($this.data( type ).indexOf(value) !== -1) ;
				}
			}
			else {
				return ($this.data( type ) !== null );
			}

			// return value !== null ?
			// 	$this.data( type ).indexOf(value) !== -1 :
			// 	$this.data( type ) !== null;
		});
	};

	})(jQuery); 
	// FILTER BY DATA - END 
	
})()