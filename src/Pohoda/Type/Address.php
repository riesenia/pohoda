<?php
namespace Rshop\Synchronization\Pohoda\Type;

use Rshop\Synchronization\Pohoda\Agenda;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Address extends Agenda
{
    /**
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = ['country'];

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['company', 'division', 'name', 'city', 'street', 'zip', 'ico', 'dic', 'icDph', 'country', 'phone', 'mobilPhone', 'fax', 'email']);

        // validate / format options
        $resolver->setNormalizer('company', $resolver->string255Normalizer);
        $resolver->setNormalizer('division', $resolver->string32Normalizer);
        $resolver->setNormalizer('name', $resolver->string32Normalizer);
        $resolver->setNormalizer('city', $resolver->string45Normalizer);
        $resolver->setNormalizer('street', $resolver->string45Normalizer);
        $resolver->setNormalizer('zip', $resolver->string15Normalizer);
        $resolver->setNormalizer('ico', $resolver->string15Normalizer);
        $resolver->setNormalizer('dic', $resolver->string18Normalizer);
        $resolver->setNormalizer('icDph', $resolver->string18Normalizer);
        $resolver->setNormalizer('phone', $resolver->string40Normalizer);
        $resolver->setNormalizer('mobilPhone', $resolver->string24Normalizer);
        $resolver->setNormalizer('fax', $resolver->string24Normalizer);
        $resolver->setNormalizer('email', $resolver->string98Normalizer);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('typ:address', null, $this->_namespace('typ'));

        $this->_addElements($xml, ['company', 'division', 'name', 'city', 'street', 'zip', 'ico', 'dic', 'icDph', 'country', 'phone', 'mobilPhone', 'fax', 'email'], 'typ');

        return $xml;
    }
}
