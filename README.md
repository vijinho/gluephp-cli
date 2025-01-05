# GluePHP

Glue is a tiny PHP micro-framework. It provides one simple service: to maps URLs to Classes. Everything else is up to you. The database, ORM, template engine and all other components are under your control. Glue just glues everything together.

*Note:* The original project was archived and is no longer in development, and the webpage can only be viewed in The Wayback Machine now: (https://web.archive.org/web/20120207223717/http://gluephp.com/)[gluephp.com/] so this is my fork of Glue.

In MVC terms, Glue is the URL Routing and Controller portion while you have total control over your choice of a Model and View layer.

## Philosophy

Glue is based on the following principles:

-   Do one job and do it well
-   Easily work with others
-   Enforce as little conformity as possible

## History

Glue was created by [Joe Topjian](https://web.archive.org/web/20120207223717/http://terrarum.net/) and was inspired by [web.py](https://web.archive.org/web/20120207223717/http://webpy.org/)

## License

Glue is BSD licensed.

## Documentation

### Installation

```
$ git clone https://github.com/vijinho/gluephp-cli
```

#### Apache Configuration

Glue requires `mod_rewrite` for pretty-URLs. The following standard `.htaccess` file works well:

```
RewriteEngine On 
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php
```

Also make sure your site configuration has `AllowOverride All` set.

### Hello, World!

The following example illustrates a simple “Hello, World!”. Copy and paste the below code into \`index.php\` and then access it. Ensure you have `glue.php` in the same directory.

(examples/hello-world/index.php)[examples/hello-world/index.php):

```php
<?php
    require_once('glue.php');
    $urls = array(
        '/' => 'index'
    );
    class index {
        function GET() {
            echo "Hello, World!";
        }
    }
    glue::stick($urls);
?>
```

Test on php built-in server: start it with `php -S 0.0.0.0:12312` in the examples/hello-world/ folder, the browse to: http://0.0.0.0:12312/

### URLs

#### URL Basics

The main component of Glue is the `$urls` array. This is an associative array where the key is the _URL you want to match_ and the value is the _Class to run when matched_.

The following are all “static” URLs that are being mapped to specific PHP classes:

```php
<?php
    $urls = array(
        '/' => 'index',
        '/contact.html' => 'contact',
        '/about.html' => 'about'
    );
?>
```

#### Regular Expressions in URLs

You can also use Regular Expressions in your URL keys:

```php
<?php
    $urls = array(
        '/' => 'index',
        '/article/[a-zA-Z0-9]+.html' => 'article'
    );
?>
```

The above example would match:

-   `http://example.com`
-   `http://example.com/article/HelloWorld.html`
-   `http://example.com/article/abcdefg.html`

#### Capturing Data in URLs

You can also capture parts of the URLs and pass them on to the class methods:

```php
<?php

    require_once('glue.php');

    $urls = array(
        '/' => 'index',
        '/(\d+)' => 'index'
    );

    class index {
        function GET($matches) {
            if ($matches[1]) {
                echo "The magic number is: " . $matches[1];
            } else {
                echo "You did not enter a number.";
            }
        }
    }

    glue::stick($urls);

?>
```

When you visit `http://example.com` you will see “You did not enter a number.”.

However, visiting `http://example.com/500` will output “The magic number is 500”.

You are not restricted to using the variable name `$matches`. This can be any name you want. It will always contain an array of matched regular expressions from `$urls`.

#### Using Named Regular Expressions

Named Regular Expressions are a rather unknown regular expression feature. They allow you to “tag” or name a regular expression for later reference. By using them in Glue, you’re able to have an associative `$matches` array instead of a simple index-based array.

```php
    <?php
    
        require_once('glue.php');
    
        $urls = array(
            '/' => 'index',
            '/(?P<number>\d+)' => 'index'
        );
    
        class index {
            function GET($matches) {
                if (array_key_exists('number', $matches)) {
                    echo "The magic number is: " . $matches['number'];
                } else {
                    echo "You did not enter a number.";
                }
            }
        }
    
        glue::stick($urls);
    
    ?>
```

### Class Methods

The second most important parts of Glue are the methods contained in each class. Each method corresponds to the _type of HTTP Method requested_. The majority of the time, these will be `GET` methods.

This example shows how to use `GET` and `POST` to process a form:

```php
<?php
    require_once('glue.php');

    $urls = array(
        '/' => 'index'
    );

    class index {
        function GET() {
            echo '<form name="form1" method="POST" action="' . $_SERVER['PHP_SELF'] . '">';
            echo '<input type="text" name="textbox1">';
            echo '<input type="submit" name="submit">';
            echo '</form>';
        }
 
        function POST() {
            echo 'The value you entered was ' . $_POST['textbox1'];
        }
    }
?>
```

### The Static Method

The final component of Glue is the `glue::stick()` Static Method. It takes one argument: the `$urls` array.

```php
<?php
    glue::stick($urls);
?>
```

Changes from the original GluePHP to specify request path & method to run from the command-line:

I added the option to specify the request path and method on the command line so that it can for example be run from the command line.  I also added a new method 'CLI' for when run on the command line.

```php
<?php
    static function stick($urls, $path = null, $method = null) {

        // check that method is valid
        $method = strtoupper($method);
        if (empty($method) && array_key_exists('REQUEST_METHOD', $_SERVER)) {
            $method = $_SERVER['REQUEST_METHOD'];
        } else {
            switch ($method) {
                case 'POST':
                case 'PUT':
                case 'DELETE':
                case 'CLI':
                    break;
                default:
                case 'GET':
                    $method = 'GET';
                    break;
            }
        }

        if (empty($path) && array_key_exists('REQUEST_URI', $_SERVER)) {
            $path = $_SERVER['REQUEST_URI'];
        }
?>
```

