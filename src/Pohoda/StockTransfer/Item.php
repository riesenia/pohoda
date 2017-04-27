<?php
namespace Rshop\Synchronization\Pohoda\StockTransfer;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Type\StockItem;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Item extends Agenda
{
    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['quantity', 'stockItem', 'note'];

    /**
     * Configure options for options resolver
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('quantity', $resolver->floatNormalizer);
        $resolver->setNormalizer('note', $resolver->string90Normalizer);
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
        // process stock item
        if (isset($data['stockItem'])) {
            $data['stockItem'] = new StockItem($data['stockItem'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('pre:prevodkaItem', null, $this->_namespace('pre'));

        $this->_addElements($xml, $this->_elements, 'pre');

        return $xml;
    }
}
