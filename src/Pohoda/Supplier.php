<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Supplier\StockItem;
use Riesenia\Pohoda\Supplier\SupplierItem;

class Supplier extends Agenda
{
    /** @var string */
    public static $importRoot = 'lst:supplier';

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process stockItem
        if (isset($data['stockItem'])) {
            $data['stockItem'] = new StockItem($data['stockItem'], $ico, $resolveOptions);
        }

        // process suppliers
        if (isset($data['suppliers'])) {
            $data['suppliers'] = \array_map(function ($supplier) use ($ico, $resolveOptions) {
                return new SupplierItem($supplier['supplierItem'], $ico, $resolveOptions);
            }, $data['suppliers']);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('sup:supplier', '', $this->_namespace('sup'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['stockItem', 'suppliers'], 'sup');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['stockItem', 'suppliers']);
    }
}
