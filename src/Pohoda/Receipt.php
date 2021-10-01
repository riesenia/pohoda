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
use Riesenia\Pohoda\Receipt\Header;
use Riesenia\Pohoda\Receipt\Item;

class Receipt extends Agenda
{
    use AddParameterToHeaderTrait;

    /** @var string */
    public static $importRoot = 'lst:prijemka';

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
        if (!isset($this->_data['prijemkaDetail'])) {
            $this->_data['prijemkaDetail'] = [];
        }

        $this->_data['prijemkaDetail'][] = new Item($data, $this->_ico);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('pri:prijemka', '', $this->_namespace('pri'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['header', 'prijemkaDetail', 'summary'], 'pri');

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
