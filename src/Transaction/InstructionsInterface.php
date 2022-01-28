<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction;

/**
 * An interface containing all instruction constants.
 */
interface InstructionsInterface
{
    public const INSTRUCTION_VOID = 'void';
    public const INSTRUCTION_ABORT = 'abort';
    public const INSTRUCTION_RELEASE = 'release';
}
