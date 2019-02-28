<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\HtmlSanitizer\NodeVisitor;

use SN\HtmlSanitizer\Model\Cursor;

class IgnoredNodeVisitor implements NodeVisitorInterface
{
    /**
     * @var string
     */
    protected $qName;

    /**
     * Default values.
     *
     * @param string $qName
     */
    public function __construct(string $qName)
    {
        $this->qName = $qName;
    }

    /**
     * @param \DOMNode $domNode
     * @param Cursor   $cursor
     * @return bool
     */
    public function supports(\DOMNode $domNode, Cursor $cursor): bool
    {
        return $domNode->nodeName === $this->qName;
    }

    /**
     * @param \DOMNode $domNode
     * @param Cursor   $cursor
     */
    public function enterNode(\DOMNode $domNode, Cursor $cursor)
    {
    }

    /**
     * @param \DOMNode $domNode
     * @param Cursor   $cursor
     */
    public function leaveNode(\DOMNode $domNode, Cursor $cursor)
    {
    }
}
