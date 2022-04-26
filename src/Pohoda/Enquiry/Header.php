<?php
/**
 * @author Pavel Fiala fiala.pvl@gmail.com
 * @link https://github.com/daddyy/pohoda
 */


declare(strict_types=1);

namespace z41f1t\Extension\Pohoda\Enquiry;

use Riesenia\Pohoda\Common\AddParameterTrait;
use Riesenia\Pohoda\Common\OptionsResolver;

class Header extends \Riesenia\Pohoda\Offer\Header
{
    use AddParameterTrait;

    /** @var string[] */
    protected $_refElements = ['number', 'priceLevel', 'centre', 'activity', 'contract', 'regVATinEU', 'MOSS', 'evidentiaryResourcesMOSS'];

    /** @var string[] */
    protected $_elements = ['enquiryType', 'number', 'date', 'validTill', 'text', 'partnerIdentity', 'myIdentity', 'priceLevel', 'centre', 'activity', 'contract', 'regVATinEU', 'MOSS', 'evidentiaryResourcesMOSS', 'accountingPeriodMOSS', 'isExecuted', 'details', 'note', 'intNote', 'markRecord'];

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('enq:enquiryHeader', '', $this->_namespace('enq'));

        $this->_addElements($xml, \array_merge($this->_elements, ['parameters']), 'enq');
        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setDefault('enquiryType', 'receivedEnquiry');
        $resolver->setAllowedValues('enquiryType', ['receivedEnquiry', 'issuedEnquiry']);
        $resolver->setNormalizer('date', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('validTill', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('text', $resolver->getNormalizer('string240'));
        $resolver->setNormalizer('isExecuted', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('markRecord', $resolver->getNormalizer('bool'));
    }
}
