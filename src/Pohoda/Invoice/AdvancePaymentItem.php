<?php
namespace Rshop\Synchronization\Pohoda\Invoice;

use Rshop\Synchronization\Pohoda\Agenda;
use Rshop\Synchronization\Pohoda\Type\CurrencyItem;
use Rshop\Synchronization\Pohoda\Type\StockItem;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvancePaymentItem extends Item
{
    /**
     * Ref elements
     *
     * @var array
     */
    protected $_refElements = ['sourceDocument', 'accounting', 'classificationVAT', 'classificationKVDPH', 'centre', 'activity', 'contract'];

    /**
     * All elements
     *
     * @var array
     */
    protected $_elements = ['sourceDocument', 'quantity', 'payVAT', 'rateVAT', 'discountPercentage', 'homeCurrency', 'foreignCurrency', 'note', 'accounting', 'classificationVAT', 'classificationKVDPH', 'centre', 'activity', 'contract'];

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
        $resolver->setNormalizer('payVAT', $resolver->boolNormalizer);
        $resolver->setAllowedValues('rateVAT', ['none', 'low', 'high']);
        $resolver->setNormalizer('discountPercentage', $resolver->floatNormalizer);
        $resolver->setNormalizer('note', $resolver->string90Normalizer);
    }

    /**
     * Get XML
     *
     * @return \SimpleXMLElement
     */
    public function getXML()
    {
        $xml = $this->_createXML()->addChild('inv:invoiceAdvancePaymentItem', null, $this->_namespace('inv'));

        $this->_addElements($xml, array_merge($this->_elements, ['parameters']), 'inv');

        return $xml;
    }
}
