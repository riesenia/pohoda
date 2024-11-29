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

class ReceiptSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'date' => new \DateTimeImmutable('2015-01-10'),
            'dateOfReceipt' => '',
            'text' => 'Prijemka',
            'partnerIdentity' => [
                'id' => 20
            ],
            'activity' => [
                'id' => 1,
            ],
            'intNote' => 'Note'
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Riesenia\Pohoda\Receipt');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<pri:prijemka version="2.0"><pri:prijemkaHeader>' . $this->_defaultHeader() . '</pri:prijemkaHeader></pri:prijemka>');
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

        $this->getXML()->asXML()->shouldReturn('<pri:prijemka version="2.0"><pri:prijemkaHeader>' . $this->_defaultHeader() . '</pri:prijemkaHeader><pri:prijemkaDetail><pri:prijemkaItem><pri:quantity>2</pri:quantity><pri:stockItem><typ:stockItem><typ:ids>model</typ:ids><typ:store>X</typ:store></typ:stockItem></pri:stockItem></pri:prijemkaItem><pri:prijemkaItem><pri:quantity>1</pri:quantity><pri:stockItem><typ:stockItem><typ:ids>STM</typ:ids></typ:stockItem></pri:stockItem><pri:note>STM</pri:note></pri:prijemkaItem></pri:prijemkaDetail></pri:prijemka>');
    }

    public function it_can_set_parameters()
    {
        $this->addParameter('IsOn', 'boolean', 'true');
        $this->addParameter('VPrNum', 'number', 10.43);
        $this->addParameter('RefVPrCountry', 'list', 'SK', 'Country');
        $this->addParameter('CustomList', 'list', ['id' => 5], ['id' => 6]);

        $this->getXML()->asXML()->shouldReturn('<pri:prijemka version="2.0"><pri:prijemkaHeader>' . $this->_defaultHeader() . '<pri:parameters><typ:parameter><typ:name>VPrIsOn</typ:name><typ:booleanValue>true</typ:booleanValue></typ:parameter><typ:parameter><typ:name>VPrNum</typ:name><typ:numberValue>10.43</typ:numberValue></typ:parameter><typ:parameter><typ:name>RefVPrCountry</typ:name><typ:listValueRef><typ:ids>SK</typ:ids></typ:listValueRef><typ:list><typ:ids>Country</typ:ids></typ:list></typ:parameter><typ:parameter><typ:name>RefVPrCustomList</typ:name><typ:listValueRef><typ:id>5</typ:id></typ:listValueRef><typ:list><typ:id>6</typ:id></typ:list></typ:parameter></pri:parameters></pri:prijemkaHeader></pri:prijemka>');
    }

    protected function _defaultHeader()
    {
        return '<pri:date>2015-01-10</pri:date><pri:dateOfReceipt/><pri:text>Prijemka</pri:text><pri:partnerIdentity><typ:id>20</typ:id></pri:partnerIdentity><pri:activity><typ:id>1</typ:id></pri:activity><pri:intNote>Note</pri:intNote>';
    }
}
