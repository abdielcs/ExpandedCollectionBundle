<?php

/*
 * This file is part of the ExpandedCollectionBundle.
 *
 * (c) Abdiel Carrazana <abdielcs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace abdielcs\ExpandedCollectionBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

/**
 *  Transformer for converting between middle class and target class instances.
 *
 * @author Abdielcs Carrazana <abdielcs@gmail.com>
 */
class MiddleClassTransformer implements DataTransformerInterface
{

    private $propertyClass;

    private $middleClass;

    public function __construct($propertyClass, $middleClass)
    {
        $this->propertyClass = $propertyClass;
        $this->middleClass = $middleClass;
    }

    /**
     * Transform middle instances to target instances
     *
     * @param mixed $collection
     * @return array|ArrayCollection
     */
    public function transform($collection)
    {
        if($collection != null){
            $result = new ArrayCollection();

            $propertyRef = new \ReflectionClass($this->propertyClass);
            $middleRef = new \ReflectionClass($this->middleClass);
            $getMethod = 'get' . $propertyRef->getShortName();
            if ($middleRef->hasMethod($getMethod)) {
                foreach($collection as $item){
                    $property = $item->$getMethod($item);
                    $result[] = $property;
                }
            }
            $collection = $result;
        }


        return $collection;
    }

    /**
     * Transform target instances to middle instances
     *
     * @param mixed $collection
     * @return array|ArrayCollection
     */
    public function reverseTransform($collection)
    {
        $result = new ArrayCollection();

        $propertyRef = new \ReflectionClass($this->propertyClass);
        $middleRef = new \ReflectionClass($this->middleClass);
        $setMethod = 'set' . $propertyRef->getShortName();
        $getId = 'getId';
        if ($middleRef->hasMethod($setMethod)) {
            foreach($collection as $item){
                $middle = $middleRef->newInstance();
                $middle->$setMethod($item);
                $result[] = $middle;
            }
        }
        return $result;
    }
}