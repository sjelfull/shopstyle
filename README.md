# ShopStyle plugin for Craft CMS

Products feed from ShopStyle.

![Screenshot](resources/icon.png)

## Installation

To install ShopStyle, follow these steps:

1. Download & unzip the file and place the `shopstyle` directory into your `craft/plugins` directory
4. Install plugin in the Craft Control Panel under Settings > Plugins
5. The plugin folder should be named `shopstyle` for Craft to see it.

ShopStyle works on Craft 2.4.x and Craft 2.5.x.

## Configuring ShopStyle

Copy the config file shopstyle.php over to craft/config and add your API key.

```php
<?php
return [
    // Your API key
    'pid'        => '',

    // Limit results to this, or the total if that is below the max
    'maxResults' => 2000,
];
```

## Using ShopStyle

Call http://sitedomain/actions/shopStyle. It should return a JSON with all the products.

Brought to you by [Superbig](https://superbig.co)
