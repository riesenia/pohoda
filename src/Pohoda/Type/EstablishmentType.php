<?php
namespace Rshop\Synchronization\Pohoda\Type;

use Rshop\Synchronization\Pohoda\Agenda;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstablishmentType extends Agenda
{
    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['company', 'city', 'street', 'zip'];

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
        $resolver->setNormalizer('company', $resolver->string255Normalizer);
        $resolver->setNormalizer('city', $resolver->string45Normalizer);
        $resolver->setNormalizer('street', $resolver->string64Normalizer);
        $resolver->setNormalizer('zip', $resolver->string15Normalizer);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('typ:establishment', null, $this->_namespace('typ'));

        $this->_addElements($xml, $this->_elements, 'typ');

        return $xml;
    }
}
