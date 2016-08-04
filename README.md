<<<<<<< HEAD
Gremlin Tech Solutions - Social Scroller
============================

Allow a frontend customer attribute for a customer to add their linkedin profile url, in which is limited to
250 chars and validated as a url using the Validation.js classes.

The sql setup creates the attribute to make the attribute available on all customer forms. And the field block
is used to load the field to the frontend additional instad of taking control of the register persistant form.


Setup
----------------
Go to System -> Configuration -> GremlinTech -> Social Scroller

1. Enabled  - is the module enabled if not do nothing
2. Debug - Not implemented as of version 1.0.0

3. Configure your twitter oauth settings. [a link](http://apps.twitter.com/)
4. Enter the username you wish to get the twitter feed from.
5. Enter the number of tweets to show.

6. Configure your google app [a link](http://console.developers.google.com/)
7. In the console visit library and search Google+ Api and enable
8. Create a browser api key in ap and credentials.
9. Enter the unique url of the page without the +.

10. Facebook not enabled as of version 1.0.0
11. Wordpress not enabled as of version 1.0.0

12. Open the Layout.xml and modify the location of the block.
13. Call $this->getChildHtml() to show the block.


Cache
----------------
Enable the feed cache via magento admin.
System -> Cache Management -> Social Scroller Feed Cache -> Enable

If not enabled you are likly to get blocked due to the number of queries that will be made.
When enabled the feed will refresh in accordance with the cache settings in your magento admin.


DEMO
________________

See this working on one of my sites at :

[a link](https://www.fashionthreadsboutique.co.uk)
=======
# gremlintech_socialscroller
>>>>>>> 2e51c5bff329eabc5bb51ee3293690b3a0fbc8e4
