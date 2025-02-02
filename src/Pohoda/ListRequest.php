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
use Riesenia\Pohoda\Filter\QueryFilter;
use Riesenia\Pohoda\ListRequest\Filter;
use Riesenia\Pohoda\ListRequest\RestrictionData;
use Riesenia\Pohoda\ListRequest\UserFilterName;
use Symfony\Component\OptionsResolver\Options;

class ListRequest extends Agenda
{
    /**
     * Add filter.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addFilter(array $data): self
    {
        $this->_data['filter'] = new Filter($data, $this->_ico);

        return $this;
    }

    /**
     * Add query filter (SQL).
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addQueryFilter(array $data): self
    {
        $this->_data['queryFilter'] = new QueryFilter($data, $this->_ico);

        return $this;
    }

    /**
     * Add restriction data.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addRestrictionData(array $data): self
    {
        $this->_data['restrictionData'] = new RestrictionData($data, $this->_ico);

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
        // UserList is custom
        if ($this->_data['type'] == 'UserList') {
            $xml = $this->_createXML()->addChild($this->_data['namespace'] . ':listUserCodeRequest', '', $this->_namespace($this->_data['namespace']));
            $xml->addAttribute('version', '1.1');
            $xml->addAttribute('listVersion', '1.1');
        } else {
            $xml = $this->_createXML()->addChild($this->_data['namespace'] . ':list' . $this->_data['type'] . 'Request', '', $this->_namespace($this->_data['namespace']));
            $xml->addAttribute('version', '2.0');

            // IntParam doesn't have the version attribute
            if ($this->_data['type'] != 'IntParam') {
                $xml->addAttribute($this->_getLcFirstType() . 'Version', '2.0');
            }

            if (isset($this->_data[$this->_getLcFirstType() . 'Type'])) {
                $xml->addAttribute($this->_getLcFirstType() . 'Type', $this->_data[$this->_getLcFirstType() . 'Type']);
            }

            $request = $xml->addChild($this->_data['namespace'] . ':request' . $this->_data['type']);

            if (isset($this->_data['restrictionData'])) {
                $this->_addElements($xml, ['restrictionData'], 'lst');
            }

            $this->_addElements($request, ['filter', 'queryFilter', 'userFilterName'], 'ftr');
        }

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
        $resolver->setNormalizer('type', function ($options, $value) {
            // Addressbook is custom
            if ($value == 'Addressbook') {
                return 'AddressBook';
            }

            // IssueSlip is custom
            if ($value == 'IssueSlip') {
                return 'Vydejka';
            }

            // CashSlip is custom
            if ($value == 'CashSlip') {
                return 'Prodejka';
            }

            return $value;
        });
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

    /**
     * Get LC first type name.
     *
     * @return string
     */
    protected function _getLcFirstType(): string
    {
        // ActionPrice is custom
        if ($this->_data['type'] == 'ActionPrice') {
            return 'actionPrices';
        }

        return \lcfirst($this->_data['type']);
    }
}
