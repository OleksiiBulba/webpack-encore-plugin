# Webpack-Encore plugin for Micro framework

This plugin adds several twig functions that help to include script and style html tags to twig templates from webpack-encore entries.

## Get started

Before starting to work with the plugin, all you need is:
* Project based on [Micro Framework](https://github.com/Micro-PHP/skeleton);
* [@symfony/webpack-encore](https://www.npmjs.com/package/@symfony/webpack-encore) installed by npm or yarn;
* Run composer command `composer require oleksiibulba/webpack-encore-plugin`;
* Add `\Boo\WebpackEncorePlugin\WebpackEncorePlugin` to your plugins list;

## Usage

To use the plugin you need to create an entrypoint in your webpack.config.js, run build, and add one of the twig functions to a template:
```html
<head>
    {{ encore_entry_script_tags('your_entry_name') }}
    {{ encore_entry_link_tags('your_entry_name') }}
</head>
```

Here is the signature of the twig functions:
* encore_entry_script_tags | encore_entry_link_tags:
  * entryName, type: string, required;
  * extraAttributes, type: array, optional, default value: empty array;
* getJavaScriptFiles | getCssFiles | entryExists:
  * entryName, type: string, required;

If two or more entries contain common files, then they will be printed only once;

### Extra attributes
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

## Contributing

Please feel free to open pull request or create an issue, they are more than welcome!

## Authors

The code was taken and adapted from `symfony/webpack-encore-bundle` that was created by [Symfony Community](https://symfony.com/contributors) and [Fabien Potencier](mailto:fabien@symfony.com) in particular.
Adapted for Micro framework plugin by Oleksii Bulba.

For the full copyright and license information, please view the LICENSE file that was distributed with this source code.

## License

[MIT](https://opensource.org/licenses/MIT)
