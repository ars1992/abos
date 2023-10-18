<?php

namespace App\Serializer;

use App\Entity\Sub;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SubNormalizer implements NormalizerInterface
{
    public function __construct(
        private UrlGeneratorInterface $router,
    ) {
    }

    /**
     * @param Sub $object
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
            "self" => $this->router->generate("readSub", ["id" => $object->getId()]),
        ];

        $this->createRelationLinks($object, $returnData);

        return $returnData;
    }

    private function createRelationLinks(Sub $object, array &$returnData): void
    {
        if ($object->getPay()) {
            $returnData["relationships"] = [
                "pay" => [
                    "links" => [
                        "related" => $this->router->generate("readPayType", ["id" => $object->getPay()->getId()])
                    ]
                ]
            ];
        } else {
            $returnData["relationships"] = [
                "pay" => [
                    "links" => [
                        "related" => null
                    ]
                ]
            ];
        }
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Sub;
    }
}
