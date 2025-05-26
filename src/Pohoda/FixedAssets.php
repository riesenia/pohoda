<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda;

/**
 * FixedAssets - Long-term assets (Dlouhodobý majetek)
 * Extends IntDoc as assets are handled as internal documents in Pohoda
 */
class FixedAssets extends IntDoc
{
    /** @var string */
    public static $importRoot = 'lst:intDoc';

    /**
     * {@inheritdoc}
     */
    protected function _getDocumentNamespace(): string
    {
        return 'int';
    }

    /**
     * {@inheritdoc}
     */
    protected function _getDocumentName(): string
    {
        return 'intDoc';
    }

    /**
     * Get agenda type for fixed assets
     *
     * @return string
     */
    public function getAgendaType(): string
    {
        return 'fixedAssets';
    }

    /**
     * Get Czech agenda identifier for filtering
     *
     * @return string
     */
    public function getCzechAgendaType(): string
    {
        return 'dlouhodoby_majetek';
    }
}