# discreeetly
discreeetly is perfect for when you don't want to create a burner Twitter account to detach yourself from a high-profile government leak, or for when you just want to tell someone that you think they're cute. It allows users to write tweets that will be posted to a twitter account, without the need for them to register for anything or give over any identifying information. Sometimes you just can't be the first person to tweet something out, so let discreeetly take the heat! It's running right now at [https://www.discreeetly.com](https://www.discreeetly.com)
#### Queue
The submitter's IP is hashed and the hash is saved with each submission so that the app can limit the amount of submissions that an IP can have queued up at any time. The maximum amount of submissions you choose to allow an IP to queue is a setting in the __config.php__. The submitter's real IP is never stored anywhere. 
#### Moderation
All of the submissions are moderated before they're actually tweeted out by the associated Twitter account. A human gets an opportunity to not let the app tweet out anything that could get the account shut down for violating Twitter's terms of service. It's up to the human on what they'll let through. The human will receive a Twitter DM for each of the submissions and the app will wait for the moderator to reply with a yes/no response, and then handles accordingly. 

## Setting it Up
#### Composer 
Run that `composer install` in the __/html__ folder. A popular Twitter API wrapper package is required.
#### Creating The Twitter App 
Create a new app with your Twitter account at [apps.twitter.com](https://apps.twitter.com), and when creating an access tokens make sure to include the option of allowing it to read/write direct messages. Copy the two app consumers keys and the two access token to the __config.php__
#### config.php
Also, go set the rest of these values. 
#### Database
Run the __schema.sql__ file in your MySQL/MariaDB environment. In order to support emojis, you need to make sure your database supports utf8mb4 character encoding. If yours doesn't, the __schema.sql__ will generate errors when you run it. 
#### cronjobs
There are two files in the __/cron__ directory that need to be scheduled to execute as often as possible. These scripts are what look for approved submissions that are waiting in the queue to be tweeted, and they look for new DMs from the moderator to act on. 
#### php.ini settings
If you are running this with an SSL certificate (and you should), make sure you set the following php.ini directives as follows:
- `session.cookie_http=1`
- `session.use_only_cookies=1`
- `session.cookie_secure=1`
 
## Feature Ideas for The Future
- Use sessions to allow the anonymous user to stay apprised to faves and replies of the tweets they've submitted, and allow them to send out reply tweets in that tweet thread. 
- Allowing the submission to be a __reply__ tweet, to an existing tweet.
- Allowing user to schedule their tweets.
 
## I Have Questions!
Send me a tweet [@itsmetomsmith](https://www.twitter.com/itsmetomsmith), or email me tom AT itsmetomsmith DOT com 