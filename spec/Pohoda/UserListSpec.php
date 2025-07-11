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

class UserListSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'code' => 'CODE',
            'name' => 'NAME'
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Riesenia\Pohoda\UserList');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->addItemUserCode([
            'code' => 'CODE 2',
            'name' => 'NAME 2'
        ]);

        $this->getXML()->asXML()->shouldReturn('<lst:listUserCode version="1.1" code="CODE" name="NAME"><lst:itemUserCode code="CODE 2" name="NAME 2"/></lst:listUserCode>');
    }
}
