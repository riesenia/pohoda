<?php
namespace spec\Rshop\Synchronization\Pohoda;

use PhpSpec\ObjectBehavior;

class ContractSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'text' => 'zakazka15',
            'responsiblePerson' => ['ids' => 'Z0005']
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Rshop\Synchronization\Pohoda\Contract');
        $this->shouldHaveType('Rshop\Synchronization\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<con:contract version="2.0"><con:contractDesc>' . $this->_defaultHeader() . '</con:contractDesc></con:contract>');
    }

    public function it_can_set_parameters()
    {
        $this->addParameter('VPrNum', 'number', 10.43);

        $this->getXML()->asXML()->shouldReturn('<con:contract version="2.0"><con:contractDesc>' . $this->_defaultHeader() . '<con:parameters><typ:parameter><typ:name>VPrNum</typ:name><typ:numberValue>10.43</typ:numberValue></typ:parameter></con:parameters></con:contractDesc></con:contract>');
    }

    protected function _defaultHeader()
    {
        return '<con:text>zakazka15</con:text><con:responsiblePerson><typ:ids>Z0005</typ:ids></con:responsiblePerson>';
    }
}
