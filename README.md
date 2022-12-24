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

- The API request parameters are always the correct data types and request parameter combinations are always valid.

- Flights do not last more than 24 hours.

- The times stored in flights are relative to the airport's timezone.

  - A flight from Montreal to Vancouver will have departure time in EST and arrival time in PST.

  - This also affects the resulting arrival date. A flight from Montreal to Vancouver from 10PM to 2AM will arrive one day later, but a flight from Montreal to Vancouver from 10PM to 8PM will arrive on the same day (8PM Vancouver is later than 10PM Montreal).

- A round trip always includes a return date. If a flight path can be finished before the return date, then the last flight is delayed until the return date.
