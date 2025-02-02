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

class ListRequestSpec extends ObjectBehavior
{
    public function it_creates_correct_xml_for_category()
    {
        $this->beConstructedWith([
            'type' => 'Category'
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lst:listCategoryRequest version="2.0" categoryVersion="2.0"><lst:requestCategory/></lst:listCategoryRequest>');
    }

    public function it_creates_correct_xml_for_action_prices()
    {
        $this->beConstructedWith([
            'type' => 'ActionPrice'
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lst:listActionPriceRequest version="2.0" actionPricesVersion="2.0"><lst:requestActionPrice/></lst:listActionPriceRequest>');
    }

    public function it_creates_correct_xml_for_order()
    {
        $this->beConstructedWith([
            'type' => 'Order'
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lst:listOrderRequest version="2.0" orderVersion="2.0" orderType="receivedOrder"><lst:requestOrder/></lst:listOrderRequest>');
    }

    public function it_creates_correct_xml_for_advance_invoice()
    {
        $this->beConstructedWith([
            'type' => 'Invoice',
            'invoiceType' => 'issuedAdvanceInvoice'
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lst:listInvoiceRequest version="2.0" invoiceVersion="2.0" invoiceType="issuedAdvanceInvoice"><lst:requestInvoice/></lst:listInvoiceRequest>');
    }

    public function it_creates_correct_xml_for_vydejka()
    {
        $this->beConstructedWith([
            'type' => 'Vydejka'
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lst:listVydejkaRequest version="2.0" vydejkaVersion="2.0"><lst:requestVydejka/></lst:listVydejkaRequest>');
    }

    public function it_creates_correct_xml_for_issue_slip()
    {
        $this->beConstructedWith([
            'type' => 'IssueSlip'
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lst:listVydejkaRequest version="2.0" vydejkaVersion="2.0"><lst:requestVydejka/></lst:listVydejkaRequest>');
    }

    public function it_creates_correct_xml_for_address_book()
    {
        $this->beConstructedWith([
            'type' => 'Addressbook'
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lAdb:listAddressBookRequest version="2.0" addressBookVersion="2.0"><lAdb:requestAddressBook/></lAdb:listAddressBookRequest>');
    }

    public function it_creates_correct_xml_for_int_params()
    {
        $this->beConstructedWith([
            'type' => 'IntParam'
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lst:listIntParamRequest version="2.0"><lst:requestIntParam/></lst:listIntParamRequest>');
    }

    public function it_creates_correct_xml_for_user_lists()
    {
        $this->beConstructedWith([
            'type' => 'UserList'
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lst:listUserCodeRequest version="1.1" listVersion="1.1"/>');
    }

    public function it_creates_correct_xml_for_invoice_with_user_filter_name()
    {
        $this->beConstructedWith([
            'type' => 'Invoice'
        ], '123');

        $this->addUserFilterName('CustomFilter')->getXML()->asXML()->shouldReturn('<lst:listInvoiceRequest version="2.0" invoiceVersion="2.0" invoiceType="issuedInvoice"><lst:requestInvoice><ftr:userFilterName>CustomFilter</ftr:userFilterName></lst:requestInvoice></lst:listInvoiceRequest>');
    }

    public function it_creates_correct_xml_for_invoice_with_query_filter()
    {
        $this->beConstructedWith([
            'type' => 'Invoice'
        ], '123');

        $this->addQueryFilter([
            'filter' => '(FA.DatSave>=CONVERT(DATETIME, \'01/31/2025 16:30:00\', 101) OR FA.DatLikv>=CONVERT(DATETIME, \'01/31/2025\', 101))',
            'textName' => '(Uloženo >= 2025-01-31 16:30:00; Likv. >= 2025-01-31)',
        ])->getXML()->asXML()->shouldReturn('<lst:listInvoiceRequest version="2.0" invoiceVersion="2.0" invoiceType="issuedInvoice"><lst:requestInvoice><ftr:queryFilter><ftr:filter>(FA.DatSave&gt;=CONVERT(DATETIME, \'01/31/2025 16:30:00\', 101) OR FA.DatLikv&gt;=CONVERT(DATETIME, \'01/31/2025\', 101))</ftr:filter><ftr:textName>(Uloženo &gt;= 2025-01-31 16:30:00; Likv. &gt;= 2025-01-31)</ftr:textName></ftr:queryFilter></lst:requestInvoice></lst:listInvoiceRequest>');
    }

    public function it_creates_correct_xml_for_stock_with_complex_filter()
    {
        $this->beConstructedWith([
            'type' => 'Stock'
        ], '123');

        $this->addFilter(['storage' => ['ids' => 'MAIN'], 'lastChanges' => '2018-04-29 14:30'])->getXML()->asXML()->shouldReturn('<lStk:listStockRequest version="2.0" stockVersion="2.0"><lStk:requestStock><ftr:filter><ftr:storage><typ:ids>MAIN</typ:ids></ftr:storage><ftr:lastChanges>2018-04-29T14:30:00</ftr:lastChanges></ftr:filter></lStk:requestStock></lStk:listStockRequest>');
    }

    public function it_creates_proper_restriction_data()
    {
        $this->beConstructedWith(
            ['type' => 'Invoice'],
            '123'
        );

        $this->addRestrictionData(['liquidation' => true]);

        $this->getXml()->asXML()->shouldReturn('<lst:listInvoiceRequest version="2.0" invoiceVersion="2.0" invoiceType="issuedInvoice"><lst:requestInvoice/><lst:restrictionData><lst:liquidation>true</lst:liquidation></lst:restrictionData></lst:listInvoiceRequest>');
    }
}
