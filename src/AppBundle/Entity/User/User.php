<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\Feedback;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="s_users")
 */
class User implements UserInterface
{
  const ROLE_ADMIN = 'ROLE_ADMIN';

  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * @var string
   * @ORM\Column(name="login", type="string", length=255, nullable=true)
   */
  protected $login;

  /**
   * @var string
   * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
   */
  protected $firstName;

  /**
   * @var string
   * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
   */
  protected $lastName;

  /**
   * @var string
   * @ORM\Column(name="middle_name", type="string", length=255, nullable=true)
   */
  protected $middleName;

  /**
   * Флаг, что представитель отделения в компании
   * @var int
   * @ORM\Column(type="integer", nullable=true)
   */
  private $representative;

  /**
   * Отделение, в котором пользователь является представителем
   * @var null|CompanyBranch
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\CompanyBranch")
   * @ORM\JoinColumn(name="branch_id", nullable=true, onDelete="RESTRICT")
   */
  private $branch;

  /**
   * @var
   */
  private $isAdmin;

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;

    return $this;
  }
  /**
   * @return string
   */
  public function getLogin()
  {
    return $this->login;
  }

  /**
   * @param string $login
   *
   * @return $this
   */
  public function setLogin($login)
  {
    $this->login = $login;

    return $this;
  }

  /**
   * @return string
   */
  public function getFirstName()
  {
    return $this->firstName;
  }

  /**
   * @param string $firstName
   *
   * @return $this
   */
  public function setFirstName($firstName)
  {
    $this->firstName = $firstName;

    return $this;
  }

  /**
   * @return string
   */
  public function getLastName()
  {
    return $this->lastName;
  }

  /**
   * @param string $lastName
   *
   * @return $this
   */
  public function setLastName($lastName)
  {
    $this->lastName = $lastName;

    return $this;
  }

  /**
   * @return string
   */
  public function getMiddleName()
  {
    return $this->middleName;
  }

  /**
   * @return string
   */
  public function getFullName()
  {
    $resultParts = [];
    foreach ([$this->lastName, $this->firstName, $this->middleName] as $namePart)
    {
      if ($namePart)
      {
        $resultParts[] = $namePart;
      }
    }
    return implode(' ', $resultParts);
  }

  /**
   * @param string $middleName
   *
   * @return $this
   */
  public function setMiddleName($middleName)
  {
    $this->middleName = $middleName;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getIsAdmin()
  {
    return $this->isAdmin;
  }

  /**
   * @param mixed $isAdmin
   * @return User
   */
  public function setIsAdmin($isAdmin)
  {
    $this->isAdmin = $isAdmin;
    return $this;
  }


  /**
   * @return mixed
   */
  public function getRoles()
  {
    $roles = ['ROLE_USER'];

    if ($this->getIsAdmin())
    {
      $roles[] = 'ROLE_ADMIN';
    }

    return $roles;
  }

  /**
   * @return string|null
   */
  public function getPassword()
  {
    return null;
  }

  /**
   * @return string|null
   */
  public function getSalt()
  {
    return null;
  }

  /**
   * @return string
   */
  public function getUsername()
  {
    return $this->getLogin();
  }

  /**
   * @return mixed
   */
  public function eraseCredentials()
  {

  }

  /**
   * @return int
   */
  public function getRepresentative()
  {
    return $this->representative;
  }

  /**
   * @param $representative
   * @return $this
   */
  public function setRepresentative($representative)
  {
    $this->representative = $representative;

    return $this;
  }

  /**
   * @return null|CompanyBranch
   */
  public function getBranch()
  {
    return $this->branch;
  }

  /**
   * @param CompanyBranch $branch
   *
   * @return $this
   */
  public function setBranch($branch)
  {
    $this->branch = $branch;

    return $this;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ? $this->getFullName() : 'Новый пользователь';
  }
}
