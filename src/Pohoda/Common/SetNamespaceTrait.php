<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Common;

trait SetNamespaceTrait
{
    /** @var string */
    protected $_namespace;

    /**
     * Set namespace.
     *
     * @param string $namespace
     */
    public function setNamespace(string $namespace)
    {
        $this->_namespace = $namespace;
    }
}
