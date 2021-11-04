<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Contact\Form;
use Contact\Data;


$app->get('/', function (Request $request, Response $response, $args) {
    return $this->get('common_template')->render($response, "home.phtml", ["title" => "Contact Us", "message" => "Content"])->withStatus(200);
});


$app->get('/admin', function (Request $request, Response $response, $args) {
        $data = NEW Data();
        return $this->get('common_template')->render($response, "admin.phtml", ["title" => "Admin", "message" => "Content", "filters" => $data->get_filtered_values()])->withStatus(200);
});

$app->post('/contact/submit', function (Request $request, Response $response, $args) {
        $inputs = $request->getParsedBody();
        $form = new Form();
        if($form->validate($inputs) == false){
                $form->submit($inputs);
                $result = "Success";
        }else{
                $result = "Failed";
        }
        $response->getBody()->write(json_encode(array("result" => $result)));
        return $response->withAddedHeader('Content-Type','application/json')->withStatus(200);
});

$app->get('/admin/data', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
        $data = NEW Data();
        $draw =  $_GET['draw'];
        $start =  (int)$_GET['start'];
        $length = (int)  $_GET['length'];
        $search = $_GET['search']['value'];
        $filter = $_GET['columns'][3]["search"]["value"];
        $result = $data->get($start, $length, $search, $filter);
        $total = (int)$data->get_total($search, $filter);
        $return =  array
        (
            "draw" => (int) $draw,
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $result,
        );
        $response->getBody()->write(json_encode($return));
        return $response->withAddedHeader('Content-Type','application/json')->withStatus(200);
});