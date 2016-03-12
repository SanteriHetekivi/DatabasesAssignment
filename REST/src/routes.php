<?php
// Routes

$app->get('/GET/{table}[/{id}[/{columns}[/{whereColumns}[/{whereOperators}[/{whereValues}[/{order}[/{by}[/{limit}[/join]]]]]]]]]', function ($request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/' route");
    //var_dump($args);
    $mysql = new MySQL();
    $return = array();
    $return["data"] = $mysql->GET($args);

    return $this->renderer->render($response, 'json.phtml', $return);
});

