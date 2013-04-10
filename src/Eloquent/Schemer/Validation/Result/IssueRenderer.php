<?php

/*
 * This file is part of the Schemer package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Schemer\Validation\Result;

use Eloquent\Schemer\Constraint\ConstraintVisitorInterface;
use Eloquent\Schemer\Constraint\Renderer\ConstraintFailureRenderer;

class IssueRenderer implements IssueRendererInterface
{
    /**
     * @param ConstraintVisitorInterface|null $constraintRenderer
     */
    public function __construct(
        ConstraintVisitorInterface $constraintRenderer = null
    ) {
        if (null === $constraintRenderer) {
            $constraintRenderer = new ConstraintFailureRenderer;
        }

        $this->constraintRenderer = $constraintRenderer;
    }

    /**
     * @return ConstraintVisitorInterface
     */
    public function constraintRenderer()
    {
        return $this->constraintRenderer;
    }

    /**
     * @param ValidationIssue $issue
     *
     * @return string
     */
    public function render(ValidationIssue $issue)
    {
        if ($issue->pointer()->hasAtoms()) {
            return sprintf(
                "Validation failed for value at '%s': %s",
                $issue->pointer()->string(),
                $issue->constraint()->accept($this->constraintRenderer())
            );
        }

        return sprintf(
            "Validation failed for value at document root: %s",
            $issue->constraint()->accept($this->constraintRenderer())
        );
    }

    /**
     * @param array<ValidationIssue> $issues
     *
     * @return array<string>
     */
    public function renderMany(array $issues)
    {
        $self = $this;

        return array_map(function (ValidationIssue $issue) use ($self) {
            return $self->render($issue);
        }, $issues);
    }

    private $constraintRenderer;
}
