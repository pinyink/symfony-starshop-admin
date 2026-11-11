<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdminVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        // Mendukung semua pengecekan izin/atribut (atau sesuaikan dengan prefix tertentu jika diperlukan)
        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        // Jika user memiliki ROLE_ADMIN atau role admin di Symfony security, berikan akses penuh
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        return false;
    }
}
