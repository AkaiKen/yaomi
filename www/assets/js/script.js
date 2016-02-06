;(function(){
	"use strict";

	jQuery(document).ready(function(){
		
		// LEGACY
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
			//onPostpone : function(){ /*setOpacity(this,0);*/ },
			//onRecall : function(){ new fade(this,1); }
		});

		listen_loader();
		
		//polyfill_number();
		
		if(jQuery('.filter-rarities').length) {
			var inputRarities = jQuery('.filter-rarities').find('input');
			listen_filter_rarities(inputRarities);
		}

		if(jQuery('.filter-colors').length) {
			var inputColors = jQuery('.filter-colors').find('input');
			listen_filter_colors(inputColors);
		}

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

	function listen_update_cards(callback) {

		var form = jQuery('.card-group').find('form');
		var cards = form.find('.card');
		
		// store current value in a jQuery data
		jQuery(cards).each(function(){
			var t = jQuery(this);
			t.data('old_val[total]', t.find('.qty-total').val());
			t.data('old_val[deck]', t.find('.qty-deck').val());
		});

		if(jQuery('.quick-input').length) {
			// we are in 'quick input' mode
			var quick_input = cards.find('input.qty-quick-total');
			quick_input.off('change.update').on('change.update',function(event) {
				stop_event(event);
				var t = jQuery(this);
				__fire_update(t);
			});
		}

		var input = cards.find('input.qty-total, input.qty-deck');

		// when the Enter key is fired while the focus being on an input...
		input.off('keyup.update').on('keyup.update',function(event) {
			if(event.keyCode == 13) {
				// ... this input loses focus...
				jQuery(this).blur();
			}
		});

		// ...and you know what happens to little inputs that lose focus?
		input.off('blur.update').on('blur.update', function(event) {
			stop_event(event);		
			var t = jQuery(this);
			__fire_update(t);

		});

		// we fire the event also on spin buttons (from polyfill)
		// @TODO: create my own polyfill, I need MOAR control
		var spin_buttons = input.closest('.qty-number').find('.number-spin-btn');
		spin_buttons.off('click.update').on('click.update', function(event){
			stop_event(event);
			var t = jQuery(this).closest('.qty-number').find('input');
			__fire_update(t);
		});

		// we don't want the form to submit itself 
		// (it's still a form because I want yaomi to *work* without js)
		form.on('submit', function(event){
			stop_event(event);
		});

		function __fire_update(t) {
			// only if current value is different from old one
			// (we don't want to fire update when unnecessary)
			var card_parent = t.closest('.card');
			if(t.hasClass('qty-total') || t.hasClass('qty-quick-total')){
				if(t.val() !== card_parent.data('old_val[total]')){
					update_cards(form, card_parent, callback);
				}
			}
			else if (t.hasClass('qty-deck')){
				if(t.val() !== card_parent.data('old_val[deck]')){
					update_cards(form, card_parent, callback);
				}
			}
		}
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
			if(t.attr('type') === 'radio'){
				if(t.is(':checked')) {
					data[t.attr('name')] = t.val();
				}
			}
			else {
				data[t.attr('name')] = t.val();
			}
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

		var total_qty_val = parseInt(card.find('.qty-total').val(), 10);
		var deck_qty_val = parseInt(card.find('.qty-deck').val(), 10);

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

		jQuery('#fold-card-groups').off('click.fold').on('click.fold', function(){
			jQuery('.card-group').foldable('close');
		});

		jQuery('#unfold-card-groups').off('click.unfold').on('click.unfold', function(){
			jQuery('.card-group').foldable('open');
		});
	}

	function listen_loader() {

		hide_loader();

		jQuery("a[href]").on('click', function(){
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

	// FILTERS \\
	function listen_filter_rarities(input) {
		filter_cards(input, 'rarity');
		jQuery(input).off('change.filter_rarities').on('change.filter_rarities', function(event){
			stop_event(event);
			filter_cards(this, 'rarity');
		});
	}

	function listen_filter_colors(input) {
		// because we can have cards with two or more colors
		filter_cards(input, 'color', false);
		jQuery(input).off('change.filter_colors').on('change.filter_colors', function(event){
			stop_event(event);
			filter_cards(this, 'color', false);
		});
	}

	function filter_cards(input, filter, isStrict) {

		var cards = jQuery('.card');

		jQuery(input).each(function() {
			var $t = jQuery(this);
			var $filtered_cards = jQuery(".card").filter_by_data(filter, $t.val(), isStrict );

			if($t.is(':checked')) {
				$filtered_cards.removeClass(filter + '-hidden');
			}
			else {
				$filtered_cards.addClass(filter + '-hidden');
			}

		});

		cleansing_filters();
	}

	function cleansing_filters() {

		jQuery('.card-group').each(function(){
			var $t = jQuery(this),
				$cards = $t.find('.card');

			if($cards.not('.hidden').length) {
				$t.show();
			}
			else {
				$t.hide();
			}
		});

	}


})();
