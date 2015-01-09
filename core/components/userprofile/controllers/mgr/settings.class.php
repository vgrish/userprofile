<?php

require_once dirname(dirname(dirname(__FILE__))) . '/index.class.php';

class ControllersSettingsManagerController extends userprofileMainController {

	public static function getDefaultController() {
		return 'settings';
	}

}

class userprofileSettingsManagerController extends userprofileMainController {

	public function getPageTitle() {
		return $this->modx->lexicon('userprofile') . ' :: ' . $this->modx->lexicon('up_settings');
	}

	public function getLanguageTopics() {
		return array('userprofile:default');
	}

	public function loadCustomCssJs() {

		$this->addJavascript($this->userprofile->config['jsUrl'] . 'mgr/misc/up.combo.js');
		$this->addJavascript($this->userprofile->config['jsUrl'] . 'mgr/widgets/extended.grid.js');
		$this->addJavascript($this->userprofile->config['jsUrl'] . 'mgr/sections/settings.js');

		$this->addHtml(str_replace('			', '', '
			<script type="text/javascript">
				Ext.onReady(function() {
					MODx.load({ xtype: "userprofile-page-settings"});
				});
			</script>'
		));
	}

	public function getTemplateFile() {
		return $this->userprofile->config['templatesPath'] . 'mgr/settings.tpl';
	}

}

// MODX 2.3
class ControllersMgrSettingsManagerController extends ControllersSettingsManagerController {

	public static function getDefaultController() {
		return 'mgr/settings';
	}

}

class userprofileMgrSettingsManagerController extends userprofileSettingsManagerController {

}
