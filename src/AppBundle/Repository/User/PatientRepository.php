<?php

namespace AppBundle\Repository\User;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use AppBundle\Entity\User\Patient;
use AppBundle\Entity\User\User;
use AppBundle\Exception\Patient\AmbiguousPatientResolveException;
use AppBundle\Model\Filter\PatientFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class PatientRepository extends ServiceEntityRepository
{
  public function __construct (ManagerRegistry $registry, $entityClass=Patient::class)
  {
    parent::__construct($registry, $entityClass);
  }

  /**
   * @param User $user
   * @return int
   */
  public function countByUser (User $user)
  {
    return (int)$this->createQueryBuilder('p')
      ->where('p.user = :user')
      ->setParameter('user', $user)
      ->select('COUNT(p)')
      ->getQuery()
      ->getSingleScalarResult();
  }

  /**
   * @param Patient $patient
   * @param User|null $user
   * @return Patient|null
   * @throws AmbiguousPatientResolveException
   */
  public function resolveByPatient (Patient $patient, User $user = null)
  {
    $qb = $this->createQueryBuilder('p');

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

    if ($patient->getInsurancePolicyNumber())
    {
      $qb
        ->andWhere('p.insurancePolicyNumber = :policyNumber')
        ->setParameter('policyNumber', $patient->getInsurancePolicyNumber());
    }

    try
    {
      return $qb->getQuery()->getOneOrNullResult();
    }
    catch (NonUniqueResultException $e)
    {
      throw new AmbiguousPatientResolveException();
    }
  }

  /**
   * @param PatientFilter $filter
   * @return Patient[]
   */
  public function findByFilter (PatientFilter $filter)
  {
    return $this->createQueryBuilderByFilter($filter)->getQuery()->getResult();
  }

  /**
   * @param PatientFilter $filter
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function createQueryBuilderByFilter (PatientFilter $filter)
  {
    $qb = $this->createQueryBuilder('p');

    if ($filter->getUser() !== null)
    {
      $qb
        ->andWhere('p.user = :user')
        ->setParameter('user', $filter->getUser());
    }

    if ($filter->getLastName())
    {
      $qb
        ->andWhere('p.lastName LIKE :lastname')
        ->setParameter('lastname', '%' . $filter->getLastName() . '%');
    }

    if ($filter->getFirstName())
    {
      $qb
        ->andWhere('p.firstName LIKE :firstname')
        ->setParameter('firstname', '%' . $filter->getFirstName() . '%');
    }

    if ($filter->getMiddleName())
    {
      $qb
        ->andWhere('p.middleName LIKE :middlename')
        ->setParameter('middlename', '%' . $filter->getMiddleName() . '%');
    }

    if ($filter->getInsurancePolicyNumber())
    {
      $qb
        ->andWhere('p.insurancePolicyNumber LIKE :policyNumber')
        ->setParameter('policyNumber', '%' . $filter->getInsurancePolicyNumber() . '%');
    }

    return $qb;
  }
}