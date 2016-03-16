<?php
// Routes

$app->get('/{app}/action/[/{par0}[/{par1}]]', function ($request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/' route");

    return $this->renderer->render($response, 'json.phtml', $args);
});

