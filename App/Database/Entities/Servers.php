<?php

namespace App\Database\Entites;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "servers")]
class Servers {
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private $id;
}