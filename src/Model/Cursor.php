<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace SN\HtmlSanitizer\Model;

use SN\HtmlSanitizer\Node\NodeInterface;

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
