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
use SN\HtmlSanitizer\Node\HasChildrenTrait;
use SN\HtmlSanitizer\Node\TagNode;

class HasChildrenTraitTest extends TestCase
{
    public function testTrait(): void
    {
        $trait = $this->getObjectForTrait(HasChildrenTrait::class);

        $document = new DocumentNode();
        $node = new TagNode($document, 'div', []);
        $trait->addChild($node);

        $refProp = new \ReflectionProperty($trait, 'children');
        $refProp->setAccessible(true);

        $this->assertContains($node, $refProp->getValue($trait));

        // HasChildrenTrait::renderChildren
        $document->addChild($node);
        $this->assertSame('<div></div>', $document->render());
    }
}
