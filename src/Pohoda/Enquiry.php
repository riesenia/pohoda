<?php
/**
 * @author Pavel Fiala fiala.pvl@gmail.com
 * @link https://github.com/daddyy/pohoda
 */

declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\AddParameterToHeaderTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Enquiry\Header;
use Riesenia\Pohoda\Enquiry\Item;
use Riesenia\Pohoda\Enquiry\Summary;

class Enquiry extends Agenda
{
    use AddParameterToHeaderTrait;

    /** @var string */
    public static $importRoot = 'enq:enquiry';

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
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addItem(array $data): self
    {
        if (!isset($this->_data['enquiryDetail'])) {
            $this->_data['enquiryDetail'] = [];
        }

        $this->_data['enquiryDetail'][] = new Item($data, $this->_ico);

        return $this;
    }

    /**
     * Add offer summary.
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
        $xml = $this->_createXML()->addChild('enq:enquiry', '', $this->_namespace('enq'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['header', 'enquiryDetail', 'summary'], 'enq');

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
