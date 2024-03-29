<?php
/**
 * Basic couch DB connection handling class.
 *
 * This class uses a custom HTTP client, which may have more bugs then the
 * default PHP HTTP clients, but supports keep alive connections without any
 * extension dependecies.
 *
 * @package Core
 * @version $Revision: 97 $
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */
class sfCouchConnection
{
  /**
   * Connection pointer for connections, once keep alive is working on the
   * CouchDb side.
   *
   * @var resource
   */
  protected $connection;

  /**
   * CouchDB connection options
   *
   * @var array
   */
  protected $options = array(
    'timeout'    => .1,
    'keep-alive' => true,
    'http-log'   => false,
  );

  /**
   * Instance of sfCouchConnection for singleton implementation.
   *
   * @var sfCouchConnection
   */
  protected static $instance = null;

  /**
   * Array containing the list of allowed HTTP methods to interact with couch
   * server.
   *
   * @var array
   */
  protected static $allowedMethods = array(
    'DELETE' => true,
    'GET'    => true,
    'POST'   => true,
    'PUT'    => true,
  );

  /**
   * Construct a couch DB connection
   *
   * Construct a couch DB connection from basic connection parameters for one
   * given database. Method is protected and should not be called directly.
   * For initializing a connection use the static method createInstance().
   *
   * @param string $host
   * @param int $port
   * @return sfCouchConnection
   */
  protected function __construct ()
  {
    $this->options['host'] = sfConfig::get('app_couchdb_host', 'localhost');
    $this->options['port'] = (int) sfConfig::get('app_couchdb_port', 5984);
    $this->options['database'] = '/' . sfConfig::get('app_couchdb_database', 'couchdb') . '/';
    $this->options['ip'] = gethostbyname($this->options['host']);
  }

  /**
   * Set option value
   *
   * Set the value for an connection option. Throws an
   * sfCouchOptionException for unknown options.
   *
   * @param string $option
   * @param mixed $value
   * @return void
   */
  public function setOption ($option, $value)
  {
    switch ($option) {
    case 'keep-alive':
      $this->options[$option] = (bool) $value;
      break;

    case 'http-log':
      $this->options[$option] = $value;
      break;

    default:
      throw new exception('sfCouch: ' . $options);
    }
  }

  /**
   * Get configured couch DB connection instance
   *
   * Get configured couch DB connection instance
   *
   * @return sfCouchConnection
   */
  public static function getInstance ()
  {
    // Check if connection has been properly confugured, and bail out
    // otherwise.
    if (self::$instance === null) {
      self::$instance = new sfCouchConnection();
    }

    // If a connection has been configured properly, jsut return it
    return self::$instance;
  }

  /**
   * HTTP method request wrapper
   *
   * Wraps the HTTP method requests to interact with teh couch server. The
   * supported methods are:
   *  - GET
   *  - DELETE
   *  - POST
   *  - PUT
   *
   * Each request takes the request path as the first parameter and
   * optionally data as the second parameter. The requests will return a
   * object wrapping the server response.
   *
   * @param string $method
   * @param array $params
   * @return sfCouch...
   */
  public function __call ($method, $params)
  {
    // Check if request method is an allowed HTTP request method.
    $method = strtoupper($method);
    if (!isset(self::$allowedMethods[$method])) {
      throw new exception('sfCouch: Unsupported request method: ' . $method);
    }

    // Check if required parameter containing the path is set and valid.
    if ($params[0][0] == '{') {
      $path = $this->options['database'];
      $data = ((isset($params[0])) ? (string) $params[0] : null);
    }
    else {
      $path = $this->options['database'] . $params[0];
      $data = ((isset($params[1])) ? (string) $params[1] : null);
    }

    // Finally perform request and return the result from the server

    return $this->request($method, $path, $data);
  }

  /**
   * Check for server connection
   *
   * Checks if the connection already has been established, or tries to
   * establish the connection, if not done yet.
   *
   * @return void
   */
  protected function checkConnection ()
  {
    // If the connection could not be established, fsockopen sadly does not
    // only return false (as documented), but also always issues a warning.
    if (($this->connection === null) && (($this->connection = fsockopen($this->options['ip'], $this->options['port'], $errno, $errstr)) === false)) {
      // This is a bit hackisch...
      throw new exception("sfCouch: Could not connect to couchdb server");
    }
  }

  /**
   * Build a HTTP 1.1 request
   *
   * Build the HTTP 1.1 request headers from the gicven input.
   *
   * @param string $method
   * @param string $path
   * @param string $data
   * @return string
   */
  protected function buildRequest ($method, $path, $data)
  {
    // Create basic request headers
    $request = "$method $path HTTP/1.1\r\nHost: {$this->options['host']}\r\n";

    // Set keep-alive header, which helps to keep to connection
    // initilization costs low, especially when the database server is not
    // available in the locale net.
    $request .= "Connection: " . ($this->options['keep-alive'] ? 'Keep-Alive' : 'Close') . "\r\n";

    // Also add headers and request body if data should be sent to the
    // server. Otherwise just add the closing mark for the header section
    // of the request.
    if ($data !== null) {
      $request .= "Content-type: application/json\r\n";
      $request .= "Content-Length: " . strlen($data) . "\r\n\r\n";
      $request .= "$data\r\n";
    }
    else {
      $request .= "\r\n";
    }

    return $request;
  }

