<?php
namespace Rshop\Synchronization\Pohoda\Common;

use Rshop\Synchronization\Pohoda\Type\Parameter;

trait AddParameterTrait
{
    /**
     * Set user-defined parameter
     *
     * @param string name (can be set without preceding VPr / RefVPr)
     * @param string type
     * @param mixed value
     * @param mixed list
     * @return \Rshop\Synchronization\Pohoda\Agenda
     */
    public function addParameter($name, $type, $value, $list = null)
    {
        if (!isset($this->_data['parameters'])) {
            $this->_data['parameters'] = [];
        }

        $this->_data['parameters'][] = new Parameter([
            'name' => $name,
            'type' => $type,
            'value' => $value,
            'list' => $list
        ], $this->_ico);

        return $this;
    }
}
