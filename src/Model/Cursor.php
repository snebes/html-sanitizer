<?php

/*
 * This file is part of the HTML sanitizer project.
 *
 * (c) Steve Nebes <snebes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HtmlSanitizer\Model;

use HtmlSanitizer\Node\NodeInterface;

/**
 * @author Steve Nebes <snebes@gmail.com>
 *
 * @final
 */
class Cursor
{
    /**
     * @var NodeInterface
     */
    public $node;
}
