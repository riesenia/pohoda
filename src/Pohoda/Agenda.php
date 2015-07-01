<?php
namespace Rshop\Synchronization\Pohoda;

use Rshop\Synchronization\Pohoda;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base class for Pohoda objects
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
abstract class Agenda
{
    /**
     * Data
     *
     * @var array
     */
    protected $_data;

    /**
     * ICO
     *
     * @var string
     */
    protected $_ico;

    /**
     * XML object
     *
     * @var \SimpleXMLElement
     */
    protected $_xml;

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     * @return void
     */
    abstract protected function _configureOptions(OptionsResolver $resolver);

    /**
     * Get XML
     *
     * @return string
     */
    abstract public function getXML();

    /**
     * Construct agenda using provided data
     *
     * @param array data
     * @param string ICO
     * @param bool if options resolver should be used
     */
    public function __construct($data, $ico, $resolveOptions = true)
    {
        // set ICO
        $this->_ico = $ico;

        $this->_data = $resolveOptions ? $this->_resolveOptions($data) : $data;
    }

    /**
     * Create XML
     *
     * @return \SimpleXMLElement
     */
    protected function _createXML()
    {
        return new \SimpleXMLElement('<?xml version="1.0" encoding="Windows-1250"?><root ' . implode(' ', array_map(function ($k, $v) {
            return 'xmlns:' . $k . '="' . $v . '"';
        }, array_keys(Pohoda::$namespaces), Pohoda::$namespaces)) . '></root>');
    }

    /**
     * Get namespace
     *
     * @param string
     * @return string
     */
    protected function _namespace($short)
    {
        if (!isset(Pohoda::$namespaces[$short])) {
            throw new \OutOfRangeException('Invalid namespace.');
        }

        return Pohoda::$namespaces[$short];
    }

    /**
     * Add batch elements
     *
     * @param \SimpleXMLElement
     * @param array
     * @param string namespace
     * @return void
     */
    protected function _addElements($xml, array $elements, $namespace = null)
    {
        foreach ($elements as $element) {
            if (isset($this->_data[$element])) {
                $xml->addChild(($namespace ? $namespace . ':' : '') . $element, $this->_data[$element]);
            }
        }
    }

    /**
     * Add batch ref elements
     *
     * @param \SimpleXMLElement
     * @param array
     * @param string namespace
     * @return void
     */
    protected function _addRefElements($xml, array $elements, $namespace = null)
    {
        foreach ($elements as $element) {
            if (isset($this->_data[$element])) {
                $node = $xml->addChild(($namespace ? $namespace . ':' : '') . $element);

                $ref = $this->_data[$element];

                if (!is_array($ref)) {
                    $ref = ['ids' => $ref];
                }

                foreach ($ref as $key => $value) {
                    $node->addChild('typ:' . $key, $value, $this->_namespace('typ'));
                }
            }
        }
    }

    /**
     * Resolve options
     *
     * @param array data
     * @return array resolved data
     */
    protected function _resolveOptions($data)
    {
        $resolver = new OptionsResolver();

        // define maxLength normalizers
        foreach ([10, 24, 90, 240] as $length) {
            $resolver->{'string' . $length . 'Normalizer'} = $this->_createStringNormalizer($length);
        }

        // define date normalizer
        $resolver->dateNormalizer = function ($options, $value) {
            $time = strtotime($value);

            if (!$time) {
                throw new \DomainException("Not a valid date: " . $value);
            }

            return date('Y-m-d', $time);
        };

        // define float normalizer
        $resolver->floatNormalizer = function ($options, $value) {
            return str_replace(',', '.', preg_replace('/[^0-9,.]/', '', $value));
        };

        // define int normalizer
        $resolver->intNormalizer = function ($options, $value) {
            return (int)$value;
        };

        // define bool normalizer
        $resolver->boolNormalizer = function ($options, $value) {
            return !$value || strtolower($value) === 'false' ? 'false' : 'true';
        };

        $this->_configureOptions($resolver);

        return $resolver->resolve($data);
    }

    /**
     * Create string normalizer
     *
     * @param int max length
     * @return \Closure
     */
    protected function _createStringNormalizer($length)
    {
        return function ($options, $value) use ($length) {
            return mb_substr($value, 0, $length, 'utf-8');
        };
    }
}
