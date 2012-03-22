<?php
/**
 * Elgg delete rating action
 *
 */

$ratings = elgg_get_annotations(array(
	'guid' => (int) get_input('guid'),
	'annotation_owner_guid' => elgg_get_logged_in_user_guid(),
	'annotation_name' => 'rating',
));
if ($ratings) {
	if ($ratings[0]->canEdit()) {
		$ratings[0]->delete();
		system_message(elgg_echo("ratings:deleted"));
		forward(REFERER);
	}
}

register_error(elgg_echo("ratings:notdeleted"));
forward(REFERER);
