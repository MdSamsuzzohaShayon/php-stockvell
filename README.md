# Stockvell credit system

 - [Live Preview](http://stockvell.allinone-office.com)

### Development
 - member dashboard -> member create stockvell, add two more fields (address proof, govt id), request to be the leader
 - Review all the functions once
 - Single pack translate  😎
 - Modify language change option from js 😎
 - Remove all space from agreement while creting stockvell pack
 - Display all stockvell for publics
 - refer link 
 - monthly payment made
 - show leader - leader activity in dashboard 😎
 - Create admin staff
 - Appoint leader of the pack  (make table of applicant) 😎
 - Member apply to be leader of the pack 😎
 - set max member of a pack 
 - the simple member submit a proof of address, and Government ID 😎
 - Join pack 😎
 - Mobile sidebar for admin and dashboard 😎
 - Edit admin profile details 😎
 - Edit all member details by admin 😎
 - Single pack page design 😎
 - Create and edit stockvell pack by admin  😎
 - Login with phone and email 😎
 - Make phone number full including country code and plus sign (modify database) 😎
 - Unique member phone number (signup, database) 😎
 - Signup image upload handler -> rename file 😎
 - Create all classes according to database table (1. stockvell, 2.members, 3.admins, 4.stockvell_to_member)
 - Working stock member leader relationship (many to many) 😎
 - Dynamic input field 😎
 - Many to many relationship for all members in stockvell 😎
 - Display signup error message 😎
 - Database migration 😎
 - TinyMCE || CK Editor text editor for stockvell agreement pack 😎
 - Remove files like login-con and signup-com 😎
 - Add Database.classes.php into another file 😎
 - Create dashboard for members (Multiple tabs for update member profile, add stockvell pack, edit) 😎
 - Create admin panel for admins (list of stockvell) 😎


### Deployment Instructions
 - Install all packages of Composer
 - Connect via SSH `ssh -p 2222 aiocel@ssh.web11.us.cloudlogin.co`
 - Install php curl `sudo apt-get install php7.4-curl`
 - Run `database-migrations.php`
 - Setup `.htaccess` file for redirecting 
 - Change all `.env` variables
 - All file owner change `sudo chown -R www-data:www-data project-name`
 - All file previlages change `sudo chmod -R 775 project-name`
 - Database Migrations (Delete database and create database once again)

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

### Modifications - 1

Registration Page
 - City is missing 😎
 - Country default is Benin 😎
 - Phone default country code is +229 😎
 - Government ID (png, jpeg, jpg or PDF only) 😎
 - Button to cancel is missing 😎

### Modifications - 2
 - signing up, there is no need of Gov ID
 - My password is incorrect or the information provided does not match our records
 - Login using phone : “+”  shows up
 - Signup phone input code js is not working
 - Address image not uploading when a user requested to the leader
 - Validate image or file size using javascript
 - Setup email server to send mail
 - Setup sms gateway to send message
 - Translate all page properly



Login Page
 - Use Phone/Email to login (to give both options to members) 😎
 - Forgot Password link is missing (to reset password) 😎

Member Dashboard :

A simple member can create a Stockvell Pack 
but he must be approved as Leader of the Pack before the pack become visible to others to join. 
I am not sure how technically it will be possible, 

but please suggest some possibilities so that a simple member does not create a Pack (only A LEADER or AN ADMIN can create a Pack). A simple must apply to be a Leader once he creates a Pack. To apply as LEADER, the simple member submit a proof of address, and Government ID.
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
 - Cannot view Government ID for now  😎
 - Cannot edit Member details
Saw 2 leaders for one pack (December pack) ; this pack was created by a member. Normally, it is 1 leader for each Pack. In other words, 01 Pack has only ONE leader
Add a pack is not working currently
 - Admin profile is missing (change admin details, and password)


Others
Languages : I can help you for the translation text in French for some parts. Just let me know how to do that


These are the general remarks we have for now.
Overall, we are satisfied with your work and please let’s open the new contract as we agree. I will accept and send the payment for the second part so that you can continue.


### Modifications - 2
 - Detail stockvell not working in admin (Pending pack)
 - No page for __http://stockvell.allinone-office.com/pack_single.php?stockvell_id=1__


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








### Question
 - Explain input validation
 Step-1: **Admin** create a pack
 Step-2: A **user** join the pack (many user can be the **member** of the a pack)
 Step-3: A member can make a request to be leader of the pack
 Step-4: Admin will verify his document and appoint as leader

 User types - There are 3 type of prople in this website
 1. Admin -> You who control the website
 2. User -> who signup with this website 
 2. Member -> Who join a stockvell pack he/she will be member of that pack  

 did you mean leader of a pack will be able to create another pack?
 - Input validation is already done (user can not leave it blank)

 - To help me to translate the page you can go to the file **languages/fr.php** and change text (It's an php array, if you still don't know how to do it let me know I will make a small video for you)
 - Input field already validated - some input fileds are required until you fill those it won't let you submit the form

### Message to client
 - admin url http://stockvell.allinone-office.com/admin (You can change or add another admin on database lavel). user email `stockvellexample@gmail.com` password `Test1234`
 - If you want to wadd another admin add the line from below to the `__construct` of `DatabaseMigrations` class on `database-migrations.php` file or run `http://stockvell.allinone-office.com/database-migrations.php`
  
  ```
  $this->addToTheAdminsTable("admins", "stockvell_admin", "youradmin@gmail.com", "+880_1785208590", "password_of_your_admin");
  ```
 - Translate language -> there is an array inside `languages/fr.php` -> You can add new line like this 
  ```
  "This is english language" => "This is translated french language",
  ```

<hr/>
<hr/>
<hr/>
<hr/>


All those point which has tick mark at the end are updated successfuly but I did not upload it to server, after finishing everything I will upload the website to server.
I have mention few things please read it and
____________________________________________________________________________________________________________________________________________________________
____________________________________________________________________________________________________________________________________________________________
____________________________________________________________________________________________________________________________________________________________
____________________________________________________________________________________________________________________________________________________________
    
### ADMIN Dashboard :
 1. Can you show the dropdown with the country name + code ✅✅
 2. All members menu : there is a word "Properties". What does that word mean - ➡️➡️➡️➡️➡️➡️➡️➡️ all the attributes or details of a user ✅✅
 3. All members menu : When you click on View, it says "404 page not found" ➡️➡️➡️➡️➡️➡️➡️➡️ If you create a new user and upload a photo this shouldn't show any error now  ✅✅
 4. Update member informations! : On that page, there is a duplicate of "Password". Can you replace one with "Confirm Password"  ✅✅
 5. Approved packs : On that page the pack "N1" has 02 leaders. Normally, there is ONE leader for each approved pack ➡️➡️➡️➡️➡️➡️➡️➡️ indeed the number 2 is not the number of leader it's id of the leader, anyways, changed it to name. ✅✅
 6. In the pack details page, is there a way to show the Leader also and use a symbol maybe "L" to indicate the leader. You can show the Leader at the top of the pack details. ➡️➡️➡️➡️➡️➡️➡️➡️ given L sign in the pack detail page (in member list table) ➡️➡️➡️➡️➡️➡️➡️➡️ showing them with "L" sign beside their name  ✅✅
 
 7. On the Admin dashboard, how can admin sees all the leaders and the packs associated. For example : Admin can see for each pack the leader (name,  contact, pack name, and a button or link to disapprove or approve the leader). For example, when a leader revoke from his position, Admin will downgrade him from Leader role to as a simple member ➡️➡️➡️➡️➡️➡️➡️➡️ made an option for suspending leader. if admin suspend leader he will no longer be the admin of the pack and you can make someone else a admin ✅✅

 8. On the admin dashboard : How can Admin see the document uploaded (Government ID and Address) edit stockvell , leader requests, see address and govt id proof, appoints leader, suspend leader, approve of disapprove leader request  ➡️➡️➡️➡️➡️➡️➡️➡️ in pending pack and approved pack I made 2 button those are leader request and detail if you click them you can see everything ✅✅

 9. On Member Dashboard, when creating a Pack, can you add currency selection next to payment field  ✅✅

 10. On Member dashboard, when creating a Pack, can you include the format of the file accepted to be uploaded. For example for Address Proof (PNG, JPEG, JPG, PDF) ; And Govt ID Proof (PNG, JPEG, JPG, PDF) ✅✅

 11. On Member dashboard, in Pending Pack menu, there is the table and the column "Total Members (u)" Why it said 10 as total members. The pack has not yet approved and there is only the pack owner.  ➡️➡️➡️➡️➡️➡️➡️➡️ removed it because of member knows there is no member until the pack is approved ✅✅

 12. On Member dashboard, in Pending Pack menu, there is the table and the column "Withdraw Period" Why it said 1. What is the period of withdraw? Withdraw can be weekly or monthly by a member in the group.  ✅✅

 13. When an approved pack already have a leader, can you prevent anyone other to click the button "Leader Request". In other words, an approved Pack has one unique Leader at a time. When the leader resigns or when admin désapprouve that leader, another member can then request.. Picture 2   ✅✅

 14. On Admin Profile!, we tried to edit the phone, but when we clicked on Update, it shows "HTTP ERROR 500"  ✅✅

 15. Where can admin approved a member as Leader of the dashboard? ➡️➡️➡️➡️➡️➡️➡️➡️ Only a admin can see that from stockvell detail page  ✅✅

 16. Where can admin see the Proof of Address file ? ➡️➡️➡️➡️➡️➡️➡️➡️ Only a admin can see that from stockvell detail page ✅✅
 
 17. I sent you a message on 13 Sept. asking to keep the content of the home page when replacing the home page. See (picture 3)  ➡️➡️➡️➡️➡️➡️➡️➡️ Sorry I did not remember that, I deleted your previous home page code. do you have those content on you computer, please share with me on fiverr.

>>>>>There are 02 updates :
1) There is a new content on the home page at http://stockvell.allinone-office.com/
Please keep the text paragraph content when you make the changes.. You can backup the existing content and after when you are ready, you can copy the content in the new update.
2) Include or modify existing Illustration pictures at any section of the application. Example the illustration which is on the third section “ Stockvell is saving for more than 20,000 members of the Stockvell pack.” is very good reference. Find similar illustrations to use in the other parts of the application. Example the bottle picture of the top header will be replaced with illustrations. I don't know if I explain but please let me know
<<<

