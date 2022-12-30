# Flight assessment

Online demonstration is available here: https://flight-assessment.000webhostapp.com/

## Overview

This is a simple API that returns a list of flights from one airport to another.

Requests use the following parameters:

- `departure_airport` - IATA code of the airport to depart from.
- `arrival_airport` -IATA code of the airport to arrive at.
- `departure_date` - Starting date of the trip. Assumes that the trip starts at 12AM in the first flight's timezone.
- `trip_type` - `one-way` or `round-trip`.
- `return_date` (optional) - The date the flight should return on or before. Assumes that any time until 11:59PM of the date in the last flight's timezone is valid.
  - This parameter is only used if the trip type is `round-trip`.
- `preferred_airline` (optional) - If this is set, then only flights from the specified airline will considered.
- `sort` (optional) - Sorts the resulting flights. Can be set to `price`, `duration` or `stops`.

## Setup

Get dependencies:

    composer install

Run automated tests:

    composer test

Run linter:

    composer lint

Start web server:

    composer start

## Assumptions

- The API request parameters are always the correct data types and request parameter combinations are always valid.

- Flights do not last more than 24 hours.

- The times stored in flights are relative to the airport's timezone.

  - A flight from Montreal to Vancouver will have departure time in EST and arrival time in PST.

  - This also affects the resulting arrival date. A flight from Montreal to Vancouver from 10PM to 2AM will arrive one day later, but a flight from Montreal to Vancouver from 10PM to 8PM will arrive on the same day (8PM Vancouver is later than 10PM Montreal).

- If a flight path can be finished before the return date, then the last flight is delayed until the return date.
