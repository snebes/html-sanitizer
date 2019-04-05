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
use SN\HtmlSanitizer\Node\TagNode;

class TagNodeTest extends TestCase
{
    public function testGetTagName(): void
    {
        $document = new DocumentNode();
        $tagNode = new TagNode($document, 'div', []);

        $this->assertSame('div', $tagNode->getTagName());
    }

    public function testGetAttribute(): void
    {
        $document = new DocumentNode();
        $tagNode = new TagNode($document, 'div', [
            'class' => 'p-0',
        ]);

        $this->assertSame('p-0', $tagNode->getAttribute('class'));
        $this->assertNull($tagNode->getAttribute('style'));
    }

    public function testSetAttribute(): void
    {
        $document = new DocumentNode();
        $tagNode = new TagNode($document, 'div');

        $tagNode->setAttribute('class', 'p-0');
        $this->assertSame('p-0', $tagNode->getAttribute('class'));

        $tagNode->setAttribute('class', 'will not set');
        $this->assertSame('p-0', $tagNode->getAttribute('class'));
    }

    public function testRender(): void
    {
        $document = new DocumentNode();
        $tagNode = new TagNode($document, 'div', [
            'class' => 'p-0',
        ]);

        $this->assertSame('<div class="p-0"></div>', $tagNode->render());

        $tagNode = new TagNode($document, 'br', [], true);
        $this->assertSame('<br />', $tagNode->render());
    }

    public function testRenderAttributes(): void
    {
        $document = new DocumentNode();

        // test null value.
        $tagNode = new TagNode($document, 'div', ['class' => null]);
        $this->assertSame('<div></div>', $tagNode->render());

        // test 1 value.
        $tagNode = new TagNode($document, 'div', ['data-test' => '`test`']);
        $this->assertSame('<div data-test="`test` "></div>', $tagNode->render());
    }
}
