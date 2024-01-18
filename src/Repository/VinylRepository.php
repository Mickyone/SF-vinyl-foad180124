<?php

namespace App\Repository;

use App\Entity\Vinyl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vinyl>
 *
 * @method Vinyl|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vinyl|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vinyl[]    findAll()
 * @method Vinyl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinylRepository extends ServiceEntityRepository
{
    private $cover = '/assets/images/'; // Adjust the path as needed

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vinyl::class);
    }

    /**
     * @return Vinyl[] Returns an array of Vinyl objects
     */
    public function findAllWithImagePath(): array
    {
        $vinyls = $this->findAll();
        $formattedVinyls = [];

        foreach ($vinyls as $vinyl) {
            $formattedVinyls[] = [
                'titre' => $vinyl->getTitre(),
                'artiste' => $vinyl->getArtiste(),
                'annee' => $vinyl->getAnnee(),
                'cover' => $this->cover . $vinyl->getCover(),
                'audio' => $vinyl->getAudio(),
                'album' => $vinyl->getAlbum(),
            ];
        }

        return $formattedVinyls;
    }
}