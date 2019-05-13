<?php
include 'Data.php';

print_r($hotels[$_GET['city']][$_GET['hotel']]['rooms'][$_GET['room']]['photos']);die;