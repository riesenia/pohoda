<?php
namespace Rshop\Synchronization\Pohoda\Stock;

use Rshop\Synchronization\Pohoda\Agenda;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Picture extends Agenda
{
    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['filepath', 'description', 'order', 'default']);

        // validate / format options
        $resolver->setRequired('filepath');
        $resolver->setNormalizer('order', $resolver->intNormalizer);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('stk:picture', null, $this->_namespace('stk'));

        if ($this->_data['default']) {
            $xml->addAttribute('default', 'true');
        }

        $this->_addElements($xml, ['filepath', 'description', 'order'], 'stk');

        return $xml;
    }
}
