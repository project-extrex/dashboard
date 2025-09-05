<?php 
if (isset($user_data)) {
    echo htmlspecialchars($user_data->getName(), ENT_QUOTES, 'UTF-8');
    echo "resources " .  $resources->getCoins();
}