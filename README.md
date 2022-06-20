## Craft Reference Tags UI

This package provides an advanced UI component that allows the user to
generate [Reference Tags](https://craftcms.com/docs/4.x/reference-tags.html) directly from the element editor.

### Requirements

This plugin requires Craft CMS 4.x or later.

### Installation

1. Include the package:

```
composer require matfish/craft-ref-tags-ui
```

2. Install the plugin:

```
php craft plugin/install ref-tags-ui
```

### Usage

On any Element editor page (Entry, Asset, Category, Tag) click `Ctrl+Alt+R` to
trigger the modal:


![](https://user-images.githubusercontent.com/1510460/174532283-945ebd5c-7aaa-4c1f-ac68-d61cea1fd43d.png)

By Default the modal will load Global Sets, as this is the most common use case.

Once all required fields have been filled in, the Reference Tag will be displayed at the bottom. Clicking on it will
copy the tag to the clipboard and close the modal.

Now you can simply paste the tag in every text field on your page.

Remember that when rendering the field on the Front end you will need to use the `parseRefs` filter:

```twig
{{ entry.myField | parseRefs }}
```

#### Changing the default trigger

Create a `config/ref-tags-ui.php` file:

```
<?php

return  [
    'trigger'=>'Ctrl+Alt+R'
];
```

Set the `trigger` value to whatever combination you prefer. E.g:

```
    'trigger'=>'Ctrl+Shift+H'
```

### License

You can try Reference Tags UI in a development environment for as long as you like. Once your site goes live, you are
required to purchase a license for the plugin. License is purchasable through
the [Craft Plugin Store](https://plugins.craftcms.com/ref-tags-ui).

For more information, see
Craft's [Commercial Plugin Licensing](https://craftcms.com/docs/3.x/plugins.html#commercial-plugin-licensing).
