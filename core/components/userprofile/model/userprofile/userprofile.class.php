<?php

/**
 * The base class for userprofile.
 */
class userprofile
{
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
	function __construct(modX &$modx, array $config = array())
	{
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
			'dateFormat' => 'd F Y, H:i',
			'dateNow' => 10,
			'dateDay' => 'day H:i',
			'dateMinutes' => 59,
			'dateHours' => 10,

			'gravatarUrl' => 'https://www.gravatar.com/avatar/',
			'gravatarSize' => 300,
			'gravatarIcon' => 'mm',

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

	/**
	 * @param $sp
	 */
	public function OnUserFormPrerender($sp)
	{
		if ($this->isNew($sp)) return;
		$this->modx->controller->addLexiconTopic('userprofile:default');
		$id = $sp['id'];
		$user = $sp['user'];
		$up_extended = array();
		$profile = $user->getOne('Profile')->toArray();
		$profile = array_merge($profile, array(
				'gravatar' => $this->config['gravatarUrl'] . md5(strtolower($profile['email'])) . '?s='.$this->config['gravatarSize'] . '&d=' . $this->config['gravatarIcon'],

			)
		);
		if ($upExtended = $this->modx->getObject('upExtended', array('user_id' => $id))) {
			$up_extended = $upExtended->toArray();
		}
		// если extended пуст
		if (!is_array($profile['extended'])) $profile['extended'] = array();
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

	/**
	 * @param $sp
	 */
	public function OnBeforeUserFormSave($sp)
	{
		if ($this->isNew($sp)) return;
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
		foreach ($data as $dd) {
			if (is_array($dd)) {
				$extended = array_merge($extended, $dd);
			}
		}
		$profile->set('extended', $extended);
		$profile->save();
	}

	/**
	 * @param $sp
	 */
	public function OnUserSave($sp)
	{
		if (!$this->isNew($sp)) return;
		$user = $sp['user'];
		$userArr = $user->toArray();
		$id = $userArr['id'];
		if (!$upExtended = $this->modx->getObject('upExtended', array('user_id' => $id))) {
			$upExtended = $this->modx->newObject('upExtended', array('user_id' => $id));
		}
		$upExtended->set('registration', date('Y-m-d H:i:s'));
		$upExtended->save();
	}

	/**
	 * @param $sp
	 */
	public function OnLoadWebDocument($sp)
	{
		if ($this->modx->user->isAuthenticated($this->modx->context->get('key'))) {
			$id = $this->modx->user->id;
			if (!$upExtended = $this->modx->getObject('upExtended', array('user_id' => $id))) {
				$upExtended = $this->modx->newObject('upExtended', array('user_id' => $id));
			}
			$upExtended->set('lastactivity', date('Y-m-d H:i:s'));
			$upExtended->set('ip', $this->modx->request->getClientIp()['ip']);
			$upExtended->save();
		}
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

	/**
	 * @param array $d
	 * @return bool
	 */
	public function isNew($d = array())
	{
		if ($d['mode'] == 'new') {
			return true;
		}
		return false;
	}

	/**
	 * Formats date to "10 minutes ago" or "Yesterday in 22:10"
	 * This algorithm taken from https://github.com/livestreet/livestreet/blob/7a6039b21c326acf03c956772325e1398801c5fe/engine/modules/viewer/plugs/function.date_format.php
	 * @param string $date Timestamp to format
	 * @param string $dateFormat
	 *
	 * @return string
	 */
	public function dateFormat($date, $dateFormat = null) {
		$date = preg_match('/^\d+$/',$date) ?  $date : strtotime($date);
		$dateFormat = !empty($dateFormat) ? $dateFormat : $this->config['dateFormat'];
		$current = time();
		$delta = $current - $date;
		if ($this->config['dateNow']) {
			if ($delta < $this->config['dateNow']) {return $this->modx->lexicon('ticket_date_now');}
		}
		if ($this->config['dateMinutes']) {
			$minutes = round(($delta) / 60);
			if ($minutes < $this->config['dateMinutes']) {
				if ($minutes > 0) {
					return $this->declension($minutes, $this->modx->lexicon('ticket_date_minutes_back',array('minutes' => $minutes)));
				}
				else {
					return $this->modx->lexicon('ticket_date_minutes_back_less');
				}
			}
		}
		if ($this->config['dateHours']) {
			$hours = round(($delta) / 3600);
			if ($hours < $this->config['dateHours']) {
				if ($hours > 0) {
					return $this->declension($hours, $this->modx->lexicon('ticket_date_hours_back',array('hours' => $hours)));
				}
				else {
					return $this->modx->lexicon('ticket_date_hours_back_less');
				}
			}
		}
		if ($this->config['dateDay']) {
			switch(date('Y-m-d', $date)) {
				case date('Y-m-d'):
					$day = $this->modx->lexicon('ticket_date_today');
					break;
				case date('Y-m-d', mktime(0, 0, 0, date('m')  , date('d')-1, date('Y')) ):
					$day = $this->modx->lexicon('ticket_date_yesterday');
					break;
				case date('Y-m-d', mktime(0, 0, 0, date('m')  , date('d')+1, date('Y')) ):
					$day = $this->modx->lexicon('ticket_date_tomorrow');
					break;
				default: $day = null;
			}
			if($day) {
				$format = str_replace("day",preg_replace("#(\w{1})#",'\\\${1}',$day),$this->config['dateDay']);
				return date($format,$date);
			}
		}
		$m = date("n", $date);
		$month_arr = $this->modx->fromJSON($this->modx->lexicon('ticket_date_months'));
		$month = $month_arr[$m - 1];
		$format = preg_replace("~(?<!\\\\)F~U", preg_replace('~(\w{1})~u','\\\${1}', $month), $dateFormat);
		return date($format ,$date);
	}

	/**
	 * Declension of words
	 * This algorithm taken from https://github.com/livestreet/livestreet/blob/eca10c0186c8174b774a2125d8af3760e1c34825/engine/modules/viewer/plugs/modifier.declension.php
	 *
	 * @param int $count
	 * @param string $forms
	 * @param string $lang
	 *
	 * @return string
	 */
	public function declension($count, $forms, $lang = null) {
		if (empty($lang)) {
			$lang = $this->modx->getOption('cultureKey',null,'en');
		}
		$forms = $this->modx->fromJSON($forms);
		if ($lang == 'ru') {
			$mod100 = $count % 100;
			switch ($count%10) {
				case 1:
					if ($mod100 == 11) {$text = $forms[2];}
					else {$text = $forms[0];}
					break;
				case 2:
				case 3:
				case 4:
					if (($mod100 > 10) && ($mod100 < 20)) {$text = $forms[2];}
					else {$text = $forms[1];}
					break;
				case 5:
				case 6:
				case 7:
				case 8:
				case 9:
				case 0:
				default: $text = $forms[2];
			}
		}
		else {
			if ($count == 1) {
				$text = $forms[0];
			}
			else {
				$text = $forms[1];
			}
		}
		return $text;
	}

}