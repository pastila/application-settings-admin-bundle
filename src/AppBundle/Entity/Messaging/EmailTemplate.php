<?php

namespace AppBundle\Entity\Messaging;

use Accurateweb\EmailTemplateBundle\Entity\EmailTemplate as BaseEmailTemplate;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of EmailTemplate
 *
 * @ORM\Entity()
 * @ORM\Table(name="email_templates")
 */
class EmailTemplate extends BaseEmailTemplate
{

}