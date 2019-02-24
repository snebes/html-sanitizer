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
use HtmlSanitizer\Node\NodeInterface;
use HtmlSanitizer\Node\TagNode;

class TagNodeVisitor extends AbstractNodeVisitor implements NamedNodeVisitorInterface
{
    use HasChildrenNodeVisitorTrait;

    /**
     * @var string
     */
    private $qName;

    /**
     * Default values.
     *
     * @param string $qName
     * @param array  $config
     */
    public function __construct(string $qName, array $config = [])
    {
        parent::__construct($config);

        $this->qName = $qName;
    }

    public function getDomNodeName(): string
    {
        return $this->qName;
    }

    protected function createNode(\DOMNode $domNode, Cursor $cursor): NodeInterface
    {
        return new TagNode($cursor->node, $this->qName);
    }
}
