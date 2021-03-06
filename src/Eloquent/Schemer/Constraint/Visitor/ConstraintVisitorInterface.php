<?php

/*
 * This file is part of the Schemer package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Schemer\Constraint\Visitor;

use Eloquent\Schemer\Constraint\ArrayValue;
use Eloquent\Schemer\Constraint\DateTimeValue;
use Eloquent\Schemer\Constraint\Generic;
use Eloquent\Schemer\Constraint\NumberValue;
use Eloquent\Schemer\Constraint\ObjectValue;
use Eloquent\Schemer\Constraint\Schema;
use Eloquent\Schemer\Constraint\StringValue;
use Eloquent\Schemer\Constraint\Visitor\ConstraintVisitorInterface;

interface ConstraintVisitorInterface
{
    /**
     * @param Schema $schema
     *
     * @return mixed
     */
    public function visitSchema(Schema $schema);

    // generic constraints =====================================================

    /**
     * @param Generic\EnumConstraint $constraint
     *
     * @return mixed
     */
    public function visitEnumConstraint(Generic\EnumConstraint $constraint);

    /**
     * @param Generic\TypeConstraint $constraint
     *
     * @return mixed
     */
    public function visitTypeConstraint(Generic\TypeConstraint $constraint);

    /**
     * @param Generic\AllOfConstraint $constraint
     *
     * @return mixed
     */
    public function visitAllOfConstraint(Generic\AllOfConstraint $constraint);

    /**
     * @param Generic\AnyOfConstraint $constraint
     *
     * @return mixed
     */
    public function visitAnyOfConstraint(Generic\AnyOfConstraint $constraint);

    /**
     * @param Generic\OneOfConstraint $constraint
     *
     * @return mixed
     */
    public function visitOneOfConstraint(Generic\OneOfConstraint $constraint);

    /**
     * @param Generic\NotConstraint $constraint
     *
     * @return mixed
     */
    public function visitNotConstraint(Generic\NotConstraint $constraint);

    // object constraints ======================================================

    /**
     * @param ObjectValue\MaximumPropertiesConstraint $constraint
     *
     * @return mixed
     */
    public function visitMaximumPropertiesConstraint(ObjectValue\MaximumPropertiesConstraint $constraint);

    /**
     * @param ObjectValue\MinimumPropertiesConstraint $constraint
     *
     * @return mixed
     */
    public function visitMinimumPropertiesConstraint(ObjectValue\MinimumPropertiesConstraint $constraint);

    /**
     * @param ObjectValue\RequiredConstraint $constraint
     *
     * @return mixed
     */
    public function visitRequiredConstraint(ObjectValue\RequiredConstraint $constraint);

    /**
     * @param ObjectValue\PropertiesConstraint $constraint
     *
     * @return mixed
     */
    public function visitPropertiesConstraint(ObjectValue\PropertiesConstraint $constraint);

    /**
     * @param ObjectValue\AdditionalPropertyConstraint $constraint
     *
     * @return mixed
     */
    public function visitAdditionalPropertyConstraint(ObjectValue\AdditionalPropertyConstraint $constraint);

    /**
     * @param ObjectValue\DependencyConstraint $constraint
     *
     * @return mixed
     */
    public function visitDependencyConstraint(ObjectValue\DependencyConstraint $constraint);

    // array constraints =======================================================

    /**
     * @param ArrayValue\ItemsConstraint $constraint
     *
     * @return mixed
     */
    public function visitItemsConstraint(ArrayValue\ItemsConstraint $constraint);

    /**
     * @param ArrayValue\AdditionalItemConstraint $constraint
     *
     * @return mixed
     */
    public function visitAdditionalItemConstraint(ArrayValue\AdditionalItemConstraint $constraint);

    /**
     * @param ArrayValue\MaximumItemsConstraint $constraint
     *
     * @return mixed
     */
    public function visitMaximumItemsConstraint(ArrayValue\MaximumItemsConstraint $constraint);

    /**
     * @param ArrayValue\MinimumItemsConstraint $constraint
     *
     * @return mixed
     */
    public function visitMinimumItemsConstraint(ArrayValue\MinimumItemsConstraint $constraint);

    /**
     * @param ArrayValue\UniqueItemsConstraint $constraint
     *
     * @return mixed
     */
    public function visitUniqueItemsConstraint(ArrayValue\UniqueItemsConstraint $constraint);

    // string constraints ======================================================

    /**
     * @param StringValue\PatternConstraint $constraint
     *
     * @return mixed
     */
    public function visitPatternConstraint(StringValue\PatternConstraint $constraint);

    /**
     * @param StringValue\MaximumLengthConstraint $constraint
     *
     * @return mixed
     */
    public function visitMaximumLengthConstraint(StringValue\MaximumLengthConstraint $constraint);

    /**
     * @param StringValue\MinimumLengthConstraint $constraint
     *
     * @return mixed
     */
    public function visitMinimumLengthConstraint(StringValue\MinimumLengthConstraint $constraint);

    /**
     * @param StringValue\DateTimeFormatConstraint $constraint
     *
     * @return mixed
     */
    public function visitDateTimeFormatConstraint(StringValue\DateTimeFormatConstraint $constraint);

    /**
     * @param StringValue\EmailFormatConstraint $constraint
     *
     * @return mixed
     */
    public function visitEmailFormatConstraint(StringValue\EmailFormatConstraint $constraint);

    /**
     * @param StringValue\HostnameFormatConstraint $constraint
     *
     * @return mixed
     */
    public function visitHostnameFormatConstraint(StringValue\HostnameFormatConstraint $constraint);

    /**
     * @param StringValue\Ipv4AddressFormatConstraint $constraint
     *
     * @return mixed
     */
    public function visitIpv4AddressFormatConstraint(StringValue\Ipv4AddressFormatConstraint $constraint);

    /**
     * @param StringValue\Ipv6AddressFormatConstraint $constraint
     *
     * @return mixed
     */
    public function visitIpv6AddressFormatConstraint(StringValue\Ipv6AddressFormatConstraint $constraint);

    /**
     * @param StringValue\UriFormatConstraint $constraint
     *
     * @return mixed
     */
    public function visitUriFormatConstraint(StringValue\UriFormatConstraint $constraint);

    // number constraints ======================================================

    /**
     * @param NumberValue\MultipleOfConstraint $constraint
     *
     * @return mixed
     */
    public function visitMultipleOfConstraint(NumberValue\MultipleOfConstraint $constraint);

    /**
     * @param NumberValue\MaximumConstraint $constraint
     *
     * @return mixed
     */
    public function visitMaximumConstraint(NumberValue\MaximumConstraint $constraint);

    /**
     * @param NumberValue\MinimumConstraint $constraint
     *
     * @return mixed
     */
    public function visitMinimumConstraint(NumberValue\MinimumConstraint $constraint);

    // date-time constraints ===================================================

    /**
     * @param DateTimeValue\MaximumDateTimeConstraint $constraint
     *
     * @return mixed
     */
    public function visitMaximumDateTimeConstraint(DateTimeValue\MaximumDateTimeConstraint $constraint);

    /**
     * @param DateTimeValue\MinimumDateTimeConstraint $constraint
     *
     * @return mixed
     */
    public function visitMinimumDateTimeConstraint(DateTimeValue\MinimumDateTimeConstraint $constraint);
}
