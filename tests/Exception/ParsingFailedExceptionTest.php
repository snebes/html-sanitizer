<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\SN\HtmlSanitizer\Exception;

use PHPUnit\Framework\TestCase;
use SN\HtmlSanitizer\Exception\ParsingFailedException;
use SN\HtmlSanitizer\Parser\MastermindsParser;

class ParsingFailedExceptionTest extends TestCase
{
    public function testException(): void
    {
        $parser = new MastermindsParser();
        $exception = new ParsingFailedException($parser);

        $this->assertStringStartsWith('HTML parsing failed using parser', $exception->getMessage());
        $this->assertSame($parser, $exception->getParser());
    }
}
