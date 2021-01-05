Branches etc
------------
Develop branch contains the filters
Last commits were 2/12 14.24 to hub, 6 off. Remove options.
and 8/12 16.11
Live site is mostly 26/11
Gallery/source is 26/11, as is build
www/NAFY.zip is last state of develop branch
www/NAFY2.zip is 03/12. From 2/12 picedit, index, common and config are later.
documents/NAFY 20201204 1519.sql has changes to paintings - 04/12.
downloads/NAFY.sql is live site
Can use cunha schema for live db debug
www/NAFY/InstallGallery.sql is empty db with old schema - 25/08?
www/NAFY/Update.sql seems to be Lupe's of 14/09

Styles
------
Probably out of date
PageBuilder->makeBanner set the height of the banner, hard coded, removed 3/7/30
All menu styles are defined in preset style sheets in source/css. They are 
copied in Customer.php->makeCustomStyle.
Menus.css needs to be removed.

Sandbox mode
------------
CommonMaster.php - set PP_TEST
carddetails.php and artistfee - change token in Javascript 
