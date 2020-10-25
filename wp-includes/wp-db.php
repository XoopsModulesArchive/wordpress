<?php
//  WordPress DB Class

//  ORIGINAL CODE FROM:
//  Justin Vincent (justin@visunet.ie)
//	http://php.justinvincent.com

define('EZSQL_VERSION', 'WP1.25');
define('OBJECT', 'OBJECT', true);
define('ARRAY_A', 'ARRAY_A', false);
define('ARRAY_N', 'ARRAY_N', false);

if (!defined('SAVEQUERIES')) {
    define('SAVEQUERIES', false);
}

class wpdb
{
    public $show_errors = true;

    public $num_queries = 0;

    public $last_query;

    public $col_info;

    public $queries;

    // Our tables

    public $posts;

    public $users;

    public $categories;

    public $post2cat;

    public $comments;

    public $links;

    public $linkcategories;

    public $options;

    public $optiontypes;

    public $optionvalues;

    public $optiongroups;

    public $optiongroup_options;

    public $postmeta;

    // ==================================================================

    //	DB Constructor - connects to the server and selects a database

    public function __construct($dbuser, $dbpassword, $dbname, $dbhost)
    {
        $this->dbh = &$GLOBALS['xoopsDB']->conn;

        return;
        $this->dbh = @mysql_connect($dbhost, $dbuser, $dbpassword);

        if (!$this->dbh) {
            $this->bail(
                "
<h1>Error establishing a database connection</h1>
<p>This either means that the username and password information in your <code>wp-config.php</code> file is incorrect or we can't contact the database server at <code>$dbhost</code>. This could mean your host's database server is down.</p>
<ul>
	<li>Are you sure you have the correct username and password?</li>
	<li>Are you sure that you have typed the correct hostname?</li>
	<li>Are you sure that the database server is running?</li>
</ul>
<p>If you're unsure what these terms mean you should probably contact your host. If you still need help you can always visit the <a href='http://wordpress.org/support/'>WordPress Support Forums</a>.</p>
"
            );
        }

        $this->select($dbname);
    }

    // ==================================================================

    //	Select a DB (if another one needs to be selected)

    public function select($db)
    {
        if (!@mysqli_select_db($GLOBALS['xoopsDB']->conn, $db, $this->dbh)) {
            $this->bail(
                "
<h1>Can&#8217;t select database</h1>
<p>We were able to connect to the database server (which means your username and password is okay) but not able to select the <code>$db</code> database.</p>
<ul>
<li>Are you sure it exists?</li>
<li>On some systems the name of your database is prefixed with your username, so it would be like username_wordpress. Could that be the problem?</li>
</ul>
<p>If you don't know how to setup a database you should <strong>contact your host</strong>. If all else fails you may find help at the <a href='http://wordpress.org/support/'>WordPress Support Forums</a>.</p>"
            );
        }
    }

    // ====================================================================

    //	Format a string correctly for safe insert under all PHP conditions

    public function escape($string)
    {
        return addslashes($string); // Disable rest for now, causing problems
        if (!$this->dbh || '-1' == version_compare(phpversion(), '4.3.0')) {
            return $GLOBALS['xoopsDB']->escape($string);
        }
  

        return $GLOBALS['xoopsDB']->escape($string, $this->dbh);
    }

    // ==================================================================

    //	Print SQL/DB error.

    public function print_error($str = '')
    {
        global $EZSQL_ERROR;

        if (!$str) {
            $str = $GLOBALS['xoopsDB']->error();
        }

        $EZSQL_ERROR[] = ['query' => $this->last_query, 'error_str' => $str];

        $str = htmlspecialchars($str, ENT_QUOTES);

        $query = htmlspecialchars($this->last_query, ENT_QUOTES);

        // Is error output turned on or not..

        if ($this->show_errors) {
            // If there is an error then take note of it

            print "<div id='error'>
			<p class='wpdberror'><strong>WordPress database error:</strong> [$str]<br>
			<code>$query</code></p>
			</div>";
        } else {
            return false;
        }
    }

    // ==================================================================

    //	Turn error handling on or off..

    public function show_errors()
    {
        $this->show_errors = true;
    }

    public function hide_errors()
    {
        $this->show_errors = false;
    }

    // ==================================================================

    //	Kill cached query results

    public function flush()
    {
        $this->last_result = [];

        $this->col_info = null;

        $this->last_query = null;
    }

    // ==================================================================

    //	Basic Query	- see docs for more detail

    public function query($query)
    {
        // initialise return

        $return_val = 0;

        $this->flush();

        // Log how the function was called

        $this->func_call = "\$db->query(\"$query\")";

        // Keep track of the last query for debug..

        $this->last_query = $query;

        // Perform the query via std mysql_query function..

        if (SAVEQUERIES) {
            $this->timer_start();
        }

        $this->result = @$GLOBALS['xoopsDB']->queryF($query, $this->dbh);

        ++$this->num_queries;

        if (SAVEQUERIES) {
            $this->queries[] = [$query, $this->timer_stop()];
        }

        // If there is an error then take note of it..

        if ($GLOBALS['xoopsDB']->error()) {
            $this->print_error();

            return false;
        }

        if (preg_match('/^\\s*(insert|delete|update|replace) /i', $query)) {
            $this->rows_affected = $GLOBALS['xoopsDB']->getAffectedRows();

            // Take note of the insert_id

            if (preg_match('/^\\s*(insert|replace) /i', $query)) {
                $this->insert_id = $GLOBALS['xoopsDB']->getInsertId($this->dbh);
            }

            // Return number of rows affected

            $return_val = $this->rows_affected;
        } else {
            $i = 0;

            while ($i < @mysqli_num_fields($this->result)) {
                $this->col_info[$i] = @mysql_fetch_field($this->result);

                $i++;
            }

            $num_rows = 0;

            while (false !== ($row = @$GLOBALS['xoopsDB']->fetchObject($this->result))) {
                $this->last_result[$num_rows] = $row;

                $num_rows++;
            }

            @$GLOBALS['xoopsDB']->freeRecordSet($this->result);

            // Log number of rows the query returned

            $this->num_rows = $num_rows;

            // Return number of rows selected

            $return_val = $this->num_rows;
        }

        return $return_val;
    }

