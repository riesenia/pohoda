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

class Stock extends Agenda
{
    use AddActionTypeTrait;
    use AddParameterToHeaderTrait;

    /** @var string */
    public static $importRoot = 'lStk:stock';

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // pass to header
        if ($data) {
            $data = ['header' => new Header($data, $ico, $resolveOptions)];
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Add price.
     *
     * @param string $code
     * @param float  $value
     *
     * @return $this
     */
    public function addPrice(string $code, float $value): self
    {
        if (!isset($this->_data['stockPriceItem'])) {
            $this->_data['stockPriceItem'] = [];
        }

        $this->_data['stockPriceItem'][] = new Price([
            'code' => $code,
            'value' => $value
        ], $this->_ico);

        return $this;
    }

    /**
     * Add image.
     *
     * @param string   $filepath
     * @param string   $description
     * @param int|null $order
     * @param bool     $default
     *
     * @return $this
     */
    public function addImage(string $filepath, string $description = '', int $order = null, bool $default = false): self
    {
        $this->_data['header']->addImage($filepath, $description, $order, $default);

        return $this;
    }

    /**
     * Add category.
     *
     * @param int $categoryId
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
     * @param array $data
     *
     * @return $this
     */
    public function addIntParameter(array $data): self
    {
        $this->_data['header']->addIntParameter($data);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('stk:stock', null, $this->_namespace('stk'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['actionType', 'header', 'stockPriceItem'], 'stk');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['header']);
    }
}
