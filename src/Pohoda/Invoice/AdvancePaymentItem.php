<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Invoice;

use Riesenia\Pohoda\Common\OptionsResolver;

class AdvancePaymentItem extends Item
{
    /** @var array */
    protected $_refElements = ['sourceDocument', 'accounting', 'classificationVAT', 'classificationKVDPH', 'centre', 'activity', 'contract'];

    /** @var array */
    protected $_elements = ['sourceDocument', 'quantity', 'payVAT', 'rateVAT', 'discountPercentage', 'homeCurrency', 'foreignCurrency', 'note', 'accounting', 'classificationVAT', 'classificationKVDPH', 'centre', 'activity', 'contract'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('inv:invoiceAdvancePaymentItem', null, $this->_namespace('inv'));

        $this->_addElements($xml, \array_merge($this->_elements, ['parameters']), 'inv');

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
        $resolver->setNormalizer('quantity', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('payVAT', $resolver->getNormalizer('bool'));
        $resolver->setAllowedValues('rateVAT', ['none', 'third', 'low', 'high']);
        $resolver->setNormalizer('discountPercentage', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('note', $resolver->getNormalizer('string90'));
    }
}
