# Multi-Source Data Providers Task

## Prerequisite Before Initialization
- PHP >= 8.0
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension

## Installation Steps
- At first, we have to run =>  composer install  <=
- Then if you need to run test please use one of these commands =>  phpunit OR  ./vendor/bin/phpunit  <=
- Finally, for running our application please run =>  php -S localhost:8000 -t public   <=

## Testing Our Application

- PostMan Collection included in project files in main path for easy testing, also here's a link for importing the collection directly https://www.getpostman.com/collections/4a97a9deaab496cd0d05.
- storage folder includes liveFiles folder which contain provider's JSON files, also it contains mockingFiles for providers' JSON files for testing purpose.


## Summery

- Adapter Design Pattern has been used for solving multi-source data issue.
- Also, adapter gives me the ability to mock our source files during testing.
- Finally, Thanks for your consideration of my profile.
