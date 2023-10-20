<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(Crawler $elementosContainer) {
    $dados = [];

    foreach ($elementosContainer as $container) {      
      //Cria nova instancia da classe Crawler passando o $container como argumento para o construtor, permitindo que
      //use os metodos Crawler no elemento atual
      $crawlerContainer = new Crawler($container);
      //Seleciona elemento <h4> do .my-xs.paper-title, obtem o texto e armazena em $titulo
      $titulo = $crawlerContainer->filter('h4.my-xs.paper-title')->text();

      //Inicialização de $id e $tag com string vazio
      $id = "";
      $tag = "";

      //Inicialização $autores com array vazio
      $autores = [];

      //Seleciona .volume-info e obtem o texto armazenando em $id
      $id = $crawlerContainer->filter('.volume-info')->text();
      //Seleciona o primeiro elemento de .tags e obtem o texto armazenando em $tag
      $tag = $crawlerContainer->filter('.tags')->first()->text();

      //Itera os elementos <span> dentro de .authors, each é usado para retornar a chave e o valor do elemnto atual e avançar o cursor
      $crawlerContainer->filter('.authors span')->each(function ($node, $i) use (&$id, &$tag, &$autores) {
        //Remove ; no final e armazena em $nomeAutor
        $nomeAutor = rtrim($node->text(), ";");
        //Obtem valor atrivuido 'title' e armazena em $instutuicao
        $instituicao = $node->attr('title');

        $author = new Person($nomeAutor, $instituicao);

        //Novo array associativo a $autores, duas chaves: nomes é $nomeAutor e intituicao é $instituicao
        array_push($autores, $author);
      });
      
      $dado = new Paper($id, $titulo, $tag, $autores);

      // Estrutura de dados definindo os tipos da variável
      array_push($dados, $dado);
    }

    return $dados;
  }

}
