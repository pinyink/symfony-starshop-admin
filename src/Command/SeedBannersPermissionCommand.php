<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Permission;
use Doctrine\ORM\EntityManagerInterface;
use PhpRbacBundle\Core\Manager\PermissionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-banners-permission',
    description: 'Seed banners permission into RBAC tree',
)]
class SeedBannersPermissionCommand extends Command
{
    public function __construct(
        private PermissionManager $permissionManager,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $existing = $this->entityManager->getRepository(Permission::class)->findOneBy(['code' => 'banners']);
            if (!$existing) {
                $this->permissionManager->add('banners', 'Banner Management Access', $this->permissionManager::ROOT_ID);
                $this->permissionManager->addPath("/banners/read", [
                    'banners' => 'Banner Management Access',
                    'read' => 'Read Access'
                ]);
                $this->permissionManager->addPath("/banners/add", [
                    'banners' => 'Banner Management Access',
                    'add' => 'Add Access'
                ]);
                $this->permissionManager->addPath("/banners/update", [
                    'banners' => 'Banner Management Access',
                    'update' => 'Update Access'
                ]);
                $this->permissionManager->addPath("/banners/delete", [
                    'banners' => 'Banner Management Access',
                    'delete' => 'Delete Access'
                ]);
                $io->success('Banners permission seeded successfully.');
            } else {
                $io->note('Banners permission already exists.');
            }
        } catch (\Exception $e) {
            $io->warning('Permission might already exist: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
