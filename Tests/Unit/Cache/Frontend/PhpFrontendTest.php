<?php
namespace TYPO3\Flow\Tests\Unit\Cache\Frontend;

/*                                                                        *
 * This script belongs to the Flow framework.                             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the MIT license.                                          *
 *                                                                        */

/**
 * Testcase for the PHP source code cache frontend
 *
 */
class PhpFrontendTest extends \TYPO3\Flow\Tests\UnitTestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @test
     */
    public function setChecksIfTheIdentifierIsValid()
    {
        $cache = $this->getMock(\TYPO3\Flow\Cache\Frontend\StringFrontend::class, array('isValidEntryIdentifier'), array(), '', false);
        $cache->expects($this->once())->method('isValidEntryIdentifier')->with('foo')->will($this->returnValue(false));
        $cache->set('foo', 'bar');
    }

    /**
     * @test
     */
    public function setPassesPhpSourceCodeTagsAndLifetimeToBackend()
    {
        $originalSourceCode = 'return "hello world!";';
        $modifiedSourceCode = '<?php ' . $originalSourceCode . chr(10) . '#';

        $mockBackend = $this->getMock(\TYPO3\Flow\Cache\Backend\PhpCapableBackendInterface::class, array(), array(), '', false);
        $mockBackend->expects($this->once())->method('set')->with('Foo-Bar', $modifiedSourceCode, array('tags'), 1234);

        $cache = $this->getAccessibleMock(\TYPO3\Flow\Cache\Frontend\PhpFrontend::class, array('dummy'), array(), '', false);
        $cache->_set('backend', $mockBackend);
        $cache->set('Foo-Bar', $originalSourceCode, array('tags'), 1234);
    }

    /**
     * @test
     * @expectedException \TYPO3\Flow\Cache\Exception\InvalidDataException
     */
    public function setThrowsInvalidDataExceptionOnNonStringValues()
    {
        $cache = $this->getMock(\TYPO3\Flow\Cache\Frontend\PhpFrontend::class, array('dummy'), array(), '', false);
        $cache->set('Foo-Bar', array());
    }

    /**
     * @test
     */
    public function requireOnceCallsTheBackendsRequireOnceMethod()
    {
        $mockBackend = $this->getMock(\TYPO3\Flow\Cache\Backend\PhpCapableBackendInterface::class, array(), array(), '', false);
        $mockBackend->expects($this->once())->method('requireOnce')->with('Foo-Bar')->will($this->returnValue('hello world!'));

        $cache = $this->getAccessibleMock(\TYPO3\Flow\Cache\Frontend\PhpFrontend::class, array('dummy'), array(), '', false);
        $cache->_set('backend', $mockBackend);

        $result = $cache->requireOnce('Foo-Bar');
        $this->assertSame('hello world!', $result);
    }
}
