<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Common;

use Symfony\Component\OptionsResolver\OptionsResolver as SymfonyOptionsResolver;

class OptionsResolver extends SymfonyOptionsResolver
{
    /** @var array<string,\Closure> */
    protected $_loadedNormalizers = [];

    /**
     * Get normalizer.
     *
     * @param string $type
     *
     * @return \Closure
     */
    public function getNormalizer(string $type): \Closure
    {
        if (isset($this->_loadedNormalizers[$type])) {
            return $this->_loadedNormalizers[$type];
        }

        if (strpos($type, 'string') === 0) {
            $normalizer = $this->_createNormalizer('string', (int) substr($type, 6));
        } else {
            $normalizer = $this->_createNormalizer($type);
        }

        $this->_loadedNormalizers[$type] = $normalizer;

        return $normalizer;
    }

    /**
     * Create normalizer.
     *
     * @param string     $type
     * @param mixed|null $param
     *
     * @return \Closure
     */
    protected function _createNormalizer(string $type, $param = null): \Closure
    {
        switch ($type) {
            case 'string':
                return function ($options, $value) use ($param) {
                    // remove new lines
                    $value = str_replace(["\r\n", "\r", "\n"], ' ', $value);

                    return mb_substr($value, 0, $param, 'utf-8');
                };

            case 'date':
            case 'datetime':
                return function ($options, $value) {
                    $time = strtotime($value);

                    if (!$time) {
                        throw new \DomainException('Not a valid date: ' . $value);
                    }

                    return date('Y-m-d', $time);
                };

            case 'time':
                return function ($options, $value) {
                    $time = strtotime($value);

                    if (!$time) {
                        throw new \DomainException('Not a valid time: ' . $value);
                    }

                    return date('H:i:s', $time);
                };

            case 'float':
            case 'number':
                return function ($options, $value) {
                    return (string) floatval(str_replace(',', '.', preg_replace('/[^0-9,.-]/', '', $value)));
                };

            case 'int':
            case 'integer':
                return function ($options, $value) {
                    return (string) intval($value);
                };

            case 'bool':
            case 'boolean':
                return function ($options, $value) {
                    return !$value || is_string($value) && strtolower($value) === 'false' ? 'false' : 'true';
                };

            default:
                throw new \DomainException('Not a valid normalizer type: ' . $type);
        }
    }
}
