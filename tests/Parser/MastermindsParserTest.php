<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\SN\HtmlSanitizer\Parser;

use PHPUnit\Framework\TestCase;
use SN\HtmlSanitizer\Parser\MastermindsParser;

class MastermindsParserTest extends TestCase
{
    public function testParse(): void
    {
        $html = '<section><div><p>test</p></section>';

        $parser = new MastermindsParser();
        $domNode = $parser->parse($html);

        $this->assertInstanceOf(\DOMNode::class, $domNode);
        $this->assertSame('section', $domNode->firstChild->nodeName);
        $this->assertSame('div', $domNode->firstChild->childNodes[0]->nodeName);
        $this->assertSame('p', $domNode->firstChild->childNodes[0]->childNodes[0]->nodeName);
        $this->assertSame('test', $domNode->firstChild->childNodes[0]->childNodes[0]->nodeValue);
    }

    public function testParseBad(): void
    {
        $html = '<section><div>';

        $parser = new MastermindsParser();
        $domNode = $parser->parse($html);

        $this->assertInstanceOf(\DOMNode::class, $domNode);
        $this->assertSame('section', $domNode->firstChild->nodeName);
        $this->assertSame('div', $domNode->firstChild->childNodes[0]->nodeName);
    }
}
