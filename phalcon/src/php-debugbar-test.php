<?php
include "../vendor/autoload.php";
use DebugBar\StandardDebugBar;
use Phalcon\Mvc\Controller;

$di = null;
$application = new Phalcon\Mvc\Application($di); // Important: mustn't ignore $di param . The Same Micro APP: new Phalcon\Mvc\Micro($di);
$di['app'] = $application; //  Important
$di->set("jquery", function () {
	$jquery = new Ajax\JsUtils(["driver" => "Jquery"]);
	$jquery->ui(new Ajax\JqueryUI());//optional for JQuery UI
	$jquery->bootstrap(new Ajax\Bootstrap());//Optional for Twitter Bootstrap
	//$jquery->semantic(new Ajax\Semantic());//Optional for Semantic-UI
	return $jquery;
});
$debugbar = new StandardDebugBar();
$debugbarRenderer = $debugbar->getJavascriptRenderer();
$debugbar["messages"]->addMessage("hello world!");
class ExController extends Controller{
public function initialize() {
	$this->view->setVar("jquery", $this->jquery->genCDNs());
}
?>
<html>
<head>
	<?php echo $debugbarRenderer->renderHead() ?>
</head>
<body>
...
<?php echo $debugbarRenderer->render();
//(new Snowair\Debugbar\ServiceProvider())->start();
// after start the debugbar, you can do noting but handle your app right now.
//echo $application->handle()->getContent();
?>
</body>
</html>