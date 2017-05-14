<?php

//header('Content-Type: application/json');

//isset($_GET['token']) or die("Serve un token partita!");

function get_questions($token){

        $mysqli = new mysqli("localhost", "hack", "xampp", "hack");
        /* check connection */
        if ($mysqli->connect_errno) {
            printf("Connect failed: %s\n", $mysqli->connect_error);
            exit();
        }

        // prima parte - verifica che la partita esista

        $q = sprintf("SELECT COUNT(*) FROM `games` WHERE `token` = '%s'", $token);
        if ($result = $mysqli->query($q)) {
            /* fetch object array */
            $row = $result->fetch_row();
            if ($row[0] == 0)
               die(/*"Game not found"*/);
        } else
            printf("Errormessage: %s\n", $mysqli->error);

        // seconda parte - scarica le domande
        $json = array();
        $q = 'SELECT `id` FROM `questions` ORDER BY RAND() LIMIT 5';
        if ($result = $mysqli->query($q)) {
            /* fetch object array */

            while ($row = $result->fetch_assoc()) {
                //printf ("%s (%s)\n", $row[0], $row[1]);

                $a = explode('|', $row['a']);
                $acorrect = $a[0];
                shuffle($a);
                $correct = array_search($acorrect, $a);
                array_push($json, array(
                               'q' => $row['q'],
                               'a' => $a,
                               'correct' => $correct));


            }
            /* free result set */
            $result->close();
        }
        else {
            printf("Errormessage: %s\n", $mysqli->error);
        }

        /* close connection */
        $mysqli->close();
        return utf8ize($json);
}

function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = utf8ize($v);
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
}
