<?php

namespace Managers;


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

            $formattedData['items'][] = $tempData;
        }

        return collect($formattedData);
    }


}