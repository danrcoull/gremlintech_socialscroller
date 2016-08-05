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

3. Configure your twitter oauth settings. [Twitter Apps](http://apps.twitter.com/)
4. Enter the username you wish to get the twitter feed from.
5. Enter the number of tweets to show.

6. Configure your google app [google devloper console](http://console.developers.google.com/)
7. In the console visit library and search Google+ Api and enable
8. Create a browser api key in ap and credentials.
9. Enter the unique url of the page without the +.

10. Facebook create an app, and enter api information see link in helpful links
11. Wordpress not enabled as of version 1.0.0

12. Open the Layout.xml and modify the location of the block.
13. Call $this->getChildHtml() to show the block.


Cache
----------------
Enable the feed cache via magento admin.
System -> Cache Management -> Social Scroller Feed Cache -> Enable

If not enabled you are likly to get blocked due to the number of queries that will be made.
When enabled the feed will refresh in accordance with the cache settings in your magento admin.


Demo
----------------

See this working on one of my sites at :

[fashion threads boutique](https://www.fashionthreadsboutique.co.uk)

helpful Setup
----------------

if you need some help with setting up facebook apps 2016 see below link.

[Setup Facebook app 2015/2016](https://www.rocketmarketinginc.com/blog/get-never-expiring-facebook-page-access-token/)



