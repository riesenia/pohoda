<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Pohoda\IntDoc;

use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Document\Header as DocumentHeader;

class Header extends DocumentHeader
{
    /** @var string[] */
    protected $_refElements = ['number', 'accounting', 'classificationVAT', 'classificationKVDPH', 'centre', 'activity', 'contract', 'regVATinEU', 'MOSS', 'evidentiaryResourcesMOSS'];

    /** @var string[] */
    protected $_elements = ['number', 'symVar', 'symPar', 'originalDocumentNumber', 'originalCorrectiveDocument', 'date', 'dateTax', 'dateAccounting', 'dateDelivery', 'dateKVDPH', 'dateKHDPH', 'accounting', 'classificationVAT', 'classificationKVDPH', 'numberKHDPH', 'text', 'partnerIdentity', 'myIdentity', 'liquidation', 'centre', 'activity', 'contract', 'regVATinEU', 'MOSS', 'evidentiaryResourcesMOSS', 'accountingPeriodMOSS', 'note', 'intNote', 'markRecord'];

    protected function _configureOptions(OptionsResolver $resolver)
    {
        parent::_configureOptions($resolver);

        // validate / format options
        $resolver->setNormalizer('symVar', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('symPar', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('date', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateTax', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateAccounting', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateDelivery', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateKVDPH', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('dateKHDPH', $resolver->getNormalizer('date'));
        $resolver->setNormalizer('numberKHDPH', $resolver->getNormalizer('string32'));
        $resolver->setNormalizer('text', $resolver->getNormalizer('string240'));
        $resolver->setNormalizer('liquidation', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('markRecord', $resolver->getNormalizer('bool'));
    }
}
