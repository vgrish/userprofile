<?php
if (empty($_REQUEST['action'])) {
	@session_write_close();
	die('Access denied');
}
//print_r($_REQUEST);die;
define('MODX_API_MODE', true);
if (file_exists(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/index.php')) {
	require dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/index.php'; // на время разработки
} else {
	require dirname(dirname(dirname(dirname(__FILE__)))).'/index.php'; // на постоянку
}
//
$modx->getService('error','error.modError');
$modx->getRequest();
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');
$modx->error->message = null;
// Switch context
$context = 'web';
if (!empty($_REQUEST['ctx']) && $modx->getCount('modContext', $_REQUEST['ctx'])) {
	$context = $_REQUEST['ctx'];
}
if ($context != 'web') {
	$modx->switchContext($context);
	$modx->user = null;
	$modx->getUser($ctx);
}
//
define('MODX_ACTION_MODE', true);

// Get properties
$properties = array();
if (!empty($_REQUEST['form_key']) && isset($_SESSION['upform'][$_REQUEST['form_key']])) {
	$properties = $_SESSION['upform'][$_REQUEST['form_key']];
}
//
$up = $modx->getService('userprofile', 'userprofile', MODX_CORE_PATH . 'components/userprofile/model/userprofile/', $properties);
if ($modx->error->hasError() || !($up instanceof userprofile)) {
	@session_write_close();
	die('Error');
}
$up->initialize($ctx);
$action = $_REQUEST['action'];
unset($_REQUEST['action']);
if (!$response = $up->loadAction($action, $_REQUEST)) {
	$response = $modx->toJSON(array(
		'success' => false
		,'message' => $modx->lexicon('up_action_err')
	));
}
@session_write_close();
echo $response;