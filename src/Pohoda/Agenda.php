<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda;
use Riesenia\Pohoda\Common\OptionsResolver;

/**
 * Base class for Pohoda objects.
 *
 * @method setNamespace($namespace)
 * @method setNodeName($nodeName)
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
abstract class Agenda
{
    /** @var string */
    protected $_ico;

    /** @var array */
    protected $_data;

    /** @var array */
    protected $_refElements = [];

    /** @var array */
    protected $_elementsAttributesMapper = [];

    /**
     * Construct agenda using provided data.
     *
     * @param array  $data
     * @param string $ico
     * @param bool   $resolveOptions
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // set ICO
        $this->_ico = $ico;

        // resolve options
        $this->_data = $resolveOptions ? $this->_resolveOptions($data) : $data;
    }

    /**
     * Get XML.
     *
     * @return \SimpleXMLElement
     */
    abstract public function getXML(): \SimpleXMLElement;

    /**
     * Configure options for options resolver.
     *
     * @param OptionsResolver $resolver
     */
    abstract protected function _configureOptions(OptionsResolver $resolver);

    /**
     * Create XML.
     *
     * @return \SimpleXMLElement
     */
    protected function _createXML(): \SimpleXMLElement
    {
        return new \SimpleXMLElement('<?xml version="1.0" encoding="Windows-1250"?><root ' . implode(' ', array_map(function ($k, $v) {
            return 'xmlns:' . $k . '="' . $v . '"';
        }, array_keys(Pohoda::$namespaces), Pohoda::$namespaces)) . '></root>');
    }

    /**
     * Get namespace.
     *
     * @param string|null $short
     *
     * @return string|null
     */
    protected function _namespace(string $short = null): ?string
    {
        if ($short === null) {
            return null;
        }

        if (!isset(Pohoda::$namespaces[$short])) {
            throw new \OutOfRangeException('Invalid namespace.');
        }

        return Pohoda::$namespaces[$short];
    }

    /**
     * Add batch elements.
     *
     * @param \SimpleXMLElement $xml
     * @param array             $elements
     * @param string|null       $namespace
     */
    protected function _addElements(\SimpleXMLElement $xml, array $elements, string $namespace = null)
    {
        foreach ($elements as $element) {
            if (!isset($this->_data[$element])) {
                continue;
            }

            // ref element
            if (in_array($element, $this->_refElements)) {
                $this->_addRefElement($xml, ($namespace ? $namespace . ':' : '') . $element, $this->_data[$element], $namespace);
                continue;
            }

            // element attribute
            if (isset($this->_elementsAttributesMapper[$element])) {
                list($attrElement, $attrName, $attrNamespace) = $this->_elementsAttributesMapper[$element];

                // get element
                $attrElement = $namespace ? $xml->children($namespace, true)->{$attrElement} : $xml->{$attrElement};

                $attrElement->addAttribute(($attrNamespace ? $attrNamespace . ':' : '') . $attrName, htmlspecialchars($this->_data[$element]), $this->_namespace($attrNamespace));
                continue;
            }

            // Agenda object
            if ($this->_data[$element] instanceof self) {
                // set namespace
                if ($namespace && method_exists($this->_data[$element], 'setNamespace')) {
                    $this->_data[$element]->setNamespace($namespace);
                }

                // set node name
                if (method_exists($this->_data[$element], 'setNodeName')) {
                    $this->_data[$element]->setNodeName($element);
                }

                $this->_appendNode($xml, $this->_data[$element]->getXML());
                continue;
            }

            // array of Agenda objects
            if (is_array($this->_data[$element])) {
                $child = $xml->addChild(($namespace ? $namespace . ':' : '') . $element, null, $this->_namespace($namespace));

                foreach ($this->_data[$element] as $node) {
                    $this->_appendNode($child, $node->getXML());
                }

                continue;
            }

            $xml->addChild(($namespace ? $namespace . ':' : '') . $element, htmlspecialchars($this->_data[$element]), $this->_namespace($namespace));
        }
    }

    /**
     * Add ref element.
     *
     * @param \SimpleXMLElement $xml
     * @param string            $name
     * @param mixed             $value
     * @param string|null       $namespace
     *
     * @return \SimpleXMLElement
     */
    protected function _addRefElement(\SimpleXMLElement $xml, string $name, $value, string $namespace = null): \SimpleXMLElement
    {
        $node = $xml->addChild($name, null, $this->_namespace($namespace));

        if (!is_array($value)) {
            $value = ['ids' => $value];
        }

        foreach ($value as $key => $value) {
            $node->addChild('typ:' . $key, htmlspecialchars((string) $value), $this->_namespace('typ'));
        }

        return $node;
    }

    /**
     * Append SimpleXMLElement to another SimpleXMLElement.
     *
     * @param \SimpleXMLElement $xml
     * @param \SimpleXMLElement $node
     */
    protected function _appendNode(\SimpleXMLElement $xml, \SimpleXMLElement $node)
    {
        $dom = dom_import_simplexml($xml);
        $dom2 = dom_import_simplexml($node);

        if ($dom === false || $dom2 === false) {
            throw new \InvalidArgumentException('Invalid XML.');
        }

        $dom->appendChild($dom->ownerDocument->importNode($dom2, true));
    }

    /**
     * Resolve options.
     *
     * @param array $data
     *
     * @return array
     */
    protected function _resolveOptions(array $data): array
    {
        $resolver = new OptionsResolver();

        $this->_configureOptions($resolver);

        return $resolver->resolve($data);
    }
}
