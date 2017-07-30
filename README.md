# Organization Organizer
In simple terms, a CRUD for designed for account managers that take care of many different organizations and their web accounts. 

The Organization Organizer is built on the Micro-MVC (updated for Neo4j use) which can be found here: https://github.com/siteraiser/Micro-MVC.

The new database handling requires the password password to be set from within each controller, you might want to provide a config path instead, the same way the PDO connection is set (not included in this demo, the original micro-mvc has a secure folder for an example). If you have no need to use different databases, you could alter the connections within the requestHandler class.

There is a labelling class (https://github.com/siteraiser/Organization-Organizer/blob/master/classes/labels.php) which can be edited to add new properties to any existing labels in the provided schema / database. It is used to manage the form outputs as well (select lists, text input or check box currently). If you want to add another type of label, that will likely require some more funtions to be added in for  handling insert, update and delete functionality. 

The organizer is currently designed to only allow local and local network access (and it's controlled here: https://github.com/siteraiser/Organization-Organizer/blob/master/classes/requestHandler.php). It hasn't been fully designed and tested for security yet. It is meant for storing account details so make sure you know what you are doing before using it over the internet!

To setup: 
Add files to htdocs, setup a Neo4j database and go to localhost or the IP address you've provided in the request handler for local network access (servier ip). Run composer install for the latest php connector (vendor files) which can also be found here: https://github.com/graphaware/neo4j-php-client/ and have been tested on Neo4j version 3.2.1.



Basically the terms of the GNU General Public License are intended... do what you will, I'm using it as it is anyway!



