<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda;

use Riesenia\Pohoda\Type\Link;

class IssueSlip extends Document
{
    /** @var string */
    public static $importRoot = 'lst:vydejka';

    /**
     * Add link.
     *
     * @param array<string,mixed> $data
     *
     * @return $this
     */
    public function addLink(array $data): self
    {
        if (!isset($this->_data['links'])) {
            $this->_data['links'] = [];
        }

        $this->_data['links'][] = new Link($data, $this->_ico);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _getDocumentElements(): array
    {
        return \array_merge(['links'], parent::_getDocumentElements());
    }

    /**
     * {@inheritdoc}
     */
    protected function _getDocumentNamespace(): string
    {
        return 'vyd';
    }

    /**
     * {@inheritdoc}
     */
    protected function _getDocumentName(): string
    {
        return 'vydejka';
    }
}
