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
$row = $up->prepareData($row);
// get upExtendedSetting
$tabsFields = $up->getTabsFields($row['type_id']);
// tabs
foreach($tabsFields as $nameTab => $fields) {
	$row_idx = 1;
	$row['section'] = $nameTab;
	$row['tabtitle'] = $modx->lexicon('up_tab_title_'.$nameTab);
	if(!empty($activeTab)) {
		$row['active'] = ($activeTab == $nameTab) ? 'active' : '';
	}
	else {
		$row['active'] = ($row_idx == 1) ? 'active' : '';
	}
	$row['row_idx'] = $row_idx ++;
	$rows .= empty($tplSectionNavRow)
		? $up->pdoTools->getChunk('', $row)
		: $up->pdoTools->getChunk($tplSectionNavRow, $row, $up->pdoTools->config['fastMode']);

	if(is_array($fields)) {
		foreach($fields as $field) {


			// row

		}
	}


//	 =>


}
// NavOuter

$row['navtabs'] = empty($tplSectionNavOuter)
	? $up->pdoTools->getChunk('', array('rows' => $rows))
	: $up->pdoTools->getChunk($tplSectionNavOuter, array('rows' => $rows), $up->pdoTools->config['fastMode']);
// tabs

$row['tabcontent'] = '';

/*
 * <ul class="nav nav-tabs">
        <li class="first active">
            <a href="#tab1" data-toggle="tab">таб 1</a>
        </li>
        <li class="last">
            <a href="#tab2" data-toggle="tab">таб 2</a>
        </li>
    </ul>

    <div class="tab-content">

        <div class="tab-pane active" id="tab1">
            <p>телефон: 987889798789</p>
            <p>телефон: 987889798789</p>
            <p>телефон: 987889798789</p>

        </div>
        <div class="tab-pane" id="tab2">...2</div>


    </div>
 *
 * [social] => Array
(
	[facebook] =>
	[odnoklassniki] =>
    [vk] =>
    [mail] =>
    [twitter] =>
  )*/


// output
$output = empty($tplUserInfo)
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