# Pohoda XML

## Instalacia

Pridaním do *composer.json*:

```json
{
    "require": {
        "rshop/pohoda": "1.x-dev"
    }
}
```

## Príklad exportu objednávok

```php
use Rshop\Synchronization\Pohoda;

$pohoda = new Pohoda('ICO');

// create file
$pohoda->open($filename, 'Import orders');

// create order
$order = $pohoda->createOrder([
    'numberOrder' => $order_number,
    'isReserved' => true,
    'date' => $created,
    'text' => '...',
    'partnerIdentity' => [
        'address' => [
            'name' => $billing_name,
            'street' => $billing_street,
            'city' => $billing_city,
            'zip' => $billing_zip,
            'email' => $email,
            'phone' => $phone
        ],
        'shipToAddress' => [
            'name' => $shipping_name,
            'street' => $shipping_street,
            'city' => $shipping_city,
            'zip' => $shipping_zip,
            'email' => $email,
            'phone' => $phone
        ]
    ]
]);

// add items
foreach ($items as $item) {
    $order->addItem([
        'code' => $item->code,
        'text' => $item->text,
        'quantity' => $item->quantity,
        'payVAT' => false,
        'rateVAT' => $item->rate,
        'homeCurrency' => [
            'unitPrice' => $item->unit_price
        ],
        'stockItem' => [
            'stockItem' => [
                'id' => $item->pohoda_id
            ]
        ]
    ]);
}

// add summary
$order->addSummary([
    'roundingDocument' => 'none'
]);

// add order to export (identified by $order_number)
$pohoda->addItem($order_number, $order);

// finish export
$pohoda->close();
```

## Príklad importu produktov

```php
use Rshop\Synchronization\Pohoda;

$pohoda = new Pohoda('ICO');

// load file
$pohoda->loadStock($filename);

while ($stock = $pohoda->next()) {
    // access header
    $header = $stock->children('stk', true)->stockHeader;

    // ...
}
```
