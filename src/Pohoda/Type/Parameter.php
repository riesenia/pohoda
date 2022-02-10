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

class Parameter extends Agenda
{
    /** @var string */
    protected $_namespace;

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('typ:parameter', '', $this->_namespace('typ'));

        $xml->addChild('typ:name', $this->_data['name']);

        if ($this->_data['type'] == 'list') {
            $this->_addRefElement($xml, 'typ:listValueRef', $this->_data['value']);

            if (isset($this->_data['list'])) {
                $this->_addRefElement($xml, 'typ:list', $this->_data['list']);
            }

            return $xml;
        }

        $xml->addChild('typ:' . $this->_data['type'] . 'Value', \htmlspecialchars($this->_data['value']));

        return $xml;
    }

    /**
     * {@inheritdoc}
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

            if (\strpos($value, $prefix) === 0) {
                return $value;
            }

            return $prefix . $value;
        });
        $resolver->setRequired('type');
        $resolver->setAllowedValues('type', ['text', 'memo', 'currency', 'boolean', 'number', 'datetime', 'integer', 'list']);
        $resolver->setNormalizer('value', function ($options, $value) use ($resolver) {
            $normalizer = $options['type'];

            // date for datetime
            if ($normalizer == 'datetime') {
                $normalizer = 'date';
            }

            try {
                return \call_user_func($resolver->getNormalizer($normalizer), [], $value);
            } catch (\Exception $e) {
                return \is_array($value) ? $value : (string) $value;
            }
        });
    }
}
