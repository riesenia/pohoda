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

        // resolve options
        $this->_data = $resolveOptions ? $this->_resolveOptions($data) : $data;
    }

    /**
     * Set user-defined parameter
     *
     * @param string name (can be set without preceding VPr / RefVPr)
     * @param string type
     * @param mixed value
     * @param mixed list
     * @return \Rshop\Synchronization\Pohoda\Agenda
     */
    public function addParameter($name, $type, $value, $list = null)
    {
        if (!in_array($type, ['text', 'memo', 'currency', 'boolean', 'number', 'datetime', 'integer', 'list'])) {
            throw new \OutOfRangeException('Invalid parameter type.');
        }

        if (!isset($this->_data['parameters'])) {
            $this->_data['parameters'] = [];
        }

        try {
            $value = call_user_func($this->_createNormalizer($type), [], $value);
        } catch (\Exception $e) {
            // silent
        }

        $prefix = 'VPr';

        if ($type == 'list') {
            $prefix = 'RefVPr';
        }

        $parameter = [
            'name' => strpos($name, $prefix) === 0 ? $name : $prefix . $name,
            'type' => $type,
            'value' => $value
        ];

        if ($list) {
            $parameter['list'] = $list;
        }

        $this->_data['parameters'][] = $parameter;

        return $this;
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
    protected function _addElements(\SimpleXMLElement $xml, array $elements, $namespace = null)
    {
        foreach ($elements as $element) {
            if (!isset($this->_data[$element])) {
                continue;
            }

            $xml->addChild(($namespace ? $namespace . ':' : '') . $element, htmlspecialchars($this->_data[$element]));
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
    protected function _addRefElements(\SimpleXMLElement $xml, array $elements, $namespace = null)
    {
        foreach ($elements as $element) {
            if (!isset($this->_data[$element])) {
                continue;
            }

            $this->_addRefElement($xml, ($namespace ? $namespace . ':' : '') . $element, $this->_data[$element]);
        }
    }

    /**
     * Add ref element
     *
     * @param \SimpleXMLElement
     * @param string element name
     * @param mixed value
     * @return void
     */
    protected function _addRefElement(\SimpleXMLElement $xml, $name, $value)
    {
        $node = $xml->addChild($name);

        if (!is_array($value)) {
            $value = ['ids' => $value];
        }

        foreach ($value as $key => $value) {
            $node->addChild('typ:' . $key, htmlspecialchars($value), $this->_namespace('typ'));
        }
    }

    /**
     * Add parameters element
     *
     * @param \SimpleXMLElement
     * @param string namespace
     * @param string element name
     * @return void
     */
    protected function _addParameters(\SimpleXMLElement $xml, $namespace = null, $element = 'parameters')
    {
        if (!isset($this->_data['parameters'])) {
            return;
        }

        $parameters = $xml->addChild(($namespace ? $namespace . ':' : '') . $element);

        foreach ($this->_data['parameters'] as $parameter) {
            $node = $parameters->addChild('typ:parameter', null, $this->_namespace('typ'));

            $node->addChild('typ:name', $parameter['name']);

            if ($parameter['type'] == 'list') {
                if (isset($parameter['list'])) {
                    $this->_addRefElement($node, 'typ:list', $parameter['list']);
                }

                $this->_addRefElement($node, 'typ:listValueRef', $parameter['value']);
            } else {
                $node->addChild('typ:' . $parameter['type'] . 'Value', htmlspecialchars($parameter['value']));
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

        // define string normalizers
        foreach ([10, 24, 48, 90, 240] as $length) {
            $resolver->{'string' . $length . 'Normalizer'} = $this->_createStringNormalizer($length);
        }

        // define date normalizer
        $resolver->dateNormalizer = $this->_createDateNormalizer();

        // define float normalizer
        $resolver->floatNormalizer = $this->_createFloatNormalizer();

        // define int normalizer
        $resolver->intNormalizer = $this->_createIntNormalizer();

        // define bool normalizer
        $resolver->boolNormalizer = $this->_createBoolNormalizer();

        $this->_configureOptions($resolver);

        return $resolver->resolve($data);
    }

    /**
     * Create normalizer
     *
     * @param string type
     * @param mixed normalizer parameter
     * @return \Closure
     */
    protected function _createNormalizer($type, $param = null)
    {
        switch ($type) {
            case 'string':
                return function ($options, $value) use ($param) {
                    return mb_substr($value, 0, $param, 'utf-8');
                };

            case 'date':
            case 'datetime':
                return function ($options, $value) {
                    $time = strtotime($value);

                    if (!$time) {
                        throw new \DomainException("Not a valid date: " . $value);
                    }

                    return date('Y-m-d', $time);
                };

            case 'float':
            case 'number':
                return function ($options, $value) {
                    return (float)str_replace(',', '.', preg_replace('/[^0-9,.]/', '', $value));
                };

            case 'int':
            case 'integer':
                return function ($options, $value) {
                    return (int)$value;
                };

            case 'bool':
            case 'boolean':
                return function ($options, $value) {
                    return !$value || strtolower($value) === 'false' ? 'false' : 'true';
                };

            default:
                throw new \DomainException("Not a valid normalizer type: " . $type);
        }
    }

    /**
     * Handle dynamic method calls
     *
     * @param string method name
     * @param array arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        // _create<Type>Normalizer for creating normalizers
        if (preg_match('/_create([A-Z][a-zA-Z0-9]+)Normalizer/', $method, $matches)) {
            return call_user_func([$this, '_createNormalizer'], lcfirst($matches[1]), isset($arguments[0]) ? $arguments[0] : null);
        }

        throw new \BadMethodCallException("Unknown method: " . $method);
    }
}