But, it seems that you brought back the old paragraphs content again...

For the illustration to change, please can you change that picture of the bottle on the home page with an illustration? You said it will not take more time. Please you can replace that.



 18. The user on the sign up page must see the checkboxes for Terms and conditions and Privacy policy: (check box to accept the terms) before sign up.  ✅✅

------
    19) The administrator must approve the closure of a Stockvell group. In other words, when the leader confirms the Pack is completed (all members have withdrawn their payment), the leader can close the Stockvell, BUT the Admin must approved the Closure before it is completely closed...

----
    20 The Stockvell leader defines next member to withdraw funds (withdrawal can be random or first come, first withdrawn)

----
 21. The Stockvell leader Generate a share link that members can use to share the stockvell in which they are in social networks. should the pack detail page visible to anyone? 
  Generate link by leader link will have 3 part or encoded string, 
    1) type (View, Edit) 
    2) random 4 digit code
    3) stockvell id
 22. How can the administrator manage the category of Stockvell (create, modify, delete) ? ➡️➡️➡️➡️➡️➡️➡️➡️ there is no option for that now, you said you only need 3 category so I add those three category. but you can add or delete from /config/option-list.php `$category_list` array  ✅✅
 23. The Stockvell leader defines the number of people. I cannot see that on the creation of the stockvell pack. prevent joining members if members reach the limit  ✅✅


