<?php

namespace Managers;


use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SpreadSheetManager
{
    /**
     * @param string $filePath
     * @param bool $header
     * @return \Illuminate\Support\Collection
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function excelToArray(string $filePath, bool $header = true) {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rowIterator = $worksheet->getRowIterator();

        $formattedData = [];
        $headers = [];
        $headersCounter = 0;

        if ($header && $rowIterator->seek(1)) {
            $headersIterator = $rowIterator->seek(1)->current()->getCellIterator();

            foreach ($headersIterator as $headerIndex => $headerItem) {
                $headerItemValue = $headerItem->getFormattedValue();

                if ($headerItemValue) {
                    $headers[$headerIndex] = $headerItemValue;
                }
            }

            if ($headers) {
                $formattedData['headers'] = $headers;
                $headersCounter = \count($headers);
                $worksheet->removeRow(1);
            }
        }

        foreach ($rowIterator as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $tempData = [];
            $cellCounter = 0;

            foreach ($cellIterator as $index => $cell) {
                $cellValue = $cell->getFormattedValue();

                if (!$header || !$headers) {
                    $tempData[$cell->getColumn()] = $cellValue;
                    continue;
                }

                if ($header && $headers && $cellCounter++ <= $headersCounter && isset($headers[$index])) {
                    $tempData[$headers[$index]] = $cellValue;
                    continue;
                }
            }

            if ($tempData['title'] !== '') {
                $formattedData['items'][] = $tempData;
            }
        }

        return collect($formattedData);
    }

    public static function array_remove_null($item)
    {
        if (!is_array($item)) {
            return $item;
        }

        return collect($item)
            ->reject(function ($item) {
                return is_null($item);
            })
            ->flatMap(function ($item, $key) {

                return is_numeric($key)
                    ? [array_remove_null($item)]
                    : [$key => array_remove_null($item)];
            })
            ->toArray();
    }

    /**
     * @param array $metaData
     * @param string $filePath
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function arrayToExcel(Array $metaData = [], string $filePath='Test.xlsx') {

        $headers = array_keys($metaData[0]);
        array_unshift($metaData, $headers);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($metaData, NULL, 'A1');

        try{
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($filePath);
            return true;

        } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e){
            return false;
        }

    }
}