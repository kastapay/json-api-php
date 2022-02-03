<?php

/*
 * This file is part of JSON-API.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tobscure\JsonApi\Exception\Handler;

use Throwable;

class FallbackExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @param bool $debug
     */
    public function __construct($debug)
    {
        $this->debug = $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function manages(Throwable $e)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Throwable $e)
    {
        $status = 500;
        $error = $this->constructError($e, $status);

        return new ResponseBag($status, [$error]);
    }

    /**
     * @param \Throwable $e
     * @param $status
     *
     * @return array
     */
    private function constructError(Throwable $e, $status)
    {
        $error = ['code' => $status, 'title' => 'Internal server error'];

        if ($this->debug) {
            $error['detail'] = (string) $e;
        }

        return $error;
    }
}
