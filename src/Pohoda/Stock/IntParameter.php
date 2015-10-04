<?php
namespace Rshop\Synchronization\Pohoda\Stock;

use Rshop\Synchronization\Pohoda\Agenda;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntParameter extends Agenda
{
    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['intParameterID', 'value']);

        // validate / format options
        $resolver->setRequired('intParameterID');
        $resolver->setNormalizer('intParameterID', $resolver->intNormalizer);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('stk:intParameter', null, $this->_namespace('stk'));

        $this->_addElements($xml, ['intParameterID'], 'stk');

        // value
        $xml->addChild('stk:intParameterValues')->addChild('stk:intParameterValue')->addChild('stk:parameterValue', $this->_data['value']);

        return $xml;
    }
}
