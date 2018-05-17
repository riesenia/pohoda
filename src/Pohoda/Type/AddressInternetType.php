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

class AddressInternetType extends Agenda
{
    /** @var array */
    protected $_elements = ['company', 'title', 'surname', 'name', 'city', 'street', 'number', 'zip', 'ico', 'dic', 'icDph', 'phone', 'mobilPhone', 'fax', 'email', 'www'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('typ:address', null, $this->_namespace('typ'));

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
        $resolver->setNormalizer('title', $resolver->getNormalizer('string7'));
        $resolver->setNormalizer('surname', $resolver->getNormalizer('string32'));
        $resolver->setNormalizer('name', $resolver->getNormalizer('string32'));
        $resolver->setNormalizer('city', $resolver->getNormalizer('string45'));
        $resolver->setNormalizer('street', $resolver->getNormalizer('string45'));
        $resolver->setNormalizer('number', $resolver->getNormalizer('string10'));
        $resolver->setNormalizer('zip', $resolver->getNormalizer('string15'));
        $resolver->setNormalizer('ico', $resolver->getNormalizer('string15'));
        $resolver->setNormalizer('dic', $resolver->getNormalizer('string18'));
        $resolver->setNormalizer('icDph', $resolver->getNormalizer('string18'));
        $resolver->setNormalizer('phone', $resolver->getNormalizer('string40'));
        $resolver->setNormalizer('mobilPhone', $resolver->getNormalizer('string24'));
        $resolver->setNormalizer('fax', $resolver->getNormalizer('string24'));
        $resolver->setNormalizer('email', $resolver->getNormalizer('string64'));
        $resolver->setNormalizer('www', $resolver->getNormalizer('string32'));
    }
}
