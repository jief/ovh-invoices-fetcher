<?php

require __DIR__ . '/vendor/autoload.php';

use \Ovh\Api;

if (!is_file('config.inc.php')) {
  die('Init config.inc.php before !' . PHP_EOL);
}

require  __DIR__ . '/config.inc.php';

try {
  $ovh = new Api($applicationKey, $applicationSecret, $endPoint, $consumerKey);

  $codes = $ovh->get('/me/bill', [
    'date.from' => date('y') . '-01-01',
    'date.to' => date('y') . '-12-31',
  ]);

  foreach ($codes as $code) {
    $invoice = $ovh->get('/me/bill/' . $code);

    if (isset($invoice['pdfUrl'])) {
      $date = new \DateTime($invoice['date']);

      $path = sprintf(
        'invoices/%s - OVH_%s.pdf',
        $date->format('Ymd'),
        $invoice['billId']
      );

      if (!is_file($path)) {
        echo sprintf('Downloading %s...' . PHP_EOL, $invoice['billId']);
        file_put_contents($path, file_get_contents($invoice['pdfUrl']));
      }
    }
  }
} catch (\Exception $e) {
  die($e->getMessage() . PHP_EOL);
}
