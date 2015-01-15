<?php

$chunks = array();

$tmp = array(
	'tpl.upProfile' => array(
		'file' => 'up_profile_main',
		'description' => '',
	),
	'tpl.upUserInfo' => array(
		'file' => 'up_user_info_main',
		'description' => '',
	),
	'tpl.upUserInfo.small' => array(
		'file' => 'up_user_info_small',
		'description' => '',
	),

	'tpl.upUser.Row' => array(
		'file' => 'up_user_row',
		'description' => '',
	),

	'upSectionTickets' => array(
		'file' => 'up_section_tickets',
		'description' => '',
	),
/*	'tpl.upSectionTickets.Row' => array(
		'file' => 'up_section_tickets_row',
		'description' => '',
	),*/

	'upSectionComments' => array(
		'file' => 'up_section_comments',
		'description' => '',
	),
/*	'tpl.upSectionComments.Row' => array(
		'file' => 'up_section_comments_row',
		'description' => '',
	),*/

	'upSectionFavorites' => array(
		'file' => 'up_section_favorites',
		'description' => '',
	),

	'tpl.upProfile.form' => array(
		'file' => 'up_profile_form',
		'description' => '',
	),
	'tpl.upProfile.confirm' => array(
		'file' => 'up_profile_confirm',
		'description' => '',
	),

	'tpl.mFilter2.UserProfile.outer' => array(
		'file' => 'up_mfilter_outer',
		'description' => '',
	),


);

// Save chunks for setup options
$BUILD_CHUNKS = array();

foreach ($tmp as $k => $v) {
	/* @avr modChunk $chunk */
	$chunk = $modx->newObject('modChunk');
	$chunk->fromArray(array(
		'id' => 0,
		'name' => $k,
		'description' => @$v['description'],
		'snippet' => file_get_contents($sources['source_core'] . '/elements/chunks/chunk.' . $v['file'] . '.tpl'),
		'static' => BUILD_CHUNK_STATIC,
		'source' => 1,
		'static_file' => 'core/components/' . PKG_NAME_LOWER . '/elements/chunks/chunk.' . $v['file'] . '.tpl',
	), '', true, true);

	$chunks[] = $chunk;

	$BUILD_CHUNKS[$k] = file_get_contents($sources['source_core'] . '/elements/chunks/chunk.' . $v['file'] . '.tpl');
}

unset($tmp);
return $chunks;