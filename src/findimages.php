<?php

namespace collection;

class findimages extends action {

    public function get() {
        //var_dump($this->data); die;
        $search = $this->data->searchtext;
        $items = \ORM::for_table('items')
        ->where_raw('MATCH (title, description) AGAINST (? IN NATURAL LANGUAGE MODE)', array($search))
        ->find_array();
        //var_dump($items); die;

        return json_encode($items);
    }
}