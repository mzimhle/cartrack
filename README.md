# Background
I had decided to put in two websites for my assessment:
- http://api.cartrack.loc/
- http://cartrack.loc/

The first website, http://api.cartrack.loc/, is the one that holds the API endpoints, where our CRUD calls will be made. The second website, http://cartrack.loc/, is the website where we will be calling the api.

I created two tables in my database on heroku, animal and member, the website on http://cartrack.loc/ allows you to list, update, delete add and search for these two tables.

# Folder Structure
This is the structure of each of the two website as mentioned above:
* cartrack.loc/
    * api/
        * config/
        * library/
    * logs/
    * www/
        * animal/
        * config/
        * css/
        * includes/
        * library/
        * member/

The api/ folder is the root folder for http://api.cartrack.loc/ and the www/ folder is the root folder for http://cartrack.loc/.

In each website, there is a config/ folder, where you will see, one or all of these files depending on which website root folder you are looking at, config/database.php and/or config/settings.ini.
The settings.ini file contains all passwords and log in details that will be required globally in the website, in this the case of the API website, it keeps all database login details for heroku. In the case of the cartrack.loc website, it keeps all api links needed for crud executions on it.

The database.php file contains the database connection, it is ONLY in the api/config folder.

# API Website
This is where we will be calling out API, we have 5 calls to make:
##### http://api.cartrack.collop.co.za/search.php

The Accepts only GET arguments.
*   entity (animal or member string)
*   filter_search (This is string required)
*   iDisplayStart (optional integer - default 0)
*   iDisplayLength (optional integer - default 100)

Returns json data of animal or member results for searching, structure is:

```
{
    "code":200,
    "message":"Records found",
    "record":[
                {
                    "id":"14",
                    "name":"Akhona",
                    "cellphone":"0824589654",
                    "email":"akhona.zwemu@yahoo.com",
                    "added":"2021-05-25 17:40:26",
                    "updated":null
                },{
                    ...
                }],
                "count":7,
                "display":7
}
```
Only codes returned are 200 for success (even if no data is returned) and 500 for failure.

##### http://api.cartrack.collop.co.za/insert.php
Accepts GET arguments:
*   entity (animal or member string)
*   name (required name of member or animal)
*   cellphone (required cellphone of member only)
*   email (optional email of member only)

Returns json data for animal and member:
```
{
    "code":200,
    "message":"New record added",
    "record":[],
    "count":0,
    "display":0
}
```
The return json 'code' is either 200 with 'message', "New record added" on successful insert and 'code' is 500 with 'message' "No record added" on failure.

##### http://api.cartrack.collop.co.za/view.php
Accepts GET arguments:
*   entity (animal or member string)
*   id (required id of member or animal)

Return json data 
```
{
    "code":200,
    "message":"Record found",
    "record":
        {
            "id":"14",
            "name":"Akhona",
            "cellphone":"0824589654",
            "email":"akhona.zwemu@yahoo.com",
            "added":"2021-05-25 17:40:26",
            "updated":null
        },
    "count":0,
    "display":0
}
```
The return json 'code' is either 200 with 'message', "Record found" on successful insert and 'code' is 500 with 'message' "Record not found" on failure.

##### http://api.cartrack.collop.co.za/delete.php
Accepts GET arguments:
*   entity (animal or member string)
*   id (required id of member or animal)

Return json data 
```
{
    "code":200,
    "message":"Successfully deleted",
    "record": [],
    "count":0,
    "display":0
}
```
The return json 'code' is either 200 with 'message', "Successfully deleted" on successful insert and 'code' is 500 with 'message' "No record was deleted" on failure.

##### http://api.cartrack.collop.co.za/update.php

Accepts GET arguments:
*   entity (animal or member string)
*   id (required id of member or animal)
*   name (required name of member or animal)
*   cellphone (required cellphone of member only)
*   email (optional email of member only)

Return json data 
```
{
    "code":200,
    "message":"Successfully updated",
    "record": [],
    "count":0,
    "display":0
}
```
The return json 'code' is either 200 with 'message', "Successfully deleted" on successful insert and 'code' is 500 with 'message' "No record was deleted" on failure.

# API Classes ( http://api.cartrack.loc/ )

/api/library/classes/abstract/crud.php

This is the class that will be extended by our animal and member classes, it contains the database connection, the methods that insert, delete, update, view, fetch and fetch all records for each table's class file.

/api/library/classes/class/animal.php
/api/library/classes/class/member.php

These are our class files for our two tables, the database connection and CRUD methods are in the crud.php file they extend, but these are used for only table specific methods, for example, members have names, email address and cellphone number, so all methods that are used to validate those, are stored in these files, while mutual methods of CRUD are in one file, the crud.php file.

/api/library/classes/enum/table.php

This file has an enum of the available tables, instead of using strings everywhere we can call them by their enum names in order to this is so that even if the table names have changed from one place. This is also cleaner than having table strings everywhere.

/api/library/classes/request/request.php

Because we will use one API to call from different tables, we need a single file that will be called for different functions from different tables, so we use this request file in order to get the object of each table depending on the entity entered via the API calls. It only has one property, the object, which will be assigned the class object of selected table class.

# Website Classes ( http://cartrack.loc/ )

/www/library/classes/request.php

This is the only class file here, it accepts the entity (animal or member) to be executed on (CRUD), where it will take those parameters and pass them to the API. All its methods use one method which is post($url), url is depending on the action to be taken (CRUD) as to which URL will be passed.
Settings for the links are found in the /www/config/settings.ini file.

# VHOST Files

I have attached two files that I used along with apache to setup my vhost files, they are:

- /cartrack.api.vhost.conf
- /cartrack.vhost.conf

All rules that are suppose to be or usually are in the .htaccess file are also included here, just to centralize and properly organize them as I have the following rules for both websites mainly:
- Setup root folder
- Default file for each folder
- Deny access to config/ folder
- Deny access to libraary/classes/ folder
- Default viewing files for apache errors such as 404, 403 and 500
- Define error logs
