<?php
namespace Rshop\Synchronization\Pohoda;

use Rshop\Synchronization\Pohoda\Common\AddParameterToHeaderTrait;
use Rshop\Synchronization\Pohoda\Invoice\Header;
use Rshop\Synchronization\Pohoda\Invoice\Item;
use Rshop\Synchronization\Pohoda\Invoice\AdvancePaymentItem;
use Rshop\Synchronization\Pohoda\Invoice\Summary;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Invoice extends Agenda
{
    use AddParameterToHeaderTrait;

    /**
     * Root for import
     *
     * @var string
     */
    public static $importRoot = 'lst:invoice';

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['header']);
    }

    /**
     * Construct agenda using provided data
     *
     * @param array data
     * @param string ICO
     * @param bool if options resolver should be used
     */
    public function __construct($data, $ico, $resolveOptions = true)
    {
        // pass to header
        $data = ['header' => new Header($data, $ico, $resolveOptions)];

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Add invoice item
     *
     * @param array item data
     * @return \Rshop\Synchronization\Pohoda\Invoice
     */
    public function addItem($data)
    {
        if (!isset($this->_data['invoiceDetail'])) {
            $this->_data['invoiceDetail'] = [];
        }

        $this->_data['invoiceDetail'][] = new Item($data, $this->_ico);

        return $this;
    }

    /**
     * Add advance payment item
     *
     * @param array item data
     * @return \Rshop\Synchronization\Pohoda\Invoice
     */
    public function addAdvancePaymentItem($data)
    {
        if (!isset($this->_data['invoiceDetail'])) {
            $this->_data['invoiceDetail'] = [];
        }

        $this->_data['invoiceDetail'][] = new AdvancePaymentItem($data, $this->_ico);

        return $this;
    }

    /**
     * Add invoice summary
     *
     * @param array summary data
     * @return \Rshop\Synchronization\Pohoda\Invoice
     */
    public function addSummary($data)
    {
        $this->_data['summary'] = new Summary($data, $this->_ico);

        return $this;
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('inv:invoice', null, $this->_namespace('inv'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['header', 'invoiceDetail', 'summary'], 'inv');

        return $xml;
    }
}
