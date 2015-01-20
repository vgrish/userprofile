<?php

$settings = array();

$tmp = array(

	'active' => array(
		'xtype' => 'combo-boolean',
		'value' => true,
		'area' => 'userprofile_main',
	),

	'hide_users' => array(
		'value' => '1',
		'xtype' => 'textfield',
		'area' => 'userprofile_main',
	),
	'hide_groups' => array(
		'value' => '1',
		'xtype' => 'textfield',
		'area' => 'userprofile_main',
	),
	'hide_inactive' => array(
		'xtype' => 'combo-boolean',
		'value' => true,
		'area' => 'userprofile_main',
	),


	'main_url' => array(
		'type' => 'textfield',
		'value' => 'users',
		'area' => 'userprofile_main',
	),
	'allowed_sections' => array(
		'type' => 'textfield',
		'value' => 'info,tickets,comments,favorites',
		'area' => 'userprofile_main',
	),

	'front_css' => array(
		'type' => 'textfield',
		'value' => '[[+assetsUrl]]css/web/default.css',
		'area' => 'userprofile_script',
	),
	'front_js' => array(
		'type' => 'textfield',
		'value' => '[[+assetsUrl]]js/web/default.js',
		'area' => 'userprofile_script',
	),

/*	'enable_for_templates' => array(
		'value' => '1',
		'xtype' => 'textfield',
		'area' => 'payandsee_main',
	),*/

		//временные 

				'assets_path' => array(
					'xtype' => 'textfield',
					'value' => '{base_path}userprofile/assets/components/userprofile/',
					'area' => 'userprofile_temp',
				),
				'assets_url' => array(
					'xtype' => 'textfield',
					'value' => '/userprofile/assets/components/userprofile/',
					'area' => 'userprofile_temp',
				),
				'core_path' => array(
					'xtype' => 'textfield',
					'value' => '{base_path}userprofile/core/components/userprofile/',
					'area' => 'userprofile_temp',
				),


		//временные

		/*
	'some_setting' => array(
		'xtype' => 'combo-boolean',
		'value' => true,
		'area' => 'userprofile_main',
	),
	*/
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'userprofile_' . $k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
