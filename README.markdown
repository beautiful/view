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

// Specific template filename
echo new View(new Template_Mustache('blog/post/alt'), new View_Blog_Post);

// Using default template renderer with custom filename
echo new View('blog/post/alt', new View_Blog_Post);

// Using multiple templates with one ViewModel
// Using mustache
$view = new View(new View_Blog_Post);
echo $view->render(new Template_Mustache);
echo $view->render(new Template_Mustache('blog/post/alt'));
echo $view->render(new Template_Default);
echo $view->render(new Template_Json); // Template_Json not included

```

## Author

Luke Morton

## License

MIT

## Contributing

Forking fork it and send me a pull request.