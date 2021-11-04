<?php
namespace Contact;
use System\Database;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Data extends Database{
    public function __construct(){
        parent::__construct();
    }

    public function get($start, $length, $search, $filter){
        $search_terms = "where 1 = 1 ";
        $searchStatus = false;
        if(!empty($filter)){
            $search_terms .= " and case when gender <> 'other' then gender else other end  = '$filter' ";
            $searchStatus = true; 
        }
        if(!empty($search)){
            $search_terms .= " and concat(`name`, ' ',`email`, ' ',`gender`, ' ',`content`, ' ') like '%%$search%%' ";
            $searchStatus = true;     
        }
        $results = $this->db->query("select name, email, case when gender <> 'other' then gender else other end gender, `content`, `time` from contact ".($searchStatus == false ? "": $search_terms)." order by time desc limit %i , %i", $start, $length);
        return  $results;
    }

    public function get_total($search, $filter){
        $search_terms = "where 1 = 1";
        $searchStatus = false;
        if(!empty($filter)){
            $search_terms .= " and case when gender <> 'other' then gender else other end  = '$filter' ";
            $searchStatus = true; 
        }
        if(!empty($search)){
            $search_terms .= " and concat(`name`, ' ',`email`, ' ',`gender`, ' ',`content`, ' ') like '%%$search%%' ";
            $searchStatus = true;     
        }
        $results = $this->db->queryFirstField("select count(*) count from contact ".($searchStatus == false ? "": $search_terms)."");
        return  $results;
    }

    public function get_filtered_values(){
        return $this->db->queryFirstColumn("select distinct case when gender <> 'other' then gender else other end gender from contact ");
    }
}