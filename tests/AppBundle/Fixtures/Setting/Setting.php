<?php


namespace Tests\AppBundle\Fixtures\Setting;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Setting extends Fixture
{
  /**
   * @param ObjectManager $manager
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    $region_default = new \AppBundle\Entity\Common\Setting();
    $region_default->setName('region_default');
    $region_default->setValue('2');
    $manager->persist($region_default);

    $agreement = new \AppBundle\Entity\Common\Setting();
    $agreement->setName('agreement');
    $agreement->setValue('#agreement');
    $manager->persist($agreement);

    $personal_data = new \AppBundle\Entity\Common\Setting();
    $personal_data->setName('personal_data');
    $personal_data->setValue('#personal_data');
    $manager->persist($personal_data);

    $social_telegram = new \AppBundle\Entity\Common\Setting();
    $social_telegram->setName('social_telegram');
    $social_telegram->setValue('#social_telegram');
    $manager->persist($social_telegram);

    $social_instagram = new \AppBundle\Entity\Common\Setting();
    $social_instagram->setName('social_instagram');
    $social_instagram->setValue('#social_instagram');
    $manager->persist($social_instagram);

    $manager->flush();

    $this->addReference('setting-region_default', $region_default);
    $this->addReference('setting-agreement', $agreement);
    $this->addReference('setting-personal_data', $agreement);
    $this->addReference('setting-social_telegram', $agreement);
    $this->addReference('setting-social_instagram', $agreement);
  }
}