  /**
   * Perform a request to the server and return the result
   *
   * Perform a request to the server and return the result converted into a
   * sfCouchResponse object. If you do not expect a JSON structure, which
   * could be converted in such a response object, set the forth parameter to
   * true, and you get a response object retuerned, containing the raw body.
   *
   * @param string $method
   * @param string $path
   * @param string $data
   * @param bool $raw
   * @return sfCouchResponse
   */
  protected function request ($method, $path, $data)
  {
    // Try establishing the connection to the server
    $this->checkConnection();

    // Send the build request to the server
    if (fwrite($this->connection, $request = $this->buildRequest($method, $path, $data)) === false) {
      // Reestablish which seems to have been aborted
      //
      // The recursion in this method might be problematic if the
      // connection establishing mechanism does not correctly throw an
      // exception on failure.




      $this->connection = null;
      return $this->request($method, $path, $data);
    }

    // If requested log request information to http log
    if ($this->options['http-log'] !== false) {
      $fp = fopen($this->options['http-log'], 'a');
      fwrite($fp, "\n\n" . $request);
    }

    // Read server response headers
    $rawHeaders = '';
    $headers = array(
      'connection' => ($this->options['keep-alive'] ? 'Keep-Alive' : 'Close'),
    );

    // Remove leading newlines, should not accur at all, actually.
    while ((($line = fgets($this->connection )) !== false) && (($lineContent = rtrim($line)) === ''))
      ;

    // Thow exception, if connection has been aborted by the server, and
    // leave handling to the user for now.
    if ($line === false) {
      throw new exception('sfCouch: Connection abborted unexpectedly (nonexisting Database?)');
    }

    do {
      // Also store raw headers for later logging
      $rawHeaders .= $lineContent . "\n";

      // Extract header values
      if (preg_match('(^HTTP/(?P<version>\d+\.\d+)\s+(?P<status>\d+))S', $lineContent, $match)) {
        $headers['version'] = $match['version'];
        $headers['status'] = (int) $match['status'];
      }
      else {
        list($key, $value) = explode(':', $lineContent, 2);
        $headers[strtolower($key)] = ltrim($value);
      }
    } while ((($line = fgets($this->connection )) !== false) && (($lineContent = rtrim($line)) !== ''));

    // Read response body
    $body = '';
    if (!isset($headers['transfer-encoding']) || ($headers['transfer-encoding'] !== 'chunked')) {
      // HTTP 1.1 supports chunked transfer encoding, if the according
      // header is not set, just read the specified amount of bytes.
      $bytesToRead = (int) (isset($headers['content-length']) ? $headers['content-length'] : 0);

      // Read body only as specified by chunk sizes, everything else
      // are just footnotes, which are not relevant for us.
      while ($bytesToRead > 0) {
        $body .= $read = fgets($this->connection, $bytesToRead + 1);
        $bytesToRead -= strlen($read);
      }
    }
    else {
      // When transfer-encoding=chunked has been specified in the
      // response headers, read all chunks and sum them up to the body,
      // until the server has finished. Ignore all additional HTTP
      // options after that.
      do {
        $line = rtrim(fgets($this->connection ));

        // Get bytes to read, with option appending comment
        if (preg_match('(^([0-9a-f]+)(?:;.*)?$)', $line, $match)) {
          $bytesToRead = hexdec($match[1]);

          // Read body only as specified by chunk sizes, everything else
          // are just footnotes, which are not relevant for us.
          $bytesLeft = $bytesToRead;
          while ($bytesLeft > 0) {
            $body .= $read = fread($this->connection, $bytesLeft + 2);
            $bytesLeft -= strlen($read);
          }
        }
      } while ($bytesToRead > 0);

      // Chop off \r\n from the end.
      $body = substr($body, 0, -2);
    }

    // Reset the connection if the server asks for it.
    if ($headers['connection'] !== 'Keep-Alive') {
      fclose($this->connection );
      $this->connection = null;
    }

    // If requested log response information to http log
    if ($this->options['http-log'] !== false) {
      fwrite($fp, "\n" . $rawHeaders . "\n" . $body . "\n");
      fclose($fp);
    }

    // Handle some response state as special cases
    switch ($headers['status']) {
    case 301:
    case 302:
    case 303:
    case 307:
      $path = parse_url($headers['location'], PHP_URL_PATH);
      return $this->request($method, $path, $data);
      break;
    case 404:
      return null;
      break;
    }

    if ((substr($headers['content-type'], 0, 5) != 'text/') && ($headers['content-type'] != 'application/json')) {
      return ($body);
    }

    // Create repsonse object from couch db response
    return sfCouchResponse::parse($headers, $body);
  }
}
