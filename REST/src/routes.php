<?php
// Routes

use Psr\Http\Message\RequestInterface;

$app->get('/{app}/{action}[/{par0}[/{par1}]]', function (RequestInterface $request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/' route");
    $data = array();
    $app = false;
    $action = false;
    $pars = array();
    $errorInfo = array(Err::FILE => __FILE__, Err::FUNC => __FUNCTION__);
    /**
     * Parsing given arguments.
     */
    foreach($args as $name => $arg)
    {
        if($name === "action") $action = $arg;
        elseif($name === "app") $app = $arg;
        elseif(Checker::Contains($name, "par")) $pars[$name] = $arg;
    }
    $body    = $request->getBody();
    $values  = json_decode($body,true);
    $pars["values"] = $values;
    /**
     * Checking app and action names.
     */
    if(Checker::isString($app, false, $errorInfo) && Checker::isString($action, false, $errorInfo))
    {
        /**
         * Making app from given app's name.
         */
        SetupRoot::setApp($app);
        $pathApp = SetupRoot::pathApp();
        if($pathApp)
        {
            // Require app
            require $pathApp . "require.php";
            if(!method_exists($this, $action) || !is_callable(array($this, $action)))
            {
                array_unshift($pars, $action);
                $action = "GET";
            }
            $app = new App();
            /**
             * Calling given action with given parameters.
             */
            $data = $app->CALL($action, $pars);
        }
    }

    return $this->renderer->render($response, 'json.phtml', array("data" => DATA::MAKE($data)));
});

$app->post('/{app}/{action}[/{par0}[/{par1}]]', function (RequestInterface $request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/' route");
    $data = array();
    $app = false;
    $action = false;
    $pars = array();
    $errorInfo = array(Err::FILE => __FILE__, Err::FUNC => __FUNCTION__);
    /**
     * Parsing given arguments.
     */
    foreach($args as $name => $arg)
    {
        if($name === "action") $action = $arg;
        elseif($name === "app") $app = $arg;
        elseif(Checker::Contains($name, "par")) $pars[] = $arg;
    }
    $body    = $request->getBody();
    $values   = json_decode($body,true);
    $pars["values"] = $values;
    /**
     * Checking app and action names.
     */
    if(Checker::isString($app, false, $errorInfo) && Checker::isString($action, false, $errorInfo))
    {
        /**
         * Making app from given app's name.
         */
        SetupRoot::setApp($app);
        $pathApp = SetupRoot::pathApp();
        if($pathApp)
        {
            // Require app
            require $pathApp . "require.php";
            $app = new App();
            if(!method_exists($app, $action) || !is_callable(array($app, $action)))
            {
                array_unshift($pars, $action);
                $action = "ADD";
            }
            /**
             * Calling given action with given parameters.
             */
            $data = $app->CALL($action, $pars);
        }
    }

    return $this->renderer->render($response, 'json.phtml', array("data" => DATA::MAKE($data)));
});

$app->put('/{app}/{action}[/{par0}[/{par1}]]', function (RequestInterface $request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/' route");
    $data = array();
    $app = false;
    $action = false;
    $pars = array();
    $errorInfo = array(Err::FILE => __FILE__, Err::FUNC => __FUNCTION__);
    /**
     * Parsing given arguments.
     */
    foreach($args as $name => $arg)
    {
        if($name === "action") $action = $arg;
        elseif($name === "app") $app = $arg;
        elseif(Checker::Contains($name, "par")) $pars[] = $arg;
    }
    $body    = $request->getBody();
    $values   = json_decode($body,true);
    $pars["values"] = $values;
    /**
     * Checking app and action names.
     */
    if(Checker::isString($app, false, $errorInfo) && Checker::isString($action, false, $errorInfo))
    {
        /**
         * Making app from given app's name.
         */
        SetupRoot::setApp($app);
        $pathApp = SetupRoot::pathApp();
        if($pathApp)
        {
            // Require app
            require $pathApp . "require.php";
            $app = new App();
            if(!method_exists($this, $action) || !is_callable(array($this, $action)))
            {
                array_unshift($pars, $action);
                $action = "UPDATE";
            }
            /**
             * Calling given action with given parameters.
             */
            $data = $app->CALL($action, $pars);
        }
    }

    return $this->renderer->render($response, 'json.phtml', array("data" => DATA::MAKE($data)));
});

$app->delete('/{app}/{action}[/{par0}[/{par1}]]', function (RequestInterface $request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/' route");
    $data = array();
    $app = false;
    $action = false;
    $pars = array();
    $errorInfo = array(Err::FILE => __FILE__, Err::FUNC => __FUNCTION__);
    /**
     * Parsing given arguments.
     */
    foreach($args as $name => $arg)
    {
        if($name === "action") $action = $arg;
        elseif($name === "app") $app = $arg;
        elseif(Checker::Contains($name, "par")) $pars[] = $arg;
    }
    $body    = $request->getBody();
    $values  = json_decode($body,true);
    $pars["values"] = $values;
    /**
     * Checking app and action names.
     */
    if(Checker::isString($app, false, $errorInfo) && Checker::isString($action, false, $errorInfo))
    {
        /**
         * Making app from given app's name.
         */
        SetupRoot::setApp($app);
        $pathApp = SetupRoot::pathApp();
        if($pathApp)
        {
            // Require app
            require $pathApp . "require.php";
            $app = new App();
            if(!method_exists($this, $action) || !is_callable(array($this, $action)))
            {
                array_unshift($pars, $action);
                $action = "DELETE";
            }
            /**
             * Calling given action with given parameters.
             */
            $data = $app->CALL($action, $pars);
        }
    }

    return $this->renderer->render($response, 'json.phtml', array("data" => DATA::MAKE($data)));
});


