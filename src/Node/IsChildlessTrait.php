<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace SN\HtmlSanitizer\Node;

/**
 * Used by nodes which can't have children.
 *
 * @author Steve Nebes <snebes@gmail.com>
 */
trait IsChildlessTrait
{
    public function addChild(NodeInterface $child)
    {
    }
}
