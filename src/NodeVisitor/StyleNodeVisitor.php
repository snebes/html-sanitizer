<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace HtmlSanitizer\NodeVisitor;

use HtmlSanitizer\Model\Cursor;
use HtmlSanitizer\Node\TagNode;

/**
 * @author Steve Nebes <snebes@gmail.com>
 *
 * @internal
 */
class StyleNodeVisitor extends AbstractNodeVisitor implements NamedNodeVisitorInterface
{
    public function getSupportedNodeNames(): array
    {
        return ['style'];
    }

    public function supports(\DOMNode $domNode, Cursor $cursor): bool
    {
        return 'style' === $domNode->nodeName;
    }

    public function enterNode(\DOMNode $domNode, Cursor $cursor)
    {
        $node = new TagNode($cursor->node, 'style');

        $cursor->node->addChild($node);
        $cursor->node = $node;
    }

    public function leaveNode(\DOMNode $domNode, Cursor $cursor)
    {
        $cursor->node = $cursor->node->getParent();
    }
}
