<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\SN\HtmlSanitizer;

use Psr\Log\NullLogger;
use SN\HtmlSanitizer\Extension\HTML5Extension;
use PHPUnit\Framework\TestCase;
use SN\HtmlSanitizer\Sanitizer;
use SN\HtmlSanitizer\SanitizerBuilder;

class SanitizerTest extends TestCase
{
    public function testLogger(): void
    {
        $builder = new SanitizerBuilder();
        $builder->registerExtension(new HTML5Extension());
        $builder->setLogger(new NullLogger());

        $sanitizer = $builder->build([
            'extensions'       => ['html5'],
            'max_input_length' => 12,
        ]);

        $this->assertSame('<div></div>', $sanitizer->sanitize('<div></div>'));

        // Tests the max_input_length.
        $this->assertSame('', $sanitizer->sanitize('<sectiontable></sectiontable>'));
    }

    /**
     * Valid / Invalid strings from:
     *
     * @see https://stackoverflow.com/questions/1301402/example-invalid-utf8-string
     */
    public function testIsValidUtf8(): void
    {
        $sanitizer = Sanitizer::create(['extensions' => ['html5']]);

        $this->assertSame('', $sanitizer->sanitize("\xc3\x28"));

        $refMethod = new \ReflectionMethod($sanitizer, 'isValidUtf8');
        $refMethod->setAccessible(true);

        $this->assertTrue($refMethod->invoke($sanitizer, "a"));
        $this->assertTrue($refMethod->invoke($sanitizer, "\xc3\xb1"));
        $this->assertTrue($refMethod->invoke($sanitizer, "\xe2\x82\xa1"));
        $this->assertTrue($refMethod->invoke($sanitizer, "\xf0\x90\x8c\xbc"));

        $this->assertFalse($refMethod->invoke($sanitizer, "\xc3\x28"));
        $this->assertFalse($refMethod->invoke($sanitizer, "\xa0\xa1"));
        $this->assertFalse($refMethod->invoke($sanitizer, "\xe2\x28\xa1"));
        $this->assertFalse($refMethod->invoke($sanitizer, "\xe2\x82\x28"));
        $this->assertFalse($refMethod->invoke($sanitizer, "\xf0\x28\x8c\xbc"));
        $this->assertFalse($refMethod->invoke($sanitizer, "\xf0\x90\x28\xbc"));
        $this->assertFalse($refMethod->invoke($sanitizer, "\xf0\x28\x8c\x28"));
        $this->assertFalse($refMethod->invoke($sanitizer, "\xf8\xa1\xa1\xa1\xa1"));
        $this->assertFalse($refMethod->invoke($sanitizer, "\xfc\xa1\xa1\xa1\xa1\xa1"));
    }

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
