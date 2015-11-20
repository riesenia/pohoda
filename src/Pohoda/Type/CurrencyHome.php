<?php
namespace Rshop\Synchronization\Pohoda\Type;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Common\SetNamespaceTrait;
use Rshop\Synchronization\Pohoda\Common\SetNodeNameTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyHome extends Agenda
{
    use SetNamespaceTrait, SetNodeNameTrait;

    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['priceNone', 'priceLow', 'priceLowVAT', 'priceLowSum', 'priceHigh', 'priceHighVAT', 'priceHighSum'];

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
        $resolver->setNormalizer('priceNone', $resolver->floatNormalizer);
        $resolver->setNormalizer('priceLow', $resolver->floatNormalizer);
        $resolver->setNormalizer('priceLowVAT', $resolver->floatNormalizer);
        $resolver->setNormalizer('priceLowSum', $resolver->floatNormalizer);
        $resolver->setNormalizer('priceHigh', $resolver->floatNormalizer);
        $resolver->setNormalizer('priceHighVAT', $resolver->floatNormalizer);
        $resolver->setNormalizer('priceHighSum', $resolver->floatNormalizer);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        if (is_null($this->_namespace)) {
            throw new \LogicException("Namespace not set.");
        }

        if (is_null($this->_nodeName)) {
            throw new \LogicException("Node name not set.");
        }

        $xml = $this->_createXML()->addChild($this->_namespace . ':' . $this->_nodeName, null, $this->_namespace($this->_namespace));

        $this->_addElements($xml, $this->_elements, 'typ');

        return $xml;
    }
}
