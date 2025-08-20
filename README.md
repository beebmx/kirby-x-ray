<p align="center"><a href="https://github.com/beebmx/kirby-x-ray" rel="noopener"><img src="/.github/assets/logo.svg?raw=true" width="175" alt="X-Ray Logo"></a></p>

<p align="center">
<a href="https://github.com/beebmx/kirby-x-ray/actions"><img src="https://img.shields.io/github/actions/workflow/status/beebmx/kirby-x-ray/tests.yml?branch=main" alt="Build Status"></a>
<a href="https://packagist.org/packages/beebmx/kirby-x-ray"><img src="https://img.shields.io/packagist/dt/beebmx/kirby-x-ray" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/beebmx/kirby-x-ray"><img src="https://img.shields.io/packagist/v/beebmx/kirby-x-ray" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/beebmx/kirby-x-ray"><img src="https://img.shields.io/packagist/l/beebmx/kirby-x-ray" alt="License"></a>
</p>

# X-Ray for Kirby

`X-Ray` provides a comprehensive overview of the content present on your site and individual pages, allowing you to inspect and evaluate them at a glance.

![X-Ray](/.github/assets/banner.jpg)

****

## Overview

- [1. Installation](#installation)
- [2. Usage](#usage)
- [3. Options](#options)
- [4. License](#license)
- [5. Credits](#credits)

## Installation

### Composer

```
composer require beebmx/kirby-x-ray
```

## Usage

`X-Ray` adds a new panel `area`, and by default, it is already configured to be accessible from the panel navigation, but you can customize it with different options.

### Overview section

The `overview` section provides a quick summary of the content on your site, including `site` or `page` overview, `pages` and `files` displayed, and their sizes.
By default, it shows the `site` or `page` overview, but you can change the view to `files` or `pages` using the option with the proper `enum`.

```php
use Beebmx\KirbyXRay\Enums\FilterType;

'beebmx.x-ray' => [
    'overview' => FilterType::Files,
],
```

> [!NOTE]
> You can choose between `FilterType::Page`, `FilterType::Files`, and `FilterType::Pages` to change the default view of the overview section.

![X-Ray preview](/.github/assets/preview.jpg)

> [!NOTE]
> `X-Ray` supports light and dark modes, so it will adapt to the current panel theme.

### Page button

For your convenience, `X-Ray` comes with the ability to add a button to the page panel that allows you to quickly access the X-Ray details of the current page.

```yaml
buttons:
  x-ray: true     # Enable the button in the page panel
  settings: true
```

> [!NOTE]
> If a user doesn’t have permission to access `X-Ray`, the button will be disabled and the area won’t be accessible (see [Permissions](#permissions)).

### Permissions

By default, `X-Ray` is enabled for all users (with panel access), but you can restrict access to it by setting a custom permission in your user roles.

```yaml
# site/blueprints/users/editor.yml

title: Editor
permissions:
  access:
    x-ray: false
```

## Options

| Option                       |   Type   |     Default      | Description                                                                                            |
|:-----------------------------|:--------:|:----------------:|:-------------------------------------------------------------------------------------------------------|
| beebmx.x-ray.autoclean.files |  `bool`  |       true       | When this option is `true` and a `file` is changed, it will automatically reset the cache of the page. |
| beebmx.x-ray.autoclean.pages |  `bool`  |       true       | When this option is `true` and a `page` is changed, it will automatically reset the cache of the page. |
| beebmx.x-ray.cache           |  `bool`  |       true       | When this option is `true`, it will cache the data of the `site` and `pages` inspected.                |
| beebmx.x-ray.icon            | `string` |    x-ray-icon    | With this option, you can change the `default` icon of the area and the buttons.                       |
| beebmx.x-ray.limit.overview  |  `int`   |        5         | The number of rows displayed in the `overview` section.                                                |
| beebmx.x-ray.limit.resource  |  `int`   |        10        | The number of rows displayed in the full table (resources) section.                                    |
| beebmx.x-ray.title           | `string` |      X Ray       | The title of the area.                                                                                 |
| beebmx.x-ray.overview        |  `enum`  | FilterType::Page | The `default` view in the `overview` section.                                                          |


Here's an example of a full use of the options from the `config.php` file:

```php
use Beebmx\KirbyXRay\Enums\FilterType;

'beebmx.x-ray' => [
    'autoclean' => [
        'files' => true,
        'pages' => true,
    ],
    'cache' => true,
    'icon' => 'x-ray-icon',
    'limit' => [
        'overview' => 5,
        'resource' => 10,
    ],
    'title' => 'X Ray',
    'overview' => FilterType::Page,
],
```

## License

Licensed under the [MIT](LICENSE.md).

## Credits

- Fernando Gutierrez [@beebmx](https://github.com/beebmx)
- Jonas Ceja [@jonatanjonas](https://github.com/jonatanjonas) `logo`
- [All Contributors](../../contributors)
