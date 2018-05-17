<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Common;

use Riesenia\Pohoda\Type\ActionType;

trait AddActionTypeTrait
{
    /**
     * Add action type.
     *
     * @param string     $type
     * @param mixed|null $filter
     *
     * @return self
     */
    public function addActionType(string $type, $filter = null): self
    {
        if (isset($this->_data['actionType'])) {
            throw new \OutOfRangeException('Duplicate action type.');
        }

        $this->_data['actionType'] = new ActionType([
            'type' => $type,
            'filter' => $filter
        ], $this->_ico);

        return $this;
    }
}
