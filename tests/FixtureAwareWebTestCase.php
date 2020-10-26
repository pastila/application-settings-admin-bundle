<?php
/**
 *  (c) 2019 ИП Рагозин Денис Николаевич. Все права защищены.
 *  
 *  Настоящий файл является частью программного продукта, разработанного ИП Рагозиным Денисом Николаевичем
 *  (ОГРНИП 315668300000095, ИНН 660902635476).
 *  
 *  Алгоритм и исходные коды программного кода программного продукта являются коммерческой тайной
 *  ИП Рагозина Денис Николаевича. Любое их использование без согласия ИП Рагозина Денис Николаевича рассматривается,
 *  как нарушение его авторских прав.
 *   Ответственность за нарушение авторских прав наступает в соответствии с действующим законодательством РФ.
 */

namespace Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class FixtureAwareWebTestCase extends WebTestCase
{
  /**
   * @var ORMExecutor
   */
  private $fixtureExecutor;

  /**
   * @var ContainerAwareLoader
   */
  private $fixtureLoader;

  /**
   * @var EntityManager
   */
  private $entity_manager;

  /**
   * @var ORMPurger
   */
  private $purger;

  public static function setUpBeforeClass ()
  {
    gc_enable();
    self::bootKernel();
  }


  protected function setUp()
  {
    $this->entity_manager = self::$kernel->getContainer()->get('doctrine')->getManager();
    $this->purgeDatabase();
  }

  /**
   * Добавить фикстуру к имеющимся, без очищения старых данных
   * @param FixtureInterface $fixture
   * @param $append boolean Залить ли фикстуру повторно
   * @deprecated
   * @uses StoreWebTestCase::setUpFixtures
   */
  protected function appendFixture(FixtureInterface $fixture, $append = false)
  {
    if ($append || !$this->getFixtureLoader()->hasFixture($fixture))
    {
      $this->getFixtureLoader()->addFixture($fixture);
      $this->getFixtureExecutor()->execute([$fixture], true);
    }
  }

  /**
   * Adds a new fixture to be loaded.
   *
   * @param FixtureInterface $fixture
   */
  protected function addFixture(FixtureInterface $fixture)
  {
    $this->getFixtureLoader()->addFixture($fixture);
  }

  /**
   * Executes all the fixtures that have been loaded so far.
   */
  protected function executeFixtures()
  {
    $this->getFixtureExecutor()->execute($this->getFixtureLoader()->getFixtures());
  }

  /**
   * @return ORMExecutor
   */
  private function getFixtureExecutor()
  {
    if (!$this->fixtureExecutor) {
      /** @var \Doctrine\ORM\EntityManager $entityManager */
      $entityManager = $this->getEntityManager();
      $this->fixtureExecutor = new ORMExecutor($entityManager, new ORMPurger($entityManager));
    }
    return $this->fixtureExecutor;
  }

  /**
   * @return ContainerAwareLoader
   */
  private function getFixtureLoader()
  {
    if (!$this->fixtureLoader) {
      $this->fixtureLoader = new ContainerAwareLoader(self::$kernel->getContainer());
    }
    return $this->fixtureLoader;
  }

  /**
   * @return EntityManager
   */
  protected function getEntityManager()
  {
    if (!$this->entity_manager)
    {
      $this->entity_manager = self::$kernel->getContainer()->get('doctrine')->getManager();
      $this->entity_manager->getConnection()->setSqlLogger(null);
    }

    return $this->entity_manager;
  }

  /**
   * Получить объект по референсу из фикстур
   * @deprecated
   * @param $reference
   * @return object
   */
  protected function getByReference($reference)
  {
    return $this->getReference($reference);
  }

  protected function purgeDatabase()
  {
    $this->purger = new ORMPurger($this->entity_manager);
    $this->purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
    $this->entity_manager->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS=0');

    $this->purger->purge();
    $this->purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);

    $this->entity_manager->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS=1');
  }

  /**
   * Получить объект по референсу из фикстур
   * @param $reference
   * @return object
   */
  protected function getReference($reference)
  {
    return $this->getFixtureExecutor()->getReferenceRepository()->getReference($reference);
  }

  protected function tearDown ()
  {
    parent::tearDown();
    $this->entity_manager->close();
//    $this->entity_manager->getConnection()->close();
    $this->entity_manager = null;
    $this->purger = null;
    $this->fixtureLoader = null;
    $this->fixtureExecutor = null;
    gc_collect_cycles();
  }

  protected function setUpFixtures()
  {

  }
}