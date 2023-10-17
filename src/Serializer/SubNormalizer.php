<?php

namespace App\Serializer;

use App\Entity\Sub;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SubNormalizer implements NormalizerInterface
{

    // private UrlGeneratorInterface $router;
    // private ObjectNormalizer $normalizer;

    // public function __construct(
    //     UrlGeneratorInterface $router,
    //     ObjectNormalizer $normalizer,
    // ) {
    //     $this->router = $router;
    //     $this->normalizer = $normalizer;
    // }

    /**
     * @param Subscription $object
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|null
     */

    public function normalize($object, string $format = null, array $context = []): array
    {
        $returnData = [];
        $returnData["type"] = "sub";
        $returnData["id"] = $object->getId();
        $returnData["attributes"] = [
            "name" => $object->getName(),
            "startDate" => $object->getStartDate(),
        ];
        $returnData["links"] = [
            "self" => "",
        ];
        $returnData["relationships"] = [
            "pay" => [
                "links" => [
                    "related" => ""
                ]
            ]
        ];

        return $returnData;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Sub;
    }
}
