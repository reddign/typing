# [Typing Titans](https://typing.etownmca.com)

## Overview
- Take typing tests to determine your WPM (words per minute) and accuracy
- Choose from a variety of tests with varying durations and difficulties
- Your scores are automatically saved so you can see how you compare to other users

### Features
- [x] Save user scores
- [x] Ranking/Leaderboard page
- [x] Extensible level system
- [x] Light and dark modes
- [ ] User profile page
- [ ] User leveling system
- [ ] Level difficulty rating (Flesch Kincaid readibility tests)
- [ ] Per-level leaderboards

#### A guide to the subdirectories
- [test](test) - where the user takes a test from the selected level
- [level](level) - level selection page
- [libs](libs) - miscellaenous libraries needed for other pages
- [login](login) - pages for user login and registration
- [ranking](ranking) - user rankings page(s)
- [home](home) - website homepage
- [util](util) - miscellaneous utility scripts

#### Running from source
- Clone the repo with ```git clone https://github.com/reddign/typing.git```
- Create a file in the ```libs``` directory called ```dbcreds.ini``` with the following contents *(replace the temporary values with valid ones)*
```ini
# Credentials used to connect to the MySQL database
ip = "database.ip.address"
schema = "schema-with-needed-tables"
username = "your_username"
password = "your password"
```
- Start the webserver of your choosing and navigate to the project's url