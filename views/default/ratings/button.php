<?php
/**
 * Elgg ratings button
 *
 * @uses $vars['entity']
 */

if (!isset($vars['entity'])) {
	return true;
}

$guid = $vars['entity']->getGUID();

// check to see if the user has already rated this
if (elgg_is_logged_in() && $vars['entity']->canAnnotate(0, 'ratings')) {
	if (!elgg_annotation_exists($guid, 'ratings')) {
		$url = elgg_get_site_url() . "action/ratings/add?guid={$guid}";
		$params = array(
			'href' => $url,
			'text' => elgg_view_icon('thumbs-up'),
			'title' => elgg_echo('ratings:ratethis'),
			'is_action' => true,
			'is_trusted' => true,
		);
		$ratings_button = elgg_view('output/url', $params);
	} else {
		$options = array(
			'guid' => $guid,
			'annotation_name' => 'ratings',
			'owner_guid' => elgg_get_logged_in_user_guid()
		);
		$url = elgg_get_site_url() . "action/ratings/delete?guid={$guid}";
		$params = array(
			'href' => $url,
			'text' => elgg_view_icon('thumbs-up-alt'),
			'title' => elgg_echo('ratings:remove'),
			'is_action' => true,
			'is_trusted' => true,
		);
		$ratings_button = elgg_view('output/url', $params);
	}
}

echo $ratings_button;
