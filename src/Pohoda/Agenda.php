<?php
namespace Rshop\Synchronization\Pohoda;

use Rshop\Synchronization\Pohoda;
use Rshop\Synchronization\Pohoda\Type\ActionType;
use Rshop\Synchronization\Pohoda\Type\Parameter;
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
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = [];

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
     * @return \SimpleXMLElement
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
     * Add action type
     *
     * @param string type
     * @param mixed filter
     * @return \Rshop\Synchronization\Pohoda\Stock
     */
    public function addActionType($type, $filter = null)
    {
        if (isset($this->_data['actionType'])) {
            throw new \OutOfRangeException('Duplicate action type.');
        }

        $this->_data['actionType'] = new ActionType([
            'type' => $type,
            'filter' => $filter
        ], $this->_ico);

        return $this;
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
        if (!isset($this->_data['parameters'])) {
            $this->_data['parameters'] = [];
        }

        $this->_data['parameters'][] = new Parameter([
            'name' => $name,
            'type' => $type,
            'value' => $value,
            'list' => $list
        ], $this->_ico);

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

            if (in_array($element, $this->_refElements)) {
                $this->_addRefElement($xml, ($namespace ? $namespace . ':' : '') . $element, $this->_data[$element]);
                continue;
            }

            $child = $xml->addChild(($namespace ? $namespace . ':' : '') . $element, is_array($this->_data[$element]) ? null : htmlspecialchars($this->_data[$element]));

            if (is_array($this->_data[$element])) {
                foreach ($this->_data[$element] as $node) {
                    $this->_appendNode($child, $node->getXML());
                }
            }
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
     * Append SimpleXMLElement to another SimpleXMLElement
     *
     * @param \SimpleXMLElement
     * @param \SimpleXMLElement
     * @return void
     */
    protected function _appendNode(\SimpleXMLElement $xml, \SimpleXMLElement $node)
    {
        $dom = dom_import_simplexml($xml);
        $dom2 = dom_import_simplexml($node);

        $dom->appendChild($dom->ownerDocument->importNode($dom2, true));
    }

    /**
     * Add actionType element
     *
     * @param \SimpleXMLElement
     * @param string namespace
     * @return void
     */
    protected function _addActionType(\SimpleXMLElement $xml, $namespace = null)
    {
        if (!isset($this->_data['actionType'])) {
            return;
        }

        $this->_data['actionType']->setNamespace($namespace);
        $this->_appendNode($xml, $this->_data['actionType']->getXML());
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
            $this->_appendNode($parameters, $parameter->getXML());
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
        foreach ([8, 10, 12, 15, 16, 18, 24, 32, 38, 40, 45, 48, 64, 90, 98, 240, 255] as $length) {
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
