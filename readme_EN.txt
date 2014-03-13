BTC-price.org - a universal service that was created to ensure that would help indicate Bitcoin price in almost any site online . The script , which you can download here , and install on your site automatically recalculates the euro or dollar Fed in Bitcoin with a predetermined interval you specify on the course leading exchanges to choose from.

Download the archive script (there will be some files: 3 php-file to parse the exchange rate from the exchanges (BTC-E, Bitstamp, Huobi), 
js script, CSS file, and this manual).

Select the source of exchange (ie - exchange) and download the appropriate file parser quotations in the root folder of your site. 
For Exchange BTC-E.com it will vp-btce.php, for Bitstamp.com - vp-bitstamp.php, vp-huobi.php for Huobi.com.

Configure script of replacement - file scrypt.js (you help - comments in the code - they are after the double slash (/ / ) in each desired row ).
Line 19 - list the source of your site currency (ie the currency of which it is necessary to recalculate in bitcoin).
Line 20 - Set Exchange (or rather - a link to the file Purser Exchange. For reliability, you can specify an absolute path, for example : var url_parser = "http://yoursite.org/vp-btce.php", instead : var url_parser = "vp -btce.php ".
Note that :
- Fed up of the dollar in bitcoin prices can be translated at BTC-E or Bitstamp.
- Euro is considered on the basis of the internal rate Fed Dollar / euro at the exchange Bitstamp.
- Yuan translated at exchange Kusu Huobi.
Refresh interval data in milliseconds - specified in row 22.

In the page code, which must be done counting, place the contents of this file (scrypt.js) Right after the first tag <body>.

In the page header (between <head> and </ head>), which should be done counting, put the following line:
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"> </ script>

In file CSS (which describes the design of your pages required), add the file contents style.css, 
customizing the look "buttons" switching rates (originally id - "start_convert" and "restart_convert") In accordance with the design of your site.

You skipt integrated into the pages for the job.
Now, for what would a computer - or rather, the user's browser, it was clear where the values ​​should be overwritten, 
all recounts the original price in the code pages must be placed (if they have not been placed there) in <span> tag with class "price ", 
as in the example:
was (in the code): Price: 1000 euro
become (in the code): Price: <span class="price"> 1000 euro </ span>

Ensure that users see that the price listed is in bitcoin, and not clear in what is placed in a tag <span> need and the number of the currency
and that it too would be replaced!

If you have a lot of places where it is necessary to replace the value and price indication being used by another class, not the class "price", 
you can assign the correct class in the script, line 23 of the source file scrypt.js.


That would take into account the additional costs inherent in conversion (input and output) rates, add the appropriate amount of additional cost coefficient in the 12 line of the file scrypt.js
(10% - 1.1 is 20% - is 1.2, etc.):
was: new_val = (old_val / kotirovka). toFixed (4);
become: new_val = (old_val / kotirovka * 1.1). toFixed (4);

IMPORTANT: The script assumes informational purposes only! Check the exchange rate at the final transactions!
The sources of data for parsing, which uses a script to change, which can make the script incorrect!
As well, the course may change significantly after an update.

The site owner, for his part, promises the best possible operational script small difference in the above case.
For this notification, please complete the contact form at startup.
(You can not fill it, but then we can not tell you about the changes in the script!)

The idea of the script: 2ox@inbox.ru
Script code: seorubl@yandex.ru

Assist in the development of the project(BTC): 1FUXJiHjrpkZ8ke4ZCwXd8wVPm98DQWatL
