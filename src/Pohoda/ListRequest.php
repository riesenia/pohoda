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
use Riesenia\Pohoda\ListRequest\Filter;
use Riesenia\Pohoda\ListRequest\UserFilterName;
use Symfony\Component\OptionsResolver\Options;

class ListRequest extends Agenda
{
    /**
     * Add filter.
     *
     * @param array $data
     *
     * @return $this
     */
    public function addFilter(array $data): self
    {
        $this->_data['filter'] = new Filter($data, $this->_ico);

        return $this;
    }

    /**
     * Add user filter name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function addUserFilterName(string $name): self
    {
        $this->_data['userFilterName'] = new UserFilterName(['userFilterName' => $name], $this->_ico);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild($this->_data['namespace'] . ':list' . $this->_data['type'] . 'Request', null, $this->_namespace($this->_data['namespace']));
        $xml->addAttribute('version', '2.0');
        $xml->addAttribute(\lcfirst($this->_data['type']) . 'Version', '2.0');

        if (isset($this->_data[\lcfirst($this->_data['type']) . 'Type'])) {
            $xml->addAttribute(\lcfirst($this->_data['type']) . 'Type', $this->_data[\lcfirst($this->_data['type']) . 'Type']);
        }

        $request = $xml->addChild($this->_data['namespace'] . ':request' . $this->_data['type']);
        $this->_addElements($request, ['filter', 'userFilterName'], 'ftr');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['type', 'namespace', 'orderType', 'invoiceType']);

        // validate / format options
        $resolver->setRequired('type');
        $resolver->setDefault('namespace', function (Options $options) {
            if ($options['type'] == 'Stock') {
                return 'lStk';
            }

            if ($options['type'] == 'AddressBook') {
                return 'lAdb';
            }

            return 'lst';
        });
        $resolver->setAllowedValues('orderType', [null, 'receivedOrder', 'issuedOrder']);
        $resolver->setDefault('orderType', function (Options $options) {
            if ($options['type'] == 'Order') {
                return 'receivedOrder';
            }

            return null;
        });
        $resolver->setAllowedValues('invoiceType', [null, 'issuedInvoice', 'issuedCreditNotice', 'issuedDebitNote', 'issuedAdvanceInvoice', 'receivable', 'issuedProformaInvoice', 'penalty', 'issuedCorrectiveTax', 'receivedInvoice', 'receivedCreditNotice', 'receivedDebitNote', 'receivedAdvanceInvoice', 'commitment', 'receivedProformaInvoice', 'receivedCorrectiveTax']);
        $resolver->setDefault('invoiceType', function (Options $options) {
            if ($options['type'] == 'Invoice') {
                return 'issuedInvoice';
            }

            return null;
        });
    }
}
