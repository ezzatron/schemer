<?php

/*
 * This file is part of the Schemer package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Schemer\Reader;

use Eloquent\Schemer\Uri\UriInterface;
use Eloquent\Schemer\Value\ValueInterface;

interface ReaderInterface
{
    /**
     * @param UriInterface|string $uri
     * @param string|null         $mimeType
     *
     * @return ValueInterface
     */
    public function read($uri, $mimeType = null);

    /**
     * @param string      $path
     * @param string|null $mimeType
     *
     * @return ValueInterface
     */
    public function readPath($path, $mimeType = null);

    /**
     * @param string      $data
     * @param string|null $mimeType
     *
     * @return ValueInterface
     */
    public function readString($data, $mimeType = null);
}
