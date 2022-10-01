<?php


require $_SERVER['DOCUMENT_ROOT'].'/imports.php';

define('TAB','&nbsp;&nbsp;&nbsp;&nbsp;');
define('BR','<br>');
$primitives = ['string','int','bool'];

echo '<code>';

$Model = new User\Schemas\User;
$ModelName = 'User';

# constructor
echo TAB.'public function __construct('.BR;
echo TAB.TAB.'private '.$ModelName.' $'.$ModelName.BR;
echo TAB.TAB.')'.BR;
echo TAB.'{'.BR;
echo TAB.TAB.'# constructor'.BR;
echo TAB.'}'.BR;


echo BR.BR;
# Exports
echo TAB.'public function export()'.BR;
echo TAB.'{'.BR;
echo TAB.TAB.'return ['.BR;

$exportables = count($Model::getSchema());
$i = 0;

foreach ($Model::getSchema() as $key => $property) {
    if(!isset($property['export'])) {
        echo TAB.TAB.TAB."'".$key."'".' => $this->'.$ModelName.'->'.$key.'(),'.BR;
        continue;
    }
    if ($property['export']==='(string)') {
        echo TAB.TAB.TAB."'".$key."'".' => (string) $this->'.$ModelName.'->'.$key.'(),'.BR;
        continue;
    } 
    if ($property['export']==='export()') {
        echo TAB.TAB.TAB."'".$key."'".' => (new '.$property['type'].'Exporter($this->'.$ModelName.'->'.$key.'()))->export(),'.BR;
        continue;
    }
}
echo TAB.TAB.'];'.BR;
echo TAB.'}'.BR;


echo BR.BR;
# Imports
echo TAB.'public function import('.BR;
echo TAB.TAB.'array $raw '.BR;
echo TAB.TAB.')'.BR;
echo TAB.'{'.BR;

foreach ($Model::getSchema() as $key => $property) {
    if(!isset($property['export'])) {
        echo TAB.TAB.'$this->'.$ModelName.'->'.$key.'($raw['."'".$key."'".']);'.BR;
        continue;
    }
    if ($property['export']==='(string)') {
        echo TAB.TAB.'$this->'.$ModelName.'->'.$key.'(new '.$property['type'].'($raw['."'".$key."'".']));'.BR;
        continue;
    }
    if ($property['export']==='export()') {
        echo TAB.TAB.'$this->'.$ModelName.'->'.$key.'((new '.$property['type'].'Importer(new '.$property['type'].'()))->import($raw['."'".$key."'".']));'.BR;
        continue;
    }
}
echo TAB.TAB.'return $this->'.$ModelName.';'.BR;

echo TAB.'}'.BR;



echo '<code>';