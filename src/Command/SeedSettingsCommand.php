<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-settings',
    description: 'Seed default general settings',
)]
class SeedSettingsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $defaults = [
            ['general', 'app_name', 'My Application', 'string', true],
            ['general', 'app_description', 'Application description', 'string', true],
            ['general', 'app_logo', 'uploads/settings/logo.png', 'file', true],
            ['general', 'app_favicon', 'uploads/settings/favicon.ico', 'file', true],
            ['general', 'timezone', 'Asia/Jakarta', 'string', true],
            ['general', 'date_format', 'd-m-Y', 'string', true],
            ['general', 'language', 'id', 'string', true],
            ['email', 'contact_email', 'admin@example.com', 'string', true],
            ['email', 'contact_phone', '+62xxx', 'string', true],
            ['landing', 'landing_eyebrow', 'Portal Pelayanan Publik Resmi', 'string', true],
            ['landing', 'landing_hero_title', 'Melayani Warga, Membangun Bandung Barat yang Maju dan Berkelanjutan', 'string', true],
            ['landing', 'landing_stats_population', '1724500', 'string', true],
            ['landing', 'landing_stats_districts', '16', 'string', true],
            ['landing', 'landing_stats_villages', '151', 'string', true],
            ['landing', 'landing_stats_employees', '12480', 'string', true],
            ['landing', 'landing_address', 'Pusat Pemerintahan Kabupaten Bandung Barat, Soreang, Jawa Barat', 'string', true],
            ['landing', 'landing_work_hours', 'Senin–Jumat, 08.00–16.00 WIB', 'string', true],
            ['landing', 'landing_footer_desc', 'Situs web resmi Pemerintah Kabupaten Bandung Barat sebagai pusat informasi pelayanan publik, transparansi penyelenggaraan pemerintahan, dan akuntabilitas daerah.', 'string', true],
            ['landing', 'social_facebook', '#', 'string', true],
            ['landing', 'social_instagram', '#', 'string', true],
            ['landing', 'social_youtube', '#', 'string', true],
            ['landing', 'social_twitter', '#', 'string', true],
            ['landing', 'social_whatsapp', '#', 'string', true],
        ];

        $repo = $this->entityManager->getRepository(Setting::class);

        foreach ($defaults as [$group, $key, $value, $type, $isPublic]) {
            $existing = $repo->findOneBy(['settingKey' => $key]);
            if (!$existing) {
                $setting = new Setting();
                $setting->setGroupName($group);
                $setting->setSettingKey($key);
                $setting->setSettingValue($value);
                $setting->setType($type);
                $setting->setIsPublic($isPublic);
                $this->entityManager->persist($setting);
                $io->text("Created setting: {$key}");
            }
        }

        $this->entityManager->flush();

        $io->success('Default settings seeded successfully.');

        return Command::SUCCESS;
    }
}
