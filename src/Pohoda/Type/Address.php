<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\Type;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Common\SetNamespaceTrait;

class Address extends Agenda
{
    use SetNamespaceTrait;

    /** @var string[] */
    protected $_refElements = ['extId'];

    /** @var string[] */
    protected $_elements = ['id', 'extId', 'address', 'addressLinkToAddress', 'shipToAddress'];

    protected $_elementsAttributesMapper = [
        'addressLinkToAddress' => ['address', 'linkToAddress', null],
    ];

    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process address
        if (isset($data['address'])) {
            $data['address'] = new AddressType($data['address'], $ico, $resolveOptions);
        }

        // process shipping address
        if (isset($data['shipToAddress'])) {
            $data['shipToAddress'] = new ShipToAddressType($data['shipToAddress'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('id', $resolver->getNormalizer('int'));
        $resolver->setNormalizer('addressLinkToAddress', $resolver->getNormalizer('bool'));
    }
}
