# Bud Challenge

This is a solution to Bud's coding challenge.

## Task:

### Part 1:
 - You are R2D2 and you have to download part of the Imperial Operating System’s Cybersecurity Center of Excellence API.
 - The base url is: https://death.star.api/
 - Each connection to the system requires an Oauth2 token. For the purposes of this exercise, this token will not expire.
(but you will have to get it from the token endpoint)
 - The system requires an SSL Certificate and key to be sent with every request, including the token.
- The endpoints are:
url: /token
response:'{"access_token": "e31a726c4b90462ccb7619e1b..", "expires_in": "99999999999", "token_type": "Bearer", "scope": "TheForce"}'
This endpoint accepts POST requests.

url: /reactor/exhaust/1 GET
headers: 
 - Authorization: Bearer [token]
 - Content-Type:  application/json
 - X-Torpedoes:   2

this endpoint accepts DELETE requests only.

/prison/leia
headers: 
 - Authorization: Bearer [token]
 - Content-Type:  application/json
this endpoint accepts GET requests only.

### Part 2:
 
- As a quick-thinking astromech droid, you will be called upon by witless humans to hack complicated defence systems on a fly. To do this, you have decided to mock the response as a series of ​ unit tests​ (long before you arrive at the Death Star).
These tests should cover success and failure. Each endpoint will be Droidspeak (known to humans as Binary), and will need to be processed and returned in Galactic Basic (commonly known as English).
- Write a service to undertake this.

url: /prison/leia

Response:
{
   "cell":  "01000011 01100101 01101100 01101100
             00100000 00110010 00110001 00111000
             0110111",
   "block": "01000100 01100101 01110100 01100101
             01101110 01110100 01101001 01101111
             01101110 00100000 01000010 01101100
             01101111 01100011 01101011 00100000
             01000001 01000001 00101101 00110010
             00110011 00101100"
 }

## Solution Details:

To impliment this solution took the following steps, all using TDD:
- Created BaseGateway to hold all commonly used functionality between gateways when making requests.
- Created Response object to wrap Guzzle responses because Guzzle's use of 'streams' makes them difficult to handle.
- Created DeathStarGateway that extended BaseGateway and added the functionality needed for the three requests in the task.
- Created a TranslatorService to translate Droidspeak to Basic.
- Created a HackingService that has both DeathStarGateway and TranslatorService as dependencies and implimented calls to DeathStarGateways endpoints.
- Added response parsing to HackingService to parse Droidspeak to Basic.
- Added error handling to throw an exception when a bad response is received.

## To Run Tests

- Make sure you have Composer installed on your local environment https://getcomposer.org/.
- Pull this repository to your local environment into a directory of your choosing.
- Within the repositories directory on the command line, run:
    - 'composer update', this should install all required dependencies.
    - './vendor/bin/phpunit' this should run all the tests.
