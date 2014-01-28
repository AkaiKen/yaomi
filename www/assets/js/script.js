;(function(){
	"use strict";

	jQuery(document).ready(function(){
		
		jQuery('details').details();

		jQuery('.foldable').foldable();

		var callback = '';
		if(jQuery('.page-collection').length > 0) {
			callback = collection_card_cleansing;
		}
		
		listen_update_cards(callback);

		jQuery('.block-content').find('.submit').remove();

		listen_toggle_groups();

		lazyLoad.init({
			//onPostpone : function(){ setOpacity(this,0); },
			//onRecall : function(){ new fade(this,1); }
		});

		listen_loader();
		


	});

	/**
     * empeche le fonctionnement normal des evenements
     * 
     * (typiquement le clic sur un lien)
     * @param e : mot-clé pour "event"
     */
	function stop_event(e,stopPropagation){
	    e.preventDefault ? e.preventDefault() : e.returnValue = false;
	    if(typeof(stopPropagation) == 'undefined'){
	        e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;
	    }
	}

	function polyfill_number() {
	    if (!Modernizr.inputtypes.number) {
	        $('input[type=number]').spinner();
	    }
	}


	function listen_update_cards(callback) {

		var form = jQuery('.card-group').find('form');
		var cards = form.find('.card');
		
		// store current value in a jQuery data
		jQuery(cards).each(function(){
			var t = jQuery(this);
			t.data('old_val[total]', t.find('.qty-total').val());
			t.data('old_val[deck]', t.find('.qty-deck').val());
		});

		var input = cards.find('input.qty-total, input.qty-deck');

		// when the Enter key is fired while the focus being on an input...
		input.on('keyup',function(e) {
			if(e.keyCode == 13) {
				// ... this input loses focus...
				jQuery(this).blur();
			}
		});

		// ...and you know what happens to little inputs that lose focus?
		input.on('blur', function(event) {
			stop_event(event);

			// only if current value is different from old one
			// (we don't want to fire update when unnecessary)
			var t = jQuery(this);
			var card_parent = t.closest('.card');
			if(t.hasClass('qty-total')){
				if(t.val() !== card_parent.data('old_val[total]')){
					update_cards(form, card_parent, callback);
				}
			}
			else if (t.hasClass('qty-deck')){
				if(t.val() !== card_parent.data('old_val[deck]')){
					update_cards(form, card_parent, callback);
				}
			}
		});

		// we don't want the form to submit itself 
		// (it's still a form because I want yaomi to *work* without js)
		form.on('submit', function(event){
			stop_event(event);
		});
	}

	function update_cards(form, card, callback) {
		
		if(typeof(card) === 'undefined') {
			return false;
		}

		display_loader();

		var data = {};
		var old_values = {};
		jQuery(card).find('input').each(function(){
			var t = jQuery(this);
			//var name = jQuery(this).attr('name');
			data[t.attr('name')] = t.val();
			old_values[t.data('qty')] = t.val();
		});
		
		jQuery.ajax({
			url: form.attr('action'),
			data: data,
			success:function(return_value){

				hide_loader();

				if(typeof(callback) === 'function') {
					callback(card);
				}

				// store new current value in a jQuery data
				// which means old is the new new				
				card.data('old_val[total]', old_values['total']);
				card.data('old_val[deck]', old_values['deck']);

				// notification!
				var message = form.data('success');
				var timer = 5000;
				var level = 'success';
				notification(message, level, timer);
			},
			error: function(return_value) {
				var message = form.data('error');
				var timer = 5000;
				var level = 'error';
				notification(message, level, timer);
			}
		});
		
		
	}

	/**
	 * [notification description]
	 * @param  {[type]} message
	 * @param  {[type]} level
	 * @param  {[type]} timer
	 * @return {[type]}
	 */
	function notification(message, level, timer) {

		// if timer is not set, nothing happens: if we can't throw up the garbage we don't eat
		// unless of course the meal is entirely edible
		// but that's another story
		if(typeof(timer) !== 'undefined') {

			if(jQuery('.notification-wrapper').length <= 0) {
				jQuery('body').append('<div class="notification-wrapper"></div>');
			}

			// we create a random id, in order to target the notification after that
			var random = Math.round(Math.random()*10000);
			var notification_id = 'grr-' + random;

			// we create a jQuery object, a div which will contain our message
			var notification = jQuery('<div class="notification" id="'+ notification_id +'" style="display:none" />');

			if(typeof(level) !== 'undefined') {
				notification.addClass(level);
			}

			// the notification is no more virtual, bam
			jQuery('.notification-wrapper').append(notification);
			notification.text(message);
			notification.fadeIn(1000);

			// now it's time to quit, dear
			setTimeout(function(){ __delete_notification(notification_id) ; }, timer);

		}

		function __delete_notification(notification_id) {
			jQuery('#' + notification_id).fadeOut(function(){
				jQuery(this).remove();
			});
		}

	} // end growl function

	function collection_card_cleansing(card){

		var total_qty_val = parseInt(card.find('.qty-total').val());
		var deck_qty_val = parseInt(card.find('.qty-deck').val());

		// you could ask: why test the two values? "deck_qty" is supposed to be a subset of "total_qty"!
		// yes, this is how I conceived the thing, but it's up to the user to take care of 
		// this coherence. I don't want to force the user.
		if(total_qty_val === 0 && deck_qty_val === 0){

			var group = card.closest('.cards');

			// we remove this card from page
			card.fadeOut(function(){
				jQuery(this).remove();

				// now we look at the cards group: did we juste remove the last card in this group?
				// if so, we remove that group too
				if(group.children().length <= 0) {
					group.closest('.card-group').fadeOut(function(){
						jQuery(this).remove();
					});
				}
			});
		}
	}

	function listen_toggle_groups() {

		jQuery('#fold-card-groups').on('click', function(){
			//console.log(jQuery('.card-group'));
			//jQuery('.card-group').foldable.toggle('close');
			//toggle_fold_groups('close');
		});

		jQuery('#unfold-card-groups').on('click', function(){
			//jQuery('.card-group').foldable('open');
			//toggle_fold_groups('open');
		});
	}

	function toggle_fold_groups(action) {

		var groups = jQuery('.card-group');

		if(action === 'close') {			
			jQuery(groups).each(function() {
				var t = jQuery(this);
				t.prop('open', false);
				t.removeClass('open');
				t.find('summary').attr('aria-expanded', false);
				t.find(':not(summary)').hide();
				t.triggerHandler('close.details');
			});	
		}
		else if(action === 'open') {
			jQuery(groups).each(function() {
				var t = jQuery(this);
				t.prop('open', true);
				t.addClass('open');
				t.find('summary').attr('aria-expanded', true);
				t.find(':not(summary)').show();
				t.triggerHandler('open.details')
			});
		}
		else {
			// nothing
		}

	}


	function listen_loader() {

		jQuery("a").on('click', function(){
			display_loader();
		});

		jQuery("form").on('submit', function(){
			display_loader();
		});

	}

	function display_loader() {
		jQuery('#loader').fadeIn(100);
	}

	function hide_loader() {
		jQuery('#loader').fadeOut(200);
	}	


})()