<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace spec\Riesenia\Pohoda;

use PhpSpec\ObjectBehavior;

class BankSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'bankType' => 'receipt',
            'account' => 'KB',
            'statementNumber' => [
                'statementNumber' => '004',
                'numberMovement' => '0002'
            ],
            'symVar' => '456',
            'symConst' => '555',
            'symSpec' => '666',
            'dateStatement' => '2021-12-20',
            'datePayment' => '2021-11-22',
            'text' => 'STORMWARE s.r.o.',
            'paymentAccount' => [
                'accountNo' => '4660550217',
                'bankCode' => '5500'
            ]
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Riesenia\Pohoda\Bank');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<bnk:bank version="2.0"><bnk:bankHeader>' . $this->_defaultHeader() . '</bnk:bankHeader></bnk:bank>');
    }

    public function it_can_set_summary()
    {
        $this->addSummary([
            'homeCurrency' => [
                'priceNone' => 500
            ]
        ]);

        $this->getXML()->asXML()->shouldReturn('<bnk:bank version="2.0"><bnk:bankHeader>' . $this->_defaultHeader() . '</bnk:bankHeader><bnk:bankSummary><bnk:homeCurrency><typ:priceNone>500</typ:priceNone></bnk:homeCurrency></bnk:bankSummary></bnk:bank>');
    }

    public function it_can_set_parameters()
    {
        $this->addParameter('IsOn', 'boolean', 'true');
        $this->addParameter('VPrNum', 'number', 10.43);
        $this->addParameter('RefVPrCountry', 'list', 'SK', 'Country');
        $this->addParameter('CustomList', 'list', ['id' => 5], ['id' => 6]);

        $this->getXML()->asXML()->shouldReturn('<bnk:bank version="2.0"><bnk:bankHeader>' . $this->_defaultHeader() . '<bnk:parameters><typ:parameter><typ:name>VPrIsOn</typ:name><typ:booleanValue>true</typ:booleanValue></typ:parameter><typ:parameter><typ:name>VPrNum</typ:name><typ:numberValue>10.43</typ:numberValue></typ:parameter><typ:parameter><typ:name>RefVPrCountry</typ:name><typ:listValueRef><typ:ids>SK</typ:ids></typ:listValueRef><typ:list><typ:ids>Country</typ:ids></typ:list></typ:parameter><typ:parameter><typ:name>RefVPrCustomList</typ:name><typ:listValueRef><typ:id>5</typ:id></typ:listValueRef><typ:list><typ:id>6</typ:id></typ:list></typ:parameter></bnk:parameters></bnk:bankHeader></bnk:bank>');
    }

    protected function _defaultHeader()
    {
        return '<bnk:bankType>receipt</bnk:bankType><bnk:account><typ:ids>KB</typ:ids></bnk:account><bnk:statementNumber><bnk:statementNumber>004</bnk:statementNumber><bnk:numberMovement>0002</bnk:numberMovement></bnk:statementNumber><bnk:symVar>456</bnk:symVar><bnk:dateStatement>2021-12-20</bnk:dateStatement><bnk:datePayment>2021-11-22</bnk:datePayment><bnk:text>STORMWARE s.r.o.</bnk:text><bnk:paymentAccount><typ:accountNo>4660550217</typ:accountNo><typ:bankCode>5500</typ:bankCode></bnk:paymentAccount><bnk:symConst>555</bnk:symConst><bnk:symSpec>666</bnk:symSpec>';
    }
}
