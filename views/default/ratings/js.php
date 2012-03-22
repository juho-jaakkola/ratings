<?php
/**
 * ratings JavaScript extension for elgg.js
 */
?>

elgg.provide('elgg.ratings');

/**
 * Repositions the ratings popup
 *
 * @param {String} hook    'getOptions'
 * @param {String} type    'ui.popup'
 * @param {Object} params  An array of info about the target and source.
 * @param {Object} options Options to pass to
 *
 * @return {Object}
 */
elgg.ui.ratingsPopupHandler = function(hook, type, params, options) {
	if (params.target.hasClass('elgg-ratings')) {
		options.my = 'right bottom';
		options.at = 'left top';
		return options;
	}
	return null;
};

/**
 * Animate rating interface
 * 
 * On mouse over change the icon for as many starts as
 * the user has selected. On mouse leave return the
 * icons to dfault.
 */
elgg.ratings.init = function() {
	$('.elgg-rating-value').hover(
		function() {
			// Mouse over:
			rating_value = $(this).attr('value');
			for ($star = 1; $star <= rating_value; $star++) {
				item = '.elgg-rating-icon-' + $star;
				$(item).removeClass('elgg-icon-star-empty');
				$(item).addClass('elgg-icon-star-alt');
			}
		},
		function() {
			// Mouse leave
			$('.elgg-rating-value').each(function() {
				$(this).find('span').removeClass('elgg-icon-star');
				$(this).find('span').addClass('elgg-icon-star-empty');
			});
		}
	);
};

elgg.register_hook_handler('init', 'system', elgg.ratings.init);
elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.ratingsPopupHandler);