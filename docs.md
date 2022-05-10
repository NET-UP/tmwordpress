# Developer Documentation

# Data stores

## Database tables

- **ticketmachine_config** - contains all configuration variables
- **ticketmachine_design** - contains all theme related variables
- **ticketmachine_log** - contains custom error & access logs

## Getting configuration variables

All variables from the **ticketmachine_config** database are accessible from the variable `global $tm_globals.`

> _Note: this does not include theme related variables from the table "ticketmachine_design". use `ticketmachine_debug($tm_globals)` to see the available options._

## Current user locale

Current user local can be accessed through `$tm_globals->locale`

> Translations are now managed by WordPress Polyglots.

# API Communication

All API variables such as the base url, tokens and secrets can be globally accessed with `global $tm_api`.

> You can switch between our staging and production API by changing the **api_environment** field in the **ticketmachine_config** table or overwriting `$tm_api->environment` locally.
>
> Example base URL for production: https://cloud.ticketmachine.de/api/v2/

## Getting an access token

> The API uses the typical OAuth2 authentication method.

    ticketmachine_tmapi_get_access_token($refresh_token, $status)

Retrieves an access token from the API and stores it locally.

---

    ticketmachine_tmapi_refresh_token_check()

Checks if the access token has expired, and requests a new one using the refresh token.

---

    ticketmachine_apiRequest($url, $data, $method, $headers)

Send a GET or POST request to the specified API endpoint and return the response.

> Example URL to get all events from organizer **abc** sorted by **date**:
> `https://cloud.ticketmachine.de/api/v2/events?organizer.og_abbreviation[eq]=abc&sort=ev_date`

# Helper functions

> All helper & utility functions can be found in the root file `utils.php`

    ticketmachine_debug($object)

Returns a preformatted `print_r($object)` for a nicer way to debug objects and arrays.

---

Write to the **ticketmachine_log** table to generate custom log files:

    ticketmachine_log($message, $type)

---

Add one or more key value pairs to an existing array:

    ticketmachine_array_push_assoc($array, $key, $value)

---

Internationalize date strings and objects into the users timezone:

    ticketmachine_i18n_date($format, $datetime)

---

Convert an internationalized date string back into a UTC ISO8601 date format:

    ticketmachine_i18n_reverse_date()

# Third party tools

## Fontawesome 5

- Font Awesome Free 5.12.0 by @fontawesome - https://fontawesome.com

- License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License)

## Bootstrap 4

- Bootstrap v4.3.1 (https://getbootstrap.com/)

- Copyright 2011-2019 The Bootstrap Authors

- Copyright 2011-2019 Twitter, Inc.

- Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)

## JQuery

- jQuery v3.3.1 | (c) JS Foundation and other contributors | jquery.org/license

- Licensed under MIT (https://github.com/jquery/jquery/blob/master/LICENSE.txt)

## JQuery UI

- jQuery UI - v1.12.1 - 2016-09-14

- http://jqueryui.com

- Copyright jQuery Foundation and other contributors;

- Licensed under MIT (https://github.com/jquery/jquery-ui/blob/master/LICENSE.txt)

## FullCalendar 4

- fullcalendar/core v4.0.1

- (c) 2019 Adam Shaw

- Licensed under MIT (https://github.com/fullcalendar/fullcalendar/blob/master/LICENSE.txt)

## Popper.js

- Copyright (C) Federico Zivolo 2017

- Distributed under the MIT License (license terms are at http://opensource.org/licenses/MIT).
