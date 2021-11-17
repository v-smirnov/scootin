<?php

namespace App\Application\Security;

use App\Infrastructure\Repository\ApiUserRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class ApiKeyAuthenticator extends AbstractAuthenticator
{
    const API_KEY_HEADER = 'API-KEY';

    private ApiUserRepositoryInterface $apiUserRepository;

    public function __construct(ApiUserRepositoryInterface $apiUserRepository)
    {
        $this->apiUserRepository = $apiUserRepository;
    }

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $apikey = $request->headers->get(self::API_KEY_HEADER);
        if ($apikey === null) {
            throw new CustomUserMessageAuthenticationException('No API key provided');
        }

        $apiUser = $this->apiUserRepository->findByApiKey($apikey);
        if ($apiUser === null) {
            throw new CustomUserMessageAuthenticationException('Invalid API key provided');
        }

        return
            new SelfValidatingPassport(
                new UserBadge(
                    $apikey,
                    function ($userIdentifier) use ($apiUser) {
                        return $apiUser;
                    }
                )
            );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = ['message' => strtr($exception->getMessageKey(), $exception->getMessageData())];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}