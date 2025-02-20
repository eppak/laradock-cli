<?php namespace Eppak\Exceptions;

use Exception;
use Throwable;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

class FileNotFoundException extends Exception
{
    public function __construct($file, $code = 0, Throwable $previous = null)
    {
        parent::__construct("File {$file} NOT FOUND", $code, $previous);
    }
}
