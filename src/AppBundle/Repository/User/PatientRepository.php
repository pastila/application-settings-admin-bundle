<?php

namespace AppBundle\Repository\User;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use AppBundle\Entity\User\Patient;
use AppBundle\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PatientRepository extends ServiceEntityRepository
{
  public function __construct (ManagerRegistry $registry, $entityClass=Patient::class)
  {
    parent::__construct($registry, $entityClass);
  }

  /**
   * @param OmsChargeComplaint $chargeComplaint
   * @param User|null $user
   * @return Patient|null
   */
  public function resolveByOmsChangeComplaint (OmsChargeComplaint $chargeComplaint, User $user = null)
  {
    $qb = $this->createQueryBuilder('p');
    $patient = $chargeComplaint->getPatient();

    if ($user !== null)
    {
      $qb
        ->andWhere('p.user = :user')
        ->setParameter('user', $user);
    }

    if ($patient->getLastName())
    {
      $qb
        ->andWhere('p.lastName = :lastname')
        ->setParameter('lastname', $patient->getLastName());
    }

    if ($patient->getFirstName())
    {
      $qb
        ->andWhere('p.firstName = :firstname')
        ->setParameter('firstname', $patient->getFirstName());
    }

    if ($patient->getMiddleName())
    {
      $qb
        ->andWhere('p.middleName = :middlename')
        ->setParameter('middlename', $patient->getMiddleName());
    }

    if ($patient->getBirthDate())
    {
      $qb
        ->andWhere('p.birthDate = :birth')
        ->setParameter('birth', $patient->getBirthDate());
    }

    return $qb->getQuery()->getOneOrNullResult();
  }
}