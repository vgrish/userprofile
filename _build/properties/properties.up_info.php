<?php

$properties = array();

$tmp = array(
	'tplUserInfo' => array(
		'type' => 'textfield',
		'value' => 'tpl.upUserInfo',
	),
	'tplNoUserInfo' => array(
		'type' => 'textfield',
		'value' => '@INLINE <p>No User</p>',
	),
	'tplSectionNavOuter' => array(
		'type' => 'textfield',
		'value' => '@INLINE <ul class="nav nav-tabs">[[+rows]]</ul>',
	),
	'tplSectionNavRow' => array(
		'type' => 'textfield',
		'value' => '@INLINE <li class="[[+active]] [[+row_idx]]"><a href="#[[+section]]" data-toggle="tab">[[+tabtitle]]</a></li>',
	),
	'tplSectionTabContentOuter' => array(
		'type' => 'textfield',
		'value' => '@INLINE <div class="tab-content">[[+content]]</div>',
	),
	'tplSectionTabContentPane' => array(
		'type' => 'textfield',
		'value' => '@INLINE <div class="tab-pane [[+active]] [[+row_idx]]" id="[[+section]]">[[+tabcontent]]</div>',
	),
	'tplSectionTabContentRow' => array(
		'type' => 'textfield',
		'value' => '@INLINE <p><b>[[+name]]</b>: [[+value]]</p>',
	),
	'excludeTabs' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'excludeFields' => array(
		'type' => 'textfield',
		'value' => 'lastactivity',
	),
	'activeTab' => array(
		'type' => 'textfield',
		'value' => '',
	),

	'enabledTabs' => array(
		'type' => 'combo-boolean',
		'value' => true,
	),

	'user_id' => array(
		'type' => 'numberfield',
		'value' => '',
	),
	'dateFormat' => array(
		'type' => 'textfield',
		'value' => 'd F Y, H:i',
	),
	'gravatarIcon' => array(
		'type' => 'textfield',
		'value' => 'mm',
	),
	'gravatarSize' => array(
		'type' => 'numberfield',
		'value' => '64',
	),
	'showLog' => array(
		'type' => 'combo-boolean',
		'value' => false,
	),
	'toPlaceholder' => array(
		'type' => 'combo-boolean',
		'value' => false,
	),
);

foreach ($tmp as $k => $v) {
	$properties[] = array_merge(
		array(
			'name' => $k,
			'desc' => PKG_NAME_LOWER . '_prop_' . $k,
			'lexicon' => PKG_NAME_LOWER . ':properties',
		), $v
	);
}

return $properties;