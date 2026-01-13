<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\ListTableExport;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Filter\QueryFilter;

class RequestTableExport extends Agenda
{
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process queryFilter if present
        if (isset($data['queryFilter'])) {
            $data['queryFilter'] = new QueryFilter($data['queryFilter'], $ico, $resolveOptions);
        }

        // process columns if present
        if (isset($data['columns'])) {
            $data['columns'] = new Columns($data['columns'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('lst:requestTableExport', '', $this->_namespace('lst'));
        $xml->addAttribute('agenda', $this->_data['agenda']);

        if (isset($this->_data['table'])) {
            $xml->addAttribute('table', $this->_data['table']);
        }

        // add queryFilter
        if (isset($this->_data['queryFilter'])) {
            $this->_addElements($xml, ['queryFilter'], 'ftr');
        }

        // add columns
        $this->_addElements($xml, ['columns'], 'tex');

        return $xml;
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined(['agenda', 'table', 'queryFilter', 'columns']);

        // validate / format options
        $resolver->setRequired('agenda');
        $resolver->setRequired('columns');
        $resolver->setAllowedValues('agenda', [
            'addressBook',
            'stock',
            'issuedInvoice',
            'receivedInvoice',
            'issuedOrder',
            'receivedOrder',
            'claims',
            'service',
            'cashbox',
            'bank',
            'individualPrice',
            'offer',
            'parameter',
            'prodejka',
            'prijemka',
            'vydejka',
            'prevodka',
            'storage',
            'contract',
            'intDoc',
            'intParam',
            'numericalSeries',
            'voucher',
            'userList'
        ]);
    }
}
