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

class ListTableExportSpec extends ObjectBehavior
{
    public function it_creates_correct_xml_for_stock_table_export()
    {
        $this->beConstructedWith([
            'requestTableExport' => [
                'agenda' => 'stock',
                'table' => 'SKz',
                'columns' => [
                    'columns' => ['id', 'ids', 'stavZ']
                ]
            ]
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lst:listTableExportRequest version="2.0" tableExportVersion="2.0"><lst:requestTableExport agenda="stock" table="SKz"><tbe:columns><tbe:column>id</tbe:column><tbe:column>ids</tbe:column><tbe:column>stavZ</tbe:column></tbe:columns></lst:requestTableExport></lst:listTableExportRequest>');
    }

    public function it_creates_correct_xml_with_limit()
    {
        $this->beConstructedWith([
            'requestTableExport' => [
                'agenda' => 'stock',
                'table' => 'SKz',
                'columns' => [
                    'columns' => ['id', 'ids']
                ]
            ]
        ], '123');

        $this->addLimit(['idFrom' => 1000, 'count' => 10])->getXML()->asXML()->shouldReturn('<lst:listTableExportRequest version="2.0" tableExportVersion="2.0"><lst:limit><ftr:idFrom>1000</ftr:idFrom><ftr:count>10</ftr:count></lst:limit><lst:requestTableExport agenda="stock" table="SKz"><tbe:columns><tbe:column>id</tbe:column><tbe:column>ids</tbe:column></tbe:columns></lst:requestTableExport></lst:listTableExportRequest>');
    }

    public function it_creates_correct_xml_with_query_filter()
    {
        $this->beConstructedWith([
            'requestTableExport' => [
                'agenda' => 'stock',
                'table' => 'SKz',
                'queryFilter' => [
                    'filter' => 'SkzIoZbozi.ID=134'
                ],
                'columns' => [
                    'columns' => ['id', 'ids', 'stavZ']
                ]
            ]
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lst:listTableExportRequest version="2.0" tableExportVersion="2.0"><lst:requestTableExport agenda="stock" table="SKz"><ftr:queryFilter><ftr:filter>SkzIoZbozi.ID=134</ftr:filter></ftr:queryFilter><tbe:columns><tbe:column>id</tbe:column><tbe:column>ids</tbe:column><tbe:column>stavZ</tbe:column></tbe:columns></lst:requestTableExport></lst:listTableExportRequest>');
    }

    public function it_creates_correct_xml_for_addressbook()
    {
        $this->beConstructedWith([
            'requestTableExport' => [
                'agenda' => 'addressBook',
                'table' => 'AD',
                'columns' => [
                    'columns' => ['ID', 'Kod', 'Nazev']
                ]
            ]
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lst:listTableExportRequest version="2.0" tableExportVersion="2.0"><lst:requestTableExport agenda="addressBook" table="AD"><tbe:columns><tbe:column>ID</tbe:column><tbe:column>Kod</tbe:column><tbe:column>Nazev</tbe:column></tbe:columns></lst:requestTableExport></lst:listTableExportRequest>');
    }

    public function it_creates_correct_xml_without_table_attribute()
    {
        $this->beConstructedWith([
            'requestTableExport' => [
                'agenda' => 'stock',
                'columns' => [
                    'columns' => ['id', 'ids']
                ]
            ]
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lst:listTableExportRequest version="2.0" tableExportVersion="2.0"><lst:requestTableExport agenda="stock"><tbe:columns><tbe:column>id</tbe:column><tbe:column>ids</tbe:column></tbe:columns></lst:requestTableExport></lst:listTableExportRequest>');
    }
}
