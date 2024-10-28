<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    public const VIEW = 'USER_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        $roles = $token->getRoleNames();

        if (in_array('ROLE_SUPER_ADMIN', $roles)) {
            return true;
        }
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW:
                // بررسی این‌که آیا کاربر جاری و کاربر مورد نظر در یک شرکت هستند
                return $this->canView($subject, $user);
        }

        return false;
    }

    private function canView($subject, User $currentUser)
    {
        return $currentUser->getCompany() === $subject->getCompany();
    }
}
