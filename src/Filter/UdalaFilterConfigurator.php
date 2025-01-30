<?php

namespace App\Filter;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Annotations\Reader;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class UdalaFilterConfigurator implements EventSubscriberInterface
{
    protected $em;
    protected $tokenStorage;
    private Security $security;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, Security $security)
    {
        $this->em              = $em;
        $this->tokenStorage    = $tokenStorage;
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $user = $this->security->getUser();
        if (null !== $user) {
            /** @var SQLFilter $filter */
            $filter = $this->em->getFilters()->enable('udala_filter');
            /** @var User $user */
            if ($user->getUdala()) {
                $filter->setParameter('udala_id', $user->getudala()->getId());
            }
        }
    }

    private function getUser()
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        $user = $token->getUser();

        if (!($user instanceof UserInterface)) {
            return null;
        }

        return $user;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }
}