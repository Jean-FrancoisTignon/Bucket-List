<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    // ou bien sans typage (moins bien) :
    // private $hasher

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPseudo('pseudo-' . $i);

            // ligne inutile à priori car géré plus tard avec le hashage
            $user->setPassword($faker->password());

            // POINTS PARTICULIERS :

            // Rôle(s) : fixé(s) ici ou bien comme valeur par défaut dans la déclaration...
            // ...de l'attribut 'roles' de l'entité
            $user->setRoles( ["ROLE_USER"] );

            // Mot de passe = doit être hashé
            // Pour cela il faut importer le service interne PasswordHasher
            // Attention générer des mots de passe forts, compatibles avec vos contraintes regex
            // On peut imaginer générer des mots de passe aléatoirement pour chaque utilisateur...
            // ...et si nécessaire leur envoyer par mail (rappel : on est censé créer des utilisateurs fictifs pour le développement, pas pour la production)
            $sPlainPassword = "azerty123";

            $hash = $this->hasher->hashPassword($user, $sPlainPassword);
            $user->setPassword($hash);

            // On persiste
            $manager->persist($user);
        }

        // Quand la boucle est terminée, on fait le 'flush' :
        $manager->flush();
    }
}
