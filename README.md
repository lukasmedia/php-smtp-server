# php-smtp-server

php-smtp-server is a PHP script written to act like any traditional postfix SMTP server. Used in conjunction with xinetd, the script is called when a mail client connects to port 25 on the server.

This script does NOT send any emails & it does not contain any authorization functionality, and thus acts solely as a badly configured open SMTP relay. 

## Installation

fakeSMTP relies on xinetd (was inetd). 
1. copy the script to a suitable path, and make sure your script is executable (chmod +x server.php); 
2. Install xinetd. For Ubuntu users: sudo apt-get install xinetd CentOS 7 users should have xinetd;
3. Once xinetd is installed, add the following lines to your /etc/xinetd.conf:

(*1 please change the "server" line to the correct path where you pulled your code)
(*2 check "user", prefilled 'www-data' if this user exists on your system. You could also use 'mail' or any other user)

```
service smtp
{
        socket_type = stream
        protocol = tcp
        wait = no
        user = www-data
        server = /var/www/fake-smtp/server.php
        server_args = -bs
}
```

4. Restart xinetd sudo: /etc/init.d/xinetd restart;
5. make sure the 'emails' folder and mail.log have sufficient rights. chown -R user:user emails and chown user:user mail.log where user:user is the user you used in the xined.conf (see *2);
6. Test your server with PHPMailer; from commandline go to your project directory and execute "php test.php". Fix errors if you see any, if not: check the "emails" folder and check the file(s) in there! 
	
## Change port

There is no port configuration in this script, but yes its possible:
1. login as root;
2. vi /etc/services and find the line with 'smtp'
3. add a line below, and if you want port 2525 add: smtp-alt        2525/tcp 
4. goto step 3 in the installation section and add the config again, but now with header 'service smtp-alt'
5. service restart xinetd

## Usage

```php
$hp->serverHello = 'Some Server identity (optional)';
```
