<?php 

if(isset($_GET["error"])) {
  echo $_GET["error"];
};

if (isset($user_data)) {
    $br = "<br>";
    echo htmlspecialchars($user_data->getName(), ENT_QUOTES, 'UTF-8');
    echo "<br > resources: <br>";
    echo "coins: " . $user_data->getResources()->getCoins() . $br;
    echo "memory: " . $user_data->getResources()->getMemory() . $br;
    echo "Disk: " . $user_data->getResources()->getDisk() . $br;
    echo "CPU: " . $user_data->getResources()->getCpu() . $br;
    echo "Databases: " . $user_data->getResources()->getDbs() . $br;
    echo "backups: " . $user_data->getResources()->getBackups() . $br;
    echo "allocations: " . $user_data->getResources()->getAllocations() . $br;
    echo "server slots: " . $user_data->getResources()->getSlots() . $br;
    echo "Owner of the resources that no one asked for: " . $resources->getUser()->getName() . $br;
    
}