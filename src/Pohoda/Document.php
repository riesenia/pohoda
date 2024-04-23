<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda\Common\AddParameterToHeaderTrait;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Document\Part;

abstract class Document extends Agenda
{
    use AddParameterToHeaderTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // pass to header
        if ($data) {
            $data = ['header' => $this->_getDocumentPart('Header', $data, $ico, $resolveOptions)];
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Add document item.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addItem(array $data): self
    {
        $key = $this->_getDocumentName() . 'Detail';

        if (!isset($this->_data[$key])) {
            $this->_data[$key] = [];
        }

        $this->_data[$key][] = $this->_getDocumentPart('Item', $data, $this->_ico);

        return $this;
    }

    /**
     * Add document summary.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addSummary(array $data): self
    {
        $this->_data['summary'] = $this->_getDocumentPart('Summary', $data, $this->_ico);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild($this->_getDocumentNamespace() . ':' . $this->_getDocumentName(), '', $this->_namespace($this->_getDocumentNamespace()));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, $this->_getDocumentElements(), $this->_getDocumentNamespace());

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['header']);
    }

    /**
     * Document part factory.
     *
     * @param string              $name
     * @param array<string,mixed> $data
     * @param string              $ico
     * @param bool                $resolveOptions
     *
     * @return Part
     */
    protected function _getDocumentPart(string $name, array $data, string $ico, bool $resolveOptions = true): Part
    {
        $fullName = \get_class($this) . '\\' . $name;

        if (!\class_exists($fullName)) {
            throw new \DomainException('Not allowed entity: ' . $name);
        }

        $part = new $fullName($data, $ico, $resolveOptions);
        $part->setNamespace($this->_getDocumentNamespace());
        $part->setNodePrefix($this->_getDocumentName());

        return $part;
    }

    /**
     * Get defined elements.
     *
     * @return string[]
     */
    protected function _getDocumentElements(): array
    {
        return ['header', $this->_getDocumentName() . 'Detail', 'summary'];
    }

    /**
     * Get document namespace.
     *
     * @return string
     */
    abstract protected function _getDocumentNamespace(): string;

    /**
     * Get document name used in XML nodes.
     *
     * @return string
     */
    abstract protected function _getDocumentName(): string;
}
