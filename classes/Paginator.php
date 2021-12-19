<?php 

class Paginator {

    public $limit;
    public $offset;
    public $previous;
    public $next;

    public function __construct($page, $records_per_page, $total_records){


        $this->limit = $records_per_page;
        

        $page = filter_var($page, FILTER_VALIDATE_INT, [
            'options' => [
                'default'   => 1,
                'min_range' => 1
            ]
        ]);

        if($page > 1){
            $this -> previous = $page - 1;
        }

        $total_page = ceil($total_records / $records_per_page);

        if($page < $total_page){
            $this -> next = $page + 1;
        }

        $this -> offset = $records_per_page * ($page - 1);

        

    }

}