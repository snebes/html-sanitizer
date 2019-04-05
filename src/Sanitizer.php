<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\HtmlSanitizer;

use SN\HtmlSanitizer\Extension\HTML5Extension;
use SN\HtmlSanitizer\Parser\MastermindsParser;
use SN\HtmlSanitizer\Parser\ParserInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Steve Nebes <snebes@gmail.com>
 *
 * @final
 */
class Sanitizer
{
    /**
     * @var DomVisitor
     */
    private $domVisitor;

    /**
     * @var int
     */
    private $maxInputLength;

    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * Default values.
     *
     * @param DomVisitor           $domVisitor
     * @param int                  $maxInputLength
     * @param ParserInterface|null $parser
     * @param LoggerInterface|null $logger
     */
    public function __construct(DomVisitor $domVisitor, int $maxInputLength, ParserInterface $parser = null, LoggerInterface $logger = null)
    {
        $this->domVisitor = $domVisitor;
        $this->maxInputLength = $maxInputLength;
        $this->parser = $parser ?: new MastermindsParser();
        $this->logger = $logger;
    }

    /**
     * Quickly create an already configured sanitizer using the default builder.
     *
     * @param array $config
     *
     * @return Sanitizer
     */
    public static function create(array $config): Sanitizer
    {
        $builder = new SanitizerBuilder();
        $builder->registerExtension(new HTML5Extension());

        return $builder->build($config);
    }

    /**
     * @param string $html
     *
     * @return string
     */
    public function sanitize(string $html): string
    {
        $sanitized = $this->doSanitize($html);

        if ($this->logger) {
            $this->logger->debug('Sanitized given input to "{output}".', [
                'output' => \mb_substr($sanitized, 0, 50).(\mb_strlen($sanitized) > 50 ? '...' : ''),
            ]);
        }

        return $sanitized;
    }

    /**
     * @param string $html
     *
     * @return string
     */
    private function doSanitize(string $html): string
    {
        // Prevent DOS attack induced by extremely long HTML strings
        if (\mb_strlen($html) > $this->maxInputLength) {
            $html = \mb_substr($html, 0, $this->maxInputLength);
        }

        /*
         * Only operate on valid UTF-8 strings. This is necessary to prevent cross
         * site scripting issues on Internet Explorer 6. Idea from Drupal (filter_xss).
         */
        if (!$this->isValidUtf8($html)) {
            return '';
        }

        // Remove NULL character
        $html = \str_replace(\chr(0), '', $html);

        try {
            $parsed = $this->parser->parse($html);
        } catch (\Exception $exception) {
            return '';
        }

        return $this->domVisitor->visit($parsed)->render();
    }

    /**
     * @param string $html
     *
     * @return bool
     */
    private function isValidUtf8(string $html): bool
    {
        // preg_match() fails silently on strings containing invalid UTF-8.
        return '' === $html || 1 === \preg_match('/^./us', $html);
    }
}
