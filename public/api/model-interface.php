<?php

$properties = [
    'id' => ['visibility'=>'private','datatype'=>'string','get'=>true,'set'=>true],
    'type' => ['visibility'=>'private','datatype'=>'string','get'=>true,'set'=>true],
    'mobileNumber' => ['visibility'=>'private','datatype'=>'string','get'=>true,'set'=>true],
    'telephoneNumber' => ['visibility'=>'private','datatype'=>'string','get'=>true,'set'=>true],
    'emailAddress' => ['visibility'=>'private','datatype'=>'string','get'=>true,'set'=>true],
    'faxNumber' => ['visibility'=>'private','datatype'=>'string','get'=>true,'set'=>true],
    'createdAt' => ['visibility'=>'private','datatype'=>'TimeStamp','get'=>true,'set'=>true],
    'updatedAt' => ['visibility'=>'private','datatype'=>'TimeStamp','get'=>true,'set'=>true]
];


define('TAB','&nbsp;&nbsp;&nbsp;&nbsp;');
define('BR','<br>');
$primitives = ['string','int','bool'];



echo '<code>';

# declaring properties
foreach ($properties as $key => $property) {
    echo TAB.$property['visibility'].' '.$property['datatype'].' $'.$key.';'.BR;
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
foreach ($properties as $key => $property) {
    echo TAB.'public function '.$key.' ('.BR;
    echo TAB.TAB.'?'.$property['datatype'].' $'.$key.' = null'.BR;
    echo TAB.TAB.')'.BR;
    echo TAB.'{'.BR;
        echo TAB.TAB.'if(null===$'.$key.'){'.BR;
        // echo TAB.TAB.TAB.'if (!isset($this->'.$key.'))'.BR;
        // echo TAB.TAB.TAB.TAB.'return null;'.BR;
        // echo TAB.TAB.TAB.'return $this->'.$key.';'.BR;
        echo TAB.TAB.TAB.'return (isset($this->'.$key.')) ?'.BR;
        echo TAB.TAB.TAB.TAB.'$this->'.$key.' : '.' null;'.BR;
        echo TAB.TAB.'}'.BR;
        if ($property['set']) {
            echo TAB.TAB.'$this->'.$key.' = $'.$key.';'.BR;
            echo TAB.TAB.'return $this;'.BR;
        } else {
            echo TAB.TAB.'return null;'.BR;
        }
    echo TAB.'}'.BR;
    echo BR.BR;
}

echo '</code>';