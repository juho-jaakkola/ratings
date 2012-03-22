<?php
/**
 * ratings JavaScript extension for elgg.js
 */
?>

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

elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.ratingsPopupHandler);