<?php
namespace Rshop\Synchronization\Pohoda\Stock;

use Rshop\Synchronization\Pohoda\Agenda;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Price extends Agenda
{
    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['code', 'value']);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('stk:stockPriceItem', null, $this->_namespace('stk'));

        return $this->_addRefElement($xml, 'stk:stockPrice', ['ids' => $this->_data['code'], 'price' => $this->_data['value']]);
    }
}
