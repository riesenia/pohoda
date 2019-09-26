<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Type;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Common\SetNamespaceTrait;
use Riesenia\Pohoda\Common\SetNodeNameTrait;

class Address extends Agenda
{
    use SetNamespaceTrait;
    use SetNodeNameTrait;

    /** @var array */
    protected $_refElements = ['extId'];

    /** @var array */
    protected $_elements = ['id', 'extId', 'address', 'addressLinkToAddress', 'shipToAddress'];

    /** @var array */
    protected $_elementsAttributesMapper = [
        'addressLinkToAddress' => ['address', 'linkToAddress', null],
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process address
        if (isset($data['address'])) {
            $data['address'] = new AddressType($data['address'], $ico, $resolveOptions);
        }
        // process shipping address
        if (isset($data['shipToAddress'])) {
            $data['shipToAddress'] = new ShipToAddressType($data['shipToAddress'], $ico, $resolveOptions);
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

        if ($this->_nodeName === null) {
            throw new \LogicException('Node name not set.');
        }

        $xml = $this->_createXML()->addChild($this->_namespace . ':' . $this->_nodeName, null, $this->_namespace($this->_namespace));

        $this->_addElements($xml, $this->_elements, 'typ');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('id', $resolver->getNormalizer('int'));
        $resolver->setNormalizer('addressLinkToAddress', $resolver->getNormalizer('bool'));
    }
}
