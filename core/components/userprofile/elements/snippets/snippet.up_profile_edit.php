<?php
/** @var array $scriptProperties */
/** @var userprofile $userprofile */
if (!$up = $modx->getService('userprofile', 'userprofile', $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/userprofile/', $scriptProperties)) {
	return 'Could not load userprofile class!';
}
$up->initialize($modx->context->key, $scriptProperties);
$isAuthenticated = $modx->user->isAuthenticated($modx->context->key);
//
if ($isAuthenticated) {
	$user_id = $modx->user->id;
}
else {
	$modx->sendErrorPage();
}
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
//
if(!empty($enabledTabs)) {
	// get TabsFields
	$tabsFields = $up->getTabsFields($row['type_id']);
	// tabs
	$realTabs = explode(',', $up->config['realTabs']);
	$excludeFields = explode(',', $excludeFields);
	$row_idx = 1;
	foreach($tabsFields as $nameTab => $fields) {
		// def
		$row['tabcontent'] = '';
		// NavRow
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
		// fields
		if(is_array($fields)) {
			foreach($fields as $field => $v) {
				if(in_array($field, $excludeFields)) {continue;}
				$row['value'] = '';
				if(in_array($nameTab, $realTabs)) {$row['value'] = $row[$field];}
				elseif(is_array($row['extended'])) {
					if(isset($row['extended'][$nameTab][$field])) {$row['value'] = $row['extended'][$nameTab][$field];}
					else {$row['value'] = '';}
				}
				$row['name'] = $modx->lexicon('up_field_'.$field);
				$row['inputname'] = 'up['.$nameTab.']['.$field.']';
				$row['tabcontent'] .= empty($tplSectionTabContentRow)
					? $up->pdoTools->getChunk('', $row)
					: $up->pdoTools->getChunk($tplSectionTabContentRow, $row, $up->pdoTools->config['fastMode']);
			}
		}
		// tabs
		$tabs .= empty($tplSectionTabContentPane)
			? $up->pdoTools->getChunk('', $row)
			: $up->pdoTools->getChunk($tplSectionTabContentPane, $row, $up->pdoTools->config['fastMode']);
	}
	// navtabs
	$row['navtabs'] = empty($tplSectionNavOuter)
		? $up->pdoTools->getChunk('', array('rows' => $rows))
		: $up->pdoTools->getChunk($tplSectionNavOuter, array('rows' => $rows), $up->pdoTools->config['fastMode']);
	// contenttabs
	$row['contenttabs'] = empty($tplSectionTabContentOuter)
		? $up->pdoTools->getChunk('', array('content' => $tabs))
		: $up->pdoTools->getChunk($tplSectionTabContentOuter, array('content' => $tabs), $up->pdoTools->config['fastMode']);

}
//
$output = empty($tplProfile)
	? $up->pdoTools->getChunk('', $row)
	: $up->pdoTools->getChunk($tplProfile, $row, $up->pdoTools->config['fastMode']);
//
$modx->regClientScript(str_replace('[[+assetsUrl]]', $up->config['assetsUrl'], $js));
$key = md5($modx->toJSON($up->config));
$_SESSION['upform'][$key] = $up->config;
$output = str_ireplace('</form>', "\n<input type=\"hidden\" name=\"form_key\" value=\"{$key}\" class=\"disable-sisyphus\" />\n</form>", $output);

return $output;