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
                '<html/>',
                '<html></html>',
            ],
            [
                '<base/>',
                '<base />',
            ],
            [
                '<head/>',
                '<head></head>',
            ],
            [
                '<link/>',
                '<link />',
            ],
            [
                '<meta/>',
                '<meta />',
            ],
            [
                '<style/>',
                '<style></style>',
            ],
            [
                '<title/>',
                '<title></title>',
            ],
            [
                '<body/>',
                '<body></body>',
            ],
            [
                '<address/>',
                '<address></address>',
            ],
            [
                '<article/>',
                '<article></article>',
            ],
            [
                '<aside/>',
                '<aside></aside>',
            ],
            [
                '<footer/>',
                '<footer></footer>',
            ],
            [
                '<header/>',
                '<header></header>',
            ],
            [
                '<h1/>',
                '<h1></h1>',
            ],
            [
                '<h2/>',
                '<h2></h2>',
            ],
            [
                '<h3/>',
                '<h3></h3>',
            ],
            [
                '<h4/>',
                '<h4></h4>',
            ],
            [
                '<h5/>',
                '<h5></h5>',
            ],
            [
                '<h6/>',
                '<h6></h6>',
            ],
            [
                '<hgroup/>',
                '<hgroup></hgroup>',
            ],
            [
                '<main/>',
                '<main></main>',
            ],
            [
                '<nav/>',
                '<nav></nav>',
            ],
            [
                '<section/>',
                '<section></section>',
            ],
            [
                '<blockquote/>',
                '<blockquote></blockquote>',
            ],
            [
                '<dd/>',
                '<dd></dd>',
            ],
            [
                '<dir/>',
                '<dir></dir>',
            ],
            [
                '<div/>',
                '<div></div>',
            ],
            [
                '<dl/>',
                '<dl></dl>',
            ],
            [
                '<dt/>',
                '<dt></dt>',
            ],
            [
                '<figcaption/>',
                '<figcaption></figcaption>',
            ],
            [
                '<figure/>',
                '<figure></figure>',
            ],
            [
                '<hr/>',
                '<hr />',
            ],
            [
                '<li/>',
                '<li></li>',
            ],
            [
                '<ol/>',
                '<ol></ol>',
            ],
            [
                '<p/>',
                '<p></p>',
            ],
            [
                '<pre/>',
                '<pre></pre>',
            ],
            [
                '<ul/>',
                '<ul></ul>',
            ],
            [
                '<a/>',
                '<a></a>',
            ],
            [
                '<abbr/>',
                '<abbr></abbr>',
            ],
            [
                '<b/>',
                '<b></b>',
            ],
            [
                '<bdi/>',
                '<bdi></bdi>',
            ],
            [
                '<bdo/>',
                '<bdo></bdo>',
            ],
            [
                '<br/>',
                '<br />',
            ],
            [
                '<cite/>',
                '<cite></cite>',
            ],
            [
                '<code/>',
                '<code></code>',
            ],
            [
                '<data/>',
                '<data></data>',
            ],
            [
                '<dfn/>',
                '<dfn></dfn>',
            ],
            [
                '<em/>',
                '<em></em>',
            ],
            [
                '<i/>',
                '<i></i>',
            ],
            [
                '<kbd/>',
                '<kbd></kbd>',
            ],
            [
                '<mark/>',
                '<mark></mark>',
            ],
            [
                '<q/>',
                '<q></q>',
            ],
            [
                '<rb/>',
                '<rb></rb>',
            ],
            [
                '<rp/>',
                '<rp></rp>',
            ],
            [
                '<rt/>',
                '<rt></rt>',
            ],
            [
                '<rtc/>',
                '<rtc></rtc>',
            ],
            [
                '<ruby/>',
                '<ruby></ruby>',
            ],
            [
                '<s/>',
                '<s></s>',
            ],
            [
                '<samp/>',
                '<samp></samp>',
            ],
            [
                '<small/>',
                '<small></small>',
            ],
            [
                '<span/>',
                '<span></span>',
            ],
            [
                '<strong/>',
                '<strong></strong>',
            ],
            [
                '<sub/>',
                '<sub></sub>',
            ],
            [
                '<sup/>',
                '<sup></sup>',
            ],
            [
                '<time/>',
                '<time></time>',
            ],
            [
                '<tt/>',
                '<tt></tt>',
            ],
            [
                '<u/>',
                '<u></u>',
            ],
            [
                '<var/>',
                '<var></var>',
            ],
            [
                '<wbr/>',
                '<wbr />',
            ],
            [
                '<area/>',
                '<area />',
            ],
            [
                '<audio/>',
                '<audio></audio>',
            ],
            [
                '<img/>',
                '<img />',
            ],
            [
                '<map/>',
                '<map></map>',
            ],
            [
                '<track/>',
                '<track />',
            ],
            [
                '<video/>',
                '<video></video>',
            ],
            [
                '<applet/>',
                '<applet></applet>',
            ],
            [
                '<embed/>',
                '<embed></embed>',
            ],
            [
                '<iframe/>',
                '<iframe></iframe>',
            ],
            [
                '<noembed/>',
                '<noembed></noembed>',
            ],
            [
                '<object/>',
                '<object></object>',
            ],
            [
                '<param/>',
                '<param />',
            ],
            [
                '<picture/>',
                '<picture></picture>',
            ],
            [
                '<source/>',
                '<source></source>',
            ],
            [
                '<canvas/>',
                '<canvas></canvas>',
            ],
            [
                '<noscript/>',
                '<noscript></noscript>',
            ],
            [
                '<script/>',
                '<script></script>',
            ],
            [
                '<del/>',
                '<del></del>',
            ],
            [
                '<ins/>',
                '<ins></ins>',
            ],
            [
                '<caption/>',
                '<caption></caption>',
            ],
            [
                '<col/>',
                '<col />',
            ],
            [
                '<colgroup/>',
                '<colgroup></colgroup>',
            ],
            [
                '<table/>',
                '<table></table>',
            ],
            [
                '<tbody/>',
                '<tbody></tbody>',
            ],
            [
                '<td/>',
                '<td></td>',
            ],
            [
                '<tfoot/>',
                '<tfoot></tfoot>',
            ],
            [
                '<th/>',
                '<th></th>',
            ],
            [
                '<thead/>',
                '<thead></thead>',
            ],
            [
                '<tr/>',
                '<tr></tr>',
            ],
            [
                '<button/>',
                '<button></button>',
            ],
            [
                '<datalist/>',
                '<datalist></datalist>',
            ],
            [
                '<fieldset/>',
                '<fieldset></fieldset>',
            ],
            [
                '<form/>',
                '<form></form>',
            ],
            [
                '<input/>',
                '<input />',
            ],
            [
                '<label/>',
                '<label></label>',
            ],
            [
                '<legend/>',
                '<legend></legend>',
            ],
            [
                '<meter/>',
                '<meter></meter>',
            ],
            [
                '<optgroup/>',
                '<optgroup></optgroup>',
            ],
            [
                '<option/>',
                '<option></option>',
            ],
            [
                '<output/>',
                '<output></output>',
            ],
            [
                '<progress/>',
                '<progress></progress>',
            ],
            [
                '<select/>',
                '<select></select>',
            ],
            [
                '<textarea/>',
                '<textarea></textarea>',
            ],
            [
                '<details/>',
                '<details></details>',
            ],
            [
                '<dialog/>',
                '<dialog></dialog>',
            ],
            [
                '<menu/>',
                '<menu></menu>',
            ],
            [
                '<menuitem/>',
                '<menuitem></menuitem>',
            ],
            [
                '<summary/>',
                '<summary></summary>',
            ],
            [
                '<content/>',
                '<content></content>',
            ],
            [
                '<element/>',
                '<element></element>',
            ],
            [
                '<shadow/>',
                '<shadow></shadow>',
            ],
            [
                '<slot/>',
                '<slot></slot>',
            ],
            [
                '<template/>',
                '<template></template>',
            ],
        ];
    }
}
