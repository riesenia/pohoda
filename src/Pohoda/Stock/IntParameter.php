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
        $resolver->setDefined(['intParameterID', 'intParameterName', 'intParameterOrder', 'intParameterType', 'value']);

        // validate / format options
        $resolver->setRequired('intParameterID');
        $resolver->setNormalizer('intParameterID', $resolver->intNormalizer);
        $resolver->setDefault('intParameterName', '...');
        $resolver->setDefault('intParameterOrder', '1');
        $resolver->setRequired('intParameterType');
        $resolver->setAllowedValues('intParameterType', ['textValue', 'currencyValue', 'booleanValue', 'numberValue', 'integerValue', 'datetimeValue', 'unit', 'listValue']);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('stk:intParameter', null, $this->_namespace('stk'));

        $this->_addElements($xml, ['intParameterID', 'intParameterName', 'intParameterOrder', 'intParameterType'], 'stk');

        // value
        $xml->addChild('stk:intParameterValues')->addChild('stk:intParameterValue')->addChild('stk:parameterValue', $this->_data['value']);

        return $xml;
    }
}
