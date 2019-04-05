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

class DocumentNodeTest extends TestCase
{
    public function testGetParent(): void
    {
        $document = new DocumentNode();

        $this->assertNull($document->getParent());
    }

    public function testRender(): void
    {
        $document = new DocumentNode();

        $this->assertSame('', $document->render());
    }
}
