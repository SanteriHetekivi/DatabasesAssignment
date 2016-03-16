<?php
// Routes

$app->get('/{app}/{action}/[/{par0}[/{par1}]]', function ($request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/' route");
    require __DIR__ . "/SetupRoot.php";
    SetupRoot::setApp($args["app"]);
    $pathApp = SetupRoot::pathApp();
    if($pathApp)
    {
        // Require libraries
        require __DIR__ . '/../src/lib/require.php';
        // Require app
        require $pathApp . "require.php";
        return $this->renderer->render($response, 'json.phtml', $args);
    }
    else die("APP NOT SUPPORTED!");
});

