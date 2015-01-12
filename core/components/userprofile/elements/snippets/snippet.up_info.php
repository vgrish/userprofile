<?php
/** @var array $scriptProperties */
/** @var userprofile $userprofile */
if (!$up = $modx->getService('userprofile', 'userprofile', $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/userprofile/', $scriptProperties)) {
	return 'Could not load userprofile class!';
}
$up->initialize($modx->context->key, $scriptProperties);
$isAuthenticated = $modx->user->isAuthenticated($modx->context->key);
//
if(empty($user_id) && $isAuthenticated) {$user_id = $modx->user->id;}
elseif(empty($user_id)) {return $modx->lexicon('up_get_user_err');}
// default properties
$default = array(
	'class' => 'upExtended',
	'where' => '{"user_id":'.$user_id.'}',
	'select' => '{"upExtended":"all"}',
	'return' => 'data',
	'fastMode' => false,
	'nestedChunkPrefix' => 'up_',
);
// Merge all properties and run!
$up->pdoTools->addTime('Query parameters are prepared.');
$up->pdoTools->setConfig(array_merge($default, $scriptProperties));
$userProfile = $up->pdoTools->run();
$up->pdoTools->addTime('Fetched userProfile.');
// get user fields
$userFields = $up->getUserFields($user_id);
$row = array_merge($userFields, $userProfile[0]);
// gravatar
$row['gravatar'] = $up->config['gravatarUrl'].md5(strtolower($userFields['email'])).'?s='.$gravatarSize.'&d='.$gravatarIcon;
// format date
$row['registration_format'] = $up->dateFormat($row['registration'], $dateFormat);
$row['lastactivity_format'] = $up->dateFormat($row['lastactivity'], $dateFormat);
// output
$output = empty($tpl)
	? $up->pdoTools->getChunk('', $row)
	: $up->pdoTools->getChunk($tplUserInfo, $row, $up->pdoTools->config['fastMode']);
if (!empty($tplWrapper) && (!empty($wrapIfEmpty) || !empty($output))) {
	$output = $up->pdoTools->getChunk($tplWrapper, array('output' => $output), $up->pdoTools->config['fastMode']);
}
if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
	$output .= '<pre class="upLog">' . print_r($up->pdoTools->getTime(), 1) . '</pre>';
}
if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder, $output);
} else {
	return $output;
}