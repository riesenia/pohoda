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

class OfferSpec extends ObjectBehavior
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
        $this->shouldHaveType('Riesenia\Pohoda\Offer');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<ofr:offer version="2.0"><ofr:offerHeader>' . $this->_defaultHeader() . '</ofr:offerHeader></ofr:offer>');
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

        $this->getXML()->asXML()->shouldReturn('<ofr:offer version="2.0"><ofr:offerHeader>' . $this->_defaultHeader() . '</ofr:offerHeader><ofr:offerDetail><ofr:offerItem><ofr:text>NAME 1</ofr:text><ofr:quantity>1</ofr:quantity><ofr:rateVAT>high</ofr:rateVAT><ofr:homeCurrency><typ:unitPrice>200</typ:unitPrice></ofr:homeCurrency></ofr:offerItem><ofr:offerItem><ofr:quantity>1</ofr:quantity><ofr:payVAT>true</ofr:payVAT><ofr:rateVAT>high</ofr:rateVAT><ofr:homeCurrency><typ:unitPrice>198</typ:unitPrice></ofr:homeCurrency><ofr:stockItem><typ:stockItem><typ:ids>STM</typ:ids></typ:stockItem></ofr:stockItem></ofr:offerItem></ofr:offerDetail></ofr:offer>');
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

        $this->getXML()->asXML()->shouldReturn('<ofr:offer version="2.0"><ofr:offerHeader>' . $this->_defaultHeader() . '</ofr:offerHeader><ofr:offerSummary><ofr:roundingDocument>math2one</ofr:roundingDocument><ofr:foreignCurrency><typ:currency><typ:ids>EUR</typ:ids></typ:currency><typ:rate>20.232</typ:rate><typ:amount>1</typ:amount><typ:priceSum>580</typ:priceSum></ofr:foreignCurrency></ofr:offerSummary></ofr:offer>');
    }

    public function it_can_set_parameters()
    {
        $this->addParameter('IsOn', 'boolean', 'true');
        $this->addParameter('VPrNum', 'number', 10.43);
        $this->addParameter('RefVPrCountry', 'list', 'SK', 'Country');
        $this->addParameter('CustomList', 'list', ['id' => 5], ['id' => 6]);

        $this->getXML()->asXML()->shouldReturn('<ofr:offer version="2.0"><ofr:offerHeader>' . $this->_defaultHeader() . '<ofr:parameters><typ:parameter><typ:name>VPrIsOn</typ:name><typ:booleanValue>true</typ:booleanValue></typ:parameter><typ:parameter><typ:name>VPrNum</typ:name><typ:numberValue>10.43</typ:numberValue></typ:parameter><typ:parameter><typ:name>RefVPrCountry</typ:name><typ:listValueRef><typ:ids>SK</typ:ids></typ:listValueRef><typ:list><typ:ids>Country</typ:ids></typ:list></typ:parameter><typ:parameter><typ:name>RefVPrCustomList</typ:name><typ:listValueRef><typ:id>5</typ:id></typ:listValueRef><typ:list><typ:id>6</typ:id></typ:list></typ:parameter></ofr:parameters></ofr:offerHeader></ofr:offer>');
    }

    protected function _defaultHeader()
    {
        return '<ofr:offerType>receivedOffer</ofr:offerType><ofr:date>2015-01-10</ofr:date><ofr:partnerIdentity><typ:id>25</typ:id></ofr:partnerIdentity><ofr:myIdentity><typ:address><typ:name>NAME</typ:name><typ:ico>123</typ:ico></typ:address></ofr:myIdentity><ofr:intNote>Note</ofr:intNote>';
    }
}
