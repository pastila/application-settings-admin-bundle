<?php


namespace Accurateweb\NewsBundle\Twig;


use Twig\Environment;

class RenderPaginationExtension extends \Twig_Extension
{
  private $environment;

  public function __construct(Environment $environment)
  {
    $this->environment = $environment;
  }

  public function getFunctions()
  {
    return [
      new \Twig_SimpleFunction('newsShowMore', [$this, 'newsShowMore'])
    ];
  }

  public function newsShowMore($page = 1)
  {
      return $this->environment->render('@AccuratewebGpnNews/widget/pagination/show_more_button.html.twig');
  }
}