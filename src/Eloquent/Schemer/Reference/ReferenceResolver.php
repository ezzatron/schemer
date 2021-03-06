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

use Eloquent\Schemer\Pointer\PointerFactory;
use Eloquent\Schemer\Pointer\PointerFactoryInterface;
use Eloquent\Schemer\Pointer\Resolver\PointerResolver;
use Eloquent\Schemer\Pointer\Resolver\PointerResolverInterface;
use Eloquent\Schemer\Reader\Reader;
use Eloquent\Schemer\Reader\ReaderInterface;
use Eloquent\Schemer\Uri\Resolver\UriResolver;
use Eloquent\Schemer\Uri\Resolver\UriResolverInterface;
use Eloquent\Schemer\Uri\UriFactory;
use Eloquent\Schemer\Uri\UriFactoryInterface;
use Eloquent\Schemer\Uri\UriInterface;
use Eloquent\Schemer\Value;

class ReferenceResolver extends Value\Transform\AbstractValueTransform
{
    /**
     * @param UriInterface                            $baseUri
     * @param ResolutionScopeMapFactoryInterface|null $scopeMapFactory
     * @param UriResolverInterface|null               $uriResolver
     * @param ReaderInterface|null                    $reader
     * @param UriFactoryInterface|null                $uriFactory
     * @param PointerFactoryInterface|null            $pointerFactory
     * @param PointerResolverInterface|null           $pointerResolver
     * @param Value\ValueTransformInterface|null      $placeholderUnwrap
     */
    public function __construct(
        UriInterface $baseUri,
        ResolutionScopeMapFactoryInterface $scopeMapFactory = null,
        UriResolverInterface $uriResolver = null,
        ReaderInterface $reader = null,
        UriFactoryInterface $uriFactory = null,
        PointerFactoryInterface $pointerFactory = null,
        PointerResolverInterface $pointerResolver = null,
        Value\ValueTransformInterface $placeholderUnwrap = null
    ) {
        parent::__construct();

        if (null === $scopeMapFactory) {
            $scopeMapFactory = new FixedResolutionScopeMapFactory;
        }
        if (null === $uriResolver) {
            $uriResolver = new UriResolver;
        }
        if (null === $reader) {
            $reader = new Reader;
        }
        if (null === $uriFactory) {
            $uriFactory = new UriFactory;
        }
        if (null === $pointerFactory) {
            $pointerFactory = new PointerFactory;
        }
        if (null === $pointerResolver) {
            $pointerResolver = new PointerResolver;
        }
        if (null === $placeholderUnwrap) {
            $placeholderUnwrap = new Value\Transform\PlaceholderUnwrapTransform;
        }

        $this->baseUri = $baseUri;
        $this->scopeMapFactory = $scopeMapFactory;
        $this->uriResolver = $uriResolver;
        $this->reader = $reader;
        $this->uriFactory = $uriFactory;
        $this->pointerFactory = $pointerFactory;
        $this->pointerResolver = $pointerResolver;
        $this->placeholderUnwrap = $placeholderUnwrap;
    }

    /**
     * @return UriInterface
     */
    public function baseUri()
    {
        return $this->baseUri;
    }

    /**
     * @return ResolutionScopeMapFactoryInterface
     */
    public function scopeMapFactory()
    {
        return $this->scopeMapFactory;
    }

    /**
     * @return UriResolverInterface
     */
    public function uriResolver()
    {
        return $this->uriResolver;
    }

    /**
     * @return Reader
     */
    public function reader()
    {
        return $this->reader;
    }

    /**
     * @return UriFactoryInterface
     */
    public function uriFactory()
    {
        return $this->uriFactory;
    }

    /**
     * @return PointerFactoryInterface
     */
    public function pointerFactory()
    {
        return $this->pointerFactory;
    }

    /**
     * @return PointerResolverInterface
     */
    public function pointerResolver()
    {
        return $this->pointerResolver;
    }

    /**
     * @return Value\ValueTransformInterface
     */
    public function placeholderUnwrap()
    {
        return $this->placeholderUnwrap;
    }

    /**
     * @param Value\ValueInterface $value
     *
     * @return Value\ConcreteValueInterface
     */
    public function transform(Value\ValueInterface $value)
    {
        return $this->placeholderUnwrap()->transform(parent::transform($value));
    }

    /**
     * @param Value\ReferenceValue $reference
     *
     * @return Value\PlaceholderValue
     * @throws Exception\UndefinedReferenceException
     */
    public function visitReferenceValue(Value\ReferenceValue $reference)
    {
        $referenceUri = $this->uriResolver()->resolve(
            $reference->uri(),
            $this->currentBaseUri()
        );

        $resolution = $this->resolution($referenceUri);
        if (null !== $resolution) {
            return $resolution;
        }
        $resolution = $this->startResolution($referenceUri);

        if (!$value = $this->resolveInline($referenceUri, $reference)) {
            $value = $this->resolveExternal($referenceUri, $reference);
        }

        $this->completeResolution($referenceUri, $value);

        return $resolution;
    }

