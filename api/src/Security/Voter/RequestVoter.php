<?php

namespace App\Security\Voter;

use App\Checker\RequestChecker;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RequestVoter extends Voter
{
    public const REQUEST_CREATE_BY_OWNER = 'REQUEST_CREATE_BY_OWNER';
    public const REQUEST_CREATE_BY_TENANT = 'REQUEST_CREATE_BY_TENANT';
    public const REQUEST_VIEW = 'REQUEST_VIEW';
    public const REQUEST_VIEW_TENANT = 'REQUEST_VIEW_TENANT';

    public function __construct(private readonly Security $security, private readonly RequestChecker $requestChecker)
    {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::REQUEST_CREATE_BY_TENANT, self::REQUEST_CREATE_BY_OWNER,self::REQUEST_VIEW, self::REQUEST_VIEW_TENANT]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::REQUEST_CREATE_BY_TENANT:
                if($this->security->isGranted( User::ROLE_TENANT ) && $this->requestChecker->checkIfTenantAttachToRequest($subject, $user->getId())) { return true;}
                break;
            case self::REQUEST_CREATE_BY_OWNER:
                if($this->security->isGranted( User::ROLE_HOMEOWNER ) && $this->requestChecker->isOwnerProperty($user, $subject)) { return true; }
                break;
            case self::REQUEST_VIEW:
                if($user === $subject->getLodger() or $user === $subject->getOwner() or $this->security->isGranted( User::ROLE_AGENCY )) { return true;}
                break;

            case self::REQUEST_VIEW_TENANT:
                if($user->getId() === intval($subject)) { return true;}
                break;
        }

        return false;
    }
}
