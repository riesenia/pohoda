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

class IssueSlipSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'date' => '2015-01-10',
            'dateOrder' => '2015-01-04',
            'text' => 'Vyd',
            'partnerIdentity' => [
                'address' => [
                    'name' => 'NAME',
                    'ico' => '123'
                ]
            ],
            'intNote' => 'Note'
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Riesenia\Pohoda\IssueSlip');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<vyd:vydejka version="2.0"><vyd:vydejkaHeader>' . $this->_defaultHeader() . '</vyd:vydejkaHeader></vyd:vydejka>');
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

        $this->addItem([
            'quantity' => 1,
            'payVAT' => 1,
            'rateVAT' => 'high',
            'homeCurrency' => [
                'unitPrice' => 198
            ],
            'stockItem' => [
                'stockItem' => [
                    'ids' => 'STM'
                ]
            ]
        ]);

        $this->getXML()->asXML()->shouldReturn('<vyd:vydejka version="2.0"><vyd:vydejkaHeader>' . $this->_defaultHeader() . '</vyd:vydejkaHeader><vyd:vydejkaDetail><vyd:vydejkaItem><vyd:text>NAME 1</vyd:text><vyd:quantity>1</vyd:quantity><vyd:rateVAT>high</vyd:rateVAT><vyd:homeCurrency><typ:unitPrice>200</typ:unitPrice></vyd:homeCurrency></vyd:vydejkaItem><vyd:vydejkaItem><vyd:quantity>1</vyd:quantity><vyd:payVAT>true</vyd:payVAT><vyd:rateVAT>high</vyd:rateVAT><vyd:homeCurrency><typ:unitPrice>198</typ:unitPrice></vyd:homeCurrency><vyd:stockItem><typ:stockItem><typ:ids>STM</typ:ids></typ:stockItem></vyd:stockItem></vyd:vydejkaItem></vyd:vydejkaDetail></vyd:vydejka>');
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

        $this->getXML()->asXML()->shouldReturn('<vyd:vydejka version="2.0"><vyd:vydejkaHeader>' . $this->_defaultHeader() . '</vyd:vydejkaHeader><vyd:vydejkaSummary><vyd:roundingDocument>math2one</vyd:roundingDocument><vyd:foreignCurrency><typ:currency><typ:ids>EUR</typ:ids></typ:currency><typ:rate>20.232</typ:rate><typ:amount>1</typ:amount><typ:priceSum>580</typ:priceSum></vyd:foreignCurrency></vyd:vydejkaSummary></vyd:vydejka>');
    }

    public function it_can_set_parameters()
    {
        $this->addParameter('IsOn', 'boolean', 'true');
        $this->addParameter('VPrNum', 'number', 10.43);
        $this->addParameter('RefVPrCountry', 'list', 'SK', 'Country');
        $this->addParameter('CustomList', 'list', ['id' => 5], ['id' => 6]);

        $this->getXML()->asXML()->shouldReturn('<vyd:vydejka version="2.0"><vyd:vydejkaHeader>' . $this->_defaultHeader() . '<vyd:parameters><typ:parameter><typ:name>VPrIsOn</typ:name><typ:booleanValue>true</typ:booleanValue></typ:parameter><typ:parameter><typ:name>VPrNum</typ:name><typ:numberValue>10.43</typ:numberValue></typ:parameter><typ:parameter><typ:name>RefVPrCountry</typ:name><typ:listValueRef><typ:ids>SK</typ:ids></typ:listValueRef><typ:list><typ:ids>Country</typ:ids></typ:list></typ:parameter><typ:parameter><typ:name>RefVPrCustomList</typ:name><typ:listValueRef><typ:id>5</typ:id></typ:listValueRef><typ:list><typ:id>6</typ:id></typ:list></typ:parameter></vyd:parameters></vyd:vydejkaHeader></vyd:vydejka>');
    }

    public function it_can_link_to_order()
    {
        $this->addLink([
            'sourceAgenda' => 'receivedOrder',
            'sourceDocument' => [
                'number' => '142100003'
            ]
        ]);

        $this->getXML()->asXML()->shouldReturn('<vyd:vydejka version="2.0"><vyd:links><typ:link><typ:sourceAgenda>receivedOrder</typ:sourceAgenda><typ:sourceDocument><typ:number>142100003</typ:number></typ:sourceDocument></typ:link></vyd:links><vyd:vydejkaHeader>' . $this->_defaultHeader() . '</vyd:vydejkaHeader></vyd:vydejka>');
    }

    protected function _defaultHeader()
    {
        return '<vyd:date>2015-01-10</vyd:date><vyd:dateOrder>2015-01-04</vyd:dateOrder><vyd:text>Vyd</vyd:text><vyd:partnerIdentity><typ:address><typ:name>NAME</typ:name><typ:ico>123</typ:ico></typ:address></vyd:partnerIdentity><vyd:intNote>Note</vyd:intNote>';
    }
}