    // ==================================================================

    //	Get one variable from the DB - see docs for more detail

    public function get_var($query = null, $x = 0, $y = 0)
    {
        $this->func_call = "\$db->get_var(\"$query\",$x,$y)";

        if ($query) {
            $this->query($query);
        }

        // Extract var out of cached results based x,y vals

        if ($this->last_result[$y]) {
            $values = array_values(get_object_vars($this->last_result[$y]));
        }

        // If there is a value return it else return null

        return (isset($values[$x]) && '' !== $values[$x]) ? $values[$x] : null;
    }

    // ==================================================================

    //	Get one row from the DB - see docs for more detail

    public function get_row($query = null, $output = OBJECT, $y = 0)
    {
        $this->func_call = "\$db->get_row(\"$query\",$output,$y)";

        if ($query) {
            $this->query($query);
        }

        if (OBJECT == $output) {
            return $this->last_result[$y] ?: null;
        } elseif (ARRAY_A == $output) {
            return $this->last_result[$y] ? get_object_vars($this->last_result[$y]) : null;
        } elseif (ARRAY_N == $output) {
            return $this->last_result[$y] ? array_values(get_object_vars($this->last_result[$y])) : null;
        }  

        $this->print_error(' $db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N');
    }

    // ==================================================================

    //	Function to get 1 column from the cached result set based in X index

    // se docs for usage and info

    public function get_col($query = null, $x = 0)
    {
        if ($query) {
            $this->query($query);
        }

        // Extract the column values

        for ($i = 0, $iMax = count($this->last_result); $i < $iMax; $i++) {
            $new_array[$i] = $this->get_var(null, $x, $i);
        }

        return $new_array;
    }

    // ==================================================================

    // Return the the query as a result set - see docs for more details

    public function get_results($query = null, $output = OBJECT)
    {
        $this->func_call = "\$db->get_results(\"$query\", $output)";

        if ($query) {
            $this->query($query);
        }

        // Send back array of objects. Each row is an object

        if (OBJECT == $output) {
            return $this->last_result;
        } elseif (ARRAY_A == $output || ARRAY_N == $output) {
            if ($this->last_result) {
                $i = 0;

                foreach ($this->last_result as $row) {
                    $new_array[$i] = (array)$row;

                    if (ARRAY_N == $output) {
                        $new_array[$i] = array_values($new_array[$i]);
                    }

                    $i++;
                }

                return $new_array;
            }
  

            return null;
        }
    }

    // ==================================================================

    // Function to get column meta data info pertaining to the last query

    // see docs for more info and usage

    public function get_col_info($info_type = 'name', $col_offset = -1)
    {
        if ($this->col_info) {
            if (-1 == $col_offset) {
                $i = 0;

                foreach ($this->col_info as $col) {
                    $new_array[$i] = $col->{$info_type};

                    $i++;
                }

                return $new_array;
            }
  

            return $this->col_info[$col_offset]->{$info_type};
        }
    }

    public function timer_start()
    {
        $mtime = microtime();

        $mtime = explode(' ', $mtime);

        $this->time_start = $mtime[1] + $mtime[0];

        return true;
    }

    public function timer_stop($precision = 3)
    {
        $mtime = microtime();

        $mtime = explode(' ', $mtime);

        $time_end = $mtime[1] + $mtime[0];

        $time_total = $time_end - $this->time_start;

        return $time_total;
    }

    public function bail($message)
    { // Just wraps errors in a nice header and footer
        if (!$this->show_errors) {
            return false;
        }

        header('Content-Type: text/html; charset=utf-8');

        echo <<<HEAD
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>WordPress &rsaquo; Error</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style media="screen" type="text/css">
		<!--
		html {
			background: #eee;
		}
		body {
			background: #fff;
			color: #000;
			font-family: Georgia, "Times New Roman", Times, serif;
			margin-left: 25%;
			margin-right: 25%;
			padding: .2em 2em;
		}
		
		h1 {
			color: #006;
			font-size: 18px;
			font-weight: lighter;
		}
		
		h2 {
			font-size: 16px;
		}
		
		p, li, dt {
			line-height: 140%;
			padding-bottom: 2px;
		}
	
		ul, ol {
			padding: 5px 5px 5px 20px;
		}
		#logo {
			margin-bottom: 2em;
		}
		-->
		</style>
	</head>
	<body>
	<h1 id="logo"><img alt="WordPress" src="http://static.wordpress.org/logo.png"></h1>
HEAD;

        echo $message;

        echo '</body></html>';

        die();
    }
}

$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
