# Conference Manager

## Route List

Client View Routes (To be added to X-Stream as web media):

http://{serverURL}/CM/client/view/room

http://{serverURL}/CM/client/view/slide

http://{serverURL}/CM/client/view/directional

If you change Javascript or CSS, bust the cache for these URL's

Example:

http://{serverURL}/CM/client/view/room?v=2

Also, if you change JS or CSS update the version of the links in /resources/views/client/shared

Example: I change public/css/client/override/room.css.

Now I would have to update the file: /resources/views/client/shared/styles.blade.php

Example:

`<link rel="stylesheet" href="{{ asset('css/client/base.css?v=45') }}" />`

# IP Override

In C:\inetpub\wwwroot\cm\app\Http\Controllers\ClientEventsController, request IP will be '::1' when accessing from localhost, so override to a player IP that you are testing with in X-Stream (line 28 )





location config/conference.php

 # How often to refresh event data (seconds)

  'refresh' => 30,

  # How long to state on slide before moving to next (seconds)

  'slide' => 5,