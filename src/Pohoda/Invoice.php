<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda\Common\AddParameterToHeaderTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Invoice\AdvancePaymentItem;
use Riesenia\Pohoda\Invoice\Header;
use Riesenia\Pohoda\Invoice\Item;
use Riesenia\Pohoda\Invoice\Summary;
use Riesenia\Pohoda\Type\Link;

class Invoice extends Agenda
{
    use AddParameterToHeaderTrait;

    /** @var string */
    public static $importRoot = 'lst:invoice';

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
     * Add link.
     *
     * @param array $data
     *
     * @return $this
     */
    public function addLink(array $data): self
    {
        if (!isset($this->_data['links'])) {
            $this->_data['links'] = [];
        }

        $this->_data['links'][] = new Link($data, $this->_ico);

        return $this;
    }

    /**
     * Add invoice item.
     *
     * @param array $data
     *
     * @return $this
     */
    public function addItem(array $data): self
    {
        if (!isset($this->_data['invoiceDetail'])) {
            $this->_data['invoiceDetail'] = [];
        }

        $this->_data['invoiceDetail'][] = new Item($data, $this->_ico);

        return $this;
    }

    /**
     * Add advance payment item.
     *
     * @param array $data
     *
     * @return $this
     */
    public function addAdvancePaymentItem(array $data): self
    {
        if (!isset($this->_data['invoiceDetail'])) {
            $this->_data['invoiceDetail'] = [];
        }

        $this->_data['invoiceDetail'][] = new AdvancePaymentItem($data, $this->_ico);

        return $this;
    }

    /**
     * Add invoice summary.
     *
     * @param array $data
     *
     * @return $this
     */
    public function addSummary(array $data): self
    {
        $this->_data['summary'] = new Summary($data, $this->_ico);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('inv:invoice', null, $this->_namespace('inv'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['links', 'header', 'invoiceDetail', 'summary'], 'inv');

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
