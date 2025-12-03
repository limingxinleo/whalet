<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Whalet\Exception;

use RuntimeException;

class RequestException extends RuntimeException
{
    public function __construct(string $message = '', public string $errorCode = '')
    {
        parent::__construct($message);
    }
}
