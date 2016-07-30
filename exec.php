<?php

if(isset($_POST['cmd'])) {
    $cmd = $_POST['cmd'];
    if ($cmd) {
		    include("config/config.php");
		    include("class/eventLogger.class.php");
        $elog = new eventLogger();

        $ss = shell_exec("netstat -tulpn | grep :25565");
        if ($cmd == "start") {
			      $elog->elog("Recieved start command.");
            if (!$ss) {
		            file_put_contents(SERVER_LOG_DIR, "[" . date("h:i:s") . "] [SYSTM]: Recieved start command, starting server... \n", FILE_APPEND);
                # shell_exec("sudo screen -S mcs");
                #
                # Not working
                $cmd = "cd ". $_SERVER['DOCUMENT_ROOT'] . "/" . SERVER_ROOT_DIR.";./run.sh";
                $output = shell_exec('sudo screen -S mcs -p 0 -X stuff "' .$cmd. '\n";');
                if($output) {
                    echo "<i class='fa fa-times-circle notification-error'></i> <div class='notification-content'> <div class='notification-header notification-error'>Error</div> Error, $output</div>";
                    $elog->elog("  └─ $output");
                } else {
                    echo "<i class='fa fa-check-circle notification-success'></i> <div class='notification-content'> <div class='notification-header notification-success'>Success</div> Success, started the server with no problem.</div>";
                    $elog->elog("  └─ Started the server successfully.");
                }

            } else {
                echo "<i class='fa fa-times-circle notification-error'></i> <div class='notification-content'> <div class='notification-header notification-error'>Error</div> Error, server is already running.</div>";
                $elog->elog("  └─ Error: Server is already running.");
            }
        } else if ($cmd == "stop") {
	          $elog->elog("Recieved stop command.");
            if ($ss) {
                file_put_contents(SERVER_LOG_DIR, "[" . date("h:i:s") . "] [SYSTM]: Recieved stop command, stopping server... \n", FILE_APPEND);
                $output = shell_exec('sudo screen -S mcs -p 0 -X stuff "' .$cmd. '\n";');
                if($output) {
                    echo "<i class='fa fa-times-circle notification-error'></i> <div class='notification-content'> <div class='notification-header notification-error'>Error</div> Error, $output</div>";
                    $elog->elog("  └─ $output");
                } else {
                    echo "<i class='fa fa-check-circle notification-success'></i> <div class='notification-content'> <div class='notification-header notification-success'>Success</div> Success, stopped the server with no problem.</div>";
                    $elog->elog("  └─ Stopped the server successfully.");
                }

            } else {
                echo "<i class='fa fa-times-circle notification-error'></i> <div class='notification-content'> <div class='notification-header notification-error'>Error</div> Error, server is not running.</div>";
                $elog->elog("  └─ Error: Server is not running..");
            }
        } else {
			      $elog->elog("Issued a server command: $cmd");
            if ($ss) {

                $output = shell_exec('sudo screen -S mcs -p 0 -X stuff "' .$cmd. '\n";');
                if($output) {
                    echo "<i class='fa fa-times-circle notification-error'></i> <div class='notification-content'> <div class='notification-header notification-error'>Error</div> Error, $output</div>";
                    $elog->elog("  └─ $output");
                } else {
                    echo "<i class='fa fa-check-circle notification-success'></i> <div class='notification-content'> <div class='notification-header notification-success'>Success</div> Success, Executed: " . $cmd . "</div>";
                    $elog->elog("  └─ Successfully executed the command.");
                }

            } else {
                echo "<i class='fa fa-times-circle notification-error'></i> <div class='notification-content'> <div class='notification-header notification-error'>Error</div> Error, server is not running. Execute 'start' to run it.</div>";
                $elog->elog("  └─ Error: Server is not running.");
            }
        }
	} else {
	    echo "<i class='fa fa-times-circle notification-error'></i> <div class='notification-content'> <div class='notification-header notification-error'>Error</div> Error, command cant be left blank.</div>";
	}
}

?>
