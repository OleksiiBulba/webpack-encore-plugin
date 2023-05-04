# Plugin installation

The plugin requires several packages to work properly:
* javascript package [@symfony/webpack-encore][link-symfony-webpack-encore] - to get the path to your assets;
* Project based on [Micro Framework][link-microframework]:
    * "micro/kernel-boot-configuration": "^1",
    * "micro/kernel-boot-plugin-depended": "^1",
    * "micro/plugin-twig": "^1",
    * "symfony/serializer": "^6.2"

You can install the plugin via composer:
```bash
composer require oleksiibulba/webpack-encore-plugin
```

[link-symfony-webpack-encore]: https://www.npmjs.com/package/@symfony/webpack-encore
[link-microframework]: https://github.com/Micro-PHP/micro
