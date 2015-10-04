<?php
namespace Rshop\Synchronization\Pohoda\Stock;

use Rshop\Synchronization\Pohoda\Agenda;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Category extends Agenda
{
    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['idCategory']);

        // validate / format options
        $resolver->setRequired('idCategory');
        $resolver->setNormalizer('idCategory', $resolver->intNormalizer);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        return $this->_createXML()->addChild('stk:idCategory', $this->_data['idCategory'], $this->_namespace('stk'));
    }
}
