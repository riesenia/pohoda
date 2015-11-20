<?php
namespace Rshop\Synchronization\Pohoda;

use Rshop\Synchronization\Pohoda\Common\AddActionTypeTrait;
use Rshop\Synchronization\Pohoda\Common\AddParameterToHeaderTrait;
use Rshop\Synchronization\Pohoda\Order\Header;
use Rshop\Synchronization\Pohoda\Order\Item;
use Rshop\Synchronization\Pohoda\Order\Summary;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Order extends Agenda
{
    use AddActionTypeTrait, AddParameterToHeaderTrait;

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
     * Add order item
     *
     * @param array item data
     * @return \Rshop\Synchronization\Pohoda\Order
     */
    public function addItem($data)
    {
        if (!isset($this->_data['orderDetail'])) {
            $this->_data['orderDetail'] = [];
        }

        $this->_data['orderDetail'][] = new Item($data, $this->_ico);

        return $this;
    }

    /**
     * Add order summary
     *
     * @param array summary data
     * @return \Rshop\Synchronization\Pohoda\Order
     */
    public function addSummary($data)
    {
        $this->_data['summary'] = new Summary($data, $this->_ico);

        return $this;
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('ord:order', null, $this->_namespace('ord'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['actionType', 'header', 'orderDetail', 'summary'], 'ord');

        return $xml;
    }
}
