<?php

require __DIR__ . '/vendor/autoload.php';

use \Ovh\Api;

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $ovh = new Api(
        $_SERVER['applicationKey'],
        $_SERVER['applicationSecret'],
        $_SERVER['endPoint'],
        $_SERVER['consumerKey']
    );

    $codes = $ovh->get(
        '/me/bill', [
          'date.from' => date('y') . '-01-01',
          'date.to' => date('y') . '-12-31',
        ]
    );

    foreach ($codes as $code) {
        $invoice = $ovh->get('/me/bill/' . $code);

        if (isset($invoice['pdfUrl'])) {
            $date = new \DateTime($invoice['date']);

            $path = sprintf('invoices/%s - OVH_%s.pdf', $date->format('Ymd'), $invoice['billId']);

            if (!is_file($path)) {
                  echo sprintf('Downloading %s...' . PHP_EOL, $invoice['billId']);
                  file_put_contents($path, file_get_contents($invoice['pdfUrl']));
            }
        }
    }
} catch (\Exception $e) {
    die($e->getMessage() . PHP_EOL);
}
