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
	public $defaultTypeId = 0;

	public $actionArr = array(
		'snippet' => 'modSnippet',
		'chunk' => 'modChunk',
	);

	public $redirectArr = array(
		'moved' => array('responseCode' => 'HTTP/1.1 301 Moved Permanently'),
		'notfound' => array('responseCode' => 'HTTP/1.1 404 Not Found'),
	);

	public $actions = array(
		//'auth/login',
		'auth',
		'profile',
	);

	/* @var pdoTools $pdoTools */
	public $pdoTools;

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
			'actionUrl' => $assetsUrl . 'action.php',

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',

			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/',

			'ctx' => 'web',
			'json_response' => 0,
			'dateFormat' => 'd F Y, H:i',
			'dateNow' => 10,
			'dateDay' => 'day H:i',
			'dateMinutes' => 59,
			'dateHours' => 10,

			'gravatarUrl' => 'https://www.gravatar.com/avatar/',
			'gravatarSize' => 300,
			'gravatarIcon' => 'mm',

			'disabledTabs' => 'activity',

			'frontend_css' => $this->modx->getOption('userprofile_front_css', null, '[[+assetsUrl]]css/web/default.css'),
			'frontend_js' => $this->modx->getOption('userprofile_front_js', null, '[[+assetsUrl]]js/web/default.js'),

			'main_url' => $this->modx->getOption('userprofile_main_url', null, 'users'),

			'defaultSection' => 'info',
			'active_section' => '',
			'defaultAction' => 'snippet',
			'delimeterSection' => '|',
			'delimeterAction' => ':',

			'requiredFields' => 'username,email,fullname',
			'profileFields' => 'username:50,email:50,fullname:50,phone:12,mobilephone:12,dob:10,gender,address,country,city,state,zip,fax,photo,comment,website,specifiedpassword,confirmpassword',

			'avatarPath' => 'images/users/',
			'avatarParams' => '{"w":294,"h":230,"zc":0,"bg":"ffffff","f":"jpg"}',


		), $config);

		$this->modx->addPackage('userprofile', $this->config['modelPath']);
		$this->modx->lexicon->load('userprofile:default');

		$this->active = $this->modx->getOption('userprofile_active', $config, false);
		// default type_id
		if ($extSetting = $this->modx->getObject('upExtendedSetting', array('active' => 1, 'default' => 1))) {
			$this->defaultTypeId = $extSetting->get('id');
		} else {
			$this->modx->log(modX::LOG_LEVEL_ERROR, print_r('[UserProfile] error get default TypeId.', 1));
		}

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
	 * @param string $ctx
	 * @param array $scriptProperties
	 * @return bool
	 */
	public function initialize($ctx = 'web', $scriptProperties = array())
	{
		$this->config = array_merge($this->config, $scriptProperties);
		if (!$this->pdoTools) {
			$this->loadPdoTools();
		}
		$this->pdoTools->setConfig($this->config);
		$this->config['ctx'] = $ctx;
		if (!empty($this->initialized[$ctx])) {
			return true;
		}
		switch ($ctx) {
			case 'mgr':
				break;
			default:
				if (!defined('MODX_API_MODE') || !MODX_API_MODE) {
					if ($css = trim($this->config['frontend_css'])) {
						if (preg_match('/\.css/i', $css)) {
							$this->modx->regClientCSS(str_replace('[[+assetsUrl]]', $this->config['assetsUrl'], $css));
						}
					}
					$config_js = preg_replace(array('/^\n/', '/\t{5}/'), '', '
					userprofile = {};
					userprofileConfig = {
						cssUrl: "' . $this->config['cssUrl'] . 'web/"
						,jsUrl: "' . $this->config['jsUrl'] . 'web/"
						,actionUrl: "' . $this->config['actionUrl'] . '"
						,ctx: "' . $this->modx->context->get('key') . '"
						,close_all_message: "' . $this->modx->lexicon('up_message_close_all') . '"
					};
					');
					$this->modx->regClientStartupScript("<script type=\"text/javascript\">\n" . $config_js . "\n</script>", true);
					if ($js = trim($this->config['frontend_js'])) {
						if (!empty($js) && preg_match('/\.js/i', $js)) {
							$this->modx->regClientScript(preg_replace(array('/^\n/', '/\t{7}/'), '', '
							<script type="text/javascript">
								if(typeof jQuery == "undefined") {
									document.write("<script src=\"' . $this->config['jsUrl'] . 'web/lib/jquery.min.js\" type=\"text/javascript\"><\/script>");
								}
							</script>
							'), true);
							$this->modx->regClientScript(str_replace('[[+assetsUrl]]', $this->config['assetsUrl'], $js));
						}
					}
				}
				$this->initialized[$ctx] = true;
				break;
		}
		return true;
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
				'gravatar' => $this->config['gravatarUrl'] . md5(strtolower($profile['email'])) . '?s=' . $this->config['gravatarSize'] . '&d=' . $this->config['gravatarIcon'],
			)
		);
		if ($upExtended = $this->modx->getObject('upExtended', array('user_id' => $id))) {
			$up_extended = $upExtended->toArray();
		}
		// если extended пуст
		if (!is_array($profile['extended'])) $profile['extended'] = array();
		$up_extended = array_merge($profile['extended'], $up_extended);
		//
		if (!$extSetting = $this->modx->getObject('upExtendedSetting', array('id' => $up_extended['type_id']))) {
			$extSetting = $this->modx->getObject('upExtendedSetting', array('active' => 1, 'default' => 1));
		}
		$ext_setting = $extSetting->toArray();
		// requires
		$requires = array(1);
		$requires = array_flip(array_merge($requires, explode(',', $ext_setting['requires'])));
//		$this->modx->log(1, print_r($ext_setting, 1));
		$data_js = preg_replace(array('/^\n/', '/\t{6}/'), '', '
			userprofile = {};
			userprofile.config = ' . $this->modx->toJSON(array(
				'connectorUrl' => $this->config['connectorUrl'],
				'extSetting' => $ext_setting,
				'upExtended' => $up_extended,
				'profile' => $profile,
				'tabs' => implode(',', array_keys($this->modx->fromJSON($ext_setting['tabfields']))),
				'disabledTabs' => $this->config['disabledTabs'],
				'requires' => $this->modx->toJSON($requires),
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
		if ($this->isNew($sp)) {return;}
		if (!$this->modx->user->hasSessionContext('mgr')) {return;}
		//
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
			$upExtended = $this->modx->newObject('upExtended', array(
				'user_id' => $user_id,
				'registration' => date('Y-m-d H:i:s'),
				'lastactivity' => date('Y-m-d H:i:s'),
			));
		}
		$upExtended->fromArray(
			$real
		);
		if (!$upExtended->save()) {
			echo $this->error('up_save_extended_err');
			exit;
		}
		// extended
		$user = $sp['user'];
		$profile = $user->getOne('Profile');
		$profileArr = $profile->toArray();
		//
		$extended = array();
		$extended = array_merge($extended, $profileArr['extended']);
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
		$upExtended->fromArray(array(
			'type_id' => $this->defaultTypeId,
			'registration' => date('Y-m-d H:i:s'),
		));
		$upExtended->save();
	}

	/**
	 * @param $sp
	 */
	public function OnLoadWebDocument($sp)
	{
		if ($this->modx->user->isAuthenticated($this->modx->context->get('key'))) {
			if (!$this->modx->user->active || $this->modx->user->Profile->blocked) {
				$this->logOut();
			} else {
				$id = $this->modx->user->id;
				if (!$upExtended = $this->modx->getObject('upExtended', array('user_id' => $id))) {
					$upExtended = $this->modx->newObject('upExtended', array('user_id' => $id, 'registration' => date('Y-m-d H:i:s')));
				}
				$upExtended->fromArray(array(
					'type_id' => $this->defaultTypeId,
					'lastactivity' => date('Y-m-d H:i:s'),
					'ip' => $this->modx->request->getClientIp()['ip'],
				));
				$upExtended->save();
			}
		}
	}

	/**
	 * @param $sp
	 */
	public function OnHandleRequest($sp)
	{
		if (!empty($_REQUEST['action'])) {


			$this->modx->log(1, print_r($_REQUEST['action'], 1));

			$this->loadAction($_REQUEST['action'], array());
			/*switch ($_REQUEST['action']) {
				case 'auth/logout':
				{
					$this->logout();
					break;
				}
				default:
					break;

			}*/

		}
	}

	/**
	 * @param $action
	 * @param array $sp
	 * @return bool|string
	 */
	public function loadAction($action, $sp = array())
	{
		if (!empty($action)) {
			@list($name, $action) = explode('/', strtolower(trim($action)));
			if (method_exists($this, $action) && (in_array($name, $this->actions))) {
				return $this->$action(array_merge($this->config, $sp));
			} else {
				return 'Could not load "' . $action . '"';
			}
		}
		return false;
	}

	/**
	 * @param array $data
	 * @return array|bool|string
	 */
	public function update($data = array())
	{
		$this->config['json_response'] = 1;
		/*		if (!$this->modx->user->isAuthenticated($this->modx->context->key)) {
					return $this->error($this->modx->lexicon('up_auth_err'));
				}*/

		$requiredFields = !empty($this->config['requiredFields'])
			? array_map('trim', explode(',', $this->config['requiredFields']))
			: array();

		$profileFields = array();
		$fields = array(
			'requiredFields' => $requiredFields,
			'avatarPath' => $this->config['avatarPath'],
			'avatarParams' => $this->config['avatarParams'],
		);
		//
		$tmp = explode(',', $this->config['profileFields']);
		foreach ($tmp as $field) {
			if (strpos($field, ':') !== false) {
				list($key, $length) = explode(':', $field);
			} else {
				$key = $field;
				$length = 0;
			}
			$profileFields[$key] = $length;
		}
		//
		foreach ($requiredFields as $field) {
			if (!isset($profileFields[$field])) {
				$profileFields[$field] = 0;
			}
		}
		//
		foreach ($profileFields as $field => $length) {
			if (isset($data[$field])) {
				if ($field == 'comment') {
					$fields[$field] = empty($length)
						? trim($data[$field])
						: trim(substr($data[$field], $length));
				} else {
					$fields[$field] = $this->Sanitize($data[$field], $length);
				}
			} // Extended fields
			elseif (preg_match('/(.*?)\[(.*?)\]/', $field, $matches)) {
				if (isset($data[$matches[1]][$matches[2]])) {
					$fields[$matches[1]][$matches[2]] = $this->Sanitize($data[$matches[1]][$matches[2]], $length);
				}
			}
		}
		//
		$changeEmail = false;
		$new_email = '';
		if (!empty($fields['email'])) {
			$current_email = $this->modx->user->Profile->get('email');
			$new_email = trim($fields['email']);
			$changeEmail = strtolower($current_email) != strtolower($new_email);
		}
		//
		/* @var modProcessorResponse $response */
		$response = $this->runProcessor('profile/update', $fields);
		if ($response->isError()) {
			$message = $response->hasMessage()
				? $response->getMessage()
				: $this->modx->lexicon('up_profile_err_update');
			$errors = array();
			if ($response->hasFieldErrors()) {
				if ($tmp = $response->getFieldErrors()) {
					foreach ($tmp as $error) {
						$errors[$error->field] = $error->message;
					}
				}
			}
			return $this->error($message, $errors);
		}
		if ($changeEmail && !empty($new_email)) {
			$change = $this->changeEmail($new_email, $this->getUserPage());
			$message = ($change === true)
				? $this->modx->lexicon('up_profile_msg_save_email')
				: $this->modx->lexicon('up_profile_msg_save_noemail', array('errors' => $change));
		} else {
			$object = $response->getObject();
			$message = !empty($object['specifiedpassword'])
				? $this->modx->lexicon('up_profile_msg_save_password', array('password' => $object['specifiedpassword']))
				: $this->modx->lexicon('up_profile_msg_save');
		}
		$saved = array();
		$user = $this->modx->getObject('modUser', $this->modx->user->id);
		$profile = $this->modx->getObject('modUserProfile', array('internalKey' => $this->modx->user->id));
		$tmp = array_merge($profile->toArray(), $user->toArray());
		if (!empty($new_email)) {
			$tmp['email'] = $new_email;
		}
		foreach ($fields as $k => $v) {
			if (isset($tmp[$k]) && isset($data[$k])) {
				$saved[$k] = $tmp[$k];
			}
		}
		return $this->success($message, $saved);


		$this->modx->log(1, print_r($data, 1));

	}

	/**
	 * @return bool|int|mixed
	 */
	public function getUserPage($uri = '')
	{
		if(empty($uri)) {$uri = $this->config['main_url'];}
		$uri .= $this->modx->getOption('container_suffix', null, '/', true);
		if (!$userPage = $this->modx->findResource($uri, $this->modx->context->key)) {
			$this->modx->log(modX::LOG_LEVEL_ERROR, print_r('UserProfile error get main_url.', 1));
			return false;
		}
		return $userPage;
	}

	/**
	 * @param array $logout_data
	 * @param int $id
	 */
	public function logout($logout_data = array(), $id = 0)
	{
		/*
				if ($this->modx->user->hasSessionContext('mgr') && !$this->modx->user->hasSessionContext($this->modx->context->key)) {
					// логиним юзера в текущем контексте
					$this->modx->user->addSessionContext($this->modx->context->key);
				}

				exit();
				$this->modx->log(1, print_r($this->modx->context->key, 1));*/

		if ($user = $this->modx->getAuthenticatedUser($this->modx->context->key)) {
			$this->modx->user = $user;
			$this->modx->getUser($this->modx->context->key);
		}
		if (!$user) {
			return;
		}
		$response = $this->modx->runProcessor('security/logout', $logout_data);
		if ($response->isError()) {
			$errors = $this->_formatProcessorErrors($response);
			$this->modx->log(modX::LOG_LEVEL_ERROR, '[UserProfile] logout error. Username: ' . $this->modx->user->get('username') . ', uid: ' . $this->modx->user->get('id') . '. Message: ' . $errors);
		}
		$this->modx->sendRedirect($this->modx->makeUrl((!empty($id)) ? $id : $this->modx->getOption('site_start'), '', '', 'full'));
	}

	/**
	 * @param $sp
	 */
	public function OnPageNotFound($sp)
	{
		if (!$this->modx->getOption('friendly_urls')) {
			return false;
		}
		// q
		$q = trim(@$_REQUEST[$this->modx->context->getOption('request_param_alias', 'q')]);
		$rarr = explode('/', rtrim($q, '/'));
		// work
		if ($rarr[0] == $this->config['main_url'] /* && (count($rarr) > 1)*/) {
			$uri = $rarr[0];
			$section = $rarr[2];
			$id = (int)$rarr[1];
			// default placehoder
			$sectionPl = $this->config['defaultSection'];
			//
			if ($this->isHide($id)) {
				return false;
			}
			// setting url
/*			$container_suffix = $this->modx->getOption('container_suffix', null, '/', true);
			$uri .= $container_suffix;

			$this->modx->log(1, print_r($uri, 1));

			if (!$userPage = $this->modx->findResource($uri, $this->modx->context->key)) {
				$this->modx->log(modX::LOG_LEVEL_ERROR, print_r('UserProfile error get main_url.', 1));
				return false;
			}*/

			$userPage = $this->getUserPage($uri);
			$this->modx->log(1, print_r($userPage, 1));
			$this->modx->log(1, print_r('work', 1));

			// allowedSections
			$allowedSections = $this->getAllowedSections(true);
			unset($allowedSections[array_search('info', $allowedSections)]);

			if (!empty($section) && (!in_array($section, $allowedSections))) {
				$this->modx->sendRedirect(
					$this->modx->makeUrl($this->modx->getOption('site_start'), '', '', 'full')
					. $this->config['main_url']
					. '/'
					. $id
					. '/'
					,
					$this->redirectArr['moved']
				);
			} elseif (in_array($section, $allowedSections)) {
				$sectionPl = $section;
			} elseif (($id == 0) && (!empty($rarr[1])) && (!in_array($rarr[1], $allowedSections))) {
				return false;
			}
			// set placeholders
			$this->modx->setPlaceholder('user_id', $id);
			$this->modx->setPlaceholder('active_section', $sectionPl);

			$this->modx->sendForward($userPage);
		}

	}

	/**
	 * @return array
	 */
	public function getAllowedSections($setting = false)
	{
		$allowedSections = array_map('trim', explode(',', trim($this->modx->getOption('userprofile_allowed_sections', null, 'info', true))));
		if ($setting) {
			return $allowedSections;
		}
		$allowed = array($this->config['defaultSection']);
		foreach (array_map('trim', explode(',', trim($this->config['allowedSections']))) as $section) {
			if (in_array($section, $allowedSections)) {
				$allowed[] = $section;
			}
		}
		return array_unique($allowed);
	}

	/**
	 * @param array $data
	 * @param array $row
	 * @param array $scriptProperties
	 * @return mixed|null|string
	 */
	public function getContent($data = array(), $row = array(), $scriptProperties = array())
	{
		$content = '';
		$emptyTpl = $scriptProperties['tplSectionEmpty'];
		if (!empty($data[0])) {
			if (empty($data[1])) {
				$action = $this->config['defaultAction'];
			} else {
				$action = $data[1];
			}
			$name = $data[0];
			if ($object = $this->modx->getObject($this->actionArr[$action], array('name' => $name))) {
				$properties = $object->getProperties();
				$scriptProperties = array_merge($properties, $scriptProperties);
				//
				$output = $object->process($scriptProperties);
				$content = $this->processTags($output);
			} else {
				$content = $this->modx->lexicon('up_get_object_err');
			}
		} else {
			$content = empty($emptyTpl)
				? $this->pdoTools->getChunk('', $row)
				: $this->pdoTools->getChunk($emptyTpl, $row, $this->pdoTools->config['fastMode']);
		}
		//$this->modx->log(1, print_r($data, 1));
		//$this->modx->log(1, print_r($scriptProperties, 1));

		return $content;
	}

	/**
	 * @param $id
	 * @return array
	 */
	public function getUserFields($id)
	{
		$profile['id'] = $id;
		if ($user = $this->modx->getObject('modUser', $id)) {
			$profile = array_merge($user->toArray(), $user->getOne('Profile')->toArray());
		}
		unset($profile['id']);
		return $profile;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public function prepareData($data = array())
	{
		$data['gravatar'] = $this->config['gravatarUrl'] . md5(strtolower($data['email'])) . '?s=' . $this->config['gravatarSize'] . '&d=' . $this->config['gravatarIcon'];
		$data['avatar'] = !empty($data['photo'])
			? $data['photo']
			: $data['gravatar'];
		if (!empty($data['resource'])) {
			$data['url'] = $this->modx->makeUrl($data['resource'], '', '', 'full');
		}
		$data['date_ago'] = $this->dateFormat($data['createdon']);
		$data['registration_format'] = $this->dateFormat($data['registration']);
		$data['lastactivity_format'] = $this->dateFormat($data['lastactivity']);
		//
		$data['main_url'] = $this->config['main_url'];
		$data['user_id'] = $this->config['user_id'];
		$data['active_section'] = $this->config['active_section'];

		return $data;
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
	 * @param array $d
	 * @return bool
	 */
	public function isHide($id = 0)
	{
		if (!empty($id)) {
			$usersArr = array_map('trim', explode(',', trim($this->modx->getOption('userprofile_hide_users'))));
			$groupsArr = array_map('trim', explode(',', trim($this->modx->getOption('userprofile_hide_groups'))));
			if (in_array($id, $usersArr)) {
				return true;
			}
			foreach ($this->modx->getIterator('modUserGroupMember', array('member' => $id)) as $group) {
				$groupId = $group->toArray()['user_group'];
				if (in_array($groupId, $groupsArr)) {
					return true;
				}
			}

			$arr['id'] = $id;
			if ($this->modx->getOption('userprofile_hide_inactive')) {
				$arr['active'] = 1;
			}
			if (!$this->modx->getCount('modUser', $arr)) {
				return true;
			}
			return false;
		} elseif ($id == 0) {
			return false;
		}
		return true;
	}

	/**
	 * Formats date to "10 minutes ago" or "Yesterday in 22:10"
	 * This algorithm taken from https://github.com/livestreet/livestreet/blob/7a6039b21c326acf03c956772325e1398801c5fe/engine/modules/viewer/plugs/function.date_format.php
	 * @param string $date Timestamp to format
	 * @param string $dateFormat
	 *
	 * @return string
	 */
	public function dateFormat($date, $dateFormat = null)
	{
		$date = preg_match('/^\d+$/', $date) ? $date : strtotime($date);
		$dateFormat = !empty($dateFormat) ? $dateFormat : $this->config['dateFormat'];
		$current = time();
		$delta = $current - $date;
		if ($this->config['dateNow']) {
			if ($delta < $this->config['dateNow']) {
				return $this->modx->lexicon('up_date_now');
			}
		}
		if ($this->config['dateMinutes']) {
			$minutes = round(($delta) / 60);
			if ($minutes < $this->config['dateMinutes']) {
				if ($minutes > 0) {
					return $this->declension($minutes, $this->modx->lexicon('up_date_minutes_back', array('minutes' => $minutes)));
				} else {
					return $this->modx->lexicon('up_date_minutes_back_less');
				}
			}
		}
		if ($this->config['dateHours']) {
			$hours = round(($delta) / 3600);
			if ($hours < $this->config['dateHours']) {
				if ($hours > 0) {
					return $this->declension($hours, $this->modx->lexicon('up_date_hours_back', array('hours' => $hours)));
				} else {
					return $this->modx->lexicon('up_date_hours_back_less');
				}
			}
		}
		if ($this->config['dateDay']) {
			switch (date('Y-m-d', $date)) {
				case date('Y-m-d'):
					$day = $this->modx->lexicon('up_date_today');
					break;
				case date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))):
					$day = $this->modx->lexicon('up_date_yesterday');
					break;
				case date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y'))):
					$day = $this->modx->lexicon('up_date_tomorrow');
					break;
				default:
					$day = null;
			}
			if ($day) {
				$format = str_replace("day", preg_replace("#(\w{1})#", '\\\${1}', $day), $this->config['dateDay']);
				return date($format, $date);
			}
		}
		$m = date("n", $date);
		$month_arr = $this->modx->fromJSON($this->modx->lexicon('up_date_months'));
		$month = $month_arr[$m - 1];
		$format = preg_replace("~(?<!\\\\)F~U", preg_replace('~(\w{1})~u', '\\\${1}', $month), $dateFormat);
		return date($format, $date);
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
	public function declension($count, $forms, $lang = null)
	{
		if (empty($lang)) {
			$lang = $this->modx->getOption('cultureKey', null, 'en');
		}
		$forms = $this->modx->fromJSON($forms);
		if ($lang == 'ru') {
			$mod100 = $count % 100;
			switch ($count % 10) {
				case 1:
					if ($mod100 == 11) {
						$text = $forms[2];
					} else {
						$text = $forms[0];
					}
					break;
				case 2:
				case 3:
				case 4:
					if (($mod100 > 10) && ($mod100 < 20)) {
						$text = $forms[2];
					} else {
						$text = $forms[1];
					}
					break;
				case 5:
				case 6:
				case 7:
				case 8:
				case 9:
				case 0:
				default:
					$text = $forms[2];
			}
		} else {
			if ($count == 1) {
				$text = $forms[0];
			} else {
				$text = $forms[1];
			}
		}
		return $text;
	}

	/**
	 * Method for change email of user
	 *
	 * @param $email
	 * @param $id
	 *
	 * @return bool
	 */
	public function changeEmail($email, $id)
	{
		$activationHash = md5(uniqid(md5($this->modx->user->get('id')), true));
		$key = md5($this->modx->user->Profile->get('internalKey'));
		/** @var modDbRegister $register */
		$register = $this->modx->getService('registry', 'registry.modRegistry')->getRegister('user', 'registry.modDbRegister');
		$register->connect();
		$register->subscribe('/emailchange/'.$key);
		//
		$msgs = $register->read(array('poll_limit' => 1, 'remove_read' => false));
		if (!empty($msgs)) {
			return false;
		}
		//
		$register->send('/emailchange/',
			array($key => array(
				'hash' => $activationHash,
				'email' => $email,
				'redirect' => $this->modx->makeUrl($this->getUserPage(), '', '', 'full'). '/' . $this->modx->user->get('id'),
			)
			), array('ttl' => 86400));
		$link = $this->modx->makeUrl($id, '', array(
				'action' => 'profile/confirmemail',
				'email' => $email,
				'hash' => $activationHash,
			)
			, 'full'
		);
		$chunk = $this->modx->getChunk($this->config['tplConfirm'],
			array_merge(
				$this->modx->user->getOne('Profile')->toArray()
				, $this->modx->user->toArray()
				, array('link' => $link)
			)
		);
		/** @var modPHPMailer $mail */
		$mail = $this->modx->getService('mail', 'mail.modPHPMailer');
		$mail->set(modMail::MAIL_BODY, $chunk);
		$mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
		$mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
		$mail->set(modMail::MAIL_SENDER, $this->modx->getOption('emailsender'));
		$mail->set(modMail::MAIL_SUBJECT, $this->modx->lexicon('up_profile_email_subject'));
		$mail->address('to', $email);
		$mail->address('reply-to', $this->modx->getOption('emailsender'));
		$mail->setHTML(true);
		$response = !$mail->send()
			? $mail->mailer->ErrorInfo
			: true;
		$mail->reset();
		return $response;
	}

	/**
	 * Method for confirmation of user email
	 *
	 * @param $data
	 */
	public function confirmemail($data)
	{
		/** @var modDbRegister $register */
		$register = $this->modx->getService('registry', 'registry.modRegistry')->getRegister('user', 'registry.modDbRegister');
		$register->connect();
		$register->subscribe('/email/change/' . md5($this->modx->user->Profile->get('internalKey')));
		$msgs = $register->read(array('poll_limit' => 1));
		if (!empty($msgs[0])) {
			$msgs = reset($msgs);
			if (@$data['hash'] === @$msgs['hash'] && !empty($msgs['email'])) {
				//$this->modx->user->set('username', $msgs['email']);
				//$this->modx->user->getOne('Profile')->set('email', $msgs['email']);
				$a = $this->modx->user->getOne('Profile');

				$this->modx->log(1, print_r($a->toArray(), 1));

				$a->set('email', $msgs['email']);


				$this->modx->log(1, print_r('===========', 1));
				$this->modx->log(1, print_r($a->toArray(), 1));

				$a->save();

				//$this->modx->user->save();
			}
		}


		$this->modx->log(1, print_r('===========', 1));
		$this->modx->log(1, print_r($msgs['email'], 1));


		$this->modx->sendRedirect($msgs['redirect']);
	}

	/**
	 * from https://github.com/bezumkin/Tickets/blob/9c09152ae4a1cdae04fb31d2bc0fa57be5e0c7ea/core/components/tickets/model/tickets/tickets.class.php#L1120
	 *
	 * Loads an instance of pdoTools
	 * @return boolean
	 */
	public function loadPdoTools()
	{
		if (!is_object($this->pdoTools) || !($this->pdoTools instanceof pdoTools)) {
			/** @var pdoFetch $pdoFetch */
			$fqn = $this->modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
			if ($pdoClass = $this->modx->loadClass($fqn, '', false, true)) {
				$this->pdoTools = new $pdoClass($this->modx, $this->config);
			} elseif ($pdoClass = $this->modx->loadClass($fqn, MODX_CORE_PATH . 'components/pdotools/model/', false, true)) {
				$this->pdoTools = new $pdoClass($this->modx, $this->config);
			} else {
				$this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not load pdoFetch from "MODX_CORE_PATH/components/pdotools/model/".');
			}
		}
		return !empty($this->pdoTools) && $this->pdoTools instanceof pdoTools;
	}

	/**
	 * from https://github.com/bezumkin/Tickets/blob/9c09152ae4a1cdae04fb31d2bc0fa57be5e0c7ea/core/components/tickets/model/tickets/tickets.class.php#L1147
	 *
	 * Process and return the output from a Chunk by name.
	 * @param string $name The name of the chunk.
	 * @param array $properties An associative array of properties to process the Chunk with, treated as placeholders within the scope of the Element.
	 * @param boolean $fastMode If false, all MODX tags in chunk will be processed.
	 * @return string The processed output of the Chunk.
	 */
	public function getChunk($name, array $properties = array(), $fastMode = false)
	{
		if (!$this->modx->parser) {
			$this->modx->getParser();
		}
		if (!$this->pdoTools) {
			$this->loadPdoTools();
		}
		return $this->pdoTools->getChunk($name, $properties, $fastMode);
	}

	/**
	 * from https://github.com/bezumkin/Office/blob/97d3e6112aa9868e7d848efd4345052ed103850b/core/components/office/controllers/profile.class.php#L254
	 *
	 * Sanitizes a string
	 *
	 * @param string $string The string to sanitize
	 * @param integer $length The length of sanitized string
	 * @return string The sanitized string.
	 */
	public function Sanitize($string = '', $length = 0)
	{
		$expr = $this->modx->getOption('up_sanitize_pcre', null, '/[^-_a-z\p{L}0-9@\s\.\,\:\/\\\]+/iu', true);
		$string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
		$sanitized = trim(preg_replace($expr, '', $string));
		return !empty($length)
			? mb_substr($sanitized, 0, $length, 'UTF-8')
			: $sanitized;
	}

	/**
	 * Shorthand for load and run an processor in this component
	 *
	 * @param string $action
	 * @param array $scriptProperties
	 *
	 * @return mixed
	 */
	function runProcessor($action = '', $scriptProperties = array())
	{
		$this->modx->error->errors = $this->modx->error->message = null;
		return $this->modx->runProcessor($action, $scriptProperties, array(
				'processors_path' => $this->config['processorsPath']
			)
		);
	}

	/**
	 * from https://github.com/bezumkin/Office/blob/master/core/components/office/controllers/auth.class.php#L608
	 *
	 * More convenient error messages
	 *
	 * @param modProcessorResponse $response
	 * @param string $glue
	 *
	 * @return string
	 */
	protected function _formatProcessorErrors(modProcessorResponse $response, $glue = 'br')
	{
		$errormsgs = array();
		if ($response->hasMessage()) {
			$errormsgs[] = $response->getMessage();
		}
		if ($response->hasFieldErrors()) {
			if ($errors = $response->getFieldErrors()) {
				foreach ($errors as $error) {
					$errormsgs[] = $error->message;
				}
			}
		}
		return implode($glue, $errormsgs);
	}

	/**
	 * Collects and processes any set of tags
	 *
	 * @param mixed $html Source code for parse
	 * @param integer $maxIterations
	 *
	 * @return mixed $html Parsed html
	 */
	public function processTags($html, $maxIterations = 10)
	{
		$this->modx->getParser()->processElementTags('', $html, false, false, '[[', ']]', array(), $maxIterations);
		$this->modx->getParser()->processElementTags('', $html, true, true, '[[', ']]', array(), $maxIterations);
		return $html;
	}

}