<?php

$userprofile = $modx->getService('userprofile', 'userprofile', $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/userprofile/', $scriptProperties);
if (!($userprofile instanceof userprofile)) return '';

$eventName = $modx->event->name;
if (method_exists($userprofile, $eventName) && $userprofile->active) {
	$eventName = lcfirst($eventName);
	$userprofile->$eventName($scriptProperties);
}