<?php
session_start();

define('APP_NAME', "Who's In The Stack");
define('APP_URL', 'http://localhost:8000');

// Include the Queue Manager
require_once __DIR__ . '/../includes/QueueManager.php';

// Helper function to render React components
function renderReactComponent($componentName, $props = [], $id = null) {
    $componentId = $id ?: 'react-' . uniqid();
    $propsJson = htmlspecialchars(json_encode($props), ENT_QUOTES, 'UTF-8');
    
    return sprintf(
        '<div id="%s" data-react-component="%s" data-react-props="%s"></div>',
        $componentId,
        $componentName,
        $propsJson
    );
}
