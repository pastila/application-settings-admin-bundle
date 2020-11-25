### Bundle requires: 
ImagingBundle, MediaBundle, ClientApplicationBundle, SlugifierBundle
### Install
- Install bundle 
```
composer require accurateweb/gpn_news_bundle dev-master
```
- Add Bundle in AppKernel.php
```

``` 
- Create Entity and redeclare $relatedNews.
```
<?php

namespace AppBundle\Entity\Common;

use Accurateweb\NewsBundle\Model\NewsInterface;
use Doctrine\ORM\Mapping as ORM;
use \Accurateweb\NewsBundle\Model\News as Base;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Common\NewsRepository")
 * @ORM\Table()
 */
class News extends Base
{
  /**
   * @var NewsInterface[]|ArrayCollection
   * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Common\News")
   */
  protected $relatedNews;
}
```
- Create repository
```
<?php

namespace AppBundle\Repository\Common;

use \Accurateweb\NewsBundle\Repository\NewsRepository as Base;

class NewsRepository extends Base
{

}
```
- Add params in config.yml
```
parameters:
    aw.news.admin.group: text # sonata admin group
    aw.news.admin.label: "Новости" # sonata admin label
    aw.news.entity_class: AppBundle\Entity\Common\News # path to child class
```
- Add route resource in routing.yml (before main bundle).
```
news:
  resource: "@AccuratewebGpnNewsBundle/Resources/config/routing.yml"
```
### Use
