<?php


require $_SERVER['DOCUMENT_ROOT'].'/imports.php';

define('TAB','&nbsp;&nbsp;&nbsp;&nbsp;');
define('BR','<br>');
$primitives = ['string','int','bool'];



echo '<code>';

# declaring properties
foreach (User\Schemas\User::getSchema() as $key => $property) {
    echo TAB.'private '.$property['type'].' $'.$key.';'.BR;
}

echo BR.BR;
# constructor
echo TAB.'public function __construct('.BR;
echo TAB.TAB.BR;
echo TAB.TAB.')'.BR;
echo TAB.'{'.BR;
echo TAB.TAB.'# constructor'.BR;
echo TAB.'}'.BR;

echo BR.BR;
# setters and getters
foreach (User\Schemas\User::getSchema() as $key => $property) {
    echo TAB.'public function '.$key.' ('.BR;
    echo TAB.TAB.'?'.$property['type'].' $'.$key.' = null'.BR;
    echo TAB.TAB.')'.BR;
    echo TAB.'{'.BR;
        echo TAB.TAB.'if(null===$'.$key.'){'.BR;
        echo TAB.TAB.TAB.'return (isset($this->'.$key.')) ?'.BR;
        echo TAB.TAB.TAB.TAB.'$this->'.$key.' : '.' null;'.BR;
        echo TAB.TAB.'}'.BR;
        echo TAB.TAB.'$this->'.$key.' = $'.$key.';'.BR;
        echo TAB.TAB.'return $this;'.BR;
    echo TAB.'}'.BR;
    echo BR.BR;
}

echo '</code>';