README

Installation process

1. DATABASE SETUP
	- Open your MySQL control panel. (e.g. phpMyAdmin)
	- Create new database and choose collation 'utf8_unicode_ci'. (e.g. ticket_system)
2. CONFIGURING DATABASE CONNECTION
	- After database is setup we need to edit one file.
	- To be more precise `./system/libraries/database/DatabaseConnection.php`
	- You will probably need to edit only those lines: 38, 44, 50, 56.
3. CONFIGURING APPLICATION
	- Before creating all the database structure we should edit some information about your application.
	- Open file `./database.sql` and edit those lines: 146-155. Not all of them are used at the moment, but later they will come in handy.
	- You could see some basic information already in place, so it shouldn't be to hard to replace it with your own.
	- Now execute the content of `database.sql` file. Now you should have all tables and starting data in place.
4. CACHING APPLICATION DATA (not complete)
	- To cache data for your application before using your main webiste URL, go to this one: 'http://your_webpage_address.your_domain_zone/temp/'
	- Now the data should be cached and you can start using the application.
5. FINISHED
	- Try the application on your default webpage URL: 'http://your_webpage_address.your_domain_zone/'


If you have any questions or would like to consult before fork, just let me know:
	Twitter: netjunky88
