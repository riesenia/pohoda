<?php
namespace Rshop\Synchronization\Pohoda\Type;

use Rshop\Synchronization\Pohoda\Agenda;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressInternetType extends Agenda
{
    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['company', 'title', 'surname', 'name', 'city', 'street', 'number', 'zip', 'ico', 'dic', 'icDph', 'phone', 'mobilPhone', 'fax', 'email', 'www'];

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
        $resolver->setNormalizer('title', $resolver->string7Normalizer);
        $resolver->setNormalizer('surname', $resolver->string32Normalizer);
        $resolver->setNormalizer('name', $resolver->string32Normalizer);
        $resolver->setNormalizer('city', $resolver->string45Normalizer);
        $resolver->setNormalizer('street', $resolver->string45Normalizer);
        $resolver->setNormalizer('number', $resolver->string10Normalizer);
        $resolver->setNormalizer('zip', $resolver->string15Normalizer);
        $resolver->setNormalizer('ico', $resolver->string15Normalizer);
        $resolver->setNormalizer('dic', $resolver->string18Normalizer);
        $resolver->setNormalizer('icDph', $resolver->string18Normalizer);
        $resolver->setNormalizer('phone', $resolver->string40Normalizer);
        $resolver->setNormalizer('mobilPhone', $resolver->string24Normalizer);
        $resolver->setNormalizer('fax', $resolver->string24Normalizer);
        $resolver->setNormalizer('email', $resolver->string64Normalizer);
        $resolver->setNormalizer('www', $resolver->string32Normalizer);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('typ:address', null, $this->_namespace('typ'));

        $this->_addElements($xml, $this->_elements, 'typ');

        return $xml;
    }
}
