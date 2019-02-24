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
class ScriptNodeVisitor extends AbstractNodeVisitor implements NamedNodeVisitorInterface
{
    public function getSupportedNodeNames(): array
    {
        return ['script', 'noscript'];
    }

    public function supports(\DOMNode $domNode, Cursor $cursor): bool
    {
        return 'script' === $domNode->nodeName || 'noscript' === $domNode->nodeName;
    }

    public function enterNode(\DOMNode $domNode, Cursor $cursor)
    {
        $node = new TagNode($cursor->node, 'script');

        $cursor->node->addChild($node);
        $cursor->node = $node;
    }

    public function leaveNode(\DOMNode $domNode, Cursor $cursor)
    {
        $cursor->node = $cursor->node->getParent();
    }
}
