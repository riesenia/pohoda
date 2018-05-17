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
use Riesenia\Pohoda\Order\Header;
use Riesenia\Pohoda\Order\Item;
use Riesenia\Pohoda\Order\Summary;

class Order extends Agenda
{
    use AddActionTypeTrait, AddParameterToHeaderTrait;

    /** @var string */
    public static $importRoot = 'lst:order';

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // pass to header
        $data = ['header' => new Header($data, $ico, $resolveOptions)];

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Add order item.
     *
     * @param array $data
     *
     * @return $this
     */
    public function addItem(array $data): self
    {
        if (!isset($this->_data['orderDetail'])) {
            $this->_data['orderDetail'] = [];
        }

        $this->_data['orderDetail'][] = new Item($data, $this->_ico);

        return $this;
    }

    /**
     * Add order summary.
     *
     * @param array $data
     *
     * @return $this
     */
    public function addSummary($data): self
    {
        $this->_data['summary'] = new Summary($data, $this->_ico);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('ord:order', null, $this->_namespace('ord'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['actionType', 'header', 'orderDetail', 'summary'], 'ord');

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
