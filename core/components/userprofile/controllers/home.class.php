<?php

/**
 * The home manager controller for userprofile.
 *
 */
class UpHomeManagerController extends UpMainController {
	/* @var userprofile $userprofile */
	public $userprofile;


	/**
	 * @param array $scriptProperties
	 */
	public function process(array $scriptProperties = array()) {
	}


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('userprofile');
	}


	/**
	 * @return void
	 */
	public function loadCustomCssJs() {
		$this->addCss($this->userprofile->config['cssUrl'] . 'mgr/main.css');
		$this->addCss($this->userprofile->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
		$this->addJavascript($this->userprofile->config['jsUrl'] . 'mgr/misc/utils.js');
		$this->addJavascript($this->userprofile->config['jsUrl'] . 'mgr/widgets/items.grid.js');
		$this->addJavascript($this->userprofile->config['jsUrl'] . 'mgr/widgets/items.windows.js');
		$this->addJavascript($this->userprofile->config['jsUrl'] . 'mgr/widgets/home.panel.js');
		$this->addJavascript($this->userprofile->config['jsUrl'] . 'mgr/sections/home.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			MODx.load({ xtype: "userprofile-page-home"});
		});
		</script>');
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		return $this->userprofile->config['templatesPath'] . 'home.tpl';
	}
}