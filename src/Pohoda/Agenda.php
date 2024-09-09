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
use Riesenia\Pohoda\ValueTransformer\EncodingTransformer;
use Riesenia\Pohoda\ValueTransformer\ValueTransformer;

/**
 * Base class for Pohoda objects.
 *
 * @method setNamespace($namespace)
 * @method setNodeName($nodeName)
 */
abstract class Agenda
{
    /** @var bool */
    public static $importRecursive = false;

    /** @var string */
    protected $_ico;

    /** @var array<string,mixed> */
    protected $_data;

    /** @var string[] */
    protected $_refElements = [];

    /** @var array<string,array{string,string,string|null}> */
    protected $_elementsAttributesMapper = [];

    /** @var OptionsResolver[] */
    private static $_resolvers = [];

    /**
     * Construct agenda using provided data.
     *
     * @param array<string,mixed> $data
     * @param string              $ico
     * @param bool                $resolveOptions
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
     *
     * @return void
     */
    abstract protected function _configureOptions(OptionsResolver $resolver);

    /**
     * Create XML.
     *
     * @return \SimpleXMLElement
     */
    protected function _createXML(): \SimpleXMLElement
    {
        return new \SimpleXMLElement('<?xml version="1.0" encoding="' . Pohoda::$encoding . '"?><root ' . \implode(' ', \array_map(function ($k, $v) {
            return 'xmlns:' . $k . '="' . $v . '"';
        }, \array_keys(Pohoda::$namespaces), Pohoda::$namespaces)) . '></root>');
    }

    /**
     * Get namespace.
     *
     * @param string $short
     *
     * @return string
     */
    protected function _namespace(string $short): string
    {
        if (!isset(Pohoda::$namespaces[$short])) {
            throw new \OutOfRangeException('Invalid namespace.');
        }

        return Pohoda::$namespaces[$short];
    }

    /**
     * Add batch elements.
     *
     * @param \SimpleXMLElement $xml
     * @param string[]          $elements
     * @param string|null       $namespace
     *
     * @return void
     */
    protected function _addElements(\SimpleXMLElement $xml, array $elements, string $namespace = null)
    {
        foreach ($elements as $element) {
            if (!isset($this->_data[$element])) {
                continue;
            }

            // ref element
            if (\in_array($element, $this->_refElements)) {
                $this->_addRefElement($xml, ($namespace ? $namespace . ':' : '') . $element, $this->_data[$element], $namespace);

                continue;
            }

            // element attribute
            if (isset($this->_elementsAttributesMapper[$element])) {
                list($attrElement, $attrName, $attrNamespace) = $this->_elementsAttributesMapper[$element];

                // get element
                $attrElement = $namespace ? $xml->children($namespace, true)->{$attrElement} : $xml->{$attrElement};

                $sanitized = $this->_sanitize($this->_data[$element]);
                $attrNamespace ? $attrElement->addAttribute($attrNamespace . ':' . $attrName, $sanitized, $this->_namespace($attrNamespace)) : $attrElement->addAttribute($attrName, $sanitized);

                continue;
            }

            // Agenda object
            if ($this->_data[$element] instanceof self) {
                // set namespace
                if ($namespace && \method_exists($this->_data[$element], 'setNamespace')) {
                    $this->_data[$element]->setNamespace($namespace);
                }

                // set node name
                if (\method_exists($this->_data[$element], 'setNodeName')) {
                    $this->_data[$element]->setNodeName($element);
                }

                $this->_appendNode($xml, $this->_data[$element]->getXML());

                continue;
            }

            // array of Agenda objects
            if (\is_array($this->_data[$element])) {
                $child = $namespace ? $xml->addChild($namespace . ':' . $element, '', $this->_namespace($namespace)) : $xml->addChild($element);

                foreach ($this->_data[$element] as $node) {
                    $this->_appendNode($child, $node->getXML());
                }

                continue;
            }

            $sanitized = $this->_sanitize($this->_data[$element]);
            $namespace ? $xml->addChild($namespace . ':' . $element, $sanitized, $this->_namespace($namespace)) : $xml->addChild($element, $sanitized);
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
        $node = $namespace ? $xml->addChild($name, '', $this->_namespace($namespace)) : $xml->addChild($name);

        if (!\is_array($value)) {
            $value = ['ids' => $value];
        }

        foreach ($value as $key => $value) {
            $node->addChild('typ:' . $key, $this->_sanitize($value), $this->_namespace('typ'));
        }

        return $node;
    }

    /**
     * Sanitize value to XML.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function _sanitize($value): string
    {
        $transformers = Pohoda::$transformers;

        if (Pohoda::$sanitizeEncoding) {
            $transformers[] = new EncodingTransformer('utf-8', Pohoda::$encoding . '//translit');
            $transformers[] = new EncodingTransformer(Pohoda::$encoding, 'utf-8');
        }

        $value = \array_reduce($transformers, function (string $value, ValueTransformer $transformer): string {
            return $transformer->transform($value);
        }, (string) $value);

        return \htmlspecialchars($value);
    }

    /**
     * Append SimpleXMLElement to another SimpleXMLElement.
     *
     * @param \SimpleXMLElement $xml
     * @param \SimpleXMLElement $node
     *
     * @return void
     */
    protected function _appendNode(\SimpleXMLElement $xml, \SimpleXMLElement $node)
    {
        $dom = \dom_import_simplexml($xml);
        $dom2 = \dom_import_simplexml($node);

        if (!$dom->ownerDocument) {
            throw new \InvalidArgumentException('Invalid XML.');
        }

        $dom->appendChild($dom->ownerDocument->importNode($dom2, true));
    }

    /**
     * Resolve options.
     *
     * @param array<string,mixed> $data
     *
     * @return array<string,mixed>
     */
    protected function _resolveOptions(array $data): array
    {
        $class = \get_class($this);

        if (!isset(self::$_resolvers[$class])) {
            self::$_resolvers[$class] = new OptionsResolver();
            $this->_configureOptions(self::$_resolvers[$class]);
        }

        return self::$_resolvers[$class]->resolve($data);
    }
}
