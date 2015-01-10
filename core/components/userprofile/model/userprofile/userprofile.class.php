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
			'processorsPath' => $corePath . 'processors/',

			'json_response' => 0,

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
		$this->modx->controller->addLexiconTopic('userprofile:default');
		$mode = $this->modx->getOption('mode', $sp);
		if ($mode == 'new') {
			return;
		}
		$id = $sp['id'];
		$user = $sp['user'];
		$up_extended = array();
		$profile = $user->getOne('Profile')->toArray();
		$profile = array_merge($profile, array(
			'gravatar' => 'http://www.gravatar.com/avatar/'. md5(strtolower($profile['email'])) .'?s=300',

			)
		);
		if ($upExtended = $this->modx->getObject('upExtended', array('user_id' => $id))) {
			$up_extended = $upExtended->toArray();
		}
		$up_extended = array_merge($profile['extended'], array(
			'real' => $up_extended,
			'personal' => $up_extended,
			'activity' => $up_extended,
		));
		if (!$extSetting = $this->modx->getObject('upExtendedSetting', array('id' => $up_extended['type_id']))) {
			$extSetting = $this->modx->getObject('upExtendedSetting', array('active' => 1));
		}
		$ext_setting = $extSetting->toArray();

//		$this->modx->log(1, print_r($ext_setting, 1));

		$data_js = preg_replace(array('/^\n/', '/\t{6}/'), '', '
			userprofile = {};
			userprofile.config = ' . $this->modx->toJSON(array(
				'connectorUrl' => $this->config['connectorUrl'],
				'extSetting' => $ext_setting,
				'upExtended' => $up_extended,
				'profile' => $profile,
				'tabs' => implode(',', array_keys($this->modx->fromJSON($ext_setting['tabfields']))),
			)) . ';
		');

		$this->modx->regClientStartupScript("<script type=\"text/javascript\">\n" . $data_js . "\n</script>", true);
		$this->modx->regClientCSS($this->getOption('cssUrl') . 'mgr/main.css');
		$this->modx->regClientStartupScript($this->getOption('jsUrl') . 'mgr/misc/up.combo.js');
		$this->modx->regClientStartupScript($this->getOption('jsUrl') . 'mgr/inject/tab.js');
	}

	public function OnBeforeUserFormSave($sp)
	{
		if ($sp['mode'] == 'new') {
			return;
		}
		$this->config['json_response'] = 1;
		$data = $sp['data'];
		$user_id = $data['id'];

		$real = array_merge($data['up']['real'], $data['up']['personal']);
		unset(
			$data['up']['real'],
			$data['up']['personal'],
			$data['up']['activity']
		);

		if (!$upExtended = $this->modx->getObject('upExtended', array('user_id' => $user_id))) {
			$upExtended = $this->modx->newObject('upExtended', array('user_id' => $user_id));
		}
		$upExtended->fromArray(
			$real
		);
		if (!$upExtended->save()) {
			echo $this->error('up_save_up_extended_err');
			exit;
		}
		// extended
		$user = $sp['user'];
		$profile = $user->getOne('Profile');
		$profileArr = $profile->toArray();
		$extended = $profileArr['extended'];
		foreach($data as $dd) {
			if(is_array($dd)) {
				$extended = array_merge($extended, $dd);
			}
		}
		$profile->set('extended', $extended);
		$profile->save();
	}

	/**
	 * @param string $message
	 * @param array $data
	 * @param array $placeholders
	 * @return array|string
	 */
	public function error($message = '', $data = array(), $placeholders = array())
	{
		$response = array(
			'success' => false,
			'message' => $this->modx->lexicon($message, $placeholders),
			'data' => $data,
		);
		return $this->config['json_response']
			? $this->modx->toJSON($response)
			: $response;
	}

	/**
	 * @param string $message
	 * @param array $data
	 * @param array $placeholders
	 * @return array|string
	 */
	public function success($message = '', $data = array(), $placeholders = array())
	{
		$response = array(
			'success' => true,
			'message' => $this->modx->lexicon($message, $placeholders),
			'data' => $data,
		);
		return $this->config['json_response']
			? $this->modx->toJSON($response)
			: $response;
	}

}