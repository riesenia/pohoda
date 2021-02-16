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

class IntDocSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'partnerIdentity' => [
                'id' => 25
            ],
            'myIdentity' => [
                'address' => [
                    'name' => 'NAME',
                    'ico' => '123'
                ]
            ],
            'date' => '2015-01-10',
            'intNote' => 'Note'
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Riesenia\Pohoda\IntDoc');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<int:intDoc version="2.0"><int:intDocHeader>' . $this->_defaultHeader() . '</int:intDocHeader></int:intDoc>');
    }

    public function it_can_add_items()
    {
        $this->addItem([
            'text' => 'NAME 1',
            'quantity' => 1,
            'rateVAT' => 'high',
            'homeCurrency' => [
                'unitPrice' => 200
            ]
        ]);

        $this->getXML()->asXML()->shouldReturn('<int:intDoc version="2.0"><int:intDocHeader>' . $this->_defaultHeader() . '</int:intDocHeader><int:intDocDetail><int:intDocItem><int:text>NAME 1</int:text><int:quantity>1</int:quantity><int:rateVAT>high</int:rateVAT><int:homeCurrency><typ:unitPrice>200</typ:unitPrice></int:homeCurrency></int:intDocItem></int:intDocDetail></int:intDoc>');
    }

    public function it_can_set_summary()
    {
        $this->addSummary([
            'roundingDocument' => 'math2one',
            'foreignCurrency' => [
                'currency' => 'EUR',
                'rate' => '20.232',
                'amount' => 1,
                'priceSum' => 580
            ]
        ]);

        $this->getXML()->asXML()->shouldReturn('<int:intDoc version="2.0"><int:intDocHeader>' . $this->_defaultHeader() . '</int:intDocHeader><int:intDocSummary><int:roundingDocument>math2one</int:roundingDocument><int:foreignCurrency><typ:currency><typ:ids>EUR</typ:ids></typ:currency><typ:rate>20.232</typ:rate><typ:amount>1</typ:amount><typ:priceSum>580</typ:priceSum></int:foreignCurrency></int:intDocSummary></int:intDoc>');
    }

    public function it_can_set_parameters()
    {
        $this->addParameter('IsOn', 'boolean', 'true');
        $this->addParameter('VPrNum', 'number', 10.43);
        $this->addParameter('RefVPrCountry', 'list', 'SK', 'Country');
        $this->addParameter('CustomList', 'list', ['id' => 5], ['id' => 6]);

        $this->getXML()->asXML()->shouldReturn('<int:intDoc version="2.0"><int:intDocHeader>' . $this->_defaultHeader() . '<int:parameters><typ:parameter><typ:name>VPrIsOn</typ:name><typ:booleanValue>true</typ:booleanValue></typ:parameter><typ:parameter><typ:name>VPrNum</typ:name><typ:numberValue>10.43</typ:numberValue></typ:parameter><typ:parameter><typ:name>RefVPrCountry</typ:name><typ:listValueRef><typ:ids>SK</typ:ids></typ:listValueRef><typ:list><typ:ids>Country</typ:ids></typ:list></typ:parameter><typ:parameter><typ:name>RefVPrCustomList</typ:name><typ:listValueRef><typ:id>5</typ:id></typ:listValueRef><typ:list><typ:id>6</typ:id></typ:list></typ:parameter></int:parameters></int:intDocHeader></int:intDoc>');
    }

    protected function _defaultHeader()
    {
        return '<int:date>2015-01-10</int:date><int:partnerIdentity><typ:id>25</typ:id></int:partnerIdentity><int:myIdentity><typ:address><typ:name>NAME</typ:name><typ:ico>123</typ:ico></typ:address></int:myIdentity><int:intNote>Note</int:intNote>';
    }
}
