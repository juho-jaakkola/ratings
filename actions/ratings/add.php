<?php
/**
 * Rating action
 */

$entity_guid = (int) get_input('guid');
$rating = (int) get_input('rating');

//check to see if the user has already rated the item
// @todo Should user be allowed to change the rating?
if (elgg_annotation_exists($entity_guid, 'rating')) {
	system_message(elgg_echo("ratings:alreadyrated"));
	forward(REFERER);
}
// Let's see if we can get an entity with the specified GUID
$entity = get_entity($entity_guid);
if (!$entity) {
	register_error(elgg_echo("ratings:notfound"));
	forward(REFERER);
}

// limit ratings through a plugin hook (to prevent rating your own content for example)
if (!$entity->canAnnotate(0, 'rating')) {
	// plugins should register the error message to explain why rating isn't allowed
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();
$annotation = create_annotation($entity->guid,
								'rating',
								$rating,
								"",
								$user->guid,
								$entity->access_id);

// tell user annotation didn't work if that is the case
if (!$annotation) {
	register_error(elgg_echo("ratings:failure"));
	forward(REFERER);
}

// notify if poster wasn't owner
if ($entity->owner_guid != $user->guid) {
	ratings_notify_user($entity->getOwnerEntity(), $user, $entity);
}

system_message(elgg_echo("ratings:ratings"));

// Forward back to the page where the user rated the object
forward(REFERER);
