<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Common;

trait AddParameterToHeaderTrait
{
    /**
     * Set user-defined parameter.
     *
     * @param string     $name  (can be set without preceding VPr / RefVPr)
     * @param string     $type
     * @param mixed      $value
     * @param mixed|null $list
     *
     * @return \Riesenia\Pohoda\Agenda
     */
    public function addParameter(string $name, string $type, $value, $list = null)
    {
        $this->_data['header']->addParameter($name, $type, $value, $list);

        return $this;
    }
}
