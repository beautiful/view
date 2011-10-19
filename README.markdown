# Beautiful View

## tl;dr

Put Kohana views on steroids by using the Beautiful
View component module. It gives us more separation of logic
clearing your Controllers and templates of view data
knowledge by placing it in `ViewModel`s.

 - Works with Kohana 3.2 +
 - Provides various template solutions: PHP, Mustache, JSON.
 - Gives you ViewModel from MVVM
 - [Download](http://google.com)
 - [Example](http://google.com)
 - [Author](http://lukemorton.co.uk)

## The long explanation

As I hope we all know by now views make up part of the MVC
triad that Kohana and other frameworks adhere to. Logic
separation is important to keeping our applications sane.
When I say sane I mean concise, focused and extensible.

So we have logic separation in our Kohana applications but
a recent surge of interest in the [Kostache][1] module
hints that developers need more separation than is
currently provided by `Kohana_View`.

[1]: https://github.com/zombor/KOstache

### Currently in Kohana_View

Let's take a simple example of a CMS page Controller using
the standard Kohana_View class.

```php
<?php
class Controller_Page extends Controller {

	public function action_view()
	{
		$stub = $this->request->param('stub');
		$model = Model::factory('Page', array('stub' => $stub));
		$view = View::factory('page', array(
			'title'      => $model->title,
			'navigation' => array(
				array(
					'url'   => Route::url('page', array('stub' => 'home')),
					'label' => 'Home',
				),
				array(
					'url'   => Route::url('page', array('stub' => 'about')),
					'label' => 'About',
				),
			),
			'content'    => $model->content,
		));
		$this->response->body($view);
	}

}
```

A simple example where we set 3 values to the view. Of 
course I could have passed in the model and then referenced 
`$model->title` and `$model->content` from within the
template.

This is all good and proper until you start adding more
data to the array. What if you want to define your
JavaScript and CSS files? Add pagination?

You could keep your controllers lighter by using HMVC to
move your navigation building to another action. You could
move the navigation definition into the template itself.
You might even describe your navigation as a model and pass
in both `Model_Page` and `Model_Navigation` to your
template.

None of these solutions are appropriate or considered in
my opinion. Too many HMVC calls across your application
prove for an unneccessary overhead. Moving navigation into
the template couples the navigation and template, what
happens if you want to reuse the navigation on another
action? You could move the navigation array into a method
in your controller, but it's really not worth convoluting
all these various areas when this stuff is clearly related
to the view!

<!--(
So the limitations I find with `Kohana_View`:

 - Does not auto escape variables against XSS.
 - Only uses PHP templates.
 - Forces you to place logic in the wrong place!
)-->

### Requirements for separation

There are two requirements for providing safe view
structured logic:

 - Remove complex logic from templates completely
 - Use an object to describe view logic.

### MVVM to the rescue

I cannot be bothered to explain MVVM right now any anyway
[wikipedia][mvvm] does a pretty good job. The long and
short of it is, you can place your view specific logic into
`ViewModel` classes.

[mvvm]: http://en.wikipedia.org/wiki/Model_View_ViewModel
 
### Incorporating `ViewModel`s in your application, today!

You could put the `ViewModel` principle directly into your
application today without any module! Take this example:

```php
<?php
class View_Page {

	protected $_stub;
	protected $_page;

	public function __construct($stub)
	{
		$this->_stub = $stub;
	}
	
	protected function page()
	{
		if ($this->_page === NULL)
		{
			$this->_page = Model::factory('Page', array('stub' => $this->_stub));
		}
		
		return $this->_page;
	}

	public function title()
	{
		return $this->page()->title;
	}
	
	public function navigation()
	{
		return array(
			array(
				'url'   => Route::url('page', array('stub' => 'home')),
				'label' => 'Home',
			),
			array(
				'url'   => Route::url('page', array('stub' => 'about')),
				'label' => 'About',
			),
		);
	}
	
	public function content()
	{
		return $this->page()->content;
	}

}
```

The class above defines a basic CMS page using 3 public
methods `::title()`, `::navigation()` and `::content()`.
It provides a lazy loading mechanism to `Model_Page` and
that's about it! The template is brief too:

```php
<!doctype html>
<html>
	<head>
		<title><?= $view->title() ?></title>
	</head>
	<body>
		<h1><?= $view->title() ?></h1>
		<ul>
			<? foreach($view->navigation() as $_link): ?>
				<li>
					<a href="<?= $_link['url'] ?>"><?= $_link['label'] ?></a>
				</li>
			<? endforeach; ?>
		</ul>
		
		<?= $view->content() ?>
	</body>
</html>
```

So you ask,

> How do you actually get this working in my applications?

Easily, within your `Controller_Page`:

```php
<?php
class Controller_Page extends Controller {

	public function action_view()
	{
		$stub = $this->request->param('stub');
		$view = View::factory('page', array('view' => new View_Page($stub)));
		$this->response->body($view);
	}

}
```

Look how slim that action is! And you can do this today
without requiring anything else. You now have:

 - A skinny action
 - A clean template
 - View logic defined as a class

### So what about Beautiful View?

Now you realise the power of the ViewModel is already in
your hands, so why use Beautiful View? 

Well firstly I would like to mention you may not need it.
You can just use a ViewModel as shown above. This module
was built to serve my own needs:

 - Even cleaner Controller actions
 - Use Mustache template rendering, because:
    - Removes all logic from templates
	- Auto escapes all data by default
	- Looks prettier than PHP
 - Render ViewModel data as JSON
 - Not break existing Kohana_View functionality

If you have similar requirements then maybe Beautiful View
is for you. Let me introduce you to her:

```php
<?php
class Controller_Page extends Controller {

	public function action_view()
	{
		$stub = $this->request->param('stub');
		$view = View::factory('page', new View_Page($stub));
		$this->response->body($view);
	}
}
```

> Oh wow, so you saved 17 extra characters... big deal!

Well that is not all we have done my friend, we also added
a line to our bootstrap:

```php
<?php
Template::$default_class = 'Template_Mustache';
```

```php
<?php
class View_Page extends ViewModel {

	// ...

}
```

We have set our default `Template` class to use `Mustache` and
our `View_Page` now extends `ViewModel`.

> Hmmm, interesting?! What does this mean for the template?

```html
<!doctype html>
<html>
	<head>
		<title>{{title}}</title>
	</head>
	<body>
		<h1>{{title}}</h1>
		<ul>
			{{#navigation}}
				<li>
					<a href="{{url}}">{{label}}</a>
				</li>
			{{/navigation}}
		</ul>
		
		{{{content}}}
	</body>
</html>
```

> Woah cool! It's just like using `Kostache` or vanilla
Mustache.

Indeed it is. You can also swap out the template in the
Controller like so:

```php
<?php
class Controller_Page extends Controller {

	public function action_view()
	{
		$stub = $this->request->param('stub');
		$template = 'page';
		
		if ($this->request->is_ajax())
		{
			$template = new Template_JSON($template);
		}
		
		$view = View::factory($template, new View_Page($stub));
		$this->response->body($view);
	}
	
}
```

If the first parameter is a string path then it will be
encapsulated into a Template object.
`Template::$default_class` will be used in this case.
   
You can then override this setting when calling
`View::render()` by passing a `Template` object as the
first parameter.

If the parameter you pass `View::__construct()` is a
`Template` object then this will be used unless it is
overridden when calling `View::render()`.

As you can see in my example above if the request is AJAX
`Template_Json` is used. Otherwise the `Template_Mustache`
will be used due to `Template::$default_class` being set
in our bootstrap.

Have a look near the bottom of this README for my ideas on
how Template_JSON works.

#### Important

A path to a template must always be set. It is not assumed
from the `ViewModel` class name, nor set by any property
in that class. This could change if someone persuades me.

## Side effects to Kohana_View

Although Beautiful View does not extend upon Kohana_View it
does steal it's namespace of `View`. My reasons behind this
are:

 - I would be happy for Kohana to incorporate this into the
   core and therefore it is being developed as a
   replacement from the outset.
 - Want all my current PHP templates to benefit from
   improvements via `Template_PHP`.
 - `View` makes more sense than `Kostache`.

You can read more into side effects by reading
`Beautiful_View` and `Template_PHP`. However here are a few
listed for clarity:

 - `View::__construct()` and `View::factory()` can take
   a `Template` object as their first parameter as well as
   a string path.
 - `View::__construct()` and `View::factory()` can take
   a `ViewModel` object as their first parameter as well as
   an array of data.
 - `View::__construct()` and `View::factory()` will
   initialise a `ViewModel` instance if you pass an array
   as the second parameter to store the data in.
 - `View::__construct()` and `View::factory()` will
   initialise a `Template_PHP` instance if you pass an
   array as the second parameter.
 - `View::bind()`, `View::set()` and their magic
   counterparts will attach data to the `ViewModel`.
   Ideally you would set data directly to the `ViewModel`
   instance however for performance reasons.
 - `View::set_filename()` now calls `Template::set_filename()`.
 - `View::render()` now calls `Template::render()`.
 - `View::bind_global()` is dropped completely because I do
   not care for these or your application if you use them.
 - `View::render()` can now accept a `Template` object as
   well as a string path.
   
## My idea for `Template_JSON`

As modern web application developers we tend to use a lot
of JSON as a means for communicating via XHR. I really want
an easy way to either render a normal HTTP request using
Mustache or if the request is XHR then to send the only
the ViewModel data as JSON.

Now I could just call all public methods and place these
into a JSON object but that doesn't give you the control
that you might need to call only a certain amount of data.

This flexibility could be provided by some kind of JSON
template file. To this end I have come up with a simple
solution. You do actually write JSON templates. The top
level properties of the JSON object are used to look up
your `ViewModel`'s public methods and properties. The
values are used as defaults if the public method or
property does not exist.

Here is a JSON template as I imagine it:

```json
{
	"title" : "Unknown Title",
	"navigation" : [],
	"content" : ""
}
```

There you have it! Then your ViewModel would be used to
populate these values if set. I think it could be awesome!

## Author & Copyright

Luke Morton 2011

## License

MIT

## Contributing

Forking fork it and send me a pull request.