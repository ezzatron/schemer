<?php

/*
 * This file is part of the Schemer package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Schemer\Reference;

use Zend\Uri\UriInterface;

class SwitchingScopeReferenceResolverFactory implements ReferenceResolverFactoryInterface
{
    /**
     * @param UriInterface $baseUri
     *
     * @return ReferenceResolver
     */
    public function create(UriInterface $baseUri)
    {
        return new ReferenceResolver(
            $baseUri,
            new SwitchingResolutionScopeMapFactory
        );
    }
}
