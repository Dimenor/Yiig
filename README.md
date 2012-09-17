# Yiig
## Implementação da Twig Template Engine para o Yii Framework

* Yii Framework   - http://yiiframework.com
* Twig 				- http://twig.sensiolabs.org

Eu gosto muito do twig template engine, desenvolvida por Fabien Potencier, mas nao encontrei uma forma de implementar no Yii que não modificasse o view renderer. E não era isso que eu queria.

Então desenvolvi uma pequena biblioteca que integra a twig em meus projetos, em que ao inves de mudar o metodo de renderização dos controladores, adiciona um novo método.

## Como usar:
* **protected/vendors/Twig** e **protected/vendors/Yiig**


Descompacte o pacote da Twig de {pacote-da-versao-baixada}/lib/Twig para de protected/vendors/Twig e cole a pasta Yiig deste repositorio ao lado dela.

* **protected/config/main.php**

Cole o codigo seguinte dentro de ['params'] e mude conforme sua necessidade

```php
	//default yiig config
	'yiig' => array(
		'extension' => '.twig', //file default extension
		
		// In this array you can use the defined options for twig: http://twig.sensiolabs.org/doc/api.html#environment-options
		'cache' => Yii::getPathOfAlias('application.runtime.twig_cache'), //false to disable cache
		'auto_reload' => true, //This recompiles the templates when a change is detected
		
		// from this line to below it's optional
		'filters' => array(
			'file' => 'application.vendors.Yiig.filters',
			'filters' => array('YFilters::yiig_hello'),
		),
		'functions' => array(
			'file' => 'application.vendors.Yiig.functions',
			'functions' => array('YFunctions::yiig_hello'),
		),
	),
```
A partir daí você ja vai ter a implementação praticamente pronta. Bastaria usar:
```php
	require_once Yii::getPathOfAlias('application.vendors.Yiig.Yiig').'.php';
	echo Yiig::makeTwigRender($nome_do_template, $matriz_com_variaveis, $caminho_das_views); //Caminho padrao das views é application.views
```

Mas para manter simples e reutilizável nos nossos controladores, podemos simplesmente criar um metodo no controlador pai:
* protected/components/Controller.php

```php
	// aqui o codigo que seu controller pai ja tinha

	/**
	* Método de implementacao nao obstrutiva do Twig para todos os controladores
	* @param $file Nome do template a ser renderizado
	* @param $data Matriz com dados a serem enviados a view twig
	*/
	public function twig($file, array $data = array()){
		require_once Yii::getPathOfAlias('application.vendors.Yiig.Yiig').'.php';
		echo Yiig::makeTwigRender($file, $data, 'application.views', $this->getId());		
	}
```

E chamar nos nossos controllers quando quisermos, seguindo a mesma api do render
```php
	//codigo do seu controlador SiteController por exemplo:
	//ao inves de $this->render
	$this->twig('index', array('nome' => 'Twig Template Engine'));
```
E no arquivo index.twig dentro de /application/views/site (Esse nome vem de acordo com o controller seguindo a mesma convenção do render padrão:
```twig
{{ nome }}
```
Para as paginas estáticas, como a AboutPage do template padrão do Yii, basta alterar a classe CViewAction pela classe YiigStatic:
* protected/controller/SeuControllerQueServePaginas.php

```php
	public function actions(){
		return array(
			'page' => array(
				'class' => 'application.vendors.Yiig.YiigStatic', //A diferença está nessa linha
			)
		);
	}
```

## Filtros e Funções
O repositorio conta com dois exemplos, um de cada, nos arquivos *Yiig/filters.php* e *Yiig/functions.php*
É possivel adicionar ou remover os metodos nesses arquivos, ou mudar o arquivo de definição no */protected/config/main.php*