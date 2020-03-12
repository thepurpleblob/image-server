<?php

namespace collection\action;

class findsingle extends action {

    public function post() {
        global $CFG;

        $objectnumber = $this->data->objectnumber;
        $item = \ORM::for_table('items')
        ->where('object_number', $objectnumber)
	->find_one();
        if (!$item) {
            return false;
        }

        // Check if image exists
        $item = $item->as_array();
        $item['exists'] = file_exists($CFG->datadir . '/' . $item['reproduction_reference']);

        return json_encode($item);
    }
}
