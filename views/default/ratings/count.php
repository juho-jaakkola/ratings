<?php
/**
 * Count of who has rated something
 *
 *  @uses $vars['entity']
 */


$list = '';
$num_of_ratings = ratings_count($vars['entity']);
$guid = $vars['entity']->getGUID();

if ($num_of_ratings) {
	// display the number of ratings
	if ($num_of_ratings == 1) {
		$ratings_string = elgg_echo('ratings:userratedthis', array($num_of_ratings));
	} else {
		$ratings_string = elgg_echo('ratings:usersratedthis', array($num_of_ratings));
	}
	$params = array(
		'text' => $ratings_string,
		'title' => elgg_echo('ratings:see'),
		'rel' => 'popup',
		'href' => "#ratings-$guid"
	);
	$list = elgg_view('output/url', $params);
	$list .= "<div class='elgg-module elgg-module-popup elgg-ratings hidden clearfix' id='ratings-$guid'>";
	$list .= elgg_list_annotations(array(
		'guid' => $guid,
		'annotation_name' => 'rating',
		'limit' => 99,
		'list_class' => 'elgg-list-ratings'
	));
	$list .= "</div>";
	echo $list;
}
