<?php
namespace Rshop\Synchronization\Pohoda;

use Rshop\Synchronization\Pohoda\Common\AddParameterToHeaderTrait;
use Rshop\Synchronization\Pohoda\IssueSlip\Header;
use Rshop\Synchronization\Pohoda\IssueSlip\Item;
use Rshop\Synchronization\Pohoda\IssueSlip\Summary;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueSlip extends Agenda
{
    use AddParameterToHeaderTrait;

    /**
     * Root for import
     *
     * @var string
     */
    public static $importRoot = 'lst:vydejka';

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
     * @return \Rshop\Synchronization\Pohoda\IssueSlip
     */
    public function addItem($data)
    {
        if (!isset($this->_data['vydejkaDetail'])) {
            $this->_data['vydejkaDetail'] = [];
        }

        $this->_data['vydejkaDetail'][] = new Item($data, $this->_ico);

        return $this;
    }

    /**
     * Add summary
     *
     * @param array summary data
     * @return \Rshop\Synchronization\Pohoda\IssueSlip
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
        $xml = $this->_createXML()->addChild('vyd:vydejka', null, $this->_namespace('vyd'));
        $xml->addAttribute('version', '2.0');

        $this->_addElements($xml, ['header', 'vydejkaDetail', 'summary'], 'vyd');

        return $xml;
    }
}
