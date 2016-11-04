<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GalleryBundle\Entity\Album;
use GalleryBundle\Entity\Image;

class LoadAlbumData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $albumOne = new Album();
        $albumOne->setId('1');
        $albumOne->setTitle('Picture Perfect Memories');
        $albumOne->setCode('one');
        $manager->persist($albumOne);

        $albumTwo = new Album();
        $albumTwo->setId('2');
        $albumTwo->setTitle('Only Yesterday');
        $albumTwo->setCode('two');
        $manager->persist($albumTwo);

        $albumThree = new Album();
        $albumThree->setId('5');
        $albumThree->setTitle('Ordinary People');
        $albumThree->setCode('tree');
        $manager->persist($albumThree);

        $albumFour = new Album();
        $albumFour->setId('7');
        $albumFour->setTitle('Day by Day');
        $albumFour->setCode('four');
        $manager->persist($albumFour);

        $albumFive = new Album();
        $albumFive->setId('9');
        $albumFive->setTitle('The Good Old Days');
        $albumFive->setCode('five');
        $manager->persist($albumFive);
        $manager->flush();
        $album = $albumOne;
        $pathToImage = '/public/images/2d.jpg';
        for ($i = 0; $i < 225; $i++) {
            if ($i >= 0 && $i < 5) {
                $album = $albumOne;
            } elseif ($i > 5 && $i < 30) {
                $album = $albumTwo;
            } elseif ($i > 30 && $i < 75) {
                $album = $albumThree;
            } elseif ($i > 75 && $i < 140) {
                $album = $albumFour;
            } elseif ($i > 140 && $i < 225) {
                $album = $albumFive;
            }
            $image = new Image();
            $image->setAlbumId($album);
            $image->setPath($pathToImage);
            $image->setUrl('/');
            $image->setWidth('320');
            $image->setHeight('242');
            $manager->persist($image);
        }
        $manager->flush();
    }

}