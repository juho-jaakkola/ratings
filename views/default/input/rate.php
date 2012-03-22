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

//@todo Should we use the same view but different content (active/inactive)
// for people who already have or haven't yet rated?
// How about including the same view inside voth 'output/rating' and 'input/rating'?

$star_selected = elgg_view_icon('star');
$star_empty = elgg_view_icon('star-empty');
$star_alt = elgg_view_icon('star-alt');

// check to see if the user has already rated this
if (elgg_is_logged_in() && $vars['entity']->canAnnotate(0, 'ratings')) {
	$guid = $entity->getGUID();
	
	if (!elgg_annotation_exists($guid, 'rating')) {
		$url = elgg_get_site_url() . "action/ratings/add?guid={$guid}";
		
		$stars = '';
		for ($item = 1; $item < 6; $item++) {
			$vote_url = "$url&rating=$item";
			
			$star_icon = elgg_view_icon('star-empty', "elgg-rating-icon-$item");
			
			$star = elgg_view('output/url', array(
				'href' => $vote_url,
				'text' => $star_icon,
				//'id' => "elgg-rating-value-$item",
				'class' => 'elgg-rating-value',
				'value' => $item,
				'is_action' => true,
			));
			
			$stars .= $star;
		}
		
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
			'annotation_name' => 'rating',
			'owner_guid' => elgg_get_logged_in_user_guid()
		);
		$ratings = elgg_get_annotations($options);
		$rating = $ratings[0];
		// @todo round the avg value
		$rating = $entity->getAnnotationsAvg('rating');
		
		$stars = '';
		for ($item = 1; $item < 6; $item++) {
			if ($item <= $rating) {
				$stars .= $star_selected;
			} else {
				$stars .= $star_empty;
			}
		}
		
		// @todo Change the vote instead of removing it?
		$url = elgg_get_site_url() . "action/ratings/delete?guid={$guid}";
		$params = array(
			'href' => $url,
			'text' => $stars,
			'title' => elgg_echo('ratings:ratethis'),
			'is_action' => true,
			'is_trusted' => true,
		);
		$ratings_button = elgg_view('output/url', $params);
	}
	
	// View number of votes if any
	$num_of_ratings = $entity->countAnnotations('rating');
	if ($num_of_ratings) {
		if ($num_of_ratings == 1) {
			$ratings_string = elgg_echo('ratings:userratedthis', array($num_of_ratings));
		} else {
			$ratings_string = elgg_echo('ratings:usersratedthis', array($num_of_ratings));
		}
		$ratings_button .= " ($ratings_string)";
	}
}

echo "$ratings_button";
