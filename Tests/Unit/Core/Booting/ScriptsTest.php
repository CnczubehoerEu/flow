<?php
namespace TYPO3\Flow\Tests\Unit\Core\Booting;

/*
 * This file is part of the TYPO3.Flow package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

/**
 * Testcase for the initialization scripts
 *
 */
class ScriptsTest extends \TYPO3\Flow\Tests\UnitTestCase
{
    /**
     * @test
     */
    public function subProcessCommandEvaluatesIniFileUsageSettingCorrectly()
    {
        $scriptsMock = $this->getAccessibleMock('TYPO3\Flow\Core\Booting\Scripts', array('dummy'));
        $settings = array('core' => array(
            'context' => 'Testing',
            'phpBinaryPathAndFilename' => '/foo/var/php'
        ));

        $message = 'The command must contain the current ini because it is not explicitly set in settings.';
        $actual = $scriptsMock->_call('buildSubprocessCommand', 'flow:foo:identifier', $settings);
        $this->assertContains(sprintf(' -c %s ', escapeshellarg(php_ini_loaded_file())), $actual, $message);

        $settings['core']['subRequestPhpIniPathAndFilename'] = null;
        $message = 'The command must contain the current ini because it is explicitly set, but NULL, in settings.';
        $actual = $scriptsMock->_call('buildSubprocessCommand', 'flow:foo:identifier', $settings);
        $this->assertContains(sprintf(' -c %s ', escapeshellarg(php_ini_loaded_file())), $actual, $message);

        $settings['core']['subRequestPhpIniPathAndFilename'] = '/foo/ini/path';
        $message = 'The command must contain a specified ini file path because it is set in settings.';
        $actual = $scriptsMock->_call('buildSubprocessCommand', 'flow:foo:identifier', $settings);
        $this->assertContains(sprintf(' -c %s ', escapeshellarg('/foo/ini/path')), $actual, $message);

        $settings['core']['subRequestPhpIniPathAndFilename'] = false;
        $message = 'The command must not contain an ini file path because it is set to FALSE in settings.';
        $actual = $scriptsMock->_call('buildSubprocessCommand', 'flow:foo:identifier', $settings);
        $this->assertNotContains(' -c ', $actual, $message);
    }
}
