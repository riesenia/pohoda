<?php
namespace Rshop\Synchronization\Pohoda\Common;

trait SetNodeNameTrait
{
    /**
     * Node name
     *
     * @var string
     */
    protected $_nodeName = null;

    /**
     * Set node name
     *
     * @param string node name
     * @return void
     */
    public function setNodeName($nodeName)
    {
        $this->_nodeName = $nodeName;
    }
}
