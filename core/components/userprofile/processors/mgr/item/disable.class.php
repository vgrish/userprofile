<?php

/**
 * Disable an Item
 */
class userprofileItemDisableProcessor extends modObjectProcessor {
	public $objectType = 'userprofileItem';
	public $classKey = 'userprofileItem';
	public $languageTopics = array('userprofile');
	//public $permission = 'save';


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->failure($this->modx->lexicon('userprofile_item_err_ns'));
		}

		foreach ($ids as $id) {
			/** @var userprofileItem $object */
			if (!$object = $this->modx->getObject($this->classKey, $id)) {
				return $this->failure($this->modx->lexicon('userprofile_item_err_nf'));
			}

			$object->set('active', false);
			$object->save();
		}

		return $this->success();
	}

}

return 'userprofileItemDisableProcessor';
