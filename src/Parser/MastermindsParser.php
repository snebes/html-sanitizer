<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\HtmlSanitizer\Parser;

use DOMNode;
use SN\HtmlSanitizer\Exception\ParsingFailedException;
use Masterminds\HTML5;
use Throwable;

/**
 * @author Steve Nebes <snebes@gmail.com>
 *
 * @internal
 */
class MastermindsParser implements ParserInterface
{
    /**
     * @param string $html
     * @return DOMNode
     */
    public function parse(string $html): DOMNode
    {
        try {
            $parser = new HTML5();

            return $parser->loadHTMLFragment($html);
        } catch (Throwable $t) {
            throw new ParsingFailedException($this, $t);
        }
    }
}
