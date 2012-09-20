<?php 

/**
 * @author Felipe Correia Brito
 * 
 * Implementation of static yii pages with twig template engine.
 * Override the run method of CViewAction to use the Yiig method makeTwigRender
 */

require_once 'Yiig.php';

class YiigStatic extends CViewAction {
	public function run()
	{
		//Discover the view to render and use the patterns
		$view_to_render = $this->getRequestedView();
		$controller = $this->getController();
		$module = $this->getModule();

		$this->onBeforeRender($event = new CEvent($this));
		if(!$event->handled)
		{
			echo Yiig::makeTwigRender("pages/{$view_to_render}", array(), 'application.views', $controller->getId());
			$this->onAfterRender(new CEvent($this));
		}
	}
}