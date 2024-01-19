<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class Authenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    // add these two lines:
    private $userRepository;
    private $urlGenerator;

    // update this line
    // public function __construct(private UrlGeneratorInterface $urlGenerator)
    // to this line:
    public function __construct(UserRepository $userRepository, UrlGeneratorInterface $urlGenerator)
    {
        // add these two lines:
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function authenticate(Request $request): Passport
    {
        // comment this line:
        // $email = $request->request->get('email', '');

        // add this block code (start)
        $usernameOrEmail = $request->request->get('credential', '');
        $credentialField = $request->request->get('credential_field', '');

        if ($credentialField === 'username') {
            $userBadge = new UserBadge($usernameOrEmail, function () use ($usernameOrEmail) {
                return $this->userRepository->findOneBy(['username' => $usernameOrEmail]);
            });
        } else {
            $userBadge = new UserBadge($usernameOrEmail, function () use ($usernameOrEmail) {
                return $this->userRepository->findOneBy(['email' => $usernameOrEmail]);
            });
        }
        // add this block code (end)

        // update the $eamil at the end to '$usernameOrEmail'
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $usernameOrEmail);

        return new Passport(
            // upadte this line:
            // new UserBadge($email),
            // to this line:
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

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
