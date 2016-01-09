<?php
$config = array(
    'county' => array(
        'properties' => array('name' => 'San Joaquin County'),
    ),
    'categories' => array(
        array('id' => 1, 'name' => 'Local Services', 'sort' => 1),
        array('id' => 2, 'name' => 'Shopping', 'sort' => 2),
        array('id' => 3, 'name' => 'Things To Do', 'sort' => 3),
        array('id' => 4, 'name' => 'Health & Fitness', 'sort' => 4),
        array('id' => 5, 'name' => 'Beauty & Spas', 'sort' => 5),
        array('id' => 6, 'name' => 'Food & Drink', 'sort' => 6),
        array('id' => 7, 'name' => 'Automotive', 'sort' => 7),
        array('id' => 8, 'name' => 'Home Services', 'sort' => 8),
        array('id' => 9, 'name' => 'Cars & Auto', 'sort' => 9),
    )
);


header('Content-type: application/json');
echo json_encode($config);