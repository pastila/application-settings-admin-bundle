<?php

namespace AppBundle\DataAdapter\Geo;

use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelAdapterInterface;
use AppBundle\Entity\Geo\Region;

/**
 * @SuppressWarnings(PHPMD)
 */
class RegionDataAdapter implements ClientApplicationModelAdapterInterface
{
    /**
     * @param Region $subject
     * @param array $options
     *
     * @return array
     */
    public function transform($subject, $options = [])
    {
        return [
            'id' => $subject->getId(),
            'name' => $subject->getName(),
        ];
    }

    public function getModelName()
    {
        return 'Region';
    }

    public function supports($subject)
    {
        return $subject instanceof Region;
    }

    public function getName()
    {
        return 'region';
    }
}
