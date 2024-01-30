<?php

namespace pizzashop\commande\app\middleWare;

use pizzashop\commande\domain\services\commande\ServiceCommande;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeInvalidException;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;

/**
 * Middleware pour vérifier si l'utilisateur est le propriétaire de la commande.
 *
 * Ce middleware s'assure que l'utilisateur actuel est autorisé à accéder à une commande spécifique.
 * Il compare l'email de l'utilisateur avec l'email associé à la commande.
 */
class CheckIfOwner
{
    /**
     * Service pour la gestion des commandes.
     *
     * @var ServiceCommande
     */
    private ServiceCommande $serviceCommande;

    /**
     * Constructeur pour le middleware CheckIfOwner.
     *
     * @param ServiceCommande $serviceCommande Le service de gestion des commandes.
     */
    public function __construct(ServiceCommande $serviceCommande)
    {
        $this->serviceCommande = $serviceCommande;
    }

    /**
     * Traite la requête en vérifiant si l'utilisateur est le propriétaire de la commande.
     *
     * Extrait l'email de l'utilisateur de la requête et l'identifiant de la commande de la route,
     * puis vérifie si l'utilisateur est le propriétaire de la commande.
     *
     * @param ServerRequestInterface $request La requête HTTP.
     * @param RequestHandlerInterface $next Le prochain gestionnaire de requête.
     * @return ResponseInterface La réponse HTTP après traitement du middleware.
     * @throws HttpUnauthorizedException Si l'utilisateur n'est pas le propriétaire de la commande.
     */
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        $email = $request->getAttribute('user')->user->properties->email->value;
        var_dump($email);
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $idCommande = $route->getArgument('id_commande');
        try {
            $this->serviceCommande->checkIfUserIsOwner($idCommande, $email);
        } catch (ServiceCommandeInvalidException|ServiceCommandeNotFoundException $e) {
            throw new HttpUnauthorizedException($request, $e->getMessage());
        }
        return $next->handle($request);
    }
}