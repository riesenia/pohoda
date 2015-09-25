<?php
namespace Rshop\Synchronization\Pohoda;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Storage extends Agenda
{
    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['code', 'name']);

        // validate / format options
        $resolver->setRequired('code');
    }

    /**
     * Add substorage
     *
     * @param Storage substorage
     * @return void
     */
    public function addSubstorage(Storage $storage)
    {
        if (!isset($this->_data['subStorages'])) {
            $this->_data['subStorages'] = [];
        }

        $this->_data['subStorages'][] = $storage;
    }

    /**
     * Get XML
     *
     * @return string
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('str:storage', null, $this->_namespace('str'));
        $xml->addAttribute('version', '2.0');

        $this->storageXML($xml);

        return $xml->asXML();
    }

    /**
     * Attach storage to XML element
     *
     * @param \SimpleXMLElement
     * @return void
     */
    public function storageXML(\SimpleXMLElement $xml)
    {
        $storage = $xml->addChild('str:itemStorage', null, $this->_namespace('str'));
        $storage->addAttribute('code', $this->_data['code']);

        if (isset($this->_data['name'])) {
            $storage->addAttribute('name', $this->_data['name']);
        }

        if (isset($this->_data['subStorages'])) {
            $subStorages = $storage->addChild('str:subStorages', null, $this->_namespace('str'));

            foreach ($this->_data['subStorages'] as $subStorage) {
                $subStorage->storageXML($subStorages);
            }
        }
    }
}
