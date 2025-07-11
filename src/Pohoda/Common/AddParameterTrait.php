<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\Common;

use Riesenia\Pohoda\Type\Parameter;

trait AddParameterTrait
{
    /**
     * Set user-defined parameter.
     *
     * @param string     $name  (can be set without preceding VPr / RefVPr)
     * @param mixed      $value
     * @param mixed|null $list
     *
     * @return \Riesenia\Pohoda\Agenda
     */
    public function addParameter(string $name, string $type, $value, $list = null)
    {
        if (!isset($this->_data['parameters'])) {
            $this->_data['parameters'] = [];
        }

        if (!\is_array($this->_data['parameters'])) {
            throw new \InvalidArgumentException('Invalid parameters format.');
        }

        $this->_data['parameters'][] = new Parameter([
            'name' => $name,
            'type' => $type,
            'value' => $value,
            'list' => $list
        ], $this->_ico);

        return $this;
    }
}
