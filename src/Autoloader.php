<?php
namespace QiblaEvents;

/**
 * Auto Loader
 *
 * @link    http://www.php-fig.org/psr/psr-4/
 * @link    https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
 *
 * @since   1.0.0
 * @package QiblaImporter
 *
 * Given a foo-bar package of classes in the file system at the following
 * paths ...
 *
 *     /path/to/packages/foo-bar/
 *         src/
 *             Baz.php             # Foo\Bar\Baz
 *             Qux/
 *                 Quux.php        # Foo\Bar\Qux\Quux
 *         tests/
 *             BazTest.php         # Foo\Bar\BazTest
 *             Qux/
 *                 QuuxTest.php    # Foo\Bar\Qux\QuuxTest
 *
 * ... add the path to the class files for the \Foo\Bar\ namespace prefix
 * as follows:
 *
 *      <?php
 *      // instantiate the loader
 *      $loader = new \Example\Psr4AutoloaderClass;
 *
 *      // register the autoloader
 *      $loader->register();
 *
 *      // register the base directories for the namespace prefix
 *      $loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/src');
 *      $loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/tests');
 *
 * The following line would cause the autoloader to attempt to load the
 * \Foo\Bar\Qux\Quux class from /path/to/packages/foo-bar/src/Qux/Quux.php:
 *
 *      <?php
 *      new \Foo\Bar\Qux\Quux;
 *
 * The following line would cause the autoloader to attempt to load the
 * \Foo\Bar\Qux\QuuxTest class from /path/to/packages/foo-bar/tests/Qux/QuuxTest.php:
 *
 *      <?php
 *      new \Foo\Bar\Qux\QuuxTest;
 */
class Autoloader
{
    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = array();

    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @since  1.0.0
     *
     * @param string $prefix        The namespace prefix.
     * @param string $relativeClass The relative class name.
     *
     * @return mixed Boolean false if no mapped file can be loaded, or the
     * name of the mapped file that was loaded.
     */
    protected function loadMappedFile($prefix, $relativeClass)
    {
        // Are there any base directories for this namespace prefix?
        if (false === isset($this->prefixes[$prefix])) {
            return false;
        }

        // Set the base dir path.
        $base_dir = $this->prefixes[$prefix];
        // Remove the prefix from the relative class name.
        $relativeClass = str_replace($prefix, '', $relativeClass);
        // Build the file path.
        $file = $base_dir . str_replace('\\', '/', $relativeClass) . '.php';

        // Get the absoluted path of the file class.
        $file = Plugin::getPluginDirPath($file);

        // If the mapped file exists, require it.
        if ($this->requireFile($file)) {
            // Yes, we're done.
            return $file;
        }

        // Never found it.
        return false;
    }

    /**
     * If a file exists, require it from the file system.
     *
     * @since  1.0.0
     *
     * @param string $file The file to require.
     *
     * @return bool True if the file exists, false if not.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;

            return true;
        }

        return false;
    }

    /**
     * Register loader with SPL auto-loader stack.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Add NameSpaces
     *
     * Add multiple namespaces at once
     *
     * @since  1.0.0
     *
     * @param array $array The list of the namespaces to add.
     *
     * @return void
     */
    public function addNamespaces(array $array)
    {
        foreach ($array as $ns => $path) {
            $this->addNamespace($ns, $path);
        }
    }

    /**
     * Adds a base directory for a namespace prefix.
     *
     * @since  1.0.0
     *
     * @param string $prefix   The namespace prefix.
     * @param string $baseDir  A base directory for class files in the
     *                         namespace.
     *
     * @return void
     */
    public function addNamespace($prefix, $baseDir)
    {
        // Normalize namespace prefix.
        $prefix = trim($prefix, '\\') . '\\';

        // Normalize the base directory with a trailing separator.
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';

        // Initialize the namespace prefix array.
        if (false === isset($this->prefixes[$prefix])) {
            $this->prefixes[$prefix] = array();
        }

        $this->prefixes[$prefix] = $baseDir;
    }

    /**
     * Loads the class file for a given class name.
     *
     * @since  1.0.0
     *
     * @param string $class The fully-qualified class name.
     *
     * @return mixed The mapped file name on success, or boolean false on
     * failure.
     */
    public function loadClass($class)
    {
        $mapped = false;

        foreach ($this->prefixes as $prefix => $path) {
            if (false !== strpos($class, $prefix)) {
                $mapped = $this->loadMappedFile($prefix, $class);
                break;
            }
        }

        return $mapped;
    }
}
