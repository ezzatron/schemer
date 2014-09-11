<?php

/*
 * This file is part of the Schemer package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Schemer\Constraint\NumberValue;

use Phake;
use PHPUnit_Framework_TestCase;

class MinimumConstraintTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->minimum = 111;
        $this->constraint = new MinimumConstraint($this->minimum, true);
    }

    public function testConstructor()
    {
        $this->assertSame($this->minimum, $this->constraint->minimum());
        $this->assertTrue($this->constraint->isExclusive());
    }

    public function testConstructorDefaults()
    {
        $this->constraint = new MinimumConstraint($this->minimum);

        $this->assertFalse($this->constraint->isExclusive());
    }

    public function testAccept()
    {
        $visitor = Phake::mock('Eloquent\Schemer\Constraint\Visitor\ConstraintVisitorInterface');
        $this->constraint->accept($visitor);

        Phake::verify($visitor)->visitMinimumConstraint($this->constraint);
    }
}