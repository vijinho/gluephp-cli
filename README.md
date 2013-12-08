# Glue

This is my fork of Glue.  I added the option to specify the request path and method
on the command line so that it can for example be run from the command line.  I also
added a new method 'CLI' for when run on the command line.

```
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
```

# Original README
Author: Joe Topjian, joe@topjian.net

Glue is a simple PHP class that maps URLs to classes. The concepts are similar to web.py for Python.

Information on how to use Glue can be found at http://gluephp.com.

License
=======
Glue is licensed under a BSD license. See LICENSE file for further details.

Pull Requests
=============
Since creating and publishing GluePHP, I have received a lot of patches and pull requests. Each
modification is vastly different than the other.

GluePHP is a __very__ simple PHP script and there are an almost infinite amount of modifications
and alternative styles that can be applied to it. Because of this, I do not accept patches or
pull requests. All patches that I have received have had very good ideas, so I do not think it
would be fair to accept some patches and not others (since most are incompatible with each other).

GluePHP is BSD licensed. By all means, fork the code, hack it up as much as you want, and
republish it. :)
