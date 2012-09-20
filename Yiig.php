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
        
        //register default array of paths
        $contextDir = (is_null($contextDir)) ? array('application.views') : $contextDir;

        //register the controllers directory
        $contextDir = array_map("_add", $contextDir, array($controllerDir));

        //generates the loaders of Twig
        $yiig_params = Yii::app()->params->yiig;
        $yiig_params['views_dir'] = array_map("_dir", $contextDir);

        $loader = new Twig_Loader_Filesystem($yiig_params['views_dir']);
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

function _add($n, $m){
    return $n.'.'.$m;
}