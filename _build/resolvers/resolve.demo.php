<?php
/**
 * @var modX $modx
 * @var modTemplate $template
 * @var modResource $resource
 */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
	case xPDOTransport::ACTION_INSTALL:
	case xPDOTransport::ACTION_UPGRADE:
		$modx = & $object->xpdo;
		$lang = $modx->getOption('manager_language') == 'en' ? 1 : 0;

		if ($template = $modx->getObject('modTemplate', array('templatename' => 'Bootstrap.inner'))) {
			if (!$resource = $modx->getObject('modResource', array('alias' => 'users'))) {
				$resource = $modx->newObject('modResource');
			}
			$resource->fromArray(array(
				'pagetitle' => !$lang ? 'Список пользователей' : 'Users list',
				'alias' => 'users',
				'uri' => 'users/',
				'uri_override' => 1,
				'published' => 1,
				'parent' => 0,
				'richtext' => 0,
				'template' => $template->id,
				'content' => file_get_contents(MODX_CORE_PATH . 'components/userprofile/elements/demo/users.html')
			));
			$resource->save();
			$parent_id = $resource->id;

			$res_arr = array(
				0 => array(
					'pagetitle' => !$lang ? 'Мои настройки' : 'My setting',
					'alias' => 'setting',
					'uri' => 'setting/',
					'uri_override' => 1,
					'published' => 1,
					'parent' => $parent_id,
					'richtext' => 0,
					'template' => $template->id,
					'content' => file_get_contents(MODX_CORE_PATH . 'components/referral/elements/demo/setting.html')
				),
			);

			foreach ($res_arr as $res) {
				if (!$resource = $modx->getObject('modResource', array('alias' => $res['alias']))) {
					$resource = $modx->newObject('modResource');
					$resource->fromArray($res);
					$resource->save();
				}
			}
		}



		break;
	case xPDOTransport::ACTION_UNINSTALL:
		break;
}
return true;