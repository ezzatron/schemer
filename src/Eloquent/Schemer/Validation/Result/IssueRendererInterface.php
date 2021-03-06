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

interface IssueRendererInterface
{
    /**
     * @param ValidationIssue $issue
     *
     * @return string
     */
    public function render(ValidationIssue $issue);

    /**
     * @param array<ValidationIssue> $issues
     *
     * @return array<string>
     */
    public function renderMany(array $issues);

    /**
     * @param array<ValidationIssue> $issues
     * @param string|null            $format
     * @param string|null            $glue
     *
     * @return string
     */
    public function renderManyString(
        array $issues,
        $format = null,
        $glue = null
    );
}
