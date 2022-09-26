

<?php

# Global Variables
define('ROOT',__dir__);
define('CACHE','cache');
define('NUMERIC','1234567890');
define('BETANUMERIC','1234567890abcdef');
define('SUCCESS',"\033[92m");
define('ERROR',"\033[91m");
define('WARNING',"\033[93m");
define('URL',$_SERVER['REQUEST_URI']);

# Requiring autoloader
require ROOT.'/src/autoloader.php';