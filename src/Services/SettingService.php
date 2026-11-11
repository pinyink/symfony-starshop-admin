<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;

class SettingService
{
    private ?array $cache = null;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->cache === null) {
            $this->loadCache();
        }

        return $this->cache[$key] ?? $default;
    }

    public function set(string $key, mixed $value, string $group = 'general', string $type = 'string', bool $isPublic = false): void
    {
        $repo = $this->entityManager->getRepository(Setting::class);
        $setting = $repo->findOneBy(['settingKey' => $key]);

        if (!$setting) {
            $setting = new Setting();
            $setting->setSettingKey($key);
            $setting->setGroupName($group);
            $setting->setType($type);
            $setting->setIsPublic($isPublic);
            $this->entityManager->persist($setting);
        }

        $setting->setSettingValue((string) $value);
        $this->entityManager->flush();

        // Invalidate cache
        $this->cache = null;
    }

    private function loadCache(): void
    {
        $this->cache = [];
        $settings = $this->entityManager->getRepository(Setting::class)->findAll();
        foreach ($settings as $setting) {
            $this->cache[$setting->getSettingKey()] = $setting->getSettingValue();
        }
    }
}
