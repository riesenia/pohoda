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

class ShipToAddressType extends Agenda
{
    /** @var string[] */
    protected $_refElements = ['country'];

    /** @var string[] */
    protected $_elements = ['company', 'division', 'name', 'city', 'street', 'zip', 'country', 'phone', 'email', 'defaultShipAddress'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('typ:shipToAddress', '', $this->_namespace('typ'));

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
        $resolver->setNormalizer('company', $resolver->getNormalizer('string255'));
        $resolver->setNormalizer('division', $resolver->getNormalizer('string32'));
        $resolver->setNormalizer('name', $resolver->getNormalizer('string32'));
        $resolver->setNormalizer('city', $resolver->getNormalizer('string45'));
        $resolver->setNormalizer('street', $resolver->getNormalizer('string45'));
        $resolver->setNormalizer('zip', $resolver->getNormalizer('string15'));
        $resolver->setNormalizer('phone', $resolver->getNormalizer('string40'));
        $resolver->setNormalizer('email', $resolver->getNormalizer('string98'));
        $resolver->setNormalizer('defaultShipAddress', $resolver->getNormalizer('bool'));
    }
}
