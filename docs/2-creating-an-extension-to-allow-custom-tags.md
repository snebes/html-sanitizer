# Creating an extension to allow custom tags

If you want to use additional tags than the one present in the sanitizer core extensions, you can create your
own extension.

There are two steps in the creation of an extension: creating the node visitor which will handle the
custom tag and registering this visitor by creating an extension class.

To better understand how to create an extension suited to your needs, you can also have a look at the
[HTML5 extension](https://github.com/snebes/html-sanitizer/tree/master/src/Extension/HTML5Extension.php)
which shows the different features available.

## Registering the extension

Once you created an extension, you need to register the extension in the sanitizer.

An extension is a class implementing the `SN\HtmlSanitizer\Extension\ExtensionInterface` interface, which requires
two methods:

- `getName()` which should return the name to use in the configuration (`basic`, `list`, etc.) ;
- and `createNodeVisitors()` which should return a list of node visitors associated to the tag the visit ;

For our node visitor, this could look like this:

```php
namespace App\Sanitizer;

use SN\HtmlSanitizer\Extension\ExtensionInterface;
use SN\HtmlSanitizer\NodeVisitor\TagNodeVisitor;

class MyTagExtension implements ExtensionInterface
{
    public function getName(): string
    {
        return 'my-tag';
    }

    public function createNodeVisitors(array $config = []): array
    {
        return [
            'my-tag' => new TagNodeVisitor($config['tags']['my-tag'] ?? []),
        ];
    }
}
```

Then, you can use the builder to create a Sanitizer that include this extension:

```php
$builder = new SN\HtmlSanitizer\SanitizerBuilder();
$builder->registerExtension(new SN\HtmlSanitizer\Extension\HTML5Extension());
// Add the other core ones you need

$builder->registerExtension(new App\Sanitizer\MyTagExtension());

$sanitizer = $builder->build([
    'extensions' => ['html5', 'my-tag'],
});
```
