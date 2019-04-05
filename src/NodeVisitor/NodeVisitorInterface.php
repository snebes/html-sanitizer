<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace SN\HtmlSanitizer\NodeVisitor;

use DOMNode;
use SN\HtmlSanitizer\Model\Cursor;

/**
 * A visitor visit supported DOM nodes to decide whether and how to include them in the final output.
 *
 * @author Steve Nebes <snebes@gmail.com>
 */
interface NodeVisitorInterface
{
    /**
     * @return array
     */
    public function getSupportedNodeNames(): array;

    /**
     * Whether this visitor supports the DOM node or not in the current context.
     *
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     *
     * @return bool
     */
    public function supports(DOMNode $domNode, Cursor $cursor): bool;

    /**
     * Enter the DOM node.
     *
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     */
    public function enterNode(DOMNode $domNode, Cursor $cursor);

    /**
     * Leave the DOM node.
     *
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     */
    public function leaveNode(DOMNode $domNode, Cursor $cursor);
}
