<?php

require('person.php');
require('people.php');

$object = new person('Arnel', 13);


echo $object->myName() .' is '. $object->myAge(). ' years old';



$object2 = new people('Uri', 26);

echo $object2->myName();





?>