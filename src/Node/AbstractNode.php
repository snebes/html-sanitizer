<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace SN\HtmlSanitizer\Node;

/**
 * @author Steve Nebes <snebes@gmail.com>
 */
abstract class AbstractNode implements NodeInterface
{
    /**
     * @var NodeInterface
     */
    private $parent;

    public function __construct(NodeInterface $parent)
    {
        $this->parent = $parent;
    }

    public function getParent(): ?NodeInterface
    {
        return $this->parent;
    }
}
