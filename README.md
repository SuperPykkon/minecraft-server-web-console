# minecraft-server-web-console
A web based console for your minecraft server. In-game color supported, execute commands, real-time output and more.

Hello everyone! today I share with you my biggest web based project.
This web based console is like no other, here is a list of awesome features:
- Real-time output.
- Fully in-game color supported! (like &1, &5, &b, etc)
- Firefox console like output.
- Highlight warnings, errors, player join/leave, commands executed, etc
- Execute commands! (Linux only)
- Easy to setup.

and more!

# Screenshots:
Don't believe some of what I said? Here are some screenshots.

http://i.imgur.com/orF1jm1.png

# How it works:
Basically, it uses the server logs (/logs/latest.log) to display the logs.
*Here's where a big twist comes in!*

Unlike many other consoles which sends a request to a php file every second and grabs the entire log data

from line 1 and dumps in all in to div#update

This console works very differently.

So firstly, it grabs the whole log data from line 1 and dumps it all in to div#update

Then, it *grabs the last line on the log file and saves it to variable 'lld'.*

Then, it *checks every second if the last line on variable 'lld' eaquls the last line on the log file.*

If lld eaquls the last line on the log file, there is nothing new.

if lld does not eaquls the last line on the log file, then there is something new on the log file!

It then loops through each and every single lines on the log file till lld eaquls the last line.

Then from the next loop on-wards it echos whatever is there till it reaches the end.

And grabs the last line again anddddd checks every second for another difference.

continue~


# Executing commands:
*NOTE: This feature is Linux distro only. (screen)*
By default, the input is hidden. All you have to do is start typing and the input will instanly pop up.
There are 2 ways of executing a command.
- Pressing "/" will open the input in Command Execution Mode (CEM)
  This is where you can type any commands and press enter to execute it.

- Pressing any other button will open the input in Say Someone Mode (SSM)
  This is where it will automatically add "/say" in the beginging.
  All you have to do is type what you want to say and press enter.

    The console uses a Linux only (I think) software called "screen" to execute commands.
    The server will be running on a screen session with the name "mcs" by default
    You should create the screen first by running the following command AS ROOT: 
    *screen -S mcs*

    Then press "/" (CEM) and type *start*.
    The console will change screen session directoy (cd) to where the server files are and run the file "./run.sh"
    You can then execute commands, the console uses a screen command:
    screen -S <screen name (mcs)> -p 0 -X stuff "<command (say hi!)>\n";
    It does this with *sudo*. This is the reason why you should create a screen as root.
    
    Because when I tried on php, it outputs: "Must be connected to a terminal."
    If anyone knows a fix, please let me know! ;D

    Not using root to create the screen session and execute commands on exec.php (remove sudo)
    will break it because PHP will run the screen commands as user S-www-data 
    and the screen session will be created as S-<your username>
    Because of this, executing commands will simply output: "No screen sessions found"

    
    This is how it executes commands:
    shell_exec('sudo screen -S minecraft -p 0 -X stuff "' .$cmd. '\n";');


# In-game colors

When you look at the log files, there is a big chance you might have seen something like this:

    [0;30;22m
    
    [0;34;1m
    
    [0;37;1m
    
Those werid characters represents colors in the text.
The console makes real good use of it with regular expression
by chaning it to Hex Color Code and putting it to a html element: "<span>"

The character "[m" represents the end of the color and that's what it
uses to close off the element "span" (</span>)


# Usage:

All you have to do is put all the files where ever you want on apache2 root directory
and do some setup on the config file (/config/config.php)
Setup the IP, Port, Server root directory etc.


*IMPORTANT: for command executor to work:*

    If you're running this on a Linux distro or VPS etc,

    for apache2 to run the screen commands with sudo you must do this:

    - Type visudo

    - Go to the bottom of the file and paste this: *www-data ALL=(ALL) NOPASSWD: ALL*

      This will allow apache2 (www-data) to use sudo without having to type the password and what not.
