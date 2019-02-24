<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace HtmlSanitizer\Extension\Table\Node;

use HtmlSanitizer\Node\AbstractTagNode;
use HtmlSanitizer\Node\HasChildrenTrait;

/**
 * @author Steve Nebes <snebes@gmail.com>
 *
 * @final
 */
class TableNode extends AbstractTagNode
{
    use HasChildrenTrait;

    public function getTagName(): string
    {
        return 'table';
    }
}
