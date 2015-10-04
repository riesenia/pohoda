<?php
namespace Rshop\Synchronization\Pohoda;

use Rshop\Synchronization\Pohoda\IntParam\Settings;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntParam extends Agenda
{
    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['name', 'description', 'parameterType', 'parameterSettings'];

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setRequired('name');
        $resolver->setRequired('parameterType');
        $resolver->setAllowedValues('parameterType', ['textValue', 'currencyValue', 'booleanValue', 'numberValue', 'integerValue', 'datetimeValue', 'unit', 'listValue']);
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
        // prepare empty parameter list for list
        if ($data['parameterType'] == 'listValue') {
            $data['parameterSettings'] = ['parameterList' => []];
        }

        // process settings
        if (isset($data['parameterSettings'])) {
            $data['parameterSettings'] = new Settings($data['parameterSettings'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('ipm:intParamDetail', null, $this->_namespace('ipm'));
        $xml->addAttribute('version', '2.0');

        $param = $xml->addChild('ipm:intParam');
        $this->_addElements($param, $this->_elements, 'ipm');

        return $xml;
    }
}
