<?php

use Auth\User;


$container->set('user', new User());

$container->set('common_template', function ($container) {
    $templates =  new \Slim\Views\PhpRenderer('../app/views/');
    $templates->setLayout("templates/layout.phtml");

    $templates->addAttribute("container_user", $container->get("user")->getDetails());
    return $templates;
});
