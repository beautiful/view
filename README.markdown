# Beautiful View

A new vision for views in Kohana. 

## Status Quo

Kohana ships with it's own view functionality in it's class
`Kohana_View` which supports PHP templates and allows data to be
passed into these templates using arrays. For example:

``` php
echo View::factory('path/to/view', array(
	'page_title' => 'A simple example',
));
```

Further variables can be assigned using various methods and property
assignment.

## The future

Something more powerful than arrays are needed. Something to take the
overburdening view logic from controllers. That some are
`ViewModel`s.

A ViewModel is a class which loads and manipulates data for viewing.
Their sole role is to provide view data.

So how to display such classes? Well this brings in `Template`s.
`Template` classes describe a template rendering method. They can be 
passed a template path to load.

Merging a `ViewModel` and a `Template` is the `View` class, similar
to the role `Kohana_View` currently provides.

## This module currently

The aim of this module is to extend the expected behaviour of
`Kohana_View`. Not to break away from existing API, but to allow the
flexibility to use `ViewModel`s and `Template`s when you want.

``` php
echo new View(new View_Blog_Post);

// Mustache
echo new View(new Template_Mustache, new View_Blog_Post);

// Specific template filename with mustache
echo new View(new Template_Mustache('blog/post/alt'), new View_Blog_Post);

// Using custom template filename with default
echo new View('blog/post/alt', new View_Blog_Post);

// Using multiple templates with one ViewModel
$view = new View(new View_Blog_Post);

echo $view->render(new Template_Mustache);
echo $view->render(new Template_Mustache('blog/post/alt'));
echo $view->render(new Template_Default);
echo $view->render(new Template_Json); // Template_Json not included

// One template with multiple ViewModels
$view = new View(new Template_Mustache('page'));

echo $view->render(NULL, new View_Home);
echo $view->render(NULL, new View_About);
echo $view->render(NULL, new View_Contact);
```

## Decisions you should be aware of

In order to keep the old `Kohana_View` API (atleast for now) I
decided to allow `Beautiful_View::__construct()` and 
`Beautiful_View::factory()` to be more flexible in their parameters.

For example both these methods allow the following:

``` php
new View('path/to/template');
new View('path/to/template', array('variable' => 'value'));
```

This is the existing signature of `Kohana_View`. However
`Beautiful_View` also adds in these extras:


``` php
new View(new Template_Default('path/to/template'));
new View(new Template_Default('path/to/template'), array('variable' => 'value'));
new View(new Template_Default('path/to/template'), new View_Page);
new View('path/to/template', new View_Page);
```

The following was also allowed but I'm not sure it should be.. what
do you think?

``` php
new View(new View_Page);
```

The above is a shortcut to:

``` php
new View(NULL, new View_Page);
```

The `View::render()` method however has a stricter policy when it
comes down to parameters. They are type-hinted and therefore the
first parameter must be of `Template`, the second of `ViewModel`.

## Author

Luke Morton

## License

MIT

## Contributing

Forking fork it and send me a pull request.