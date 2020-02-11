<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link type="text/css" rel="stylesheet" href="/css/style.css">
	<link rel="shortcut icon" type="image/png" href="/favicon-96x96.png">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.12.0/lodash.min.js"></script>
	<script src="/js/jquery.js"></script>
	<title>500 Error | SpanShop</title>
	<meta name="description" content="Shop all the top stores for your favorite best-selling products, electronics, books, clothing, video games, toys, jewelry, and more, all in one convenient place.">
	<meta name="keywords" content="price, check, comparison, online, shopping, best, products, electronics, books, clothing, video, games, toys, jewelry, sporting, goods, home, garden, tools, pet, supplies">
</head>
<body>
<div id="wrapper">
	<div id="header" class="headertwo"><div id="headwrap">
	<a href="/"><img id="headimgtwo" class="prescroll" src="/media/spanshopnamesmall.png"></a>
	<div id="menubutton" class="premenu"></div>
	<form method="get" action="/" id="searchform" class='searchtwo'>
		<input id="searchbar" type="search" name="q" placeholder="Your Wares. Your Way." autocomplete="off"><input type="submit" value="">
	</form>
	</div></div>
	<div id="content">
		<div id="results">
			<div id="error">
				<span>500 Error:</span> Sorry, but the requested page is unavailable due to a server hiccup. Our engineers have been notified, so check back later.
			</div>
		</div>
	</div>
	<div id="footer">
		<div class="disclaimer">All product and company names are trademarks &trade; or registered &reg; trademarks of their respective holders.
			Use of them does not imply any affiliation with or endorsement by them.</div>
		<div id="copyright">Copyright &copy; <?php
		$year = new DateTime(null, new DateTimeZone('America/New_York'));
		echo $year->format('Y');
		?> SpanShop.com. All rights reserved.</div>
	</div>
</div>
</body>
</html>
