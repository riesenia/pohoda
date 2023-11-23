<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Stock;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;

class StockItem extends Agenda
{
    /** @var string[] */
    protected $_refElements = ['stockInfo', 'storage'];

    /** @var string[] */
    protected $_elements = ['id', 'stockInfo', 'storage', 'code', 'name', 'count', 'quantity', 'stockPriceItem'];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process stockPriceItem
        if (isset($data['stockPriceItem'])) {
            $data['stockPriceItem'] = \array_map(function ($stockPriceItem) use ($ico, $resolveOptions) {
                return new Price($stockPriceItem['stockPrice'], $ico, $resolveOptions);
            }, $data['stockPriceItem']);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('stk:stockItem', '', $this->_namespace('stk'));

        $this->_addElements($xml, $this->_elements, 'stk');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        $resolver->setNormalizer('id', $resolver->getNormalizer('int'));
        $resolver->setNormalizer('count', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('quantity', $resolver->getNormalizer('float'));
    }
}
