<?php

if ($object->xpdo) {
	/* @var modX $modx */
	$modx =& $object->xpdo;
	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		case xPDOTransport::ACTION_UPGRADE:
			$modelPath = $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/';
			$modx->addPackage('userprofile', $modelPath);

		/* @var upExtended $delivery */
			if (!$extended = $modx->getObject('upExtendedSetting', 1)) {
				$extended = $modx->newObject('upExtendedSetting');
				$extended->fromArray(array(
					'id' => 1
					,'name' => 'профиль #1'
					,'tabs' => 'activity,personal,social'
					,'fields' => 'lastname,firstname,secondname,facebook,vk,odnoklassniki,mail,twitter'
					,'requires' => 'lastname,firstname,secondname'
					,'active' => 1
					,'default' => 1
					,'rank' => 0
					), '', true);
				$extended->save();
			}

			break;
		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;