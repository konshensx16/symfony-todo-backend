<?php

namespace App\Serializer\Normalizer;

use App\Entity\Note;
use App\Entity\Task;
use App\Entity\TaskList;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PublicDataNormalizer implements NormalizerInterface {

    /**
     * @var ObjectNormalizer
     */
    private $objectNormalizer;

    public function __construct(ObjectNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $context['ignored_attributes'] = ['user'];
        $data = $this->objectNormalizer->normalize($object, $format, $context);

        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Task || $data instanceof Note;
    }
}