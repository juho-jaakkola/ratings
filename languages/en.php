<?php
/**
 * Ratings English language file
 */

$english = array(
	'ratings:this' => 'rated this',
	'ratings:deleted' => 'Your vote has been removed',
	'ratings:see' => 'See who rated this',
	'ratings:remove' => 'Remove vote',
	'ratings:notdeleted' => 'There was a problem removing your rating',
	'ratings:ratings' => 'You have now rated this item',
	'ratings:failure' => 'There was a problem rating this item',
	'ratings:alreadyrated' => 'You have already rated this item',
	'ratings:notfound' => 'The item you are trying to rate cannot be found',
	'ratings:ratethis' => 'Rate this',
	'ratings:userratedthis' => '%s have rated',
	'ratings:usersratedthis' => '%s ratings',
	'ratings:river:annotate' => 'ratings',

	'river:ratings' => 'ratings %s %s',

	// notifications. yikes.
	'ratings:notifications:subject' => '%s ratings your post "%s"',
	'ratings:notifications:body' =>
'Hi %1$s,

%2$s ratings your post "%3$s" on %4$s

See your original post here:

%5$s

or view %2$s\'s profile here:

%6$s

Thanks,
%4$s
',
	
);

add_translation('en', $english);
