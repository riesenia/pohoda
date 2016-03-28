<?php
namespace Rshop\Synchronization\Pohoda\Invoice;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Type\CurrencyItem;
use Rshop\Synchronization\Pohoda\Type\StockItem;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvancePaymentItem extends Item
{
    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // add source document
        $this->_refElements[] = 'sourceDocument';
        $this->_elements[] = 'sourceDocument';

        parent::_configureOptions($resolver);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('inv:invoiceAdvancePaymentItem', null, $this->_namespace('inv'));

        $this->_addElements($xml, array_merge($this->_elements, ['parameters']), 'inv');

        return $xml;
    }
}
