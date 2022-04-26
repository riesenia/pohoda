<?php
declare(strict_types=1);

namespace z41f1t\Extension\Pohoda\Enquiry;

use Riesenia\Pohoda\Common\AddParameterTrait;

class Item extends \Riesenia\Pohoda\Offer\Item
{
    use AddParameterTrait;

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('enq:enquiryItem', '', $this->_namespace('enq'));

        $this->_addElements($xml, \array_merge($this->_elements, ['parameters']), 'enq');

        return $xml;
    }
}
