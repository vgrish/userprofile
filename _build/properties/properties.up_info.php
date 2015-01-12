<?php

$properties = array();

$tmp = array(
	'tpl' => array(
		'type' => 'textfield',
		'value' => 'tpl.userprofile.item',
	),

	'user_id' => array(
		'type' => 'numberfield',
		'value' => '',
	),

	'sortby' => array(
		'type' => 'textfield',
		'value' => 'name',
	),
	'sortdir' => array(
		'type' => 'list',
		'options' => array(
			array('text' => 'ASC', 'value' => 'ASC'),
			array('text' => 'DESC', 'value' => 'DESC'),
		),
		'value' => 'ASC'
	),

	'outputSeparator' => array(
		'type' => 'textfield',
		'value' => "\n",
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