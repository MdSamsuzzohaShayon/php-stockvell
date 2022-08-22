
Phusion Passenger application for reverse proxy, process manager, and operation tools
Login to cpanel -> software -> application manager

Register application
    





### Cpanel
 - [tutorial](https://www.youtube.com/watch?v=ipTn7RvIiMg)
 - Create a directory and keep all fast api files there (for example "financeapp")
 - Login to cpanel -> software -> search for "Setup Python App"
 - Create application
        - Select directory
        - Application URL = Select domain 
        - And create (leave rest of them blank)
 - Add a logfile (logs/passenger.log)
 - Inside project root folder create a "requirements.txt"
 - From application setup add that "requirements.txt" in configurating files section
 - Now install with pip
 - Upload all files to the root folder
