# mediacore_api_php

Implementation of Mediacore API on PHP

This simple lib allow to access mediacore API in easy way


Contains:
- mediacore.php - class to send API requests
- functions.php - contain many usefull functions for different mediacore actions
- main.php - main file to work with (there you can call a functions or write your own function based on class Mediacore)
- config.ini.example - example of config file (to start work with lib you should add your own data to this file and rename it to config.ini)
- dir myscripts - contain a bit of complex action (which cannot be realized in one function)
  * add_new_ips_for_LV.php - add a new IP address to Leadvertex clients Origination Points
  * LV_set_block-limit.php - allow or deny function to call with negative balance for Leadvertex clients


[!TIP]
How to start using:
- install last version of php
- from command line run "php <i>script</i>