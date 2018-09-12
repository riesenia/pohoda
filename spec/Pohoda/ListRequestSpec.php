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

    public function it_creates_correct_xml_for_stock()
    {
        $this->beConstructedWith([
            'type' => 'Stock'
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lStk:listStockRequest version="2.0" stockVersion="2.0"><lStk:requestStock/></lStk:listStockRequest>');
    }

    public function it_creates_correct_xml_for_address_book()
    {
        $this->beConstructedWith([
            'type' => 'AddressBook'
        ], '123');

        $this->getXML()->asXML()->shouldReturn('<lAdb:listAddressBookRequest version="2.0" addressBookVersion="2.0"><lAdb:requestAddressBook/></lAdb:listAddressBookRequest>');
    }

    public function it_creates_correct_xml_for_invoice_with_user_filter_name()
    {
        $this->beConstructedWith([
            'type' => 'Invoice'
        ], '123');

        $this->addUserFilterName('CustomFilter')->getXML()->asXML()->shouldReturn('<lst:listInvoiceRequest version="2.0" invoiceVersion="2.0" invoiceType="issuedInvoice"><lst:requestInvoice><ftr:userFilterName>CustomFilter</ftr:userFilterName></lst:requestInvoice></lst:listInvoiceRequest>');
    }

    public function it_creates_correct_xml_for_issue_slip_with_last_changes_filter()
    {
        $this->beConstructedWith([
            'type' => 'Vydejka'
        ], '123');

        $this->addFilter(['lastChanges' => '2018-04-29 14:30'])->getXML()->asXML()->shouldReturn('<lst:listVydejkaRequest version="2.0" vydejkaVersion="2.0"><lst:requestVydejka><ftr:filter><ftr:lastChanges>2018-04-29T14:30:00</ftr:lastChanges></ftr:filter></lst:requestVydejka></lst:listVydejkaRequest>');
    }
}
