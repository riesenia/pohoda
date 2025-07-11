<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\Common;

use Riesenia\Pohoda\Agenda;

trait AddParameterToHeaderTrait
{
    /**
     * Set user-defined parameter.
     *
     * @param string     $name  (can be set without preceding VPr / RefVPr)
     * @param mixed      $value
     * @param mixed|null $list
     *
     * @return Agenda
     */
    public function addParameter(string $name, string $type, $value, $list = null)
    {
        if (!isset($this->_data['header']) || !$this->_data['header'] instanceof Agenda || !\method_exists($this->_data['header'], 'addParameter')) {
            throw new \InvalidArgumentException('Invalid header format.');
        }

        $this->_data['header']->addParameter($name, $type, $value, $list);

        return $this;
    }
}