    /**
     * @param UriInterface         $referenceUri
     * @param Value\ReferenceValue $reference
     *
     * @return Value\ValueInterface|null
     * @throws Exception\UndefinedReferenceException
     */
    protected function resolveInline(
        UriInterface $referenceUri,
        Value\ReferenceValue $reference
    ) {
        $scopeMap = $pointer = null;
        foreach (array_reverse($this->scopeMapStack()) as $scopeMap) {
            $pointer = $scopeMap->pointerByUri($referenceUri);
            if (null !== $pointer) {
                break;
            }
        }
        if (null === $pointer) {
            return null;
        }

        $this->pushScopeMap($scopeMap);
        $value = $scopeMap->value()->accept($this);
        $this->popScopeMap();

        $value = $this->pointerResolver()->resolve($pointer, $value);
        if (null === $value) {
            throw new Exception\UndefinedReferenceException(
                $reference,
                $this->currentBaseUri()
            );
        }

        return $value;
    }

    /**
     * @param UriInterface         $referenceUri
     * @param Value\ReferenceValue $reference
     *
     * @return Value\ValueInterface
     * @throws Exception\UndefinedReferenceException
     */
    protected function resolveExternal(
        UriInterface $referenceUri,
        Value\ReferenceValue $reference
    ) {
        try {
            $value = $this->reader()->read(
                $this->uriFactory()->create(
                    $referenceUri->toString()
                ),
                $reference->mimeType()
            );
        } catch (ReadException $e) {
            throw new Exception\UndefinedReferenceException(
                $reference,
                $this->currentBaseUri(),
                $e
            );
        }

        $this->pushScopeMap(
            $this->scopeMapFactory()->create($referenceUri, $value)
        );
        $value = $value->accept($this);
        $this->popScopeMap();

        $referencePointer = $this->pointerFactory()->createFromUri(
            $referenceUri
        );
        if ($referencePointer->hasAtoms()) {
            $value = $this->pointerResolver()->resolve(
                $referencePointer,
                $value
            );

            if (null === $value) {
                throw new Exception\UndefinedReferenceException(
                    $reference,
                    $this->currentBaseUri()
                );
            }
        }

        return $value;
    }

    protected function clear()
    {
        parent::clear();

        $this->scopeMapStack = array();
        $this->resolutions = array();
    }

    protected function initialize(Value\ValueInterface $value)
    {
        parent::initialize($value);

        $this->pushScopeMap(
            $this->scopeMapFactory()->create(
                $this->uriFactory()->createGeneric(
                    $this->baseUri()->toString()
                ),
                $this->value()
            )
        );
    }

    /**
     * @param ResolutionScopeMap $scopeMap
     */
    protected function pushScopeMap(ResolutionScopeMap $scopeMap)
    {
        array_push($this->scopeMapStack, $scopeMap);
    }

    protected function popScopeMap()
    {
        array_pop($this->scopeMapStack);
    }

    /**
     * @return ResolutionScopeMap
     */
    protected function currentScopeMap()
    {
        return $this->scopeMapStack[count($this->scopeMapStack) - 1];
    }

    /**
     * @return array<ResolutionScopeMap>
     */
    protected function scopeMapStack()
    {
        return $this->scopeMapStack;
    }

    /**
     * @return UriInterface
     */
    protected function currentBaseUri()
    {
        return $this
            ->currentScopeMap()
            ->uriByPointer($this->pointerFactory()->create());
    }

    /**
     * @param UriInterface $referenceUri
     *
     * @return Value\PlaceholderValue
     */
    protected function startResolution(UriInterface $referenceUri)
    {
        $resolution = new Value\PlaceholderValue;
        $this->resolutions[$referenceUri->toString()] = $resolution;

        return $resolution;
    }

    /**
     * @param UriInterface         $referenceUri
     * @param Value\ValueInterface $value
     */
    protected function completeResolution(
        UriInterface $referenceUri,
        Value\ValueInterface $value
    ) {
        $this->resolution($referenceUri)->setInnerValue($value);
    }

    /**
     * @param UriInterface $referenceUri
     *
     * @return Value\ValueInterface|null
     */
    protected function resolution(UriInterface $referenceUri)
    {
        $key = $referenceUri->toString();
        if (array_key_exists($key, $this->resolutions)) {
            return $this->resolutions[$key];
        }

        return null;
    }

    private $baseUri;
    private $scopeMapFactory;
    private $uriResolver;
    private $reader;
    private $uriFactory;
    private $pointerFactory;
    private $pointerResolver;
    private $placeholderUnwrap;

    private $scopeMapStack;
    private $resolutions;
}
