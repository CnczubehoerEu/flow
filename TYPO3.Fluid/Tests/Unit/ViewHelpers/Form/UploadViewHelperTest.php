<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers\Form;

/*
 * This file is part of the TYPO3.Fluid package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

require_once(__DIR__ . '/Fixtures/EmptySyntaxTreeNode.php');
require_once(__DIR__ . '/Fixtures/Fixture_UserDomainClass.php');
require_once(__DIR__ . '/FormFieldViewHelperBaseTestcase.php');

/**
 * Test for the "Upload" Form view helper
 */
class UploadViewHelperTest extends \TYPO3\Fluid\Tests\Unit\ViewHelpers\Form\FormFieldViewHelperBaseTestcase
{
    /**
     * @var \TYPO3\Fluid\ViewHelpers\Form\UploadViewHelper
     */
    protected $viewHelper;

    /**
     * @var \TYPO3\Flow\Property\PropertyMapper
     */
    protected $mockPropertyMapper;

    /**
     * @var \TYPO3\Flow\Error\Result
     */
    protected $mockMappingResult;

    public function setUp()
    {
        parent::setUp();
        $this->viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\Form\UploadViewHelper', array('setErrorClassAttribute', 'registerFieldNameForFormTokenGeneration', 'getValue', 'getMappingResultsForProperty'));
        $this->mockMappingResult = $this->getMock('TYPO3\Flow\Error\Result');
        $this->viewHelper->expects($this->any())->method('getMappingResultsForProperty')->will($this->returnValue($this->mockMappingResult));
        $this->mockPropertyMapper = $this->getMock('TYPO3\Flow\Property\PropertyMapper');
        $this->viewHelper->_set('propertyMapper', $this->mockPropertyMapper);
        $this->arguments['name'] = '';
        $this->injectDependenciesIntoViewHelper($this->viewHelper);
        $this->viewHelper->initializeArguments();
    }

    /**
     * @test
     */
    public function renderCorrectlySetsTagName()
    {
        $this->tagBuilder->expects($this->once())->method('setTagName')->with('input');

        $this->viewHelper->initialize();
        $this->viewHelper->render();
    }

    /**
     * @test
     */
    public function renderCorrectlySetsTypeNameAndValueAttributes()
    {
        $mockTagBuilder = $this->getMock('TYPO3\Fluid\Core\ViewHelper\TagBuilder', array('addAttribute', 'setContent', 'render'), array(), '', false);
        $mockTagBuilder->expects($this->at(0))->method('addAttribute')->with('type', 'file');
        $mockTagBuilder->expects($this->at(1))->method('addAttribute')->with('name', 'someName');
        $this->viewHelper->expects($this->once())->method('registerFieldNameForFormTokenGeneration')->with('someName');
        $mockTagBuilder->expects($this->once())->method('render');
        $this->viewHelper->injectTagBuilder($mockTagBuilder);

        $arguments = array(
            'name' => 'someName'
        );

        $this->viewHelper->setArguments($arguments);
        $this->viewHelper->setViewHelperNode(new \TYPO3\Fluid\ViewHelpers\Fixtures\EmptySyntaxTreeNode());
        $this->viewHelper->initialize();
        $this->viewHelper->render();
    }

    /**
     * @test
     */
    public function renderCallsSetErrorClassAttribute()
    {
        $this->viewHelper->expects($this->once())->method('setErrorClassAttribute');
        $this->viewHelper->render();
    }

    /**
     * @test
     */
    public function hiddenFieldsAreNotRenderedByDefault()
    {
        $expectedResult = '';
        $this->viewHelper->initialize();
        $actualResult = $this->viewHelper->render();
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @test
     */
    public function hiddenFieldsContainDataOfTheSpecifiedResource()
    {
        $mockResource = $this->getMock('TYPO3\Flow\Resource\Resource');
        $mockResource->expects($this->any())->method('getFilename')->will($this->returnValue('theResourceFilename'));
        $mockResource->expects($this->any())->method('getResourcePointer')->will($this->returnValue('theResourcePointer'));

        $this->viewHelper->expects($this->atLeastOnce())->method('getValue')->will($this->returnValue($mockResource));

        $expectedResult = '<input type="hidden" name="[submittedFile][filename]" value="theResourceFilename" /><input type="hidden" name="[submittedFile][resourcePointer]" value="theResourcePointer" />';
        $this->viewHelper->initialize();
        $actualResult = $this->viewHelper->render();
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @test
     */
    public function hiddenFieldsAreNotRenderedIfPropertyMappingErrorsOccurred()
    {
        $mockResource = $this->getMock('TYPO3\Flow\Resource\Resource');
        $mockResource->expects($this->any())->method('getFilename')->will($this->returnValue('theResourceFilename'));
        $mockResource->expects($this->any())->method('getResourcePointer')->will($this->returnValue('theResourcePointer'));

        $this->viewHelper->expects($this->any())->method('getValue')->will($this->returnValue($mockResource));

        $this->mockMappingResult->expects($this->atLeastOnce())->method('hasErrors')->will($this->returnValue(true));

        $expectedResult = '';
        $this->viewHelper->initialize();
        $actualResult = $this->viewHelper->render();
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @test
     */
    public function hiddenFieldsContainDataOfAPreviouslyUploadedResource()
    {
        $mockResource = $this->getMock('TYPO3\Flow\Resource\Resource');
        $mockResource->expects($this->any())->method('getFilename')->will($this->returnValue('theResourceFilename'));
        $mockResource->expects($this->any())->method('getResourcePointer')->will($this->returnValue('theResourcePointer'));

        $resourceData = array(
            'error' => \UPLOAD_ERR_NO_FILE,
            'submittedFile' => array('filename' => 'theResourceFilename', 'resourcePointer' => 'theResourcePointer')
        );
        $this->mockPropertyMapper->expects($this->atLeastOnce())->method('convert')->with($resourceData, 'TYPO3\Flow\Resource\Resource')->will($this->returnValue($mockResource));

        $this->viewHelper->expects($this->atLeastOnce())->method('getValue')->will($this->returnValue($resourceData));

        $expectedResult = '<input type="hidden" name="[submittedFile][filename]" value="theResourceFilename" /><input type="hidden" name="[submittedFile][resourcePointer]" value="theResourcePointer" />';
        $this->viewHelper->initialize();
        $actualResult = $this->viewHelper->render();
        $this->assertSame($expectedResult, $actualResult);
    }
}