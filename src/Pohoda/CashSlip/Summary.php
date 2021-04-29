<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\CashSlip;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Type\CurrencyHome;

class Summary extends Agenda
{
    /** @var string[] */
    protected $_elements = ['roundingDocument', 'roundingVAT', 'calculateVAT', 'homeCurrency'];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process home currency
        if (isset($data['homeCurrency'])) {
            $data['homeCurrency'] = new CurrencyHome($data['homeCurrency'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('pro:prodejkaSummary', '', $this->_namespace('pro'));

        $this->_addElements($xml, $this->_elements, 'pro');

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
        $resolver->setAllowedValues('roundingDocument', ['none', 'math2one', 'math2half', 'math2tenth', 'up2one', 'up2half', 'up2tenth', 'down2one', 'down2half', 'down2tenth']);
        $resolver->setAllowedValues('roundingVAT', ['none', 'noneEveryRate', 'up2tenthEveryItem', 'up2tenthEveryRate', 'math2tenthEveryItem', 'math2tenthEveryRate', 'math2halfEveryItem', 'math2halfEveryRate', 'math2intEveryItem', 'math2intEveryRate']);
        $resolver->setNormalizer('calculateVAT', $resolver->getNormalizer('bool'));
    }
}
