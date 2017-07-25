<?php
namespace Rshop\Synchronization\Pohoda\UserList;

use Rshop\Synchronization\Pohoda\Agenda;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemUserCode extends Agenda
{
    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['code', 'name', 'constant']);

        // validate / format options
        $resolver->setRequired('code');
        $resolver->setRequired('name');
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('lst:itemUserCode', null, $this->_namespace('lst'));
        $xml->addAttribute('code', $this->_data['code']);
        $xml->addAttribute('name', $this->_data['name']);

        if (isset($this->_data['constant'])) {
            $xml->addAttribute('constants', $this->_data['constants']);
        }

        return $xml;
    }
}
