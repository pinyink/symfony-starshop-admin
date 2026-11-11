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
    name: 'app:seed-settings-permission',
    description: 'Seed settings permission into RBAC tree',
)]
class SeedSettingsPermissionCommand extends Command
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
            $existing = $this->entityManager->getRepository(Permission::class)->findOneBy(['code' => 'settings']);
            if (!$existing) {
                $this->permissionManager->add('settings', 'General Settings Access', $this->permissionManager::ROOT_ID);
                $this->permissionManager->addPath("/settings/read", [
                    'settings' => 'General Settings Access',
                    'read' => 'Read Access'
                ]);
                $this->permissionManager->addPath("/settings/add", [
                    'settings' => 'General Settings Access',
                    'add' => 'Add Access'
                ]);
                $this->permissionManager->addPath("/settings/update", [
                    'settings' => 'General Settings Access',
                    'update' => 'Update Access'
                ]);
                $this->permissionManager->addPath("/settings/delete", [
                    'settings' => 'General Settings Access',
                    'delete' => 'Delete Access'
                ]);
                $io->success('Settings permission seeded successfully.');
            } else {
                $io->note('Settings permission already exists.');
            }
        } catch (\Exception $e) {
            $io->warning('Permission might already exist: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
