<?php
namespace Rshop\Synchronization\Pohoda;

use Rshop\Synchronization\Pohoda\Common\AddParameterToHeaderTrait;
use Rshop\Synchronization\Pohoda\StockTransfer\Header;
use Rshop\Synchronization\Pohoda\StockTransfer\Item;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockTransfer extends Agenda
{
    use AddParameterToHeaderTrait;

    /**
     * Root for import
     *
     * @var string
     */
    public static $importRoot = 'lst:prevodka';

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['header']);
    }

    /**
     * Construct agenda using provided data
     *
     * @param array data
     * @param string ICO
     * @param bool if options resolver should be used
     */
    public function __construct($data, $ico, $resolveOptions = true)
    {
        // pass to header
        $data = ['header' => new Header($data, $ico, $resolveOptions)];

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Add item
     *
     * @param array item data
     * @return \Rshop\Synchronization\Pohoda\StockTransfer
     */
    public function addItem($data)
    {
        if (!isset($this->_data['prevodkaDetail'])) {
            $this->_data['prevodkaDetail'] = [];
        }

        $this->_data['prevodkaDetail'][] = new Item($data, $this->_ico);

        return $this;
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('pre:prevodka', null, $this->_namespace('pre'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['header', 'prevodkaDetail'], 'pre');

        return $xml;
    }
}
