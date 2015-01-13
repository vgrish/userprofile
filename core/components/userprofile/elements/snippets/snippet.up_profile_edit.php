<?php
/** @var array $scriptProperties */
/** @var userprofile $userprofile */
if (!$up = $modx->getService('userprofile', 'userprofile', $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/userprofile/', $scriptProperties)) {
	return 'Could not load userprofile class!';
}
$up->initialize($modx->context->key, $scriptProperties);
$isAuthenticated = $modx->user->isAuthenticated($modx->context->key);


$isAuthenticated = true;
//
if ($isAuthenticated) {
	$user_id = $modx->user->id;
}
else {
	$modx->sendErrorPage();
}
//
$user_id = 2;

$row = $up->getUserFields($user_id);
$row = $up->prepareData($row);
//

//echo '<pre>';
//print_r($row);

$output = empty($tplProfile)
	? $up->pdoTools->getChunk('', $row)
	: $up->pdoTools->getChunk($tplProfile, $row, $up->pdoTools->config['fastMode']);

$modx->regClientScript(str_replace('[[+assetsUrl]]', $up->config['assetsUrl'], $js));

//echo '<pre>';
//print_r($up->config['assetsUrl']);die;


$key = md5($modx->toJSON($up->config));
$_SESSION['upform'][$key] = $up->config;
$output = str_ireplace('</form>', "\n<input type=\"hidden\" name=\"form_key\" value=\"{$key}\" class=\"disable-sisyphus\" />\n</form>", $output);


return $output;