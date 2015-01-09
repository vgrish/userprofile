<?php
class upExtendedGetListProcessor extends modObjectGetListProcessor {
	public $classKey = 'upExtendedSetting';
	public $defaultSortField = 'rank';
	public $defaultSortDirection  = 'asc';
	public $permission = 'upsetting_list';
	/** {@inheritDoc} */
	public function initialize() {
		if (!$this->modx->hasPermission($this->permission)) {
			return $this->modx->lexicon('access_denied');
		}
		return parent::initialize();
	}

	/** {@inheritDoc} */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		return $c;
	}

}
return 'upExtendedGetListProcessor';
