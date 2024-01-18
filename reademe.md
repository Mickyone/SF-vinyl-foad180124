# PROCESUS

## Creation du projet => symfony new --webapp vinyl-FOAD180124

## installation de composer => composer install

## Creation et edit de env.local

## Creation de la databse => symfony console doctrine:database:create

## Creation de l'entite => symfony console make:entity

## Creation du crud => php bin/console make:crud

## installation de fixtures => composer require orm-fixtures --dev

## edit AppFixtures => for loop

## generate fixtures => php bin/console doctrine:fixtures:load

## Creation controller pour homepage => symfony console make:controller

## update HomeController =>
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'vinyls' => $vinylRepository->findAll(),
        ]);
    }
### vers =>
    #[Route('/', name: 'app_home')]
    public function index(VinylRepository $vinylRepository): Response
    {
        $vinyls = $vinylRepository->findAll();

        return $this->render('home/index.html.twig', [
            'vinyls' => $vinyls,
        ]);
    }