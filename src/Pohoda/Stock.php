<?php
namespace Rshop\Synchronization\Pohoda;

use Rshop\Synchronization\Pohoda\Common\AddActionTypeTrait;
use Rshop\Synchronization\Pohoda\Common\AddParameterToHeaderTrait;
use Rshop\Synchronization\Pohoda\Stock\Header;
use Rshop\Synchronization\Pohoda\Stock\Price;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Stock extends Agenda
{
    use AddActionTypeTrait, AddParameterToHeaderTrait;

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
     * Add price
     *
     * @param string price code
     * @param float price
     * @return \Rshop\Synchronization\Pohoda\Stock
     */
    public function addPrice($code, $value)
    {
        if (!isset($this->_data['stockPriceItem'])) {
            $this->_data['stockPriceItem'] = [];
        }

        $this->_data['stockPriceItem'][] = new Price([
            'code' => $code,
            'value' => $value
        ], $this->_ico);

        return $this;
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('stk:stock', null, $this->_namespace('stk'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['actionType', 'header', 'stockPriceItem'], 'stk');

        return $xml;
    }
}
