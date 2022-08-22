# Stockvell credit system

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
 - City
 - Country (default Benin Republic)
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
 - Defines next member to withdraw funds (withdrawal can be random or first come, first
served)
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

