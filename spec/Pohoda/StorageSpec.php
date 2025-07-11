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
use Riesenia\Pohoda\Storage;

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
        $this->shouldHaveType('Riesenia\Pohoda\Storage');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<str:storage version="2.0"><str:itemStorage code="MAIN"/></str:storage>');
    }

    public function it_can_add_substorages()
    {
        $sub = new Storage([
            'code' => 'Sub',
            'name' => 'Sub'
        ], '123');

        $this->addSubstorage($sub);

        $this->getXML()->asXML()->shouldReturn('<str:storage version="2.0"><str:itemStorage code="MAIN"><str:subStorages><str:itemStorage code="Sub" name="Sub"/></str:subStorages></str:itemStorage></str:storage>');

        $subsub = new Storage([
            'code' => 'SubSub',
            'name' => 'SubSub'
        ], '123');

        $sub->addSubstorage($subsub);

        $this->getXML()->asXML()->shouldReturn('<str:storage version="2.0"><str:itemStorage code="MAIN"><str:subStorages><str:itemStorage code="Sub" name="Sub"><str:subStorages><str:itemStorage code="SubSub" name="SubSub"/></str:subStorages></str:itemStorage></str:subStorages></str:itemStorage></str:storage>');
    }
}
