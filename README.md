# Stockvell credit system

 - [Live Preview](stockvell.allinone-office.com)

### Development
 - Make phone number full including country code and plus sign (modify database)
 - Unique member phone number (signup, database)
 - Remove all space from agreement while creting stockvell pack
 - Signup image upload handler -> rename file
 - Display all stockvell for publics
 - Create all classes according to database table (1. stockvell, 2.members, 3.admins, 4.stockvell_to_member)
 - Working stock member leader relationship (many to many)
 - Dynamic input field 😎
 - Many to many relationship for all members in stockvell 😎
 - Display signup error message 😎
 - Database migration 😎
 - TinyMCE || CK Editor text editor for stockvell agreement pack 😎
 - Remove files like login-con and signup-com 😎
 - Add Database.classes.php into another file 😎
 - Create dashboard for members (Multiple tabs for update member profile, add stockvell pack, edit) 😎
 - Create admin panel for admins (list of stockvell) 😎

### Modifications

Registration Page
 - City is missing 😎
 - Country default is Benin 😎
 - Phone default country code is +229 😎
 - Government ID (png, jpeg, jpg or PDF only) 😎
 - Button to cancel is missing 😎


Login Page
 - Use Phone/Email to login (to give both options to members)
 - Forgot Password link is missing (to reset password)

Member Dashboard :
Ideally, a simple member can create a Stockvell Pack but he must be approved as Leader of the Pack before the pack become visible to others to join. I am not sure how technically it will be possible, but please suggest some possibilities so that a simple member does not create a Pack (only A LEADER or AN ADMIN can create a Pack). A simple must apply to be a Leader once he creates a Pack. To apply as LEADER, the simple member submit a proof of address, and Government ID.
And as leader, he will be able to define next member to withdraw funds (withdrawal can be random or first come, first served)
Define the number of people is missing (can be dropdown from 02 to max 100)
 - Payment frequency (dropdown ??)
 - Withdrawal frequency (dropdown ??)
 - Generate a share link that members can use to share in their social networks 

Error Message
When member Creates Stockvell page and did NOT fill out all the fields, he got he following error appear:
Not Found
The requested URL was not found on this server.
Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request.
http://stockvell.allinone-office.com/dasboard.php?error=emptyinput

Can you block the submission when a field is empty so that it does not show that error

Admin Page:
 - Cannot view Government ID for now
 - Cannot edit Member details
Saw 2 leaders for one pack (December pack) ; this pack was created by a member. Normally, it is 1 leader for each Pack. In other words, 01 Pack has only ONE leader
Add a pack is not working currently
 - Admin profile is missing (change admin details, and password)


Others
Languages : I can help you for the translation text in French for some parts. Just let me know how to do that


These are the general remarks we have for now.
Overall, we are satisfied with your work and please let’s open the new contract as we agree. I will accept and send the payment for the second part so that you can continue.


Many thanks


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


### There are 03 users
#### A The Members
The member registers and chooses a Stockvell Pack according to his goal (if it is for new year
or other)
He fills in the registration form with the following information:
 - First Name
 - Surname
 - Phone (international phone; default +229 country code)
 - Gender (male or female)
 - Profession
 - Interest
 - Country (default Benin Republic)
 - City
 - Government ID (file upload)
 - How do you hear about the Stockvell platform? (Text area)
 - Terms and conditions and Privacy policy: (check box to accept the terms)
The member can join as many Stockvell Pack. But, to join a Stockvell, the member must read
and accept the contract of the Stockvell
A member who creates a Stockvell pack can apply to become the leader of the pack by verifying
his address and identity. The administrator can approve/reject his or her request.
Any member can apply be a Stockvell leader.
#### B The Administrator
 - Manage Stockvell Leaders.
 - Manage Members.
 - Assign leader role: A member can be a Stockvell Leader in another Stockvell group.
 - Manage Stockvell Pack: Create, Modify, approve the closure of a Stockvell group.
 - Manage the platform and act as a guarantor.

#### C The Stockvell leader
 - Creates Stokvel Pack
 - Creates the constitution/contract; a text to be read and accepted by the members to join
 - Defines the purpose
 - Choose the category of Stokvel
 - Defines the number of people
 - Set the amount of money to be paid
 - Choose The frequency (weekly or monthly) of deposit of funds
 - Generate a share link that members can use to share in their social networks
 - Set the frequency of withdrawal in the Stockvell group (Monthly or Weekly)
 - Defines next member to withdraw funds (withdrawal can be random or first come, first served)




#### D 03 Stokvel Categories (possibility for admin to create new ones)
 1. Social: (Social orientation: Funeral / baptism, communion, etc.)
 2. Professional: (Commercial: They have projects; purchase of land, vehicles, etc.)
 3. Investment: (The leader of that stokvel will auction the fund and someone will take it.
Someone from outside signs a contract and pays with interest to the Stockvell group)
#### E Language:
Should be in French or English but translatable to French from admin or using a string file
#### F Responsive
Should be responsive




