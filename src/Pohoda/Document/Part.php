<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Document;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\SetNamespaceTrait;

abstract class Part extends Agenda
{
    use SetNamespaceTrait;

    /** @var string */
    protected $_nodePrefix;

    /** @var string[] */
    protected $_elements;

    /**
     * Set node name prefix.
     *
     * @param string $prefix
     *
     * @return void
     */
    public function setNodePrefix(string $prefix)
    {
        $this->_nodePrefix = $prefix;
    }
}
