<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\SN\HtmlSanitizer;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use SN\HtmlSanitizer\Extension\HTML5Extension;
use SN\HtmlSanitizer\Parser\MastermindsParser;
use SN\HtmlSanitizer\SanitizerBuilder;

class SanitizerBuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $extension = new HTML5Extension();
        $parser = new MastermindsParser();
        $logger = new NullLogger();

        $builder = new SanitizerBuilder();
        $builder->registerExtension($extension);
        $builder->setParser($parser);
        $builder->setLogger($logger);

        $sanitizer = $builder->build([
            'extensions'       => ['html5'],
            'max_input_length' => 10,
        ]);
        $refClass = new \ReflectionClass($sanitizer);

        $refProp = $refClass->getProperty('maxInputLength');
        $refProp->setAccessible(true);
        $this->assertSame(10, $refProp->getValue($sanitizer));

        $refProp = $refClass->getProperty('parser');
        $refProp->setAccessible(true);
        $this->assertSame($parser, $refProp->getValue($sanitizer));

        $refProp = $refClass->getProperty('logger');
        $refProp->setAccessible(true);
        $this->assertSame($logger, $refProp->getValue($sanitizer));
    }

    public function testBuildException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $builder = new SanitizerBuilder();
        $builder->registerExtension(new HTML5Extension());

        $builder->build([
            'extensions' => ['xml'],
        ]);
    }
}
