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

# LOGIN CONTROLLER

## Install the bundle if needed =>     composer require symfony/maker-bundle --dev

## install the security bundle =>   composer require symfony/security-bundle

## Create a new auth =>     symfony console make:auth


## Create a loginFormType =>    php bin/console make:form LoginFormType

## update the file =>
### before:
    class LoginFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('field_name')
            ;
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                // Configure your form options here
            ]);
        }
    }
### after:
    class LoginFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('username', TextType::class, [
                    'label' => 'Username or Email',
                    'attr' => ['autocomplete' => 'username'],
                ])
                ->add('password', PasswordType::class, [
                    'label' => 'Password',
                    'attr' => ['autocomplete' => 'current-password'],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                // Configure your form options here
            ]);
        }
    }

## Decomment these lines in the SecurityController =>
    // if ($this->getUser()) {
    //     return $this->redirectToRoute('target_path');
    // }

## 


# REGISTRATION CONTROLLER

## Create a registration form =>    symfony console make:form
    the files: RegistrationController, RegistrationFormType, and register.html.twig have been created