<?php
namespace Rshop\Synchronization\Pohoda\Common;

use Rshop\Synchronization\Pohoda\Type\ActionType;

trait AddActionTypeTrait
{
    /**
     * Add action type
     *
     * @param string type
     * @param mixed filter
     * @return \Rshop\Synchronization\Pohoda\Stock
     */
    public function addActionType($type, $filter = null)
    {
        if (isset($this->_data['actionType'])) {
            throw new \OutOfRangeException('Duplicate action type.');
        }

        $this->_data['actionType'] = new ActionType([
            'type' => $type,
            'filter' => $filter
        ], $this->_ico);

        return $this;
    }
}
