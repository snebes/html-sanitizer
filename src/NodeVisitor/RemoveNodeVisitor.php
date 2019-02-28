<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\HtmlSanitizer\NodeVisitor;

use SN\HtmlSanitizer\Model\Cursor;

class RemoveNodeVisitor extends TagNodeVisitor
{
    /**
     * @param \DOMNode $domNode
     * @param Cursor   $cursor
     */
    public function enterNode(\DOMNode $domNode, Cursor $cursor)
    {
        while ($domNode->hasChildNodes()) {
            $domNode->removeChild($domNode->childNodes[0]);
        }
    }

    /**
     * @param \DOMNode $domNode
     * @param Cursor   $cursor
     */
    public function leaveNode(\DOMNode $domNode, Cursor $cursor)
    {
    }
}
