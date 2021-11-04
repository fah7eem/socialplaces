<?php
namespace Contact;
use System\Database;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Form extends Database{
    public function __construct(){
        parent::__construct();
    }

    public function validate($inputs){
        $this->name = $this->clean_inputs($inputs['name']);
        $this->email = $this->clean_inputs($inputs['email']);
        $this->gender = strtoupper($this->clean_inputs(isset($inputs['gender']) ? $inputs['gender'] : "none"));
        $this->other = strtoupper($this->clean_inputs($inputs['other']));
        $this->content = $this->clean_inputs($inputs['content']);
        try{
            v::stringType()->length(4, 255)->assert($this->name);
            v::email()->length(4, 150)->assert($this->email);
            if($this->gender == "other"){
                v::stringType()->length(1, 25)->assert($this->other);
            }
            v::stringType()->length(14, 1025)->assert($this->content);
        } catch(NestedValidationException $exception) {
            return $exception->getMessages();
        }

        return false;
    }

    public function submit(){
        $this->db->insert("contact", [
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'other' => $this->other,
            'content' => $this->content
        ]);
    }

}