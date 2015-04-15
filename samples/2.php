<?php
$array = array(1 => 'Apartment', 2=> 'Single Family Home', 3=> 'Condo', 4=> 'Townhome', 5=> 'Loft', 6=> 'Multi-Family Home');
echo json_encode($array);
print_r(json_decode(json_encode($array), 1));