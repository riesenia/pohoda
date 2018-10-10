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

class StockSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'code' => 'CODE',
            'name' => 'NAME',
            'isSales' => '0',
            'isSerialNumber' => 'false',
            'isInternet' => true,
            'storage' => 'STORAGE',
            'typePrice' => ['id' => 1],
            'sellingPrice' => 12.7,
            'sellingPricePayVAT' => true
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Riesenia\Pohoda\Stock');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<stk:stock version="2.0"><stk:stockHeader>' . $this->_defaultHeader() . '</stk:stockHeader></stk:stock>');
    }

    public function it_can_set_action_type()
    {
        $this->addActionType('update', [
            'code' => 'CODE',
            'store' => ['ids' => 'STORAGE']
        ]);

        $this->getXML()->asXML()->shouldReturn('<stk:stock version="2.0"><stk:actionType><stk:update><ftr:filter><ftr:code>CODE</ftr:code><ftr:store><typ:ids>STORAGE</typ:ids></ftr:store></ftr:filter></stk:update></stk:actionType><stk:stockHeader>' . $this->_defaultHeader() . '</stk:stockHeader></stk:stock>');
    }

    public function it_can_set_prices()
    {
        $this->addPrice('Price1', 20.43);
        $this->addPrice('Price2', 19);

        $this->getXML()->asXML()->shouldReturn('<stk:stock version="2.0"><stk:stockHeader>' . $this->_defaultHeader() . '</stk:stockHeader><stk:stockPriceItem><stk:stockPrice><typ:ids>Price1</typ:ids><typ:price>20.43</typ:price></stk:stockPrice><stk:stockPrice><typ:ids>Price2</typ:ids><typ:price>19</typ:price></stk:stockPrice></stk:stockPriceItem></stk:stock>');
    }

    public function it_can_set_images()
    {
        $this->addImage('image1.jpg');
        $this->addImage('image2.jpg', 'NAME', null, true);

        $this->getXML()->asXML()->shouldReturn('<stk:stock version="2.0"><stk:stockHeader>' . $this->_defaultHeader() . '<stk:pictures><stk:picture default="false"><stk:filepath>image1.jpg</stk:filepath><stk:description/><stk:order>1</stk:order></stk:picture><stk:picture default="true"><stk:filepath>image2.jpg</stk:filepath><stk:description>NAME</stk:description><stk:order>2</stk:order></stk:picture></stk:pictures></stk:stockHeader></stk:stock>');
    }

    public function it_can_set_categories()
    {
        $this->addCategory(1);
        $this->addCategory(2);

        $this->getXML()->asXML()->shouldReturn('<stk:stock version="2.0"><stk:stockHeader>' . $this->_defaultHeader() . '<stk:categories><stk:idCategory>1</stk:idCategory><stk:idCategory>2</stk:idCategory></stk:categories></stk:stockHeader></stk:stock>');
    }

    public function it_can_set_int_parameters()
    {
        $this->addIntParameter([
            'intParameterID' => 1,
            'intParameterType' => 'numberValue',
            'value' => 'VALUE1',
        ]);

        $this->getXML()->asXML()->shouldReturn('<stk:stock version="2.0"><stk:stockHeader>' . $this->_defaultHeader() . '<stk:intParameters><stk:intParameter><stk:intParameterID>1</stk:intParameterID><stk:intParameterType>numberValue</stk:intParameterType><stk:intParameterValues><stk:intParameterValue><stk:parameterValue>VALUE1</stk:parameterValue></stk:intParameterValue></stk:intParameterValues></stk:intParameter></stk:intParameters></stk:stockHeader></stk:stock>');
    }

    public function it_can_set_parameters()
    {
        $this->addParameter('IsOn', 'boolean', 'true');
        $this->addParameter('VPrNum', 'number', 10.43);
        $this->addParameter('RefVPrCountry', 'list', 'SK', 'Country');
        $this->addParameter('CustomList', 'list', ['id' => 5], ['id' => 6]);

        $this->getXML()->asXML()->shouldReturn('<stk:stock version="2.0"><stk:stockHeader>' . $this->_defaultHeader() . '<stk:parameters><typ:parameter><typ:name>VPrIsOn</typ:name><typ:booleanValue>true</typ:booleanValue></typ:parameter><typ:parameter><typ:name>VPrNum</typ:name><typ:numberValue>10.43</typ:numberValue></typ:parameter><typ:parameter><typ:name>RefVPrCountry</typ:name><typ:listValueRef><typ:ids>SK</typ:ids></typ:listValueRef><typ:list><typ:ids>Country</typ:ids></typ:list></typ:parameter><typ:parameter><typ:name>RefVPrCustomList</typ:name><typ:listValueRef><typ:id>5</typ:id></typ:listValueRef><typ:list><typ:id>6</typ:id></typ:list></typ:parameter></stk:parameters></stk:stockHeader></stk:stock>');
    }

    protected function it_can_delete_stock()
    {
        $this->beConstructedWith([], '123');

        $this->addActionType('delete', [
            'code' => 'CODE',
            'store' => ['ids' => 'STORAGE']
        ]);

        $this->getXML()->asXML()->shouldReturn('<stk:stock version="2.0"><stk:actionType><stk:delete><ftr:filter><ftr:code>CODE</ftr:code><ftr:store><typ:ids>STORAGE</typ:ids></ftr:store></ftr:filter></stk:delete></stk:actionType></stk:stock>');
    }

    protected function _defaultHeader()
    {
        return '<stk:stockType>card</stk:stockType><stk:code>CODE</stk:code><stk:isSales>false</stk:isSales><stk:isSerialNumber>false</stk:isSerialNumber><stk:isInternet>true</stk:isInternet><stk:name>NAME</stk:name><stk:storage><typ:ids>STORAGE</typ:ids></stk:storage><stk:typePrice><typ:id>1</typ:id></stk:typePrice><stk:sellingPrice payVAT="true">12.7</stk:sellingPrice>';
    }
}
