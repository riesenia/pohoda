<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Common;

trait SetNamespaceTrait
{
    /** @var string */
    protected $_namespace;

    /** @var string */
    protected $_nodeName;

    /**
     * Set namespace.
     *
     * @param string $namespace
     *
     * @return void
     */
    public function setNamespace(string $namespace)
    {
        $this->_namespace = $namespace;
    }

    /**
     * Set node name.
     *
     * @param string $nodeName
     *
     * @return void
     */
    public function setNodeName(string $nodeName)
    {
        $this->_nodeName = $nodeName;
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        if ($this->_namespace === null) {
            throw new \LogicException('Namespace not set.');
        }

        if ($this->_nodeName === null) {
            throw new \LogicException('Node name not set.');
        }

        $xml = $this->_createXML()->addChild($this->_namespace . ':' . $this->_nodeName, '', $this->_namespace($this->_namespace));

        $this->_addElements($xml, $this->_elements, 'typ');

        return $xml;
    }
}
