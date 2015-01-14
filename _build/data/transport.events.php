<?php

$events = array();

$tmp = array(
	'OnBeforeUserProfileСreated' => array(), //создан
	'OnUserProfileСreated' => array(), //создан
);

foreach ($tmp as $k => $v) {
	/* @var modEvent $event */
	$event = $modx->newObject('modEvent');
	$event->fromArray(array_merge(array(
			'name' => $k,
			'service' => 6,
			'groupname' => PKG_NAME,
		), $v)
		,'', true, true);

	$events[] = $event;
}

return $events;