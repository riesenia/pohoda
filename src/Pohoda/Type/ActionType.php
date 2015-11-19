<?php
namespace Rshop\Synchronization\Pohoda\Type;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Common\SetNamespaceTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionType extends Agenda
{
    use SetNamespaceTrait;

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['type', 'filter']);

        // validate / format options
        $resolver->setRequired('type');
        $resolver->setAllowedValues('type', ['add', 'add/update', 'update', 'delete']);
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

        $xml = $this->_createXML()->addChild($this->_namespace . ':actionType', null, $this->_namespace($this->_namespace));
        $action = $xml->addChild($this->_namespace . ':' . ($this->_data['type'] == 'add/update' ? 'add' : $this->_data['type']));

        if ($this->_data['type'] == 'add/update') {
            $action->addAttribute('update', 'true');
        }

        if ($this->_data['filter']) {
            $filter = $action->addChild('ftr:filter', null, $this->_namespace('ftr'));

            foreach ($this->_data['filter'] as $property => $value) {
                $ftr = $filter->addChild('ftr:' . $property, is_array($value) ? null : $value);

                if (is_array($value)) {
                    foreach ($value as $tProperty => $tValue) {
                        $ftr->addChild('typ:' . $tProperty, $tValue, $this->_namespace('typ'));
                    }
                }
            }
        }

        return $xml;
    }
}
