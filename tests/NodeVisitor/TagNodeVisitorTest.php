<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\SN\HtmlSanitizer\NodeVisitor;

use PHPUnit\Framework\TestCase;
use SN\HtmlSanitizer\Model\Cursor;
use SN\HtmlSanitizer\Node\DocumentNode;
use SN\HtmlSanitizer\Node\TagNode;
use SN\HtmlSanitizer\NodeVisitor\TagNodeVisitor;

class TagNodeVisitorTest extends TestCase
{
    public function testGetDomNodeName(): void
    {
        $visitor = new TagNodeVisitor('div', []);

        $this->assertSame('div', $visitor->getDomNodeName());
    }

    public function testGetSupportedNodeNames(): void
    {
        $visitor = new TagNodeVisitor('ins', [
            'convert_elements' => ['u'],
        ]);

        $this->assertSame('ins', $visitor->getDomNodeName());
        $this->assertSame(['ins', 'u'], $visitor->getSupportedNodeNames());
    }

    public function testSupports(): void
    {
        $visitor = new TagNodeVisitor('ins', [
            'convert_elements' => ['u'],
        ]);

        $dom = new \DOMDocument();
        $cursor = new Cursor();

        $this->assertTrue($visitor->supports($dom->createElement('ins'), $cursor));
        $this->assertTrue($visitor->supports($dom->createElement('u'), $cursor));
        $this->assertFalse($visitor->supports($dom->createElement('div'), $cursor));
    }

    public function testCreateNode(): void
    {
        $visitor = new TagNodeVisitor('p');

        $dom = new \DOMDocument();
        $cursor = new Cursor();
        $cursor->node = new DocumentNode();

        /** @var TagNode $node */
        $node = $visitor->createNode($dom->createElement('p', 'text'), $cursor);
        $this->assertInstanceOf(TagNode::class, $node);
        $this->assertSame('p', $node->getTagName());
        $this->assertSame('<p></p>', $node->render());

        $domNode = $dom->createElement('p', 'text');
        $domNode->appendChild(new \DOMAttr('class', 'm-0'));

        /** @var TagNode $node */
        $node = $visitor->createNode($domNode, $cursor);
        $this->assertInstanceOf(TagNode::class, $node);
        $this->assertSame('p', $node->getTagName());
        $this->assertSame('<p class="m-0"></p>', $node->render());
    }

    public function testEnterLeaveNode(): void
    {
        $visitor = new TagNodeVisitor('p');

        $dom = new \DOMDocument();
        $cursor = new Cursor();
        $document = new DocumentNode();
        $cursor->node = $document;
        $domNode = $dom->createElement('p', 'text');

        $visitor->enterNode($domNode, $cursor);
        $this->assertInstanceOf(TagNode::class, $cursor->node);
        $this->assertSame($document, $cursor->node->getParent());

        $visitor->leaveNode($domNode, $cursor);
        $this->assertInstanceOf(DocumentNode::class, $cursor->node);
        $this->assertSame($document, $cursor->node);
    }

    public function testAllowedAttributes(): void
    {
        $visitor1 = new TagNodeVisitor('p', [
            'allowed_attributes' => ['align'],
        ]);
        $visitor2 = new TagNodeVisitor('p', [
            'allowed_attributes' => [],
        ]);

        $dom = new \DOMDocument();
        $cursor = new Cursor();
        $cursor->node = new DocumentNode();

        $domNode = $dom->createElement('p', 'text');
        $domNode->appendChild(new \DOMAttr('align', 'right'));
        $domNode->appendChild(new \DOMAttr('class', 'm-0'));

        /** @var TagNode $node */
        $node = $visitor1->createNode($domNode, $cursor);
        $this->assertSame('<p align="right"></p>', $node->render());

        /** @var TagNode $node */
        $node = $visitor2->createNode($domNode, $cursor);
        $this->assertSame('<p></p>', $node->render());
    }

    public function testBlockedAttributes(): void
    {
        $visitor = new TagNodeVisitor('p', [
            'blocked_attributes' => 'class',
        ]);

        $dom = new \DOMDocument();
        $cursor = new Cursor();
        $cursor->node = new DocumentNode();

        $domNode = $dom->createElement('p', 'text');
        $domNode->appendChild(new \DOMAttr('class', 'm-0'));

        /** @var TagNode $node */
        $node = $visitor->createNode($domNode, $cursor);
        $this->assertSame('<p></p>', $node->render());
    }

    public function testAllowedClasses(): void
    {
        $visitor1 = new TagNodeVisitor('p', [
            'allowed_classes' => [],
        ]);
        $visitor2 = new TagNodeVisitor('p', [
            'allowed_classes' => ['p-3'],
        ]);

        $dom = new \DOMDocument();
        $cursor = new Cursor();
        $cursor->node = new DocumentNode();

        // Empty class attribute.
        $domNode = $dom->createElement('p', 'text');
        $domNode->appendChild(new \DOMAttr('class', ''));

        /** @var TagNode $node */
        $node = $visitor1->createNode($domNode, $cursor);
        $this->assertSame('<p></p>', $node->render());

        // With class attribute.
        $domNode = $dom->createElement('p', 'text');
        $domNode->appendChild(new \DOMAttr('class', 'm-0 p-3'));

        /** @var TagNode $node */
        $node = $visitor1->createNode($domNode, $cursor);
        $this->assertSame('<p></p>', $node->render());

        /** @var TagNode $node */
        $node = $visitor2->createNode($domNode, $cursor);
        $this->assertSame('<p class="p-3"></p>', $node->render());
    }

    public function testBlockedClasses(): void
    {
        $visitor = new TagNodeVisitor('p', [
            'blocked_classes' => ['m-0'],
        ]);

        $dom = new \DOMDocument();
        $cursor = new Cursor();
        $cursor->node = new DocumentNode();

        $domNode = $dom->createElement('p', 'text');
        $domNode->appendChild(new \DOMAttr('class', 'm-0 p-3'));

        /** @var TagNode $node */
        $node = $visitor->createNode($domNode, $cursor);
        $this->assertSame('<p class="p-3"></p>', $node->render());
    }
}
