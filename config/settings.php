<?php

define ('SMARTY_PATH',APP_ROOT_DIR.'\..\smarty\libs');

define ('TEMPLATE_DIR',APP_ROOT_DIR.'\templates');
define ('TEMPLATE_C_DIR',APP_ROOT_DIR.'\templates_c');
define ('CONFIG_DIR',APP_ROOT_DIR.'\config');
define ('CACHE_DIR',APP_ROOT_DIR.'\cache');

set_include_path(SMARTY_PATH);

define ('DB_DSN','mysql:host=localhost;dbname=incmotiv');
define ('DB_USER','root');
define ('DB_PASSWD',''); 

