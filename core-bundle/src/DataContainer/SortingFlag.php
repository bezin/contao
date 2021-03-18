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

class SortingFlag
{
    // Sort by initial letter ascending
    public const INITIAL_LETTER_ASC = 1;

    // Sort by initial letter descending
    public const INITIAL_LETTER_DESC = 2;

    // Sort by initial two letters ascending
    public const INITIAL_TWO_LETTERS_ASC = 3;

    // Sort by initial two letters descending
    public const INITIAL_TWO_LETTERS_DESC = 4;

    // Sort by day ascending
    public const DAY_ASC = 5;

    // Sort by day descending
    public const DAY_DESC = 6;

    // Sort by month ascending
    public const MONTH_ASC = 7;

    // Sort by month descending
    public const MONTH_DESC = 8;

    // Sort by year ascending
    public const YEAR_ASC = 9;

    // Sort by year descending
    public const YEAR_DESC = 10;

    // Sort ascending
    public const ASC = 11;

    // Sort descending
    public const DESC = 12;
}
