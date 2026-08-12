<?php

namespace aintreallydown\DepositBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PropertyVoter extends Voter
{
    public const EDIT = 'PROPERTY_EDIT';
    public const VIEW = 'PROPERTY_VIEW';

    public function __construct(
        private string $propertyClass,
        private array $methods,
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof $this->propertyClass;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                $owner = $subject->{$this->methods['get_owner']}();

                return $owner !== null
                    && $owner->{$this->methods['get_uid']}() === $user->{$this->methods['get_uid']}();

            case self::VIEW:
                break;
        }

        return false;
    }
}