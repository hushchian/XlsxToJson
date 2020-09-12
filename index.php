<?php

require_once './vendor/autoload.php';


//вычетать содержимое таблицы в масив
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$file = $reader->load('source.xlsx');
$fileContent = $file->getAllSheets()[0]->toArray();


//преобразовать структуру массива к той какую попросили
$jsonContent = [];
$priceCalculator = new App\PriceCalculator();

foreach ($fileContent as $id => $row) {
    if ($id === 0) {
        continue;
    }

    $pricePerSquare = [];
    $pricePerSquare['usd'] = str_replace(',', '', $row[8]);
    $pricePerSquare['uah'] = $priceCalculator->getUahFromUsd($pricePerSquare['usd']);

    $priceTotal = [];
    $priceTotal['usd'] = str_replace(',', '', $row[9]);
    $priceTotal['uah'] = $priceCalculator->getUahFromUsd($priceTotal['usd']);

    $fields = [];
    $fields['house'] = $row[3];
    $fields['section'] = $row[4];
    $fields['floor'] = $row[5];
    $fields['rooms'] = $row[1];
    $fields['square'] = $row[6];
    $fields['pricePerSquare'] = $pricePerSquare;
    $fields['priceTotal'] = $priceTotal;

    $jsonItem = [];
    $jsonItem['id'] = $id;
    $jsonItem['title'] = $row[0];
    $jsonItem['type'] = $row[2];
    $jsonItem['fields'] = $fields;

    $jsonContent[] = $jsonItem;
}

//преобразовать массив в json
echo json_encode($jsonContent, JSON_UNESCAPED_UNICODE);

