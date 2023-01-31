# Stockvell credit system

 - [Live Preview](http://stockvell.allinone-office.com)


### Deployment Instructions
 - All file owner change `sudo chown -R www-data:www-data project-name`
 - All file previlages change `sudo chmod -R 775 project-name`
 - Connect via SSH `ssh -p 2222 aiocel@ssh.web11.us.cloudlogin.co`
 - Install php curl `sudo apt-get install php7.4-curl`
 - Copy all file using file zilla, do not include bootstrap from `public/bootstrap`
 - Install all packages of Composer 
    ```
    composer install
    composer update
    composer show
    // or
    php composer.phar install
    php composer.phar update
    php composer.phar update "vendor/*"
    ```
 - Change all `.env` variables
 - Run `database-migrations.php`
 - Setup `.htaccess` file for redirecting 
 - Database Migrations (Delete database and create database once again)
 - Remove `database-migrations.php`
 - Delete `phpinfo.php` file and remove inclution from `index.php`
 - Remove this 2 lines from index.php
    ```
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ```

### Htaccess
 - [htaccess file not works in LAMP](https://stackoverflow.com/questions/28217272/htaccess-file-not-works-in-lamp)
 - [Redirect all to index.php using htaccess](https://stackoverflow.com/questions/18406156/redirect-all-to-index-php-using-htaccess)
 - [The server encountered an internal error or misconfiguration and was unable to complete your request](https://stackoverflow.com/questions/6438475/the-server-encountered-an-internal-error-or-misconfiguration-and-was-unable-to-c)
 - See logs - `cat /var/log/apache2/error.log`
 - Enable htaccess `systemctl restart apache2`

 - Change some code in `sudo nano /etc/apache2/sites-available/000-default.conf` to use htaccess
  ```
    <VirtualHost *:80>
      ServerAdmin webmaster@localhost
      DocumentRoot /var/www/html
      ErrorLog ${APACHE_LOG_DIR}/error.log
      CustomLog ${APACHE_LOG_DIR}/access.log combined
      <Directory /var/www/html>
        AllowOverride All
      </Directory>
    </VirtualHost>
  ```


### Planning

 - [Design prototype](https://www.figma.com/file/4zCozjL5sxemgv3l5gzmrW/Stockvell-Finance?node-id=0%3A1) Color gold and blue

 - For every stockvell they should have a goal (e.g. purchase a land)
 - Basically 2 types of user. 1, members and leaders (Seperated by roles), 2, administrator
 - Group of member save money (to purchase something for eid, new year or real estate properties)
 - Inviting people to the group to invest
 - Monthly contributions
 - Credit unions
 - Group members limit

### Pages
 - Home / landing page
 - Register page
 - Login page
 - List all stockvell pack(user is able to choose a pack according to his goal)
 - Single stockvell pack (terms and conditions, detail, leader)
 - List all stockvell pack for an user
 - Individual stockvell pack(details of the pack, add monthly deposit, due date)
 - Admin panel (only accessable to admin), List all stockvell pack for admin(Unapproved and approved stockvell pack)(admin is able to close stockvell pack), see application, assign leader, add or delete member,
 - Single stockvell
 - Validate upload image with more than 2 maga byte 







### Choose colors
 1. Choosing a dominant color. [Understands meanings of colors](https://www.color-meanings.com/)
 2. Add [two more colors](https://mycolor.space/) and take black and white as considerations/ [Select pallete, see their tutorial](https://coolors.co/c99127-0d1321-1d2d44-3e5c76-748cab)
Dominant - #C99127, Complementary - #809130, Accent - #2F4858, white - #ffffff, black - #000000
 3. Apply the 60/30/10 rule




### Composer
 - [docs](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos), [tutorial](https://www.youtube.com/watch?v=xWgtKALpx9E)
 - [Packagist got get all packages](https://packagist.org/)


### php functions
   - filter_var
   - [PHP Namespace Tutorial - Full PHP 8 Tutorial](https://www.youtube.com/watch?v=Jni9c0-NjrY), [Php OOP namespace](https://www.youtube.com/watch?v=YgUOSY581Wg)



### Send message with twilio api using curl and twillio
 - [Twilio Send an SMS using the Programmable SMS API](https://www.twilio.com/docs/sms/api)
 - [URL Library ¶](https://www.php.net/manual/en/book.curl.php#book.curl)
 - [Request example](https://stackoverflow.com/questions/2138527/php-curl-and-http-post-example)



### Left working on
 - Models/Stockvell/AdminStockvellForms.php -> closeStockvellByAdmin
 - Pending pack is not showing in admin panel
 - Use withdrawMemberMsg to send withdraw certificate message













  

