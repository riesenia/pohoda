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
use Riesenia\Pohoda\IssueSlip\Header;
use Riesenia\Pohoda\IssueSlip\Item;
use Riesenia\Pohoda\IssueSlip\Summary;

class IssueSlip extends Agenda
{
    use AddParameterToHeaderTrait;

    /** @var string */
    public static $importRoot = 'lst:vydejka';

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
     * @param array $data
     *
     * @return $this
     */
    public function addItem(array $data): self
    {
        if (!isset($this->_data['vydejkaDetail'])) {
            $this->_data['vydejkaDetail'] = [];
        }

        $this->_data['vydejkaDetail'][] = new Item($data, $this->_ico);

        return $this;
    }

    /**
     * Add summary.
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
        $xml = $this->_createXML()->addChild('vyd:vydejka', '', $this->_namespace('vyd'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['header', 'vydejkaDetail', 'summary'], 'vyd');

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
