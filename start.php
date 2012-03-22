<?php
/**
 * ratings plugin
 *
 */

elgg_register_event_handler('init', 'system', 'ratings_init');

function ratings_init() {

	elgg_extend_view('css/elgg', 'ratings/css');
	elgg_extend_view('js/elgg', 'ratings/js');

	// registered with priority < 500 so other plugins can remove ratings
	elgg_register_plugin_hook_handler('register', 'menu:river', 'ratings_river_menu_setup', 400);
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'ratings_entity_menu_setup', 400);

	$actions_base = elgg_get_plugins_path() . 'ratings/actions/ratings';
	elgg_register_action('ratings/add', "$actions_base/add.php");
	elgg_register_action('ratings/delete', "$actions_base/delete.php");
}

/**
 * Add ratings to entity menu at end of the menu
 */
function ratings_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];

	// ratings button
	$options = array(
		'name' => 'ratings',
		'text' => elgg_view('ratings/button', array('entity' => $entity)),
		'href' => false,
		'priority' => 1000,
	);
	$return[] = ElggMenuItem::factory($options);

	// ratings count
	$count = elgg_view('ratings/count', array('entity' => $entity));
	if ($count) {
		$options = array(
			'name' => 'ratings_count',
			'text' => $count,
			'href' => false,
			'priority' => 1001,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Add a rate button to river actions
 */
function ratings_river_menu_setup($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		$item = $params['item'];

		// only rate group creation #3958
		if ($item->type == "group" && $item->view != "river/group/create") {
			return $return;
		}

		// don't rate users #4116
		if ($item->type == "user") {
			return $return;
		}
		
		$object = $item->getObjectEntity();
		if (!elgg_in_context('widgets') && $item->annotation_id == 0) {
			if ($object->canAnnotate(0, 'ratings')) {
				// rating ui
				$options = array(
					'name' => 'ratings',
					'href' => false,
					'text' => elgg_view('ratings/button', array('entity' => $object)),
					'is_action' => true,
					'priority' => 100,
				);
				$return[] = ElggMenuItem::factory($options);

				// ratings count
				$count = elgg_view('ratings/count', array('entity' => $object));
				if ($count) {
					$options = array(
						'name' => 'ratings_count',
						'text' => $count,
						'href' => false,
						'priority' => 101,
					);
					$return[] = ElggMenuItem::factory($options);
				}
			}
		}
	}

	return $return;
}

/**
 * Count how many people have rated an entity.
 *
 * @param  ElggEntity $entity
 *
 * @return int Number of ratings
 */
function ratings_count($entity) {
	$type = $entity->getType();
	$params = array('entity' => $entity);
	$number = elgg_trigger_plugin_hook('ratings:count', $type, $params, false);

	if ($number) {
		return $number;
	} else {
		return $entity->countAnnotations('rating');
	}
}

/**
 * Notify $user that $voter rated his $entity.
 *
 * @param type $user
 * @param type $voter
 * @param type $entity 
 */
function ratings_notify_user(ElggUser $user, ElggUser $r, ElggEntity $entity) {
	
	if (!$user instanceof ElggUser) {
		return false;
	}
	
	if (!$voter instanceof ElggUser) {
		return false;
	}
	
	if (!$entity instanceof ElggEntity) {
		return false;
	}
	
	$title_str = $entity->title;
	if (!$title_str) {
		$title_str = elgg_get_excerpt($entity->description);
	}

	$site = get_config('site');

	$subject = elgg_echo('ratings:notifications:subject', array(
					$voter->name,
					$title_str
				));

	$body = elgg_echo('ratings:notifications:body', array(
					$user->name,
					$voter->name,
					$title_str,
					$site->name,
					$entity->getURL(),
					$voter->getURL()
				));

	notify_user($user->guid,
				$voter->guid,
				$subject,
				$body
			);
}