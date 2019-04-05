<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\SN\HtmlSanitizer\Node;

use PHPUnit\Framework\TestCase;
use SN\HtmlSanitizer\Node\DocumentNode;
use SN\HtmlSanitizer\Node\TextNode;

class TextNodeTest extends TestCase
{
    public function testRender(): void
    {
        $document = new DocumentNode();
        $textNode = new TextNode($document, 'Text');

        $this->assertSame('Text', $textNode->render());
    }
}
