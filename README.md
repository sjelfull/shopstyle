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

## Options

* To add filters for brand, retailer, price, discount, and/or size, call the action URL with one or more `filter[]` parameters, ie. `/actions/shopStyle?filter[]=d0&filter[]=b171`
* To change the sorting, use the `sort` parameter: `/actions/shopStyle?sort=PriceLoHi`
* To select a category, use the `category` parameter: `/actions/shopStyle?category=dresses`
* To add a price drop date, use the `priceDropDate` parameter: `/actions/shopStyle?priceDropDate=<date>`

Brought to you by [Superbig](https://superbig.co)
