<?php
namespace Rshop\Synchronization\Pohoda\Common;

trait AddParameterToHeaderTrait
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
        $this->_data['header']->addParameter($name, $type, $value, $list);

        return $this;
    }
}
