<?php

declare(strict_types=1);

namespace App\Twig;

use App\Services\SettingService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SettingExtension extends AbstractExtension
{
    public function __construct(
        private SettingService $settingService
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('setting', [$this, 'getSetting']),
        ];
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        return $this->settingService->get($key, $default);
    }
}
