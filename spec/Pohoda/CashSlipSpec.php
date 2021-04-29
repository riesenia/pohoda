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

class CashSlipSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'date' => '2015-01-10',
            'text' => 'Prod',
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
        $this->shouldHaveType('Riesenia\Pohoda\CashSlip');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<pro:prodejka version="2.0"><pro:prodejkaHeader>' . $this->_defaultHeader() . '</pro:prodejkaHeader></pro:prodejka>');
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

        $this->getXML()->asXML()->shouldReturn('<pro:prodejka version="2.0"><pro:prodejkaHeader>' . $this->_defaultHeader() . '</pro:prodejkaHeader><pro:prodejkaDetail><pro:prodejkaItem><pro:text>NAME 1</pro:text><pro:quantity>1</pro:quantity><pro:rateVAT>high</pro:rateVAT><pro:homeCurrency><typ:unitPrice>200</typ:unitPrice></pro:homeCurrency></pro:prodejkaItem><pro:prodejkaItem><pro:quantity>1</pro:quantity><pro:payVAT>true</pro:payVAT><pro:rateVAT>high</pro:rateVAT><pro:homeCurrency><typ:unitPrice>198</typ:unitPrice></pro:homeCurrency><pro:stockItem><typ:stockItem><typ:ids>STM</typ:ids></typ:stockItem></pro:stockItem></pro:prodejkaItem></pro:prodejkaDetail></pro:prodejka>');
    }

    public function it_can_set_summary()
    {
        $this->addSummary([
            'roundingDocument' => 'math2one',
            'homeCurrency' => [
                'priceNone' => '0.0000',
                'priceLow' => '0.0000',
                'priceLowVAT' => '0.0000',
                'priceHigh' => '156.0000',
                'priceHighVAT' => '31.2000',
                'price3' => '0.0000',
                'price3VAT' => '0.0000',
                'round' => [
                    'priceRound' => '0.0000'
                ]
            ]
        ]);

        $this->getXML()->asXML()->shouldReturn('<pro:prodejka version="2.0"><pro:prodejkaHeader>' . $this->_defaultHeader() . '</pro:prodejkaHeader><pro:prodejkaSummary><pro:roundingDocument>math2one</pro:roundingDocument><pro:homeCurrency><typ:priceNone>0</typ:priceNone><typ:price3>0</typ:price3><typ:price3VAT>0</typ:price3VAT><typ:priceLow>0</typ:priceLow><typ:priceLowVAT>0</typ:priceLowVAT><typ:priceHigh>156</typ:priceHigh><typ:priceHighVAT>31.2</typ:priceHighVAT><typ:round><typ:priceRound>0.0000</typ:priceRound></typ:round></pro:homeCurrency></pro:prodejkaSummary></pro:prodejka>');
    }

    public function it_can_set_parameters()
    {
        $this->addParameter('IsOn', 'boolean', 'true');
        $this->addParameter('VPrNum', 'number', 10.43);
        $this->addParameter('RefVPrCountry', 'list', 'SK', 'Country');
        $this->addParameter('CustomList', 'list', ['id' => 5], ['id' => 6]);

        $this->getXML()->asXML()->shouldReturn('<pro:prodejka version="2.0"><pro:prodejkaHeader>' . $this->_defaultHeader() . '<pro:parameters><typ:parameter><typ:name>VPrIsOn</typ:name><typ:booleanValue>true</typ:booleanValue></typ:parameter><typ:parameter><typ:name>VPrNum</typ:name><typ:numberValue>10.43</typ:numberValue></typ:parameter><typ:parameter><typ:name>RefVPrCountry</typ:name><typ:listValueRef><typ:ids>SK</typ:ids></typ:listValueRef><typ:list><typ:ids>Country</typ:ids></typ:list></typ:parameter><typ:parameter><typ:name>RefVPrCustomList</typ:name><typ:listValueRef><typ:id>5</typ:id></typ:listValueRef><typ:list><typ:id>6</typ:id></typ:list></typ:parameter></pro:parameters></pro:prodejkaHeader></pro:prodejka>');
    }

    protected function _defaultHeader()
    {
        return '<pro:prodejkaType>saleVoucher</pro:prodejkaType><pro:date>2015-01-10</pro:date><pro:text>Prod</pro:text><pro:partnerIdentity><typ:address><typ:name>NAME</typ:name><typ:ico>123</typ:ico></typ:address></pro:partnerIdentity><pro:intNote>Note</pro:intNote>';
    }
}
