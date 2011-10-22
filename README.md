# Beautiful View

## tl;dr

Put Kohana views on steroids by using the Beautiful
View component module. It gives us more separation of logic
clearing your Controllers and templates of view data
knowledge by placing it in `ViewModel`s.

 - Works with Kohana 3.2 +
 - Provides various template solutions: PHP, Mustache, JSON.
 - Gives you ViewModel from MVVM
 - Tested using PHPUnit
 - [Download](https://github.com/beautiful/view/zipball/master)
 - [Example](https://github.com/beautiful/example)
 - [Wiki](https://github.com/beautiful/view/wiki)

## Quick Example

```php
<?php
$template = 'example';

if ($this->request->is_ajax())
{
	$template = new Template_JSON('example');
}

$view = new View($template, new View_Example);
$this->response->body($view);
```

## Author & Copyright

[Luke Morton 2011](http://lukemorton.co.uk)

## License

MIT

## Contributing

Forking fork it and send me a pull request.
