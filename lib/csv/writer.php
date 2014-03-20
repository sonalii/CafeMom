<?php

namespace CafeMom;

require_once 'base.php';

class CSVWriter extends BaseCSV
{
    public function __construct($path)
    {
        parent::__construct($path, 'w+');
    }

    public function writeRow($row)
    {
        if (is_string($row)) {
            $row = explode(',', $row);
            $row = array_map('trim', $row);
        }
        return $this->handle->fputcsv($row, $this->delimiter, $this->enclosure);
    }

    public function writeFromArray(array $array)
    {
        foreach ($array as $key => $value) {
            $this->writeRow($value);
        }
    }

    public function finish() {
    }
}

class JSonWriter extends BaseCSV
{
    public function __construct($path)
    {
        parent::__construct($path, 'w+');
        $this->handle->fwrite("[" . PHP_EOL);
    }
    public function writeRow($row)
    {
        $this->handle->fwrite(json_encode($row));
        $this->handle->fwrite("," . PHP_EOL);
    }

    public function writeFromArray(array $array)
    {
        foreach ($array as $key => $value) {
            $this->writeRow($value);
        }
    }

    public function finish() {
        $this->handle->fwrite("]");
    }
}
