<?php

// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Vinyl;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Créer 8 entités Product et les persister
        for ($i = 0; $i < 8; $i++) {
            $vinyl = new Vinyl();
            $vinyl->setTitre('Titre' . $i);
            $vinyl->setArtiste('Artiste' . $i);
            $vinyl->setAnnee(2000 + $i);
            $vinyl->setCover('cover' . $i . '.jpg');
            $vinyl->setAudio('audio' . $i . '.mp3');
            $vinyl->setAlbum('Album' . $i);

            $manager->persist($vinyl);
        }

        $manager->flush();
    }
}

