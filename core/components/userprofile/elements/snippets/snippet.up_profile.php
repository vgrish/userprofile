<?php
/** @var array $scriptProperties */
/** @var userprofile $userprofile */
if (!$up = $modx->getService('userprofile', 'userprofile', $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/userprofile/', $scriptProperties)) {
	return 'Could not load userprofile class!';
}
$up->initialize($modx->context->key, $scriptProperties);
$isAuthenticated = $modx->user->isAuthenticated($modx->context->key);
//
if(empty($defaultSection)) {$defaultSection = 'info';}
//
$scriptProperties['user_id'] = $user_id = $modx->getPlaceholder('user_id');
$scriptProperties['active_section'] = $active_section = $modx->getPlaceholder('active_section');
// default pls
$row['main_url'] = $up->config['main_url'];
$row['user_id'] = $user_id;
$row['active_section'] = $active_section;
//
if(!$allowGuest && !$isAuthenticated && !empty($ReturnTo)) {
	$modx->sendRedirect($ReturnTo);
}
elseif (!$allowGuest && !$isAuthenticated) {
	return $modx->lexicon('up_allow_guest_err');
}
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
$outer['content'] = $up->getContent($tmp, $row, $scriptProperties);


echo '<pre>';
print_r($tmp);


/*
[0] => fav
[1] => chunk
*/






print_r($allowedSections);

echo '<pre>';
print_r($outer);

print_r($scriptProperties);