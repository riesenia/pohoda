<?php
namespace Rshop\Synchronization\Pohoda\Common;

trait SetNamespaceTrait
{
    /**
     * Namespace
     *
     * @var string
     */
    protected $_namespace = null;

    /**
     * Set namespace
     *
     * @param string namespace
     * @return void
     */
    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
    }
}
