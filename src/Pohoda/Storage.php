<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda\Common\OptionsResolver;

class Storage extends Agenda
{
    /** @var string */
    public static $importRoot = 'lst:itemStorage';

    /**
     * Add substorage.
     *
     * @param self $storage
     *
     * @return $this
     */
    public function addSubstorage(self $storage): self
    {
        if (!isset($this->_data['subStorages'])) {
            $this->_data['subStorages'] = [];
        }

        $this->_data['subStorages'][] = $storage;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('str:storage', '', $this->_namespace('str'));
        $xml->addAttribute('version', '2.0');

        $this->storageXML($xml);

        return $xml;
    }

    /**
     * Attach storage to XML element.
     *
     * @param \SimpleXMLElement $xml
     *
     * @return void
     */
    public function storageXML(\SimpleXMLElement $xml)
    {
        $storage = $xml->addChild('str:itemStorage', '', $this->_namespace('str'));
        // TODO: We usually sanitize attributes. Should we do the same here?
        $storage->addAttribute('code', $this->_data['code']);

        if (isset($this->_data['name'])) {
            $storage->addAttribute('name', $this->_data['name']);
        }

        if (isset($this->_data['subStorages'])) {
            $subStorages = $storage->addChild('str:subStorages', '', $this->_namespace('str'));

            foreach ($this->_data['subStorages'] as $subStorage) {
                $subStorage->storageXML($subStorages);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['code', 'name']);

        // validate / format options
        $resolver->setRequired('code');
    }
}
