<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Supplier;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class SupplierItem extends Agenda
{
    /** @var string[] */
    protected $_refElements = ['refAd', 'currency', 'deliveryPeriod'];

    /** @var string[] */
    protected $_elements = ['default', 'refAd', 'orderCode', 'orderName', 'purchasingPrice', 'currency', 'rate', 'payVAT', 'ean', 'printEAN', 'unitEAN', 'unitCoefEAN', 'deliveryTime', 'deliveryPeriod', 'minQuantity', 'unit', 'note'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('sup:supplierItem', '', $this->_namespace('sup'));

        // handle default
        if ($this->_data['default']) {
            $xml->addAttribute('default', $this->_data['default']);
            unset($this->_data['default']);
        }

        $this->_addElements($xml, $this->_elements, 'sup');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        $resolver->setNormalizer('default', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('orderCode', $resolver->getNormalizer('string64'));
        $resolver->setNormalizer('orderName', $resolver->getNormalizer('string90'));
        $resolver->setNormalizer('purchasingPrice', $resolver->getNormalizer('number'));
        $resolver->setNormalizer('rate', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('payVAT', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('ean', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('printEAN', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('unitEAN', $resolver->getNormalizer('string10'));
        $resolver->setNormalizer('unitCoefEAN', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('deliveryTime', $resolver->getNormalizer('int'));
        $resolver->setNormalizer('minQuantity', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('unit', $resolver->getNormalizer('string10'));
    }
}
