<?php

namespace Chuva\Php\WebScrapping\Entity;

/**
 * The Paper class represents the row of the parsed data.
 */
class Paper {

  /**
   * Paper Id.
   * @var string
   */
  public string $id;

  /**
   * Paper Title.
   *
   * @var string
   */
  public  $title;

  /**
   * The paper type (e.g. Poster, Nobel Prize, etc).
   *
   * @var string
   */
  public $type;

  /**
   * Paper authors.
   *
   * @var \Chuva\Php\WebScrapping\Entity\Person[]
   */
  public $authors;

  /**
   * Builder.
   */
  
   // Construtor é chamado toda vez que a classe é instanciada, serve para atribuir valor nos seus atributos.
   public function __construct($id, $title, $type, $authors) {
    $this->id = $id;
    $this->title = $title;
    $this->type = $type;
    $this->authors = $authors;
  }
}
