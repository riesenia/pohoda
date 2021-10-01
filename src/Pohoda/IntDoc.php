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
use Riesenia\Pohoda\IntDoc\Header;
use Riesenia\Pohoda\IntDoc\Item;
use Riesenia\Pohoda\IntDoc\Summary;

class IntDoc extends Agenda
{
    use AddParameterToHeaderTrait;

    /** @var string */
    public static $importRoot = 'lst:intDoc';

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
     * Add intDoc item.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addItem(array $data): self
    {
        if (!isset($this->_data['intDocDetail'])) {
            $this->_data['intDocDetail'] = [];
        }

        $this->_data['intDocDetail'][] = new Item($data, $this->_ico);

        return $this;
    }

    /**
     * Add intDoc summary.
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
        $xml = $this->_createXML()->addChild('int:intDoc', '', $this->_namespace('int'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['header', 'intDocDetail', 'summary'], 'int');

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
