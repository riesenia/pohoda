<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Invoice;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\AddParameterTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Type\CurrencyItem;
use Riesenia\Pohoda\Type\StockItem;

class Item extends Agenda
{
    use AddParameterTrait;

    /** @var string[] */
    protected $_refElements = ['accounting', 'classificationVAT', 'classificationKVDPH', 'centre', 'activity', 'contract'];

    /** @var string[] */
    protected $_elements = ['text', 'quantity', 'unit', 'coefficient', 'payVAT', 'rateVAT', 'percentVAT', 'discountPercentage', 'homeCurrency', 'foreignCurrency', 'note', 'code', 'guarantee', 'guaranteeType', 'stockItem', 'accounting', 'classificationVAT', 'classificationKVDPH', 'centre', 'activity', 'contract', 'expirationDate'];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process home currency
        if (isset($data['homeCurrency'])) {
            $data['homeCurrency'] = new CurrencyItem($data['homeCurrency'], $ico, $resolveOptions);
        }
        // process foreign currency
        if (isset($data['foreignCurrency'])) {
            $data['foreignCurrency'] = new CurrencyItem($data['foreignCurrency'], $ico, $resolveOptions);
        }
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
        $xml = $this->_createXML()->addChild('inv:invoiceItem', '', $this->_namespace('inv'));

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
        $resolver->setNormalizer('text', $resolver->getNormalizer('string90'));
        $resolver->setNormalizer('quantity', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('unit', $resolver->getNormalizer('string10'));
        $resolver->setNormalizer('coefficient', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('payVAT', $resolver->getNormalizer('bool'));
        $resolver->setAllowedValues('rateVAT', ['none', 'high', 'low', 'third', 'historyHigh', 'historyLow', 'historyThird']);
        $resolver->setNormalizer('percentVAT', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('discountPercentage', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('note', $resolver->getNormalizer('string90'));
        $resolver->setNormalizer('code', $resolver->getNormalizer('string64'));
        $resolver->setNormalizer('guarantee', $resolver->getNormalizer('int'));
        $resolver->setAllowedValues('guaranteeType', ['none', 'hour', 'day', 'month', 'year', 'life']);
        $resolver->setNormalizer('expirationDate', $resolver->getNormalizer('date'));
    }
}
