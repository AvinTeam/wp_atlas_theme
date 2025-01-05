<?php

$atlas_iran_area = new Iran_Area;

if (!$atlas_iran_area->num()) {
    $atlas = $atlas_iran_area->insert_old_data();
    print_r($atlas);
}