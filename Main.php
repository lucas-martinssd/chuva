<?php

namespace Chuva\Php\WebScrapping;

//Manipular a panilha, salvar e consular ela
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\DomCrawler\Crawler;

class Main {

  //Mudar a coluna que esta como numerica para "A", "B", para ficar compativel com o exel
  static function columnName($num)
  {
    $numeric = $num - 1;
    $columnName = '';
    while ($numeric >= 0) {
      $columnName = chr($numeric % 26 + 65) . $columnName;
      $numeric = floor($numeric / 26) - 1;
    }

    return $columnName;
  }

  public static function run(): void {

    $html = file_get_contents(realpath($_SERVER['DOCUMENT_ROOT']) . '/assets/origin.html');

    $crawler = new Crawler($html);

    $elementosContainer = $crawler->filter('a.paper-card');

    $dados = (new Scrapper())->scrap($elementosContainer);

    $worksheetData = [];
    $maxAuthors = 0;


    $header = ['ID', 'Title', 'Title'];
    foreach ($dados as $row) {
      foreach ($row->authors as $authorIndex => $_) {
        if ($maxAuthors < $authorIndex) {
          $maxAuthors = $authorIndex;
        }
      }
    }

    for ($i = 0; $i < $maxAuthors; ++$i) {
      array_push($header, "Author " . ($i + 1), "Author " . ($i + 1) . " Institution");
    }

    array_push($worksheetData, $header);

    foreach ($dados as $row) {
      $rowData = [$row->id, $row->title, $row->type];
      foreach ($row->authors as $author) {
        array_push($rowData, $author->name, $author->institution);
      }
      array_push($worksheetData, $rowData);
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    foreach ($worksheetData as $rowIndex => $row) {
      foreach ($row as $colIndex => $cellValue) {
        $sheet->setCellValue(self::columnName($colIndex + 1) . ($rowIndex + 1), $cellValue);
      }
    }

    $writer = new Xlsx($spreadsheet);

    $writer->save(realpath($_SERVER['DOCUMENT_ROOT']) . '/assets/dados.xlsx');

    echo "Planilha salva com sucesso!\n";
  }
}
