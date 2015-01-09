<?php
class upExtendedCreateProcessor extends modObjectCreateProcessor {
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
	public function beforeSave() {
		$this->object->fromArray(array(
			'rank' => $this->modx->getCount('upExtendedSetting')
		));
		return parent::beforeSave();
	}

}
return 'upExtendedCreateProcessor';