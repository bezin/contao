<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\DataContainer;

class SortingMode
{
    // Records are not sorted
    public const UNSORTED = 0;

    // Records are sorted by a fixed field
    public const FIXED_FIELD = 1;

    // Records are sorted by a variable field
    public const VARIABLE_FIELD = 2;

    // Records are sorted by the parent table
    public const PARENT_TABLE = 3;

    // Displays the child records of a parent record (see style sheets module)
    public const CHILD_RECORD = 4;

    // Records are displayed as tree (see site structure)
    public const TREE = 5;

    // Displays the child records within a tree structure (see articles module)
    public const CHILD_TREE = 6;
}
