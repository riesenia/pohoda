<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda\CashSlip\Header;
use Riesenia\Pohoda\CashSlip\Item;
use Riesenia\Pohoda\CashSlip\Summary;
use Riesenia\Pohoda\Common\AddParameterToHeaderTrait;
use Riesenia\Pohoda\Common\OptionsResolver;

class CashSlip extends Agenda
{
    use AddParameterToHeaderTrait;

    /** @var string */
    public static $importRoot = 'lst:prodejka';

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
     * Add item.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addItem(array $data): self
    {
        if (!isset($this->_data['prodejkaDetail'])) {
            $this->_data['prodejkaDetail'] = [];
        }

        $this->_data['prodejkaDetail'][] = new Item($data, $this->_ico);

        return $this;
    }

    /**
     * Add summary.
     *
     * @param array<string,mixed> $data
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
        $xml = $this->_createXML()->addChild('pro:prodejka', '', $this->_namespace('pro'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['header', 'prodejkaDetail', 'summary'], 'pro');

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
