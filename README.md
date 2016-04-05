# Laravel Mollie

This package allows you to use Mollie within Laravel 5.X.

## Installation

To install this library use the following command

```php
composer require paradox-nl/laravel-mollie
```

Next up register the ServiceProvider

```php
\ParadoxNL\Mollie\MollieServiceProvider::class
```

And register the alias (optional)

```php
'Mollie' => \ParadoxNL\Mollie\Facades\Mollie::class,
```

To publish the config:

```php
php artisan vendor:publish
```

The config is now located in ```config/mollie.php```. Here you can set an API key and the webhooks url

## Original documentation

[https://www.mollie.com/en/docs/overview](https://www.mollie.com/en/docs/overview)

## Methods

This package provides a couple methods straight out of the box, however if you wish to use the native methods you can use the ```getClient()``` and use the native Mollie API client.

### Create payment

Method to create a new payment

| Parameters  | Type             | Explanation                                                                                                                       |
|-------------|------------------|-----------------------------------------------------------------------------------------------------------------------------------|
| id          | integer          | Self generated order id (mollie uses time() as default)                                                                           |
| amount      | decimal          | The amount in EURO that you want to charge, e.g. 100.00 if you would want to charge €100.00.                                      |
| description | string           | The description of the payment you're creating. This will be shown to the consumer on their card or bank statement when possible. |
| type        | enum             | IDEAL,PAYPAL, PAYSAFECARD, CREDITCARD, MISTERCASH, SOFORT, BANKTRANSFER, DIRECTDEBIT, BITCOIN, BELFIUS, PODIUMCADEAUKAART         |
| parameters  | array (optional) | Extra request parameters, see official documentation for options                                                                  |
| meta_data   | array (optional) | Extra meta data to be added to the request, can be retrieved later on.                                                            |

#### Example:

```php
Mollie::createPayment(
	time(),
	10.00,
	'Some description',
	Mollie_API_Object_Method::IDEAL,
	['issuer' => "ideal_INGNL2A"],
	['extra' => 'data']
);
```

#### Returns

```json
HTTP/1.1 201 Created
Content-Type: application/json; charset=utf-8

{
    "id":              "tr_7UhSN1zuXS",
    "mode":            "test",
    "createdDatetime": "2014-06-05T08:29:39.0Z",
    "status":          "open",
    "expiryPeriod":    "PT15M",
    "amount":          10.00,
    "description":     "My first payment",
    "metadata": {
        "order_id": "12345"
    },
    "locale": "nl",
    "profileId": "pfl_QkEhN94Ba",
    "links": {
        "paymentUrl":  "https://www.mollie.com/payscreen/select-method/7UhSN1zuXS",
        "redirectUrl": "https://webshop.example.org/order/12345/"
    }
}
```


### isPaid

Method to check whether an order is paid or not.

```php
Mollie::isPaid(Illuminate\Http\Request $request)
```

#### Returns

boolean

### isOpen

Method to check whether an order is open.

```php
Mollie::isOpen(Illuminate\Http\Request $request)
```

#### Returns

boolean

### History

Method to list all transactions with a pagination, configurable by config.

```php
Mollie::history()
```

#### Returns

Array containing list of past transactions.

### getClient

Returns the native Mollie API client

```php
Mollie::getClient()->nativeMethod()
```

#### Returns

Mollie_API_Client
