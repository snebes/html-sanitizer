<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
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
use SN\HtmlSanitizer\NodeVisitor\RemoveNodeVisitor;

class RemoveNodeVisitorTest extends TestCase
{
    public function testEnterLeaveNode(): void
    {
        $visitor = new RemoveNodeVisitor('div');

        $dom = new \DOMDocument();
        $cursor = new Cursor();
        $document = new DocumentNode();
        $cursor->node = $document;
        $domNode = $dom->createElement('div');
        $domNode->appendChild($dom->createElement('p', 'text'));

        $visitor->enterNode($domNode, $cursor);
        $this->assertInstanceOf(DocumentNode::class, $cursor->node);
        $this->assertSame('', $document->render());

        $visitor->leaveNode($domNode, $cursor);
        $this->assertInstanceOf(DocumentNode::class, $cursor->node);
        $this->assertSame($document, $cursor->node);
    }
}
