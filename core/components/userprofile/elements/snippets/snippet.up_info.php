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
else {return $modx->lexicon('up_get_user_err');}
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


echo '<pre>';
print_r($userProfile);
die;

// default pls
$row['user_id'] = $user_id;
// get user fields
$userFields = $up->getUserFields($user_id);
$row = array_merge($userFields, $row);
// gravatar
$row['gravatar'] = $up->config['gravatarUrl'].md5(strtolower($userFields['email'])).'?s='.$gravatarSize.'&d='.$gravatarIcon;
// sections




$allowedSections = $up->getAllowedSections();
foreach ($allowedSections as $section) {
	$row['section'] = $section;
	$row['sectiontitle'] = $modx->lexicon('up_section_title_'.$section);
	$row['active'] = ($section == $active_section) ? 'active' : '';
	$row['rows'] .= empty($tplSectionRow)
		? $up->pdoTools->getChunk('', $row)
		: $up->pdoTools->getChunk($tplSectionRow, $row, $up->pdoTools->config['fastMode']);
}
$outer['section'] = empty($tplSectionOuter)
	? $up->pdoTools->getChunk('', $row)
	: $up->pdoTools->getChunk($tplSectionOuter, $row, $up->pdoTools->config['fastMode']);
// Preparing filters
$tmp_filters = array_map('trim', explode(',', $filters));
foreach($tmp_filters as $v) {
	if (empty($v)) {
		continue;
	}
	elseif(strpos($v, $active_section.$up->config['delimeterSection']) !== false) {@
	list($section, $action) = explode($up->config['delimeterSection'], $v);
	}
	$tmp = explode($up->config['delimeterAction'], $action);
}
if(empty($section)) {$section = $defaultSection;}
// content
$content = $up->getContent($tmp, $row, $scriptProperties);
$outer['content'] = empty($tplSectionContent)
	? $up->pdoTools->getChunk('', array('content' => $content))
	: $up->pdoTools->getChunk($tplSectionContent, array('content' => $content), $up->pdoTools->config['fastMode']);
// output
$outer = array_merge($row, $outer);
$output = empty($tpl)
	? $up->pdoTools->getChunk('', $outer)
	: $up->pdoTools->getChunk($tpl, $outer, $up->pdoTools->config['fastMode']);
if (!empty($tplWrapper) && (!empty($wrapIfEmpty) || !empty($output))) {
	$output = $up->pdoTools->getChunk($tplWrapper, array('output' => $output), $up->pdoTools->config['fastMode']);
}
if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder, $output);
} else {
	return $output;
}