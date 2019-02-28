<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\HtmlSanitizer\Parser;

use SN\HtmlSanitizer\Exception\ParsingFailedException;
use Masterminds\HTML5;

/**
 * @author Steve Nebes <snebes@gmail.com>
 *
 * @final
 */
class MastermindsParser implements ParserInterface
{
    public function parse(string $html): \DOMNode
    {
        try {
            return (new HTML5())->loadHTMLFragment($html);
        } catch (\Throwable $t) {
            throw new ParsingFailedException($this, $t);
        }
    }
}
