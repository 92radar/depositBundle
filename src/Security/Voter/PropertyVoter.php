<?php

namespace _92radar\DepositBundle\Security\Voter;

use _92radar\DepositBundle\Entity\PropertyInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PropertyVoter extends Voter
{
    public const EDIT = 'PROPERTY_EDIT';
    public const VIEW = 'PROPERTY_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof PropertyInterface;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $subject->getOwner()->getUid() === $user->getUid();

            case self::VIEW:
                break;
        }

        return false;
    }
}
