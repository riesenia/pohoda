<?php
namespace Rshop\Synchronization\Pohoda\Type;

use Rshop\Synchronization\Pohoda\Agenda;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Parameter extends Agenda
{
    /**
     * Namespace
     *
     * @var string
     */
    protected $_namespace = null;

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['name', 'type', 'value', 'list']);

        // validate / format options
        $resolver->setRequired('name');
        $resolver->setNormalizer('name', function ($options, $value) {
            $prefix = 'VPr';

            if ($options['type'] == 'list') {
                $prefix = 'RefVPr';
            }

            if (strpos($value, $prefix) === 0) {
                return $value;
            }

            return $prefix . $value;
        });
        $resolver->setRequired('type');
        $resolver->setAllowedValues('type', ['text', 'memo', 'currency', 'boolean', 'number', 'datetime', 'integer', 'list']);
        $resolver->setNormalizer('value', function ($options, $value) {
            try {
                return call_user_func($this->_createNormalizer($options['type']), [], $value);
            } catch (\Exception $e) {
                return $value;
            }
        });
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('typ:parameter', null, $this->_namespace('typ'));

        $xml->addChild('typ:name', $this->_data['name']);

        if ($this->_data['type'] == 'list') {
            $this->_addRefElement($xml, 'typ:listValueRef', $this->_data['value']);

            if (isset($this->_data['list'])) {
                $this->_addRefElement($xml, 'typ:list', $this->_data['list']);
            }

            return $xml;
        }

        $xml->addChild('typ:' . $this->_data['type'] . 'Value', htmlspecialchars($this->_data['value']));

        return $xml;
    }
}
