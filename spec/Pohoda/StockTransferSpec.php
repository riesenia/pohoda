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

class StockTransferSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'date' => '2015-01-10',
            'store' => [
                'ids' => 'MAIN'
            ],
            'text' => 'Prevodka na MAIN',
            'partnerIdentity' => [
                'address' => [
                    'name' => 'NAME',
                    'ico' => '123'
                ]
            ],
            'activity' => [
                'id' => 1
            ],
            'intNote' => 'Note'
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Riesenia\Pohoda\StockTransfer');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<pre:prevodka version="2.0"><pre:prevodkaHeader>' . $this->_defaultHeader() . '</pre:prevodkaHeader></pre:prevodka>');
    }

    public function it_can_add_items()
    {
        $this->addItem([
            'quantity' => 2,
            'stockItem' => [
                'stockItem' => [
                    'ids' => 'model',
                    'store' => 'X'
                ]
            ]
        ]);

        $this->addItem([
            'quantity' => 1,
            'stockItem' => [
                'stockItem' => [
                    'ids' => 'STM'
                ]
            ],
            'note' => 'STM'
        ]);

        $this->getXML()->asXML()->shouldReturn('<pre:prevodka version="2.0"><pre:prevodkaHeader>' . $this->_defaultHeader() . '</pre:prevodkaHeader><pre:prevodkaDetail><pre:prevodkaItem><pre:quantity>2</pre:quantity><pre:stockItem><typ:stockItem><typ:ids>model</typ:ids><typ:store>X</typ:store></typ:stockItem></pre:stockItem></pre:prevodkaItem><pre:prevodkaItem><pre:quantity>1</pre:quantity><pre:stockItem><typ:stockItem><typ:ids>STM</typ:ids></typ:stockItem></pre:stockItem><pre:note>STM</pre:note></pre:prevodkaItem></pre:prevodkaDetail></pre:prevodka>');
    }

    public function it_can_set_parameters()
    {
        $this->addParameter('IsOn', 'boolean', 'true');
        $this->addParameter('VPrNum', 'number', 10.43);
        $this->addParameter('RefVPrCountry', 'list', 'SK', 'Country');
        $this->addParameter('CustomList', 'list', ['id' => 5], ['id' => 6]);

        $this->getXML()->asXML()->shouldReturn('<pre:prevodka version="2.0"><pre:prevodkaHeader>' . $this->_defaultHeader() . '<pre:parameters><typ:parameter><typ:name>VPrIsOn</typ:name><typ:booleanValue>true</typ:booleanValue></typ:parameter><typ:parameter><typ:name>VPrNum</typ:name><typ:numberValue>10.43</typ:numberValue></typ:parameter><typ:parameter><typ:name>RefVPrCountry</typ:name><typ:listValueRef><typ:ids>SK</typ:ids></typ:listValueRef><typ:list><typ:ids>Country</typ:ids></typ:list></typ:parameter><typ:parameter><typ:name>RefVPrCustomList</typ:name><typ:listValueRef><typ:id>5</typ:id></typ:listValueRef><typ:list><typ:id>6</typ:id></typ:list></typ:parameter></pre:parameters></pre:prevodkaHeader></pre:prevodka>');
    }

    protected function _defaultHeader()
    {
        return '<pre:date>2015-01-10</pre:date><pre:store><typ:ids>MAIN</typ:ids></pre:store><pre:text>Prevodka na MAIN</pre:text><pre:partnerIdentity><typ:address><typ:name>NAME</typ:name><typ:ico>123</typ:ico></typ:address></pre:partnerIdentity><pre:activity><typ:id>1</typ:id></pre:activity><pre:intNote>Note</pre:intNote>';
    }
}
