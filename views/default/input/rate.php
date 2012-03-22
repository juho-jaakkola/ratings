<?php
/**
 * Ratings input
 *
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);
 
if (!$entity) {
	return true;
}

//@todo Create functionality to select of 5 starts.

//@todo Should we use the same view but different content (active/inactive)
// for people who already have or haven't yet rated?
// How about including the same view inside voth 'output/rating' and 'input/rating'?

$star_selected = elgg_view_icon('elgg-icon-star');
$star_empty = elgg_view_icon('elgg-icon-star-empty');
$star_alt = elgg_view_icon('elgg-icon-star-alt');

// check to see if the user has already rated this
if (elgg_is_logged_in() && $vars['entity']->canAnnotate(0, 'ratings')) {
	if (!elgg_annotation_exists($guid, 'ratings')) {
		$url = elgg_get_site_url() . "action/ratings/add?guid={$guid}";
		
		$stars = $star_empty . $star_empty . $star_empty . $star_empty . $star_empty;
		
		$params = array(
			'href' => $url,
			'text' => $stars,
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
			'text' => "@todo: " . $star_selected . $star_selected . $star_alt . $star_alt,
			'title' => elgg_echo('ratings:remove'),
			'is_action' => true,
			'is_trusted' => true,
		);
		$ratings_button = elgg_view('output/url', $params);
	}
}

echo $ratings_button;
