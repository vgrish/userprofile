<?php
class upExtendedGetProcessor extends modObjectGetProcessor {
	public $classKey = 'upExtendedSetting';
	public $languageTopics = array('userprofile');
	public $permission = 'upsetting_view';
	/** {@inheritDoc} */
	public function initialize() {
		if (!$this->modx->hasPermission($this->permission)) {
			return $this->modx->lexicon('access_denied');
		}
		return parent::initialize();
	}
}
return 'upExtendedGetProcessor';