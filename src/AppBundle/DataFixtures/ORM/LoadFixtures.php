<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

class LoadFixtures implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        Fixtures::load(
            __DIR__.'/Resources/fixtures.yml',
            $manager,
            [
                'providers' => [$this]
            ]
        );
    }

    /**
     * Upload File Fixture
     */
    public function upload($filename)
    {
        if (is_array($filename)) {
            $filename = \Faker\Provider\Base::randomElement($filename);
        }
        $path = sprintf('./web/tmp/%s', uniqid());
        $copy = copy($filename, $path);
        if (!$copy) {
            throw new \Exception('Copy failed');
        }
        $mimetype = MimeTypeGuesser::getInstance()->guess($path);
        $size     = filesize($path);
        $imageFile = new UploadedFile($path, $filename, $mimetype, $size, null, true);
        return $imageFile;
    }

    /**
    +     * Get the order of this fixture
    +     * @return integer
    +     */
    public function getOrder()
    {
        return 1;
    }
}