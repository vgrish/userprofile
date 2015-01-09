<?php

/**
 * Class userprofileMainController
 */
abstract class userprofileMainController extends modExtraManagerController {
	/** @var userprofile $userprofile */
	public $userprofile;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('userprofile_core_path', null, $this->modx->getOption('core_path') . 'components/userprofile/');
		require_once $corePath . 'model/userprofile/userprofile.class.php';

		$this->userprofile = new userprofile($this->modx);
		$this->addCss($this->userprofile->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->userprofile->config['jsUrl'] . 'mgr/userprofile.js');
		$this->addHtml('
		<script type="text/javascript">
			userprofile.config = ' . $this->modx->toJSON($this->userprofile->config) . ';
			userprofile.config.connector_url = "' . $this->userprofile->config['connectorUrl'] . '";
		</script>
		');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('userprofile:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends userprofileMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}