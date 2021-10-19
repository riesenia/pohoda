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

class OrderSpec extends ObjectBehavior
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
        $this->shouldHaveType('Riesenia\Pohoda\Order');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<ord:order version="2.0"><ord:orderHeader>' . $this->_defaultHeader() . '</ord:orderHeader></ord:order>');
    }

    public function it_can_set_action_type()
    {
        $this->addActionType('update', [
            'numberOrder' => '222'
        ]);

        $this->getXML()->asXML()->shouldReturn('<ord:order version="2.0"><ord:actionType><ord:update><ftr:filter><ftr:numberOrder>222</ftr:numberOrder></ftr:filter></ord:update></ord:actionType><ord:orderHeader>' . $this->_defaultHeader() . '</ord:orderHeader></ord:order>');
    }

    public function it_can_add_items()
    {
        $this->addItem([
            'text' => 'NAME 1',
            'quantity' => 1,
            'delivered' => 0,
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
                ],
                'insertAttachStock' => 0,
                'applyUserSettingsFilterOnTheStore' => false
            ]
        ]);

        $this->getXML()->asXML()->shouldReturn('<ord:order version="2.0"><ord:orderHeader>' . $this->_defaultHeader() . '</ord:orderHeader><ord:orderDetail><ord:orderItem><ord:text>NAME 1</ord:text><ord:quantity>1</ord:quantity><ord:delivered>0</ord:delivered><ord:rateVAT>high</ord:rateVAT><ord:homeCurrency><typ:unitPrice>200</typ:unitPrice></ord:homeCurrency></ord:orderItem><ord:orderItem><ord:quantity>1</ord:quantity><ord:payVAT>true</ord:payVAT><ord:rateVAT>high</ord:rateVAT><ord:homeCurrency><typ:unitPrice>198</typ:unitPrice></ord:homeCurrency><ord:stockItem><typ:stockItem insertAttachStock="false" applyUserSettingsFilterOnTheStore="false"><typ:ids>STM</typ:ids></typ:stockItem></ord:stockItem></ord:orderItem></ord:orderDetail></ord:order>');
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

        $this->getXML()->asXML()->shouldReturn('<ord:order version="2.0"><ord:orderHeader>' . $this->_defaultHeader() . '</ord:orderHeader><ord:orderSummary><ord:roundingDocument>math2one</ord:roundingDocument><ord:foreignCurrency><typ:currency><typ:ids>EUR</typ:ids></typ:currency><typ:rate>20.232</typ:rate><typ:amount>1</typ:amount><typ:priceSum>580</typ:priceSum></ord:foreignCurrency></ord:orderSummary></ord:order>');
    }

    public function it_can_set_parameters()
    {
        $this->addParameter('IsOn', 'boolean', 'true');
        $this->addParameter('VPrNum', 'number', 10.43);
        $this->addParameter('RefVPrCountry', 'list', 'SK', 'Country');
        $this->addParameter('CustomList', 'list', ['id' => 5], ['id' => 6]);

        $this->getXML()->asXML()->shouldReturn('<ord:order version="2.0"><ord:orderHeader>' . $this->_defaultHeader() . '<ord:parameters><typ:parameter><typ:name>VPrIsOn</typ:name><typ:booleanValue>true</typ:booleanValue></typ:parameter><typ:parameter><typ:name>VPrNum</typ:name><typ:numberValue>10.43</typ:numberValue></typ:parameter><typ:parameter><typ:name>RefVPrCountry</typ:name><typ:listValueRef><typ:ids>SK</typ:ids></typ:listValueRef><typ:list><typ:ids>Country</typ:ids></typ:list></typ:parameter><typ:parameter><typ:name>RefVPrCustomList</typ:name><typ:listValueRef><typ:id>5</typ:id></typ:listValueRef><typ:list><typ:id>6</typ:id></typ:list></typ:parameter></ord:parameters></ord:orderHeader></ord:order>');
    }

    public function it_can_delete_order()
    {
        $this->beConstructedWith([], '123');

        $this->addActionType('delete', [
            'number' => '222'
        ], 'prijate_objednavky');

        $this->getXML()->asXML()->shouldReturn('<ord:order version="2.0"><ord:actionType><ord:delete><ftr:filter agenda="prijate_objednavky"><ftr:number>222</ftr:number></ftr:filter></ord:delete></ord:actionType></ord:order>');
    }

    protected function _defaultHeader()
    {
        return '<ord:orderType>receivedOrder</ord:orderType><ord:date>2015-01-10</ord:date><ord:partnerIdentity><typ:id>25</typ:id></ord:partnerIdentity><ord:myIdentity><typ:address><typ:name>NAME</typ:name><typ:ico>123</typ:ico></typ:address></ord:myIdentity><ord:intNote>Note</ord:intNote>';
    }
}
