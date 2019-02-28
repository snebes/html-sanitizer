<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\HtmlSanitizer;

use HtmlSanitizer\Sanitizer;
use PHPUnit\Framework\TestCase;

class SanitizerTest extends TestCase
{
    /**
     * @dataProvider html5Fixtures
     *
     * @param string $input
     * @param string $expectOutput
     */
    public function testSanitize(string $input, string $expectOutput): void
    {
        $sanitizer = Sanitizer::create(['extensions' => ['html5']]);

        $this->assertSame($expectOutput, $sanitizer->sanitize($input));
    }

    /**
     * @return array
     */
    public function html5Fixtures(): array
    {
        return [
            [
                '',
                '',
            ],
            [
                '<div class="foo">Lorem ipsum</div>',
                '<div class="foo">Lorem ipsum</div>',
            ],
        ];
    }
}
