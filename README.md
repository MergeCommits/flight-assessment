# Flight assessment

## Setup

Get dependencies:

    composer install

Run automated tests:

    composer test

Run linter:

    composer lint

Start web server:

    composer start

## API Documentation

## Assumptions

- The API requests are always valid.

- The timezones stored in flights are relative to the airport's timezone.
  - A flight from Montreal to Vancouver will have departure time in EST and arrival time in PST.
