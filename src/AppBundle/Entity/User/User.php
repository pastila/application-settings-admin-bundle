<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\Page\ConstructorPage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="pk_system_user")
 */
class User
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, options={"default"=""})
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, options={"default"=""}, unique=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, options={"default"=""}, unique=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, options={"default"=""})
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, options={"default"=""})
     */
    protected $url;

    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=false, options={"default"=0})
     */
    protected $status = 0;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false, name="registered")
     */
    protected $registeredAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $login;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    protected $activation;

    /**
     * @var array
     * @ORM\Column(type="simple_array", nullable=true)
     */
    protected $roles;

    /**
     * @var array
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $data;

    /**
     * @var ArrayCollection|ConstructorPage[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Page\ConstructorPage", mappedBy="author")
     */
    protected $constructorPages;

    public function __construct()
    {
        $this->constructorPages = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }

    /**
     * @param \DateTime $registeredAt
     *
     * @return $this
     */
    public function setRegisteredAt($registeredAt)
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param \DateTime $login
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
    public function getActivation()
    {
        return $this->activation;
    }

    /**
     * @param string $activation
     *
     * @return $this
     */
    public function setActivation($activation)
    {
        $this->activation = $activation;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return ArrayCollection|ConstructorPage[]
     */
    public function getConstructorPages()
    {
        return $this->constructorPages;
    }

    /**
     * @param ArrayCollection|ConstructorPage[] $constructorPages
     *
     * @return $this
     */
    public function setConstructorPages($constructorPages)
    {
        $this->constructorPages = new ArrayCollection();

        foreach ($constructorPages as $constructorPage) {
            $this->addConstructorPage($constructorPage);
        }

        return $this;
    }

    public function addConstructorPage(ConstructorPage $constructorPage)
    {
        $this->constructorPages->add($constructorPage);
        $constructorPage->setAuthor($this);

        return $this;
    }

    public function removeConstructorPage(ConstructorPage $constructorPage)
    {
        $this->constructorPages->removeElement($constructorPage);

        return $this;
    }
}
