<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!--
            @Su.Py~#_
        -->
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>SuperPykkon | Minecraft Server Web Console</title>
        <link rel="stylesheet" media="screen, projection" href="css/core.css" />
        <link rel="stylesheet" media="screen, projection" href="css/font-awesome.css" />
        <link rel="stylesheet" media="screen, projection" href="css/notification.css" />
        <script src="js/jQuery.min.js"></script>
        <script language="javascript" src="js/jquery.timers-1.0.0.js"></script>
        <script language="javascript" src="js/notification.js"></script>

        <script type="text/javascript">
        $(document).ready(function() {
        	  var status = "";
        	  var lld = "";
        	  var togglecmdi = 1;

            notify("<i class='fa fa-info-circle'></i> <div class='notification-content'> <div class='notification-header'>Tip</div> Clicking on the server status box (top right corner) refreshes the status.</div>");
            notify("<i class='fa fa-info-circle'></i> <div class='notification-content'> <div class='notification-header'>Tip</div> The command box pops up right after you start typing the command.</div>");

        	  gh();

            function gh() {
                checklogs();
              	lld = "";
              	status = "gh";
              	$.post("reqman.php", {status: status, lld: lld}, function(clgh) {
              	    $("#console").html(clgh);
              			notify("<i class='fa fa-check-circle notification-success'></i> <div class='notification-content'> <div class='notification-header notification-success'>Success</div> Successfully grabbed the log data.</div>");
              		  sd();
              		  status = "lld";
              		  $.post("reqman.php", {status: status, lld: lld}, function(cllld) {
              			    lld = cllld;
              		  });
              		  status = "ilu";
              		  $.post("reqman.php", {status: status, lld: lld}, function(clilu) {
              			    $("#infologs").html(clilu);
              		  });
              	});
              	setInterval(update, 1000);
        	  }
        	  function checklogs() {
                logstatus = "check";
              	$.post("lpc.php", {logstatus: logstatus}, function(lsd) {
              		  if (lsd !== "-rw-rw-rw-") {
              			    notify("<i class='fa fa-times-circle notification-error'></i> <div class='notification-content'> <div class='notification-header notification-error'>Error</div> Error, No log data was received. Requesting permission change of 'latest.log' to '666'.</div>");
              			    logstatus = "update";
              			    $.post("lpc.php", {logstatus: logstatus}, function(lpcd) {
              			        if (lpcd == "-rw-rw-rw-") {
              				          notify("<i class='fa fa-check-circle notification-success'></i> <div class='notification-content'> <div class='notification-header notification-success'>Success</div> Successfully changed permission of 'latest.log' to '666'. Refreshing logs...</div>");
              					        gh();
              				      } else {
              					        notify("<i class='fa fa-times-circle notification-error'></i> <div class='notification-content'> <div class='notification-header notification-error'>Error</div> Error, Failed to change permission of 'latest.log' to '666'.</div>");
              				      }
              		      });
              	    }
              	});
        	  }

        	  function update() {
        	      status = "lld";
        	      $.post("reqman.php", {status: status, lld: lld}, function(lldrdata) {
                    console.log(lldrdata)
            			  if (lldrdata == "error.logPermissionInvalid") {
            				    checklogs();
            				    return false;
            			  }
            		    if (lldrdata !== lld) {
            			      status = "clu";
            			      $.post("reqman.php", {status: status, lld: lld}, function(clurdata) {
            				        $("#console").append(clurdata);
                            sd();
            					      status = "ilu";
            	 	            $.post("reqman.php", {status: status, lld: lld}, function(clilu) {
                             	    $("#infologs").html(clilu);
                            });
            			      });
            				    lld = lldrdata;
            		    }
        	      });
        	  }

            function sd() {
                $('html, body').animate({ scrollTop: $('#sd').offset().top }, 'slow');
            }

            $(document).on('keydown', function(e) {
            	  var key = e.keyCode || e.charCode;
                if(key == 13) {
                	  // ENTER
                		var cmd = $("#cmd-input").val();
                		cmdtype = $(".cmd-type").html();
                		cmdtype = cmdtype.replace("/", "");

                		cmd = cmdtype + cmd;
                    $.post("exec.php", {cmd: cmd}, function(cmdrd) {
                			  $(".cmd-input").addClass("hidden");
                			  $("#cmd-input").val("");
                			  $(".cmd-type").html("");
                			  togglecmdi = 1;
                			  notify(cmdrd);
                		});
                } else if(key == 27) {
                		// ESC
                		$(".cmd-input").addClass("hidden");
                	  $("#cmd-input").val("");
                		$(".cmd-type").html("");
                		togglecmdi = 1;
            	  } else if(key == 8) {
                		// BACKSPACE
                		if (document.getElementById('cmd-input').value.length == 0) {
                			   $(".cmd-input").addClass("hidden");
                			   $("#cmd-input").val("");
                			   $(".cmd-type").html("");
                		     togglecmdi = 1;
                		 }
            	  } else {
            	      $(".cmd-input").removeClass("hidden");
            		    $("#cmd-input").focus();
                	  if(key == 191) {
                  	    //  /<command>
                  			e.preventDefault();
                  		  if (togglecmdi == 1) {
                  		      $(".cmd-type").html("/");
                  				  togglecmdi = 0;
                  				  return false;
                  	    }
                  	} else if (togglecmdi == 1) {
                  	    //  /say <message>
                  		  $(".cmd-type").html("/say ");
                  	}
                  	togglecmdi = 0;
            	  }
            });

            $(".server-status").click(function() {
                ss();
            });
            ss();

            function ss() {
                $.post("ss.php", function(ssd) {
                    $(".server-status").html("Server is " + ssd).addClass("server-status-" + ssd);
            		    if (ssd == "online") {
            			      $(".server-status").removeClass("server-status-offline");
            		    } else {
            			      $(".server-status").removeClass("server-status-online");
            		    }
                });
            }

            function notify(content) {
                $.createNotification({
            	      content: content,
            	      duration: 10000
                });
            }
        });
        </script>
    </head>
    <body>

    <div class="update" id="console"></div>
    <div id="sd"></div>

    <div id="infologs"></div>
    <div class="cmd-input hidden">
        <div class="cmd-wrapper">
            <div class="cmd-type"></div>
            <input type="text" id="cmd-input" placeholder="Enter a command... Press enter to execute." />
    	</div>
    </div>

    <div class="server-status"></div>

    <div class="notification-board right top"></div>

    </body>
</html>
