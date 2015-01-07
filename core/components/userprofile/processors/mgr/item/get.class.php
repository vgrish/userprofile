<?php

/**
 * Get an Item
 */
class userprofileItemGetProcessor extends modObjectGetProcessor {
	public $objectType = 'userprofileItem';
	public $classKey = 'userprofileItem';
	public $languageTopics = array('userprofile:default');
	//public $permission = 'view';


	/**
	 * We doing special check of permission
	 * because of our objects is not an instances of modAccessibleObject
	 *
	 * @return mixed
	 */
	public function process() {
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		return parent::process();
	}

}

return 'userprofileItemGetProcessor';