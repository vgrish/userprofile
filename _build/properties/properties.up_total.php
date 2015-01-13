<?php

$properties = array();

$tmp = array(

	'placeholderPrefix' => array(
		'type' => 'textfield'
		,'value' => 'up.total.'
	),

	'user_id' => array(
		'type' => 'numberfield',
		'value' => '',
	),


	'parents' => array(
		'type' => 'textfield',
		'value' => ''
	),
	'depth' => array(
		'type' => 'numberfield',
		'value' => 10
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