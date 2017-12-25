By KAREEM YUSUF OLATAYO
Follow the stets below to setup the simple cms php application:
1.create a database in mysql. you can use phpmyadim to simplify your work
2.open constants.php file located in the includes folder inside the cms folder
3.setup the following data:server name, server user and password and database name created earlia
  define("DB_SERVER", "localhost");
  define("DB_USER","root");
  define("DB_PASS", "");
  define("DB_NAME", "cms");
4.Run cms.sql(located in the cms folder i.e your app root folder) inside your newly created database. you 
can use phpmyadmin to import cms.sql and run the script which will create 3 tables and data for you in your 
database
5.open index.php in your browser(the public area or your site) and open staff.php(the admin area of your site)
6.login to the admin area with username:admin and password:admin
Enjoy and Happy coding. Thanks