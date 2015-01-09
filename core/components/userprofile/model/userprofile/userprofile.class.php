<?php

/**
 * The base class for userprofile.
 */
class userprofile {
	/* @var modX $modx */
	public $modx;

	public $namespace = 'userprofile';
	public $cache = null;
	public $config = array();

	public $active = false;


	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

		$this->namespace = $this->getOption('userprofile', $config, 'userprofile');
		$corePath = $this->modx->getOption('userprofile_core_path', $config, $this->modx->getOption('core_path') . 'components/userprofile/');
		$assetsUrl = $this->modx->getOption('userprofile_assets_url', $config, $this->modx->getOption('assets_url') . 'components/userprofile/');
		$connectorUrl = $assetsUrl . 'connector.php';

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'connectorUrl' => $connectorUrl,

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',

			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/'
		), $config);

		$this->modx->addPackage('userprofile', $this->config['modelPath']);
		$this->modx->lexicon->load('userprofile:default');

		$this->active = $this->modx->getOption('userprofile_active', $config, false);

	}

	/**
	 * @param $key
	 * @param array $config
	 * @param null $default
	 * @return mixed|null
	 */
	public function getOption($key, $config = array(), $default = null)
	{
		$option = $default;
		if (!empty($key) && is_string($key)) {
			if ($config != null && array_key_exists($key, $config)) {
				$option = $config[$key];
			} elseif (array_key_exists($key, $this->config)) {
				$option = $this->config[$key];
			} elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
				$option = $this->modx->getOption("{$this->namespace}.{$key}");
			}
		}
		return $option;
	}

	public function OnUserFormPrerender($sp)
	{
		$this->modx->log(1, print_r('OnUserFormPrerender', 1));

		$this->modx->controller->addLexiconTopic('userprofile:default');
		$mode = $this->modx->getOption('mode', $sp);
		if ($mode == 'new') {
			return;
		}
		$id = $sp['id'];
		$user = $sp['user'];
		$profile = $user->getOne('Profile')->toArray();
		$profile = array_merge($profile, array(
			'gravatar' => 'http://www.gravatar.com/avatar/'. md5(strtolower($profile['email'])) .'?s=300',

			)
		);

/*		elseif (!$this->enableTemplates($res)) {
			return;
		}*/


		$this->modx->log(1, print_r($user->toArray(), 1));
		$this->modx->log(1, print_r($profile, 1));


		if (!$extSetting = $this->modx->getObject('upExtendedSetting', array('id' => $id))) {
			$extSetting = $this->modx->getObject('upExtendedSetting', array('active' => 1));
		}
		$ext_setting = $extSetting->toArray();

		$data_js = preg_replace(array('/^\n/', '/\t{6}/'), '', '
			userprofile = {};
			userprofile.config = ' . $this->modx->toJSON(array(
				'connectorUrl' => $this->config['connectorUrl'],
				'extSetting' => $ext_setting,
				'profile' => $profile,
			)) . ';
		');
		$this->modx->regClientStartupScript("<script type=\"text/javascript\">\n" . $data_js . "\n</script>", true);

		$this->modx->regClientCSS($this->getOption('cssUrl') . 'mgr/main.css');
		$this->modx->regClientStartupScript($this->getOption('jsUrl') . 'mgr/misc/up.combo.js');
		//$this->modx->regClientStartupScript($this->getOption('jsUrl') . 'mgr/inject/extended.grid.js');
		$this->modx->regClientStartupScript($this->getOption('jsUrl') . 'mgr/inject/tab.js');


	}

	public function OnBeforeUserFormSave($sp)
	{


		if ($sp['mode'] == 'new') {
			return;
		}
/*		elseif (!$this->enableTemplates($sp['resource'])) {
			return;
		}*/

		$this->modx->log(1, print_r('OnBeforeUserFormSave', 1));

		$this->modx->log(1, print_r($sp['data'], 1));

		$data = $sp['data'];


	}


}