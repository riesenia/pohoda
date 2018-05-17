<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Common;

trait SetNodeNameTrait
{
    /** @var string */
    protected $_nodeName;

    /**
     * Set node name.
     *
     * @param string $nodeName
     */
    public function setNodeName(string $nodeName)
    {
        $this->_nodeName = $nodeName;
    }
}
