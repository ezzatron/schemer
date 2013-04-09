<?php

/*
 * This file is part of the Schemer package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Schemer\Constraint\ObjectValue;

use Eloquent\Schemer\Constraint\ConstraintInterface;
use Eloquent\Schemer\Constraint\ConstraintVisitorInterface;
use Eloquent\Schemer\Constraint\Schema;

class PropertyConstraint implements ConstraintInterface
{
    /**
     * @param string $property
     * @param Schema $schema
     */
    public function __construct($property, Schema $schema)
    {
        $this->property = $property;
        $this->schema = $schema;
    }

    /**
     * @return string
     */
    public function property()
    {
        return $this->property;
    }

    /**
     * @return Schema
     */
    public function schema()
    {
        return $this->schema;
    }

    /**
     * @param ConstraintVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(ConstraintVisitorInterface $visitor)
    {
        return $visitor->visitPropertyConstraint($this);
    }

    private $property;
    private $schema;
}