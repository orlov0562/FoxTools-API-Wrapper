<?php

    require_once 'FoxTools.php';

    $foxTools = new FoxTools\FoxTools;

    try {
        $proxyList = $foxTools->GetProxy();
    } catch (Exception $ex) {
        die($ex);
    }

    die($proxyList);