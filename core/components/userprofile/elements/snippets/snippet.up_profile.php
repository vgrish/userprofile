<?php
/** @var array $scriptProperties */
/** @var userprofile $userprofile */
if (!$userprofile = $modx->getService('userprofile', 'userprofile', $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/userprofile/', $scriptProperties)) {
	return 'Could not load userprofile class!';
}
$userprofile->initialize($modx->context->key, $scriptProperties);
