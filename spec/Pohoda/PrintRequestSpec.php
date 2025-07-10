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

class PrintRequestSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'record' => [
                'agenda' => 'vydane_faktury',
                'filter' => [
                    'id' => '1234'
                ]
            ],
            'printerSettings' => [
                'report' => [
                    'id' => 5678
                ],
                'pdf' => [
                    'fileName' => 'C:\Test\1234.pdf'
                ],
                'parameters' => [
                    'copy' => 5
                ],
            ]
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Riesenia\Pohoda\PrintRequest');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<prn:print version="1.0"><prn:record agenda="vydane_faktury"><ftr:filter><ftr:id>1234</ftr:id></ftr:filter></prn:record><prn:printerSettings><prn:report><prn:id>5678</prn:id></prn:report><prn:pdf><prn:fileName>C:\Test\1234.pdf</prn:fileName></prn:pdf><prn:parameters><prn:copy>5</prn:copy></prn:parameters></prn:printerSettings></prn:print>');
    }
}
