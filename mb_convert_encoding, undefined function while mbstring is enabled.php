Ask Question


27


2
I have a server (Ubuntu 11.10 x64) running PHP 5.3.8 with Apache2 / MySQL. I'm currently working on a project where I'm required to do some specific character encoding, but I found out that none of the multibyte (mb_* functions) are working.

However, when I look in phpinfo(), I see that multibyte support is enabled.

I've tried things like apt-get install php5-mbstring, php-mbstring, php-multibyte, etc. etc. but none seem to work.

Can anyone point me in the right direction for this? Thanks in advance!

edit: Fixed it by recompiling PHP (this was my last resort, which I initially wanted to avoid)
./configure --enable-mbstring

The weird this is, phpinfo() already showed that it was enabled. I don't know why it didn't work before :/

php multibyte-functions
shareimprove this question
edited Nov 25 '11 at 9:50
asked Nov 25 '11 at 8:29

Harold
83911122
1
what is the error message when you try any of the mb_* functions? 每 Emir Akayd?n Nov 25 '11 at 8:33
1
Web server configuration vs. CLI configuration? Where exactly do you see what? 每 deceze? Nov 25 '11 at 8:44
1
Do you have libmbfl installed? If so - it should be shown in phpinfo() output as "Multibyte string engine" under mbstring. 每 Narf Nov 25 '11 at 8:52
Yeah I have that installed. However, I just noticed that the "Zend Multibyte Support" is disabled. Is there a way to enable this without having to recompile PHP? 每 Harold Nov 25 '11 at 8:58
I don't think it has something to do with the mbstring extension. 每 Narf Nov 25 '11 at 9:02
show 3 more comments
3 Answers
active oldest votes

43

A lot of newer Linux servers do not have PHP Multibyte modules installed by default. A simple solution is often to install php-mbstring.

On Red Hat flavors (CentOS, Fedora, etc.) you can use yum install php-mbstring.

Make sure you restart your Apache server afterwards. Use service httpd restart on RH flavors.

shareimprove this answer
answered Nov 1 '13 at 13:46

Lance Cleveland
2,60212533
13
sudo apt-get install php7.0-mbstring and then sudo service php7.0-fpm restart did the trick for me. Thanks! 每 neilsimp1 Sep 2 '16 at 18:37
I had additionally edit the /etc/php/7.0/mods_available/mbstring.ini and remove the ; for the extension line. Suddently it started working for me :) 每 Maarten Kieft Oct 30 '16 at 12:40
add a comment

 
1

In the case of your installation is php5.6 is similar to solution of neilsimp1:

Running sudo apt-get install php7.0-mbstring and then sudo service php7.0-fpm restart did the trick for me.

sudo apt-get install php5.6-mbstring
and then restart apache service

sudo service apache2 restart.
shareimprove this answer
edited Aug 27 '18 at 20:40

Daniel
6,23082251
answered Aug 27 '18 at 20:17

Ken Thompson
295
add a comment

0

Sometimes people receiving this kind of error : Fatal error: Call to undefined function mb_convert_encoding() in /public_html/this/this.php at line 188. Normally this kind of errors comes in PHP Sites and PHP framework aswell.

It looks like PHP mbstring not installed on your server.

Solution :

In my case I have just uncomment ;extension=php_mbstring.dll in php.ini file and issue has been resolved.

Don't forget to restart apache server after uncomment ;extension=php_mbstring.dll

Code taken from this blog: http://chandreshrana.blogspot.in/2016/01/call-to-undefined-function.html

shareimprove this answer
answered Jul 2 '16 at 6:06

Chandresh
31127
add a comment