<?php

/* src/Entity/Permission.php */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PhpRbacBundle\Entity\Permission as EntityPermission;
use PhpRbacBundle\Repository\PermissionRepository;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
#[ORM\Table('rbac_permissions')]
class Permission extends EntityPermission
{

}