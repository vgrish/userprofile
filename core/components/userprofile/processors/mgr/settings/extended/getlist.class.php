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
		if ($this->getProperty('combo')) {
			$c->select('id,name');
			$c->where(array('active' => 1));
/*			if ($delivery_id = $this->getProperty('delivery_id')) {

				if ($delivery = $this->modx->getObject('msDelivery', $delivery_id)) {
					$q = $this->modx->newQuery('msDeliveryMember', array('delivery_id' => $delivery_id));
					$q->select('payment_id');
					if ($q->prepare() && $q->stmt->execute()) {
						$ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN);
						$c->where(array('id:IN' => $ids));
					}
				}
			}*/
		}
		return $c;
	}

	/** {@inheritDoc} */
/*	public function prepareQueryBeforeCount(xPDOQuery $c) {
		return $c;
	}*/

	/** {@inheritDoc} */
	public function prepareRow(xPDOObject $object) {
		$array = $object->toArray();
		return $array;
	}

}
return 'upExtendedGetListProcessor';
