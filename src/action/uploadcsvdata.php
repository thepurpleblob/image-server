<?php

namespace collection\action;

class uploadcsvdata extends action {

    public function post() {
        global $CFG;

        $data = html_entity_decode($this->data->csvdata);
        $data = str_replace('&#39;', "'", $data);

        // Process CSV
        $csv = new \collection\lib\csv();
        $csv->process($data);
        $csv->verifyHeaders();
        $lines = $csv->processLines();

        // write to database
        foreach ($lines as $line) {
            if ($line['error']) {
                continue;
            }
            $objectnumber = $line['object_number'];
            if (!$item = \ORM::for_table('items')->where(['object_number' => $objectnumber])->find_one()) {
                $item = \ORM::for_table('items')->create();
                $item->object_number = $objectnumber;
            }
            $item->institution_code = $line['institution_code'];
            $item->title = $line['title'];
            $item->object_category = $line['object_category'];
            $item->description = $line['description'];
            $item->reproduction_reference = $line['reproduction_reference'];
            $item->save();
        }

        return json_encode($csv->getErrors());
    }
}
