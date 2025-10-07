<?php

/* src/Entity/Role.php */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PhpRbacBundle\Entity\Role as EntityRole;
use PhpRbacBundle\Repository\RoleRepository;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[ORM\Table('rbac_roles')]
class Role extends EntityRole
{

}
// End of file: src/Entity/Role.php