<?php
namespace spec\Rshop\Synchronization\Pohoda;

use PhpSpec\ObjectBehavior;
use Rshop\Synchronization\Pohoda\Storage;

class StorageSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'code' => 'MAIN'
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Rshop\Synchronization\Pohoda\Storage');
        $this->shouldHaveType('Rshop\Synchronization\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->shouldReturn('<str:storage version="2.0"><str:itemStorage code="MAIN"/></str:storage>');
    }

    public function it_can_add_substorages()
    {
        $sub = new Storage([
            'code' => 'Sub',
            'name' => 'Sub'
        ], '123');

        $this->addSubstorage($sub);

        $this->getXML()->shouldReturn('<str:storage version="2.0"><str:itemStorage code="MAIN"><str:subStorages><str:itemStorage code="Sub" name="Sub"/></str:subStorages></str:itemStorage></str:storage>');

        $subsub = new Storage([
            'code' => 'SubSub',
            'name' => 'SubSub'
        ], '123');

        $sub->addSubstorage($subsub);

        $this->getXML()->shouldReturn('<str:storage version="2.0"><str:itemStorage code="MAIN"><str:subStorages><str:itemStorage code="Sub" name="Sub"><str:subStorages><str:itemStorage code="SubSub" name="SubSub"/></str:subStorages></str:itemStorage></str:subStorages></str:itemStorage></str:storage>');
    }
}
