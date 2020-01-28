<?php

namespace collection;

class findimages extends action {

    public function get() {
        global $CFG;

        $search = $this->data->searchtext;
        $items = \ORM::for_table('items')
        ->where_raw('MATCH (title, description) AGAINST (? IN NATURAL LANGUAGE MODE)', array($search))
	->find_array();

        // Check if images exist
        $newitems = [];
        foreach ($items as $item) {
            $item['exists'] = file_exists($CFG->datadir . '/' . $item['reproduction_reference']);
            $newitems[] = $item;
	}

        return json_encode($newitems);
    }
}
