<?php

namespace App\Security\Voter;

use App\Entity\Request;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RequestVoter extends Voter
{
    public const REQUEST_CREATE_OWNER = 'REQUEST_CREATE_OWNER';
    public const REQUEST_CREATE_TENANT = 'REQUEST_CREATE_TENANT';
    public const REQUEST_VIEW = 'REQUEST_VIEW';
    public const REQUEST_VIEW_TENANT = 'REQUEST_VIEW_TENANT';

    public function __construct(private readonly Security $security)
    {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::REQUEST_CREATE_TENANT, self::REQUEST_CREATE_OWNER,self::REQUEST_VIEW, self::REQUEST_VIEW_TENANT]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::REQUEST_CREATE_TENANT:
                if($this->security->isGranted( User::ROLE_TENANT )) { return true;}
                break;
            case self::REQUEST_CREATE_OWNER:
                if($this->security->isGranted( User::ROLE_HOMEOWNER ) && $this->isOwnerProperty($user, $subject)) { return true; }
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

    private function isOwnerProperty(User $user, int $requestId): bool
    {
        $properties = $user->getProperties();

        foreach ($properties as $property) {

            $requests = $property->getRequests();

            foreach ($requests as $request) {
                if ($request->getId() === $requestId) { return true ;}
            }
        }

        return false;
    }

}
