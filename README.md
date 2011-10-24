# Beautiful View

## tl;dr

Add some beauty to your application with Beautiful Views.
Designed as a drop in replacement for Kohana's View class
it extends the functionality you already know with template
and view data separation.

Separation is acheived with `ViewModel`s and `Template`s
allowing you to mix and match PHP, Mustache and JSON
templating with your various `ViewModel`s.

 - Works with Kohana 3.2 +
 - Provides various template solutions: PHP, Mustache, JSON.
 - Use `ViewModel`s to isolate your view logic
 - Tested using PHPUnit
 - [Download](https://github.com/beautiful/view/zipball/master)
 - See some [Examples](https://github.com/beautiful/example)
 - Read the [Wiki](https://github.com/beautiful/view/wiki) to learn more

## Quick Examples

### Comparison of old and new View usage

```php
<?php
echo new View('example', array('title' => 'A title'));
echo new View('example', new View_Example);
```

Both are compatible with Beautiful View. However there is a
difference in your templates, using an array will result in
the array being exposed by `extract()`. If you pass in a
descendant object of ViewModel then the object will be the
only variable exposed via `$view`.

### Using Mustache template

```php
<?php
echo new View(new Template_Mustache('example'), new View_Example);

// Or set this in your bootstrap
Template::$default_class = 'Template_Mustache';

// Then just
echo new View('example', new View_Example);
```

### Controller Example of responding to XHR and HTTP

```php
<?php
class Controller_Example extends Controller {
	
	public function action_index()
	{
		$template = 'example';

		if ($this->request->is_ajax())
		{
			$template = new Template_JSON('example');
		}

		$view = new View($template, new View_Example);
		$this->response->body($view);
	}

}
```

## Author & Copyright

[Luke Morton 2011](http://lukemorton.co.uk)

## License

MIT

## Contributing

Forking fork it and send me a pull request.
