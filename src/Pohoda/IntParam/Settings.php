<?php
namespace Rshop\Synchronization\Pohoda\IntParam;

use Rshop\Synchronization\Pohoda\Agenda;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Settings extends Agenda
{
    /**
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = ['currency'];

    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['unit', 'length', 'currency', 'parameterList'];

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
        $resolver->setNormalizer('length', $resolver->intNormalizer);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('ipm:parameterSettings', null, $this->_namespace('ipm'));

        $this->_addElements($xml, $this->_elements, 'ipm');

        return $xml;
    }
}
