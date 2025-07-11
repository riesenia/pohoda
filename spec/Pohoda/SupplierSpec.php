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

class SupplierSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'stockItem' => [
                'stockItem' => [
                    'ids' => 'B04'
                ]
            ],
            'suppliers' => [
                [
                    'supplierItem' => [
                        'default' => true,
                        'refAd' => [
                            'id' => 2
                        ],
                        'orderCode' => 'A1',
                        'orderName' => 'A-zasoba',
                        'purchasingPrice' => 1968,
                        'rate' => 0,
                        'payVAT' => false,
                        'ean' => '11112228',
                        'printEAN' => true,
                        'unitEAN' => 'ks',
                        'unitCoefEAN' => 1,
                        'deliveryTime' => 12,
                        'minQuantity' => 2,
                        'note' => 'fdf'
                    ]
                ],
                [
                    'supplierItem' => [
                        'default' => false,
                        'refAd' => [
                            'ids' => 'INTEAK spol. s r. o.'
                        ],
                        'orderCode' => 'I1',
                        'orderName' => 'I-zasoba',
                        'purchasingPrice' => 500,
                        'rate' => 0,
                        'payVAT' => false,
                        'ean' => '212121212',
                        'printEAN' => true,
                        'unitEAN' => 'ks',
                        'unitCoefEAN' => 1,
                        'deliveryTime' => 12,
                        'minQuantity' => 2,
                        'note' => 'aasn'
                    ]
                ]
            ]
        ], '123');
    }

    public function it_is_initializable_and_extends_agenda()
    {
        $this->shouldHaveType('Riesenia\Pohoda\Supplier');
        $this->shouldHaveType('Riesenia\Pohoda\Agenda');
    }

    public function it_creates_correct_xml()
    {
        $this->getXML()->asXML()->shouldReturn('<sup:supplier version="2.0"><sup:stockItem><typ:stockItem><typ:ids>B04</typ:ids></typ:stockItem></sup:stockItem><sup:suppliers><sup:supplierItem default="true"><sup:refAd><typ:id>2</typ:id></sup:refAd><sup:orderCode>A1</sup:orderCode><sup:orderName>A-zasoba</sup:orderName><sup:purchasingPrice>1968</sup:purchasingPrice><sup:rate>0</sup:rate><sup:payVAT>false</sup:payVAT><sup:ean>11112228</sup:ean><sup:printEAN>true</sup:printEAN><sup:unitEAN>ks</sup:unitEAN><sup:unitCoefEAN>1</sup:unitCoefEAN><sup:deliveryTime>12</sup:deliveryTime><sup:minQuantity>2</sup:minQuantity><sup:note>fdf</sup:note></sup:supplierItem><sup:supplierItem default="false"><sup:refAd><typ:ids>INTEAK spol. s r. o.</typ:ids></sup:refAd><sup:orderCode>I1</sup:orderCode><sup:orderName>I-zasoba</sup:orderName><sup:purchasingPrice>500</sup:purchasingPrice><sup:rate>0</sup:rate><sup:payVAT>false</sup:payVAT><sup:ean>212121212</sup:ean><sup:printEAN>true</sup:printEAN><sup:unitEAN>ks</sup:unitEAN><sup:unitCoefEAN>1</sup:unitCoefEAN><sup:deliveryTime>12</sup:deliveryTime><sup:minQuantity>2</sup:minQuantity><sup:note>aasn</sup:note></sup:supplierItem></sup:suppliers></sup:supplier>');
    }
}
