<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\SN\HtmlSanitizer\Sanitizer;

use PHPUnit\Framework\TestCase;
use SN\HtmlSanitizer\Sanitizer\StringSanitizerTrait;

class StringSanitizerTraitTest extends TestCase
{
    public function testEncode(): void
    {
        $replacements = [
            '＜' => '&#xFF1C;',
            '＞' => '&#xFF1E;',
            '＋' => '&#xFF0B;',
            '＝' => '&#xFF1D;',
            '＠' => '&#xFF20;',
            '｀' => '&#xFF40;',
        ];

        $encoder = $this->getObjectForTrait(StringSanitizerTrait::class);

        foreach ($replacements as $input => $expected) {
            $this->assertSame($expected, $encoder->encodeHtmlEntities($input));
        }
    }
}
