# OVH INVOICES FETCHER

Tiny script to download automatically PDF invoices from OVH.

Inspired by https://github.com/dunglas/stripe-invoice-exporter

## INSTALL
run `composer install`

## OVH TOKEN
Go to https://eu.api.ovh.com/createToken/ and create a new token.

:eyes: Pay attention to the `Rights section`.

![Alt text](screenshot.png?raw=true "Create a token")

Copy `.env.example` to `.env` and adapt it.

## RUN
`php download.php`

The invoices are in the `invoices` folder.

## TODO
* Replace hardcoded date (from & to) by args
