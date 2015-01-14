<?php

$templates = array();

$tmp = array(
	'UserProfile.page' => array(
		'file' => 'up_profile_page',
		'description' => 'Template for page with users list',
	),
	'UserEditProfile.page' => array(
		'file' => 'up_profile_edit_page',
		'description' => 'Template for edit user profile',
	),
);

foreach ($tmp as $k => $v) {
	/* @avr modTemplate $template */
	$template = $modx->newObject('modTemplate');
	$template->fromArray(array(
		'id' => 0,
		'templatename' => $k,
		'description' => @$v['description'],
		'content' => file_get_contents($sources['source_core'].'/elements/templates/template.'.$v['file'].'.tpl'),
		'static' => BUILD_TEMPLATE_STATIC,
		'source' => 1,
		'static_file' => 'core/components/'.PKG_NAME_LOWER.'/elements/templates/template.'.$v['file'].'.tpl',
	),'',true,true);

	$templates[] = $template;
}

unset($tmp);
return $templates;