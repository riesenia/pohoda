<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Receipt;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Type\StockItem;

class Item extends Agenda
{
    /** @var array */
    protected $_refElements = ['centre', 'activity', 'contract'];

    /** @var array */
    protected $_elements = ['quantity', 'unit', 'coefficient', 'payVAT', 'rateVAT', 'discountPercentage', 'homeCurrency', 'foreignCurrency', 'code', 'stockItem', 'note', 'centre', 'activity', 'contract'];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process stock item
        if (isset($data['stockItem'])) {
            $data['stockItem'] = new StockItem($data['stockItem'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('pri:prijemkaItem', null, $this->_namespace('pri'));

        $this->_addElements($xml, $this->_elements, 'pri');

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
        $resolver->setNormalizer('unit', $resolver->getNormalizer('string10'));
        $resolver->setNormalizer('coefficient', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('payVAT', $resolver->getNormalizer('bool'));
        $resolver->setAllowedValues('rateVAT', ['none', 'third', 'low', 'high']);
        $resolver->setNormalizer('discountPercentage', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('code', $resolver->getNormalizer('string64'));
        $resolver->setNormalizer('note', $resolver->getNormalizer('string90'));
    }
}
