<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda\Common\AddActionTypeTrait;
use Riesenia\Pohoda\Common\AddParameterToHeaderTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Stock\Header;
use Riesenia\Pohoda\Stock\Price;
use Riesenia\Pohoda\Stock\StockItem;

class Stock extends Agenda
{
    use AddActionTypeTrait;
    use AddParameterToHeaderTrait;

    /** @var string */
    public static $importRoot = 'lStk:stock';

    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // pass to header
        if ($data) {
            $data = ['header' => new Header($data, $ico, $resolveOptions)];
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Add stock item.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addStockItem(array $data): self
    {
        if (!isset($this->_data['stockDetail'])) {
            $this->_data['stockDetail'] = [];
        }

        $this->_data['stockDetail'][] = new StockItem($data, $this->_ico);

        return $this;
    }

    /**
     * Add price.
     *
     * @return $this
     */
    public function addPrice(string $code, float $value): self
    {
        if (!isset($this->_data['stockPriceItem'])) {
            $this->_data['stockPriceItem'] = [];
        }

        $this->_data['stockPriceItem'][] = new Price([
            'ids' => $code,
            'price' => $value
        ], $this->_ico);

        return $this;
    }

    /**
     * Add image.
     *
     * @return $this
     */
    public function addImage(string $filepath, string $description = '', ?int $order = null, bool $default = false): self
    {
        $this->_data['header']->addImage($filepath, $description, $order, $default);

        return $this;
    }

    /**
     * Add category.
     *
     * @return $this
     */
    public function addCategory(int $categoryId): self
    {
        $this->_data['header']->addCategory($categoryId);

        return $this;
    }

    /**
     * Add int parameter.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addIntParameter(array $data): self
    {
        $this->_data['header']->addIntParameter($data);

        return $this;
    }

    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('stk:stock', '', $this->_namespace('stk'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['actionType', 'header', 'stockDetail', 'stockPriceItem'], 'stk');

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['header']);
    }
}
