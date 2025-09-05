<?php

foreach ($users as $user => $value) {
    echo "Name" . $value->getName() . "<br>";
    echo "Firstname " . $value->getFirstname() . "<br>";
    echo "Lastname " . $value->getLastname() . "<br>";
    echo "Email " . $value->getEmail() . "<br>";
    echo "Pterodactyl Id " . $value->getPtrlid() . "<br>";
    echo "Admin " . $value->isAdmin() ? "true" : "false" . "<br>";
    echo "Coins ". $value->getResources()->getCoins() . "<br>";
    echo "Slots " . $value->getResources()->getSlots() . "<br>";
    echo "<br>";
}