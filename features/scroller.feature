@frontend
Feature: Scroller Block
	In order to use the feeds from the library
	As as user
	I Should be able to view the scrolling feed in a browser

	@block
	Scenario: Layout is loaded
		Given  the page handle 'social_scroller' is within the layout
		Then '/' should contain class 'socialscroller'

	@model
	Scenario: Feed Limits
		Given a limit of '2'
		Then each library should only load '2' posts
		When collection is called the total should only be '2' x Libraries enabled

	@javascript @css
	Scenario: Loading Required Files
		Given im on the url '/'
			And 'jquery' is enabled in admin for frontend
				Then '[jquery.js]' should be included in the source
		Then '[ticker.js, ticker.css]' should be included in the source

