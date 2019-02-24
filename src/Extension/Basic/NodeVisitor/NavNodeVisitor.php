<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace HtmlSanitizer\Extension\Basic\NodeVisitor;

use HtmlSanitizer\Extension\Basic\Node\NavNode;
use HtmlSanitizer\Model\Cursor;
use HtmlSanitizer\Node\NodeInterface;
use HtmlSanitizer\Visitor\AbstractNodeVisitor;
use HtmlSanitizer\Visitor\HasChildrenNodeVisitorTrait;
use HtmlSanitizer\Visitor\NamedNodeVisitorInterface;

/**
 * @author Steve Nebes <snebes@gmail.com>
 *
 * @final
 */
class NavNodeVisitor extends AbstractNodeVisitor implements NamedNodeVisitorInterface
{
    use HasChildrenNodeVisitorTrait;

    protected function getDomNodeName(): string
    {
        return 'nav';
    }

    protected function createNode(\DOMNode $domNode, Cursor $cursor): NodeInterface
    {
        return new NavNode($cursor->node);
    }
}
