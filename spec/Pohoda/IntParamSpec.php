<?php
namespace spec\Rshop\Synchronization\Pohoda;

use PhpSpec\ObjectBehavior;

class IntParamSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'name' => 'NAME',
            'parameterType' => 'textValue',
            'parameterSettings' => ['length' => 40]
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Rshop\Synchronization\Pohoda\IntParam');
        $this->shouldHaveType('Rshop\Synchronization\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<ipm:intParamDetail version="2.0"><ipm:intParam><ipm:name>NAME</ipm:name><ipm:parameterType>textValue</ipm:parameterType><ipm:parameterSettings><ipm:length>40</ipm:length></ipm:parameterSettings></ipm:intParam></ipm:intParamDetail>');
    }
}
