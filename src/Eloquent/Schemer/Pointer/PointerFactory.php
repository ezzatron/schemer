<?php

/*
 * This file is part of the Schemer package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Schemer\Pointer;

use Eloquent\Schemer\Uri\UriInterface;

class PointerFactory implements PointerFactoryInterface
{
    /**
     * @param string|null $pointer
     *
     * @return PointerInterface
     */
    public function create($pointer = null)
    {
        if (null === $pointer) {
            return new Pointer;
        }

        return new Pointer($this->parseAtoms($pointer));
    }

    /**
     * @param UriInterface $uri
     *
     * @return PointerInterface
     */
    public function createFromUri(UriInterface $uri)
    {
        $pointer = $uri->getFragment();
        if (null === $pointer || '/' !== substr($pointer, 0, 1)) {
            return new Pointer;
        }

        return $this->create($pointer);
    }

    /**
     * @param string $pointer
     *
     * @return array<string>
     */
    protected function parseAtoms($pointer)
    {
        $atoms = explode('/', $pointer);

        if ('' !== $atoms[0]) {
            throw new Exception\InvalidPointerException($pointer);
        }
        array_shift($atoms);

        foreach ($atoms as $index => $atom) {
            $atoms[$index] = $this->parseAtom($atom);
        }

        return $atoms;
    }

    /**
     * @param string $atom
     *
     * @return string
     */
    protected function parseAtom($pointer)
    {
        return strtr($pointer, array('~0' => '~', '~1' => '/'));
    }
}
