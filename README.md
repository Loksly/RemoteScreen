RemoteScreen
============

This is an easy example of the usage of Server-Sent Events available on HTML5. It is intended to serve as a screen for displaying data on a large TV in a waiting room.

You may use the same installation for several screens, each one with the same or different information.

Usage
=====

Deploy both files (screen.html and notify.php) on an web server with PHP support.

Let's say it's url is:

http://127.0.0.1/screen.html

another screen may use another channel:

http://127.0.0.1/screen.html?channel=waitroom


Open the url using your browser (only chromium has been tested) on the screen at the waiting room.
Now using another computer, whenever you want to send a message to that screen you can use the notify url:

http://127.0.0.1/notify.php?channel=waitroom


Then on the Message box type something like this:
<code>
&lt;span style='color:red'&gt;123456&lt;/span&gt;&lt;br /&gt;&lt;span style='font-size:smaller'&gt;BOX 5&lt;/span&gt;
</code>

on the audio box type:
beep.mp3

Then press the submit button and the message with be broadcasted to each screen that has that URL opened.

Changing the channel parameter you may use the same code for all your screens, each one with the same or different information.
