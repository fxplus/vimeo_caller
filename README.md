#Vimeo caller

###Acts as a very simple wrapper for vimeo API call.

Whilst trying to ingest vimeo channels into drupal I was having some issues with the ['Feeds Oauth'](https://www.drupal.org/project/feeds_oauth) module for drupal, and as it seemed overly complex to be configuring drupal to talk to vimeo, I decided to fetch and cache the data as json, then run *Feeds* over that data instead. 

This setup also results in one less active module, makes my (hardcoded) vimeo config verionable and as a side effect should help prevent reported conflicts between feeds_oauth and oauth modules. Also the php library used is officially maintained by Vimeo.