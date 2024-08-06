# [Typing Titans](https://typing.etownmca.com)

#### A guide to the subdirectories
- [test](test) - where the user takes a test from the selected level
- [level](level) - level selection page
- [libs](libs) - miscellaenous libraries needed for other pages
- [login](login) - pages for user login and registration
- [ranking](ranking) - user rankings page(s)
- [home](home) - website homepage

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