### Questions
 - Can you make randomly selected the next member who can withdraw? ➡️➡️➡️➡️➡️➡️➡️➡️ If we select member to withdraw randomly after withdraw the same member can be selected by random selection system. What should I do for that?






# Update - 3 
____________________________________________________________________________________________________________________________________________________________
____________________________________________________________________________________________________________________________________________________________
____________________________________________________________________________________________________________________________________________________________
____________________________________________________________________________________________________________________________________________________________
    
### ADMIN Dashboard :
 1. Can you show the dropdown with the country name + code [NOT FIXED] ✅✅
 2. All members menu : there is a word "Properties". What does that word mean - ➡️➡️➡️➡️➡️➡️➡️➡️ all the attributes or details of a user [PLEASE replace "Properties" with "User Details"] ✅✅
 3. All members menu : When you click on View, it says "404 page not found" ➡️➡️➡️➡️➡️➡️➡️➡️ If you create a new user and upload a photo this shouldn't show any error now  ✅✅
 4. [this horizontal bar (check attached screenshot) does NOT to slide after clicking VIEW/EDIT in All Members page] ➡️➡️➡️➡️➡️➡️➡️➡️ when you click on those button you will be redirected to different page, there you don't have table or horizontal bar to slide (If I didnot get your proint could you please make a small video and explain the problem, because watching screenshot everything is not clear to me? ) ✅✅
 
 8. On the admin dashboard : How can Admin see the document uploaded (Government ID and Address) edit stockvell , leader requests, see address and govt id proof, appoints leader, suspend leader, approve of disapprove leader request  ➡️➡️➡️➡️➡️➡️➡️➡️ in pending pack and approved pack I made 2 button those are leader request and detail if you click them you can see everything ✅✅ [Govt ID when click shows 404 page - See video attached]
 
 
 88. On the Admin Profile Page the phone does not show Country and Country code on Admin Page. Can you make it like the others : Country (country code) ; example Benin (+229) ✅✅
 14. On Admin Profile!, we tried to edit the phone, but when we clicked on Update, it shows "old phone again" (see video) ✅✅
 89. On the Admin All approved packs!. Could you remove the two << symbols ? (see screenshot) ✅✅
 90. Could you replace in the admin menu the word "Close" with "Closed Packs" (see screenshot) ✅✅
 15. When admin tried to Appoint a leader, from the "View" pack, it show error on that URL : http://stockvell.allinone-office.com/includes/pack_single.inc.php (see video)
 
 
 
 91. on Sign up page , for country, put only the country. (see screenshot) ✅✅
 92. on Sign up page , for Government ID, please add the acceptable file format PDF, PNG, JPG, JPEG. (see screenshot) ✅✅
 93. On Member dashboard, in Pending Pack menu, there is the table and the column "Goa" empty. But I put the goal when creating the pack ✅✅
 94. On the site Pack Page, http://stockvell.allinone-office.com/packs , it show the $ currency. But the pack is created in CFA (see the two screenshots) ✅✅


95. Home page :
  Could you make the menu sticky so that the menu is visible when we scroll down ✅✅
  Illustration on the top header is too big OR the space is large in height so that we canot read How does it work?. Could you please reduce the height so that the text "How does it work?" appears when the visitor opens the home page and the visitor does not need to scroll too much. (see screenshot)  ✅✅

96. Withdraw member- The Stockvell leader defines next member to withdraw funds (withdrawal can be random or first come, first withdrawn): Did you make First come First serve ? ➡️➡️➡️➡️➡️➡️➡️➡️ Yes I have updated that as first come first serve. Additionally, admin can assign any member manually to withdraw


### Credentials
Admin panel : http://stockvell.allinone-office.com/admin
Email: stockvelladmin@gmail.com
Password: Test1234












  

