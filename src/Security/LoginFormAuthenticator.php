<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    //le nom de la route de la page login

    public const LOGIN_ROUTE = 'security_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }
    //l'objet passport permer de gerer l'authentication des utilisateurs
    public function authenticate(Request $request): Passport
    {
        // on recupere l'email depuis la requett on le sauvegarde et ensuite on genere un passport
        $email = $request->request->get('email', '');

        //on insere le dernier utilisateur dans la session 
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        //on retourne le passport
        return new Passport(
            //le userbadge cherche l'utilisateur par son email
            new UserBadge($email),
            //on resupere le mdp qui a ete taper
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // targetPath est le point de retour de l'utilisateur là ou il etait connecter
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example: on rederige l'utilisateur vers la page d'acceuil
        return new RedirectResponse($this->urlGenerator->generate('homepage'));
        // throw new \Exception('TODO: provide a valid redirect inside ' . __FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
