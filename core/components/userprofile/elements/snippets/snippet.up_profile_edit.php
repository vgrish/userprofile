<?php
/** @var array $scriptProperties */
/** @var userprofile $userprofile */
if (!$up = $modx->getService('userprofile', 'userprofile', $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/userprofile/', $scriptProperties)) {
	return 'Could not load userprofile class!';
}
$up->initialize($modx->context->key, $scriptProperties);
//$isAuthenticated = $modx->user->isAuthenticated($modx->context->key);
$isAuthenticated = $modx->user->isAuthenticated();
//
if ($isAuthenticated) {
	$user = $modx->user->id;
}
else {
	$modx->sendErrorPage();
}
//
$user = $this->modx->user->toArray();
$profile = $this->modx->user->Profile->toArray();