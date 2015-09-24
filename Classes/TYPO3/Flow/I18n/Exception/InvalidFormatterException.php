<?php
namespace TYPO3\Flow\I18n\Exception;

/*                                                                        *
 * This script belongs to the Flow framework.                             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the MIT license.                                          *
 *                                                                        */

/**
 * The "Unsatisfactory Formatter" exception
 *
 * Thrown when the I18n's FormatResolver was able to retrieve a formatter at all,
 * but did not satisfy (i.e. implement) the FormatterInterface.
 *
 * @api
 */
class InvalidFormatterException extends \TYPO3\Flow\I18n\Exception
{
}
