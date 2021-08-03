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
use Riesenia\Pohoda\Offer\Header;
use Riesenia\Pohoda\Offer\Item;
use Riesenia\Pohoda\Offer\Summary;

class Offer extends Agenda
{
    use AddParameterToHeaderTrait;

    /** @var string */
    public static $importRoot = 'lst:offer';

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
     * Add offer item.
     *
     * @param array $data
     *
     * @return $this
     */
    public function addItem(array $data): self
    {
        if (!isset($this->_data['offerDetail'])) {
            $this->_data['offerDetail'] = [];
        }

        $this->_data['offerDetail'][] = new Item($data, $this->_ico);

        return $this;
    }

    /**
     * Add offer summary.
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
        $xml = $this->_createXML()->addChild('ofr:offer', '', $this->_namespace('ofr'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['header', 'offerDetail', 'summary'], 'ord');

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
