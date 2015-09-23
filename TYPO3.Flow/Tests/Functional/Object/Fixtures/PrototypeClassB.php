<?php
namespace TYPO3\Flow\Tests\Functional\Object\Fixtures;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A class of scope prototype (but without explicit scope annotation)
 */
class PrototypeClassB
{
    /**
     * @var string
     */
    protected $someProperty;

    /**
     * @param string $someProperty
     * @return void
     */
    public function setSomeProperty($someProperty)
    {
        $this->someProperty = $someProperty;
    }

    /**
     * @return string
     */
    public function getSomeProperty()
    {
        return $this->someProperty;
    }
}