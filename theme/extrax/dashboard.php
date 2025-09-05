<?php 

if(isset($_GET["error"])) {
  echo $_GET["error"];
};

if (isset($user_data)) {
    $br = "<br>";
    echo htmlspecialchars($user_data->getName(), ENT_QUOTES, 'UTF-8');
    echo "<br > resources: <br>";
    echo "coins: " . $resources->getCoins() . $br;
    echo "memory: " . $resources->getMemory() . $br;
    echo "Disk: " . $resources->getDisk() . $br;
    echo "CPU: " . $resources->getCpu() . $br;
    echo "Databases: " . $resources->getDbs() . $br;
    echo "backups: " . $resources->getBackups() . $br;
    echo "allocations: " . $resources->getAllocations() . $br;
    echo "server slots: " . $resources->getSlots() . $br;
    echo "Owner of the resources that no one asked for: " . $resources->getUser()->getName() . $br;
    
}