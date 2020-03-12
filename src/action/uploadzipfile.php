<?php

namespace collection\action;

class uploadzipfile extends action {

    public function post() {
        global $CFG;
      
        if (isset($_FILES["zipfile"]["name"])) {
            $name = $_FILES["zipfile"]["name"];
            $tempname = $_FILES["zipfile"]["tmp_name"];

            $zip = new \ZipArchive();
            if ($zip->open($tempname)) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $path =  $zip->statIndex($i);
                    $name = $path['name'];
                    $zip->extractTo($CFG->datadir, $name);
                }
            }
        }

        return 'done';
    }
}
