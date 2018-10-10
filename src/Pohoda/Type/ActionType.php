<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Type;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Common\SetNamespaceTrait;

class ActionType extends Agenda
{
    use SetNamespaceTrait;

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        if ($this->_namespace === null) {
            throw new \LogicException('Namespace not set.');
        }

        $xml = $this->_createXML()->addChild($this->_namespace . ':actionType', null, $this->_namespace($this->_namespace));
        $action = $xml->addChild($this->_namespace . ':' . ($this->_data['type'] == 'add/update' ? 'add' : $this->_data['type']));

        if ($this->_data['type'] == 'add/update') {
            $action->addAttribute('update', 'true');
        }

        if ($this->_data['filter']) {
            $filter = $action->addChild('ftr:filter', null, $this->_namespace('ftr'));

            if ($this->_data['agenda']) {
                $filter->addAttribute('agenda', $this->_data['agenda']);
            }

            foreach ($this->_data['filter'] as $property => $value) {
                $ftr = $filter->addChild('ftr:' . $property, \is_array($value) ? null : $value);

                if (\is_array($value)) {
                    foreach ($value as $tProperty => $tValue) {
                        $ftr->addChild('typ:' . $tProperty, $tValue, $this->_namespace('typ'));
                    }
                }
            }
        }

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['type', 'filter', 'agenda']);

        // validate / format options
        $resolver->setRequired('type');
        $resolver->setAllowedValues('type', ['add', 'add/update', 'update', 'delete']);
    }
}
