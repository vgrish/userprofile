<?php
class upExtendedUpdateProcessor extends modObjectUpdateProcessor {
	public $classKey = 'upExtendedSetting';
	public $languageTopics = array('userprofile');
	public $permission = 'upsetting_save';
	/** {@inheritDoc} */
	public function initialize() {
		if (!$this->modx->hasPermission($this->permission)) {
			return $this->modx->lexicon('access_denied');
		}
		return parent::initialize();
	}

	/** {@inheritDoc} */
	public function beforeSet() {
		$props = $this->getProperty('properties');
		if ($this->modx->getObject('upExtendedSetting', array('name' => $this->getProperty('name'), 'id:!=' => $this->getProperty('id')))) {
			$this->modx->error->addField('name', $this->modx->lexicon('up_err_ae'));
		}
		return parent::beforeSet();
	}

}
return 'upExtendedUpdateProcessor';