### Choose colors
 1. Choosing a dominant color. [Understands meanings of colors](https://www.color-meanings.com/)
 2. Add [two more colors](https://mycolor.space/) and take black and white as considerations/ [Select pallete, see their tutorial](https://coolors.co/c99127-0d1321-1d2d44-3e5c76-748cab)
Dominant - #C99127, Complementary - #809130, Accent - #2F4858, white - #ffffff, black - #000000
 3. Apply the 60/30/10 rule





### Async database
 - SQLAlchemy doesn't have compatibility for using await directly,
 - [Ecommerce API with tortoise orm(important)](https://github.com/Princekrampah/learningFastAPI/blob/master/shoppingAPI/models.py)

### Composer
 - [docs](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos), [tutorial](https://www.youtube.com/watch?v=xWgtKALpx9E)
 - [Packagist got get all packages](https://packagist.org/)


### php functions
   - filter_var
   - [PHP Namespace Tutorial - Full PHP 8 Tutorial](https://www.youtube.com/watch?v=Jni9c0-NjrY), [Php OOP namespace](https://www.youtube.com/watch?v=YgUOSY581Wg)

### MySQL Database

  - Create members table
  ```
    CREATE TABLE members(
      id INT NOT NULL AUTO_INCREMENT,
      firstname VARCHAR(100) NOT NULL,
      surname VARCHAR(100) NOT NULL,
      email VARCHAR(255) NOT NULL,
      status VARCHAR(100) NOT NULL DEFAULT 'PENDING',
      password VARCHAR(255) NOT NULL,
      country VARCHAR(100) NOT NULL,
      phone VARCHAR(100) NOT NULL,
      gender VARCHAR(60) NOT NULL,
      profession VARCHAR(100) NOT NULL,
      interest TEXT,
      govt_id VARCHAR(100) NOT NULL,
      source TEXT,
      role VARCHAR(255) NOT NULL DEFAULT 'GENERAL',
      PRIMARY KEY (id)
    );
  ```

  - Create stockvells table
  ```
  CREATE TABLE stockvells(
    id INT  NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    agreement TEXT NOT NULL,
    goal VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    status VARCHAR(100) NOT NULL DEFAULT 'PENDING',
    payment INT NOT NULL,
    payment_frequency INT NOT NULL,
    withdraw_frequency INT NOT NULL,
    withdraw_member_id INT,
    member_id INT,
    leader_id INT,
    PRIMARY KEY(id),
    FOREIGN KEY(member_id) REFERENCES members(id),
    FOREIGN KEY(leader_id) REFERENCES members(id),
    FOREIGN KEY(withdraw_member_id) REFERENCES members(id),
    );
  ```

  - Change table if anything went wrong
  ```
  ALTER TABLE members MODIFY role VARCHAR(255) NOT NULL DEFAULT 'GENERAL';
  ```

  - Enable or disable check for foreign key
  ```
  SET FOREIGN_KEY_CHECKS=0; 
  SET FOREIGN_KEY_CHECKS=1;
  ```
  - Get table information
  ```
  DESCRIBE tablename;
  ```
  - [Many to many relationship](https://www.vultr.com/docs/using-many-to-many-sql-relationships-and-intermediate-tables/), [m-to-m](https://stackoverflow.com/questions/2923809/many-to-many-relationships-examples), [m-to-m](https://dba.stackexchange.com/questions/151904/mapping-many-to-many-relationship), 
#### Stockvell relationship 
 - Insert a member and a stockvell (create a stockvell and add a member to it)
  ```
  INSERT INTO stockvell_to_member(stockvell_id, member_id) VALUES (5, 9);
  INSERT INTO stockvell_to_member(stockvell_id, member_id) VALUES (6, 9);
  ```
 - Populate from relational table. Get all the members of a stockvell pack. [Tutorial wds](https://www.youtube.com/watch?v=p3qvj9hO_Bo), [tutorial traversy media](https://www.youtube.com/watch?v=9ylj9NR0Lcg&t=3640s)
  ```
  SELECT members.id, members.firstname FROM stockvell_to_member LEFT JOIN members
  ON stockvell_to_member.member_id = members.id
  WHERE stockvell_to_member.stockvell_id = '6';
  ```

 - Get all the stockvell that a person member of
  ```
  SELECT stockvells.id, stockvells.name, stockvells.status, stockvells.category FROM stockvell_to_member 
  LEFT JOIN stockvells
  ON stockvell_to_member.stockvell_id = stockvells.id
  WHERE stockvell_to_member.member_id = 6;
  ```
 - Get all collumns of both table - **Get stockvell with leader**, [tutorial](https://www.youtube.com/watch?v=bPeQHlmfPPk&list=PL0b6OzIxLPbzf12lu5etX_vjN-eUxgxnr&index=21)
  ```
  // INNER JOIN and JOIN both are same
  // Where both table has same properties we could "ON" to join table
  SELECT * FROM stockvells INNER JOIN members ON stockvells.leader_id = members.id;
  // Using name allies
  SELECT * FROM stockvells s INNER JOIN members m ON s.leader_id = m.id;
  // Select specific collumns from both table
  SELECT s.id, s.name, s.goal, s.category, m.firstname, m.email FROM stockvells s INNER JOIN members m ON s.leader_id = m.id;
  // We can also use WHERE clause here
  SELECT s.id, s.name, s.goal, s.category, m.firstname, m.email, m.gender FROM stockvells s INNER JOIN members m ON s.leader_id = m.id WHERE gender='male';
  // Order by for making it ascending or descending order according to specific column 
  SELECT s.id, s.name, s.goal, s.category, m.firstname, m.email, m.gender FROM stockvells s JOIN members m ON s.leader_id = m.id WHERE gender='male' ORDER BY m.firstname;
  ```
 - Left join returns all records from the left table and the match record from the right table [tutorial](https://www.youtube.com/watch?v=sEjnXJOytzc&list=PL0b6OzIxLPbzf12lu5etX_vjN-eUxgxnr&index=22)

  ```
  // bring all records from stockvell_to_member (left table), does not matter it matches with foreign key member id or not.
  SELECT * FROM stockvell_to_member LEFT JOIN members ON stockvell_to_member.member_id = members.id;
  // here left table is stockvell_to_member
  SELECT sm.id, sm.stockvell_id, sm.member_id, m.firstname, m.email FROM stockvell_to_member sm LEFT JOIN members m ON sm.member_id = m.id;
  // here right table is members - this will display all the columns of right table (no matter stockvell_to_member has a relationship or not)
  SELECT sm.id, sm.stockvell_id, sm.member_id, m.firstname, m.email FROM stockvell_to_member sm RIGHT JOIN members m ON sm.member_id = m.id;
  ```
- MySQL JOIN Multiple Tables - **Get stockvells with all of their members**. [Tutorial](https://www.youtube.com/watch?v=dkY-KvFtNFM&list=PL0b6OzIxLPbzf12lu5etX_vjN-eUxgxnr&index=24)
  ```
  // Getting all records from stockvell_to_member
  SELECT sm.id, s.name, m.firstname FROM stockvell_to_member sm INNER JOIN members m ON sm.member_id = m.id INNER JOIN stockvells s ON sm.stockvell_id=s.id;
  ```

- "GROUP BY" clause is used in conjuction with the select statement and aggregate functions to group together by common column value.
  ```
  // get all stockvells and their total member
  SELECT member_id, COUNT(member_id) FROM stockvell_to_member GROUP BY member_id;
  // Change table header name
  SELECT member_id, COUNT(member_id) AS total_members  FROM stockvell_to_member GROUP BY member_id;
  // Use where and "join" before "group by" and "order by" after "group by"
  SELECT s.name, COUNT(sm.member_id) AS total_members  FROM stockvell_to_member sm INNER JOIN stockvells s ON sm.member_id = s.id WHERE s.payment <= 200 GROUP BY sm.member_id ORDER BY COUNT(sm.member_id) DESC;
  // approved Working query
  SELECT s.name, sm.stockvell_id, COUNT(sm.stockvell_id) as totel_members FROM stockvell_to_member sm LEFT JOIN stockvells s ON sm.stockvell_id=s.id GROUP BY sm.stockvell_id;
  ```


- Sub query or nested query (Works with insert, update, delete, and select query). [tutorial](https://www.youtube.com/watch?v=VxiF_MgePL8&list=PL0b6OzIxLPbzf12lu5etX_vjN-eUxgxnr&index=26)


### Send message with twilio api using curl and twillio
 - [Twilio Send an SMS using the Programmable SMS API](https://www.twilio.com/docs/sms/api)
 - [URL Library ¶](https://www.php.net/manual/en/book.curl.php#book.curl)
 - [Request example](https://stackoverflow.com/questions/2138527/php-curl-and-http-post-example)




### Htaccess
 - [htaccess file not works in LAMP](https://stackoverflow.com/questions/28217272/htaccess-file-not-works-in-lamp)
 - [Redirect all to index.php using htaccess](https://stackoverflow.com/questions/18406156/redirect-all-to-index-php-using-htaccess)
 - [The server encountered an internal error or misconfiguration and was unable to complete your request](https://stackoverflow.com/questions/6438475/the-server-encountered-an-internal-error-or-misconfiguration-and-was-unable-to-c)

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


  

