<?php 
/**
 * @author Felipe Correia Brito
 * 
 * Twig implementation to Yii framework
 */
class Yiig {
	public static function makeTwigRender($file, array $data = array(), $contextDir = null, $controllerDir = null){
		// import the autoloader and send it to Yii
    	require Yii::getPathOfAlias('application.vendors.Twig').'/Autoloader.php';
        Yii::registerAutoloader(array('Twig_Autoloader', 'autoload'), true);
        
        //define the default context and controller(if any) directories
        $views 		    = array();
        $contextDir 	= (is_null($contextDir)) ? 'application.views' : $contextDir;
        $controllerDir	= (is_null($controllerDir)) ? '.generic'  : '.'.$controllerDir;

        // Set the array with the globals view directories for twig, based on the context and controller name, if exists.
        // It always be {baseview}/layouts, {baseview}/generic (for includes or other generic porpouses) 
        // and {baseview}/{controller} if any controller provided
        array_push($views, _dir($contextDir.'.layouts'));
        array_push($views, _dir($contextDir.$controllerDir));
        if($controllerDir != '.generic')
    		array_push($views, _dir($contextDir.'.generic'));

    	//generates the loaders of Twig
        $loader = new Twig_Loader_Filesystem($views);
        $yiig_params = Yii::app()->params->yiig;
        $twig = new Twig_Environment($loader, $yiig_params);

        if(isset($yiig_params['filters'])){
            require_once _dir(($yiig_params['filters']['file'])) . ".php";
            foreach($yiig_params['filters']['filters'] as $filter){
                $twig->addFilter(preg_replace("/(.*)::/", "", $filter), new Twig_Filter_Function($filter));
            }
        }

        if(isset($yiig_params['functions'])){
            require_once _dir(($yiig_params['functions']['file'])) . ".php";
            foreach($yiig_params['functions']['functions'] as $function){
                $twig->addFunction(preg_replace("/(.*)::/", "", $function), new Twig_Function_Function($function));
            }
        }

        //return the string generated from Twig to the caller
        return $twig->render($file.$yiig_params['extension'], $data);
	}
}

function _dir($dir){
	//Just a alias to get path alias in Yii
	return Yii::getPathOfAlias($dir);
}