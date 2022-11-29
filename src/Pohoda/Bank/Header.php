<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\Bank;

use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Document\Header as DocumentHeader;

class Header extends DocumentHeader
{
    /** @var string[] */
    protected $_refElements = ['account', 'accounting', 'classificationVAT', 'classificationKVDPH', 'paymentAccount', 'centre', 'activity', 'contract', 'MOSS', 'evidentiaryResourcesMOSS'];

    /** @var string[] */
    protected $_elements = ['bankType', 'account', 'statementNumber', 'symVar', 'dateStatement', 'datePayment', 'accounting', 'classificationVAT', 'classificationKVDPH', 'text', 'partnerIdentity', 'myIdentity', 'paymentAccount', 'symConst', 'symSpec', 'symPar', 'centre', 'activity', 'contract', 'MOSS', 'evidentiaryResourcesMOSS', 'accountingPeriodMOSS', 'note', 'intNote'];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process report
        if (isset($data['statementNumber'])) {
            $data['statementNumber'] = new StatementNumber($data['statementNumber'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        parent::_configureOptions($resolver);

        // validate / format options
        $resolver->setAllowedValues('bankType', ['receipt', 'expense']);
        $resolver->setNormalizer('symVar', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('dateStatement', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('datePayment', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('text', $resolver->getNormalizer('string96'));
        $resolver->setNormalizer('symConst', $resolver->getNormalizer('string4'));
        $resolver->setNormalizer('symSpec', $resolver->getNormalizer('string16'));
        $resolver->setNormalizer('symPar', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('accountingPeriodMOSS', $resolver->getNormalizer('string7'));
    }
}
