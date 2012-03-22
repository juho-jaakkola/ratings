<?php
/**
 * Elgg show the users who have rated the object
 *
 * @uses $vars['annotation']
 */

if (!isset($vars['annotation'])) {
	return true;
}

$rating = $vars['annotation'];

$user = $rating->getOwnerEntity();
if (!$user) {
	return true;
}

$user_icon = elgg_view_entity_icon($user, 'tiny');
$user_link = elgg_view('output/url', array(
	'href' => $user->getURL(),
	'text' => $user->name,
	'is_trusted' => true,
));

$ratings_string = elgg_echo('ratings:this');

$friendlytime = elgg_view_friendly_time($rating->time_created);

if ($rating->canEdit()) {
	$delete_button = elgg_view("output/confirmlink",array(
						'href' => "action/ratings/delete?annotation_id={$rating->id}",
						'text' => "<span class=\"elgg-icon elgg-icon-delete float-alt\"></span>",
						'confirm' => elgg_echo('deleteconfirm'),
						'encode_text' => false,
					));
}

$body = <<<HTML
<p class="mbn">
	$delete_button
	$user_link $ratings_string
	<span class="elgg-subtext">
		$friendlytime
	</span>
</p>
HTML;

echo elgg_view_image_block($user_icon, $body);
