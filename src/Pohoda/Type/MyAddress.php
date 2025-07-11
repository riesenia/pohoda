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

class MyAddress extends Agenda
{
    use SetNamespaceTrait;

    /** @var string[] */
    protected $_elements = ['address', 'establishment'];

    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process address
        if (isset($data['address'])) {
            $data['address'] = new AddressInternetType($data['address'], $ico, $resolveOptions);
        }

        // process establishment
        if (isset($data['establishment'])) {
            $data['establishment'] = new EstablishmentType($data['establishment'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);
    }
}
