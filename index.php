<?php
echo "<h2>pdftk</h2>";
echo "<pre>";
exec('pdftk', $output);
foreach($output as $row){
    echo $row;
}
echo "</pre>";
echo "<h2>prince</h2>";
echo "<pre>";
exec('prince', $output);
foreach($output as $row){
    echo $row;
}
echo "</pre>";
