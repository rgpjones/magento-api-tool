<?php
class RemoteService
{
    protected $_service;

    protected $_session;

    protected $_conf = array();

    protected $_confFile;

    protected $_funcs;

    protected $_opts = array();

    protected $_args = array();

    public function __construct()
    {
        $this->parseOpts();

        echo "Using service configuration from '{$this->_confFile}'\n";

        $this->_conf = array_merge($this->_conf, parse_ini_file($this->_confFile));

        $this->_service = new SoapClient($this->_conf['addr'], array('connection_timeout' => 900));
        $this->_session = ($this->_conf['wsi_compliance'])
            ? $this->_service->login(array('username' => $this->_conf['user'], 'apiKey' => $this->_conf['pass']))
            : $this->_service->login($this->_conf['user'], $this->_conf['pass']);
    }

    public function __destruct()
    {
        `stty echo`;
    }

    public function __call($func, $args)
    {
        if ($this->_conf['wsi_compliance']) {
            $args[0]['sessionId'] = $this->_session->result;
        } else {
            $args = array_values($args[0]);
            array_unshift($args, $this->_session);
        }

        return call_user_func_array(array($this->_service, $func), $args);
    }

    public function opt($id)
    {
        if (isset($this->_opts[$id])) {
            if (is_string($this->_opts[$id])) {
                return $this->_opts[$id];
            } else {
                return true;
            }
        }
        return null;
    }

    public function arg($id)
    {
        if (isset($this->_args[$id])) {
            return $this->_args[$id];
        }
        return null;
    }

    public function usage()
    {
        $usage  = "Usage: php " . basename(__FILE__) . " -c <filename> [-l] [<command> [args]]\n";
        $usage .= <<<USAGE
        -c <filename> : Configuration file to use
        -l            : List available SOAP commands
USAGE;
        die($usage . "\n");
    }

    public function ask($question, $type)
    {
        if ($type == 'password') {
            `stty -echo`;
        }

        echo $question . ': ';
        $value = fgets(STDIN);

        if ($type == 'password') {
            `stty echo`;
            echo "\n";
        }

        return trim($value);
    }

    public function getFunctions()
    {
        if (empty($this->_funcs)) {
            $functions = $this->_service->__getFunctions();
            if (is_null($functions)) {
                throw new RunTimeException('Failed to retrieve function list from Api');
            }
            foreach ($functions as $func) {
                preg_match('/^(?P<return>.*) (?P<function>.*)\((?P<args>.*)\)$/Si', $func, $match);
                $this->_funcs[] = array(
                    'function' => $match['function'],
                    'args' => explode(', ', $match['args']),
                    'return' => $match['return']
                );
            }
        }
        return $this->_funcs;
    }

    protected function parseOpts()
    {
        global $argv;

        $this->_opts = getopt('c:l');

        foreach ($argv as $i => $arg) {
            if ($i != 0) {
                if ((substr($arg, 0, 1) == '-' && array_key_exists(substr($arg, 1, 1), $this->_opts))
                        || in_array($arg, $this->_opts)) {
                    unset($argv[$i]);
                }
            }
        }
        $this->_args = array_values($argv);

        $conf = isset($this->_opts['c'])
            ? $this->_opts['c']
            : $this->getConfFromFile();

        if (is_null($conf)) {
            throw new RunTimeException('Configuration file not found');
        }
        $this->_confFile = $conf;
    }

    protected function getConfFromFile()
    {
        $confFile = null;

        $this->_conf = parse_ini_file(__DIR__ . '/.apid');
        if (isset($this->_conf['conf'])) {
            $confFile = realpath(__DIR__ . DIRECTORY_SEPARATOR . $this->_conf['conf']);
        }

        return $confFile;
    }
}
