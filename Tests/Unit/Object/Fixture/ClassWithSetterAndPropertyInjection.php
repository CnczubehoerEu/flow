<?php
namespace TYPO3\Flow\Tests\Object\Fixture;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class ClassWithSetterAndPropertyInjection
{
    /**
     * @var \TYPO3\Foo\Bar
     * @Flow\Inject
     */
    protected $firstDependency;

    /**
     * @var \TYPO3\Coffee\Bar
     * @Flow\Inject
     */
    protected $secondDependency;

    /**
     * @param \TYPO3\Flow\Object\ObjectManagerInterface
     */
    public function injectFirstDependency(\TYPO3\Flow\Object\ObjectManagerInterface $firstDependency)
    {
        $this->firstDependency = $firstDependency;
    }
}