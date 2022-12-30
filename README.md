# Flight assessment

Online demonstration is available here: https://flight-assessment.000webhostapp.com/

## Overview

This is a simple API that returns a list of flights that can be taken to get from one airport to another with several parameters. Requests use the following parameters:

- `departure_airport` is the IATA code of the airport to depart from.
- `arrival_airport` is the IATA code of the airport to arrive at.
- `departure_date` is the date to depart on.
- `trip_type` is either `one-way` or `round-trip`.
- `return_date` is the date to return on. This is only used if `trip_type` is `round-trip`.
- `preferred_airline` is the IATA code of the preferred airline. If this is set, then only flights from this airline will be returned.

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

- If a flight path can be finished before the return date, then the last flight is delayed until the return date.
