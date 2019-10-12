# phramework

A minimal PHP framework with main focus on separation of concerns by use of MVC model. 

Highlights :

- Router with support for regex routes
- Security with protection from XSS and CSRF attacks
- Basic ORM
- Collection class as wrapper for working with arrays of data.

## Usage

All your model, controller and view files go into `app` directory. 


### Router

Routes are located `routes/web.php` file. 

Routes are defined in an multidimensional array with keys as the uri and value as an array that has request types as it's keys and values as method to be called in specific Controller class for that part particular request type. 

```PHP
	"/about" => [
			"get" => "method@Controller",
			"post" => "method2@Controller"
];

```

Regex in routes are defined within {}.

`blogs/blog/id={\d+}`

Routes can be defined generally for all request types at once by defining value directly as method and Controller class name. 

```PHP
"/about" => "method@Controller";
```

### ORM

ORM is done by using Model classes where class has a `protected` variable `$table_name` which is a string containing name of the table where database operations are to be executed.


### Security

Basic protection is provided against CSRF and XSS attacks. 
Every session has its own csrf token. GET request don't need token verification but every post request must have the csrf_token in the request data that's submitted.

### Helpers

`view($view_name, $data)` : Used to call a view with, data to be passed. 
`$data` contains the variables and their values to be passed into the view. An associative array with key as variable name and value as variable value is used. 
