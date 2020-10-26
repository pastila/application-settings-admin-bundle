<?php


namespace Tests\AppBundle\Fixtures\Company;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Feedback extends Fixture implements DependentFixtureInterface
{
    /**
     * @return array
     */
    function getDependencies()
    {
        return [
            CompanyBranch::class,
        ];
    }

    /**
     * @param ObjectManager $manager
     * @return mixed
     */
    public function load(ObjectManager $manager)
    {
        $feedback = new \AppBundle\Entity\Company\Feedback();
        $feedback
            ->setBranch($this->getReference('sogaz-med-66'))
            ->setAuthorName('From fixtures')
            ->setText('Foo')
        ;

        $manager->persist($feedback);

        $manager->flush();
    }

}