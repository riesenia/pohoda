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

class AddressbookSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'identity' => [
                'address' => [
                    'name' => 'NAME',
                    'ico' => '123'
                ]
            ],
            'phone' => '123',
            'centre' => ['id' => 1]
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Riesenia\Pohoda\Addressbook');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<adb:addressbook version="2.0"><adb:addressbookHeader>' . $this->_defaultHeader() . '</adb:addressbookHeader></adb:addressbook>');
    }

    public function it_can_set_action_type()
    {
        $this->addActionType('update', [
            'company' => 'COMPANY'
        ]);

        $this->getXML()->asXML()->shouldReturn('<adb:addressbook version="2.0"><adb:actionType><adb:update><ftr:filter><ftr:company>COMPANY</ftr:company></ftr:filter></adb:update></adb:actionType><adb:addressbookHeader>' . $this->_defaultHeader() . '</adb:addressbookHeader></adb:addressbook>');
    }

    public function it_can_set_parameters()
    {
        $this->addParameter('IsOn', 'boolean', 'true');
        $this->addParameter('VPrNum', 'number', 10.43);
        $this->addParameter('RefVPrCountry', 'list', 'SK', 'Country');
        $this->addParameter('CustomList', 'list', ['id' => 5], ['id' => 6]);

        $this->getXML()->asXML()->shouldReturn('<adb:addressbook version="2.0"><adb:addressbookHeader>' . $this->_defaultHeader() . '<adb:parameters><typ:parameter><typ:name>VPrIsOn</typ:name><typ:booleanValue>true</typ:booleanValue></typ:parameter><typ:parameter><typ:name>VPrNum</typ:name><typ:numberValue>10.43</typ:numberValue></typ:parameter><typ:parameter><typ:name>RefVPrCountry</typ:name><typ:listValueRef><typ:ids>SK</typ:ids></typ:listValueRef><typ:list><typ:ids>Country</typ:ids></typ:list></typ:parameter><typ:parameter><typ:name>RefVPrCustomList</typ:name><typ:listValueRef><typ:id>5</typ:id></typ:listValueRef><typ:list><typ:id>6</typ:id></typ:list></typ:parameter></adb:parameters></adb:addressbookHeader></adb:addressbook>');
    }

    public function it_can_delete_address()
    {
        $this->beConstructedWith([], '123');

        $this->addActionType('delete', [
            'company' => 'COMPANY'
        ]);

        $this->getXML()->asXML()->shouldReturn('<adb:addressbook version="2.0"><adb:actionType><adb:delete><ftr:filter><ftr:company>COMPANY</ftr:company></ftr:filter></adb:delete></adb:actionType></adb:addressbook>');
    }

    public function it_leaves_special_characters_intact_by_default()
    {
        $this->beConstructedWith([
            'identity' => [
                'address' => [
                    'name' => 'Călărași ñüé¿s',
                    'city' => 'Dâmbovița'
                ]
            ],
            'phone' => '123',
            'centre' => ['id' => 1]
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<adb:addressbook version="2.0"><adb:addressbookHeader><adb:identity><typ:address><typ:name>Călărași ñüé¿s</typ:name><typ:city>Dâmbovița</typ:city></typ:address></adb:identity><adb:phone>123</adb:phone><adb:centre><typ:id>1</typ:id></adb:centre></adb:addressbookHeader></adb:addressbook>');
    }

    protected function _defaultHeader()
    {
        return '<adb:identity><typ:address><typ:name>NAME</typ:name><typ:ico>123</typ:ico></typ:address></adb:identity><adb:phone>123</adb:phone><adb:centre><typ:id>1</typ:id></adb:centre>';
    }
}
