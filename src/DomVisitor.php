<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace SN\HtmlSanitizer;

use SN\HtmlSanitizer\Model\Cursor;
use SN\HtmlSanitizer\Node\DocumentNode;
use SN\HtmlSanitizer\Node\TextNode;
use SN\HtmlSanitizer\NodeVisitor\NodeVisitorInterface;

/**
 * The DomVisitor iterate over the parsed DOM tree and visit nodes using NodeVisitorInterface objects.
 * For performance reasons, these objects are split in 2 groups: generic ones and node-specific ones.
 */
class DomVisitor
{
    /**
     * @var NodeVisitorInterface[]
     */
    private $nodeVisitors = [];

    /**
     * @param NodeVisitorInterface[] $visitors
     */
    public function __construct(array $visitors = [])
    {
        foreach ($visitors as $visitor) {
            if ($visitor instanceof NodeVisitorInterface) {
                foreach ($visitor->getSupportedNodeNames() as $nodeName) {
                    $this->nodeVisitors[$nodeName][] = $visitor;
                }
            }
        }
    }

    /**
     * @param \DOMNode $node
     *
     * @return DocumentNode
     */
    public function visit(\DOMNode $node): DocumentNode
    {
        $cursor = new Cursor();
        $cursor->node = new DocumentNode();

        $this->visitNode($node, $cursor);

        return $cursor->node;
    }

    /**
     * @param \DOMNode $node
     * @param Cursor   $cursor
     */
    private function visitNode(\DOMNode $node, Cursor $cursor)
    {
        /** @var NodeVisitorInterface[] $supportedVisitors */
        $supportedVisitors = $this->nodeVisitors[$node->nodeName] ?? [];

        foreach ($supportedVisitors as $visitor) {
            if ($visitor->supports($node, $cursor)) {
                $visitor->enterNode($node, $cursor);
            }
        }

        /** @var \DOMNode $child */
        foreach ($node->childNodes ?? [] as $child) {
            if ('#text' === $child->nodeName) {
                // Add text in the safe tree without a visitor for performance
                $cursor->node->addChild(new TextNode($cursor->node, $child->nodeValue));
            } elseif (!$child instanceof \DOMText) {
                // Ignore comments for security reasons (interpreted differently by browsers)
                $this->visitNode($child, $cursor);
            }
        }

        foreach (\array_reverse($supportedVisitors) as $visitor) {
            if ($visitor->supports($node, $cursor)) {
                $visitor->leaveNode($node, $cursor);
            }
        }
    }
}
