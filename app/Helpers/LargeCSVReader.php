<?php


namespace App\Helpers;


class LargeCSVReader
{

  public function __construct($file, $delimiter = "\t")
  {
    $this->file = $file;
    $this->delimiter = $delimiter;
    $this->iterator = 0;
    $this->header = null;
  }

  public function csvToArray()
  {
    $data = array();
    while (($row = fgetcsv($this->file, 1000, $this->delimiter)) !== false) {
      $is_mul_1000 = false;
      if (!$this->header) {
        $this->header = $row;
      } else {
        $this->iterator++;
        $data[] = array_combine($this->header, $row);
        if ($this->iterator != 0 && $this->iterator % 1000 == 0) {
          $is_mul_1000 = true;
          $chunk = $data;
          $data = array();
          yield $chunk;
        }
      }
    }
    fclose($this->file);
    if (!$is_mul_1000) {
      yield $data;
    }
    return;
  }
}
