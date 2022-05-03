<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Document;

use Riesenia\Pohoda\Common\AddParameterTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Type\Address;
use Riesenia\Pohoda\Type\MyAddress;

abstract class Header extends Part
{
    use AddParameterTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process partner identity
        if (isset($data['partnerIdentity'])) {
            $data['partnerIdentity'] = new Address($data['partnerIdentity'], $ico, $resolveOptions);
        }

        // process my identity
        if (isset($data['myIdentity'])) {
            $data['myIdentity'] = new MyAddress($data['myIdentity'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        if ($this->_namespace === null) {
            throw new \LogicException('Namespace not set.');
        }

        if ($this->_nodePrefix === null) {
            throw new \LogicException('Node name prefix not set.');
        }

        $xml = $this->_createXML()->addChild($this->_namespace . ':' . $this->_nodePrefix . 'Header', '', $this->_namespace($this->_namespace));

        $this->_addElements($xml, \array_merge($this->_elements, ['parameters']), $this->_namespace);

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);
    }
}
