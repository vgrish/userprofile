<?php

$properties = array();

$tmp = array(

	'parents' => array(
		'type' => 'textfield',
		'value' => ''
	),
	'depth' => array(
		'type' => 'numberfield',
		'value' => 10
	),
	'tpl' => array(
		'type' => 'textfield',
		'value' => 'tpl.Tickets.comment.list.row',
	),

	'sortby' => array(
		'type' => 'textfield',
		'value' => 'TicketComment.createdon'
	),
	'sortdir' => array(
		'type' => 'list'
		,'options' => array(
			array('text' => 'ASC','value' => 'ASC')
			,array('text' => 'DESC','value' => 'DESC')
			)
		,'value' => 'DESC',
	),
	'limit' => array(
		'type' => 'numberfield'
		,'value' => 10
	),
	'offset' => array(
		'type' => 'numberfield'
		,'value' => 0
	),

	'showLog' => array(
		'type' => 'combo-boolean'
	,'value' => false
	),


/*	'showInactive' => array(
		'type' => 'combo-boolean'
	,'value' => false
	),
	'showBlocked' => array(
		'type' => 'combo-boolean'
	,'value' => false
	),
	'idx' => array(
		'type' => 'numberfield'
		,'value' => ''
	),

	'totalVar' => array(
		'type' => 'textfield'
		,'value' => 'total'
	),*/


	'gravatarIcon' => array(
		'type' => 'textfield',
		'value' => 'mm',
	),
	'gravatarSize' => array(
		'type' => 'numberfield',
		'value' => '24',
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