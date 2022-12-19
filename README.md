# Webpack-Encore plugin for Micro framework

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

This plugin adds several twig functions that help to include script and style html tags to twig templates from webpack-encore entries.

## Get started

Before starting to work with the plugin, all you need is:
* Project based on [Micro Framework][link-microframework];
* [@symfony/webpack-encore][link-symfony-webpack-encore] installed by npm or yarn;

### Install

  You can install the plugin via composer:
```bash
composer require oleksiibulba/webpack-encore-plugin
```

### Usage

Add `OleksiiBulba\WebpackEncorePlugin\WebpackEncorePlugin` to your plugins list:
```php
<?php /* ./etc/plugins.php */

return [
    // List of plugins:
    // ...
    OleksiiBulba\WebpackEncorePlugin\WebpackEncorePlugin::class,
    // ...
];
```

To use the plugin you need to create an entrypoint in your webpack.config.js:
```javascript
/* ./webpack.config.js */
const Encore = require('@symfony/webpack-encore');
Encore
    /* ... */
    .addEntry('your_entry_name', './path/to/your_entry_file.jsx')
    /* ... */
```

run build:
```bash
yarn dev
// or
npm dev
```

and add one of the twig functions to a template:
```html
{# ./templates/base.html.twig #}
{# ... #}
<head>
    {{ encore_entry_script_tags('your_entry_name') }}
    {{ encore_entry_link_tags('your_entry_name') }}
</head>
{# ... #}
```

Here is the signature of the twig functions:
* encore_entry_script_tags | encore_entry_link_tags:
  * entryName, type: string, required;
  * extraAttributes, type: array, optional, default value: empty array;
* getJavaScriptFiles | getCssFiles | entryExists:
  * entryName, type: string, required;

If two or more entries contain common files, then they will be printed only once;

#### Extra attributes
To add extra attribute to the tags, you can pass them in the array as a second argument, like this:
```html
<head>
    {{ encore_entry_script_tags('your_entry_name', {'defer':true}) }}
</head>
```
and as a result, it will print next html (assuming your entrypoint 'app' contains only one file './js/app.js'):
```html
<head>
    <script href="/js/app.js" type="application/javascript" defer></script>
</head>
```

## Change Log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
composer test
```

## Contributing

Please feel free to open pull request or create an issue, they are more than welcome!
Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email oleksii_bulba@epam.com instead of using the issue tracker.

## Credits

- [Oleksii Bulba][link-author]
- [Fabien Potencier][email-fabien]
- [All Contributors][link-contributors]

The code was taken and adapted from `symfony/webpack-encore-bundle` that was created by [Symfony Community](https://symfony.com/contributors) and [Fabien Potencier](mailto:fabien@symfony.com) in particular.
Adapted for Micro framework plugin by [Oleksii Bulba][link-author].

For the full copyright and license information, please see the [License File](LICENSE.md) that was distributed with this source code.

## License

[The MIT License (MIT)][link-license]. Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/oleksiibulba/webpack-encore-plugin.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/oleksiibulba/webpack-encore-plugin.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/oleksiibulba/webpack-encore-plugin.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/oleksiibulba/webpack-encore-plugin.svg?style=flat-square

[link-microframework]: https://github.com/Micro-PHP/skeleton
[link-symfony-webpack-encore]: https://www.npmjs.com/package/@symfony/webpack-encore
[link-packagist]: https://packagist.org/packages/oleksiibulba/webpack-encore-plugin
[link-travis]: https://travis-ci.org/oleksiibulba/webpack-encore-plugin
[link-scrutinizer]: https://scrutinizer-ci.com/g/oleksiibulba/webpack-encore-plugin/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/oleksiibulba/webpack-encore-plugin
[link-downloads]: https://packagist.org/packages/oleksiibulba/webpack-encore-plugin
[link-author]: https://github.com/OleksiiBulba
[link-contributors]: ../../contributors
[link-license]: https://opensource.org/licenses/MIT
[email-fabien]: mailto:fabien@symfony.com
