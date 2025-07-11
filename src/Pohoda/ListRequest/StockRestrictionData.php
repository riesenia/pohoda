<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\ListRequest;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class StockRestrictionData extends Agenda
{
    /** @var string[] */
    protected $_elements = ['categories', 'pictures', 'attachments', 'intParameters', 'stockItem', 'stockPriceItem', 'stockParameters', 'stockAttach', 'stockSerialNumber', 'relatedStocks', 'relatedFiles', 'relatedLinks', 'alternativeStocks'];

    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('lst:restrictionData', '', $this->_namespace('lStk'));

        $this->_addElements($xml, $this->_elements, 'lStk');

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('categories', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('pictures', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('attachments', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('intParameters', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('stockItem', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('stockPriceItem', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('stockParameters', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('stockAttach', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('stockSerialNumber', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('relatedStocks', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('relatedFiles', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('relatedLinks', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('alternativeStocks', $resolver->getNormalizer('bool'));
    }
}
