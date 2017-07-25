<?php
namespace Rshop\Synchronization\Pohoda;

use Rshop\Synchronization\Pohoda\UserList\ItemUserCode;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserList extends Agenda
{
    /**
     * Root for import
     *
     * @var string
     */
    public static $importRoot = 'lst:listUserCode';

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['code', 'name', 'constants']);

        // validate / format options
        $resolver->setRequired('code');
        $resolver->setRequired('name');
        $resolver->setNormalizer('constants', $resolver->boolNormalizer);
    }

    /**
     * Add item user code
     *
     * @param array data
     * @return void
     */
    public function addItemUserCode($data)
    {
        if (!isset($this->_data['itemUserCodes'])) {
            $this->_data['itemUserCodes'] = [];
        }

        $this->_data['itemUserCodes'][] = new ItemUserCode($data, $this->_ico);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('lst:listUserCode', null, $this->_namespace('lst'));
        $xml->addAttribute('version', '1.1');
        $xml->addAttribute('code', $this->_data['code']);
        $xml->addAttribute('name', $this->_data['name']);

        if (isset($this->_data['constants']) && $this->_data['constants'] == 'true') {
            $xml->addAttribute('constants', $this->_data['constants']);
        }

        if (isset($this->_data['itemUserCodes'])) {
            foreach ($this->_data['itemUserCodes'] as $itemUserCode) {
                $this->_appendNode($xml, $itemUserCode->getXML());
            }
        }

        return $xml;
    }
}
