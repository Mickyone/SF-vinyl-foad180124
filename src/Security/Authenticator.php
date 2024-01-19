<?php
namespace App\Security;

use Psr\Log\LoggerInterface;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class Authenticator extends AbstractLoginFormAuthenticator implements AuthenticationEntryPointInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $userRepository;
    private $urlGenerator;

    private $logger; // Add this line

    // add loggerInterface $logger at the end of the line
    public function __construct(UserRepository $userRepository, UrlGeneratorInterface $urlGenerator, LoggerInterface $logger) 
    {
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;

        $this->logger = $logger; // Add this line
    }

    public function authenticate(Request $request): Passport
    {
        $usernameOrEmail = $request->request->get('credential', '');
        $credentialField = $request->request->get('credential_field', '');

        if ($credentialField === 'username') {
            $userBadge = new UserBadge($usernameOrEmail, function () use ($usernameOrEmail) {
                return $this->userRepository->findOneByUsernameOrEmail($usernameOrEmail);
            });
        } else {
            $userBadge = new UserBadge($usernameOrEmail, function () use ($usernameOrEmail) {
                return $this->userRepository->findOneByUsernameOrEmail($usernameOrEmail);
            });
        }

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $usernameOrEmail);

        return new Passport(
            $userBadge,
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        // Log authentication failure messages for debugging
        $this->logger->error('Authentication Failure: ' . $exception->getMessage());

        return parent::onAuthenticationFailure($request, $exception);
    }

}