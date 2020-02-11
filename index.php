<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link type="text/css" rel="stylesheet" href="./css/style.css">
	<link rel="shortcut icon" type="image/png" href="./favicon-96x96.png">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.12.0/lodash.min.js"></script>
	<script src="./js/jquery.js"></script>
	<?php if(isset($_GET["q"]) && !empty($_GET['q'])) { ?>
	<title><?php echo $_GET["q"]; ?> | SpanShop</title>
	<?php } elseif (isset($_GET['c'])) { ?>
	<title><?php echo rawurldecode($_GET["c"]); ?> | SpanShop</title>
	<?php } else { ?>
	<title>Search | SpanShop</title>
	<?php }
	include "pull/sortfilter.php"; ?>
	<meta name="description" content="Shop all the top stores for your favorite best-selling products, electronics, books, clothing, video games, toys, jewelry, and more, all in one convenient place.">
	<meta name="keywords" content="price, check, comparison, online, shopping, best, products, electronics, books, clothing, video, games, toys, jewelry, sporting, goods, home, garden, tools, pet, supplies">
</head>
<body>
<div id="wrapper">
	<?php if((isset($_GET["q"]) && strlen($_GET["q"]) >= 3) || isset($_GET["c"])) {
		include 'pull/search.php'; ?>
		<div id="header" class="headertwo">
			<div id="headwrap">
				<a href="/"><img id="headimgtwo" class="prescroll" src="./media/spanshopnamesmall.png"></a>
				<div id="menubutton"></div>
				<form method="get" action="/" id="searchform" class='searchtwo'>
					<input id="searchbar" type="search" name="q"
					<?php if(isset($_GET["q"])) {
							echo 'value="'.htmlentities($_GET["q"]).'" placeholder="Your Wares. Your Way."';
						} else { echo 'placeholder="Your Wares. Your Way."';
					} ?> autocomplete="off"><input type="submit" value="">
					<input type='hidden' name='c' value="All">
					<input type='hidden' name='s' value="Relevance">
					<input type='hidden' name='p' value="1">
				</form>
			</div>
		</div>
		<div id="content"><div id="results">
			<form method='get' action='' autocomplete='off'>
				<?php if (isset($_GET['q'])) { ?>
				<input type='hidden' name='q' value="<?php echo $_GET['q']; ?>">
				<?php } ?>
				<select class="sortFilter" id='frontCat' name='c' onchange='this.form.submit()'>
					<option value="All">-- All Categories --</option>
					<?php foreach ($categories as $category => $value) {
						echo "<option value=".rawurlencode($category).">{$category}</option>";
					} ?>
				</select>
				<select class="sortFilter" id="frontSort" name="s" onchange='this.form.submit()'>
					<?php foreach ($sort_by as $name => $value) {
						echo "<option value=".rawurlencode($name).">Sorted By {$name}</option>";
					} ?>
				</select>
				<input type='hidden' name='p' value="1">
			</form>
			<?php
			if (isset($_GET['q']) && !empty($_GET['q'])) {
				echo "<div id='resultnum'>".number_format(array_sum($total_results), 0, '.', ',')." results for \"".$_GET['q']."\"";
			} else {
				echo "<div id='resultnum'>".number_format(array_sum($total_results), 0, '.', ',')." results in ".rawurldecode($_GET['c']);
			}
			foreach ($total_results as $market => $number) {
				echo "<span class='sepRes'>".$market." (".number_format($number, 0, '.', ',').")</span>";
			}
			echo "</div>";
			if (max($total_results) <= 50) {
				echo '<input type="hidden" id="resNum" value="'.max($total_results).'">';
			} else {
				echo '<input type="hidden" id="resNum" value="50">';
			}
			if (count($results) > 0) {
				foreach ($results as $result) {
					echo '<div class="result"><a class="prodLink" href="'.$result["link"].'">';
					echo '<div class="topres"><img src="'.$result["picture"].'"></div><div class="midres">';
					echo '<p class="prodTit">'.$result["title"].'</p><p class="prodCat">in '.$result["category"].' </p>';
					if ($result['price'] == 'Click for Price') {
                        echo '<p class="prodPrc">'.$result['price'].'</p></div>';
                    } else {
                        echo '<p class="prodPrc">'.$result["condition"]." for USD ".$result['price'].$result["shipping"].'</p></div>';
                    }
					echo '<div class="botres"><img src="./media/'.$result["market"].'.png"></div></a></div>';
				} ?>
				<form id='chgpage' method='get' action='' autocomplete='off'>
					<input id='query' type='hidden' name='q' value="<?php if (isset($_GET['q'])) {echo $_GET['q'];}?>">
					<input id='cat' type='hidden' name='c' value="<?php echo $_GET['c']; ?>">
					<input id='sort' type='hidden' name='s' value="<?php echo $_GET['s']; ?>">
					<input class='page' type='hidden' name='p' value="<?php echo $_GET['p']; ?>">
					<input id='prev' type='button' value=''>
					<input class='pageBut' type='button' value='1'>
					<?php if (max($total_results) > 10) { ?>
						<input class='pageBut' type='button' value='2'>
					<?php } if (max($total_results) > 20) { ?>
					<input class='pageBut' type='button' value='3'>
					<?php } if (max($total_results) > 30) { ?>
					<input class='pageBut' type='button' value='4'>
					<?php } if (max($total_results) > 40) { ?>
					<input class='pageBut' type='button' value='5'>
					<?php } ?>
					<input id='next' type='button' value=''>
				</form>
			<?php } else {
				echo "<p class='noresultwarn'>Sorry, no matches were found.</p>";
			}
			?>
		</div></div>
		<div id="footer">
			<div class="disclaimer">SpanShop is part of a product affiliate program which means that we might make money from purchases
				made through links found on this site.</div>
			<div class="disclaimer">All product and company names are trademarks &trade; or registered &reg; trademarks of their respective holders.
				Use of them does not imply any affiliation with or endorsement by them.</div>
			<div id="copyright">Copyright &copy; <?php
			$year = new DateTime(null, new DateTimeZone('America/New_York'));
			echo $year->format('Y');
			?> SpanShop.com. All rights reserved.</div>
		</div>
	<?php } else { ?>
		<div id="header" class="headerone">
			<a href="/"><img id="headimgone"src="./media/spanshopname.png"></a>
			<form method="get" action="" id="searchform" class='searchone'>
				<input id="searchbar" type="search" name="q" placeholder="Your Wares. Your Way." autocomplete="off"><input type="submit" value="">
				<input type='hidden' name='c' value="All">
				<input type='hidden' name='s' value="Relevance">
				<input type='hidden' name='p' value="1">
			</form>
		</div>
		<div class="homeCon">
			<h3>What is SpanShop?</h3><hr>
			<p class='peebreak'>SpanShop is a platform that allows you to search for the products you love across multiple online marketplaces simultaneously, such as Ebay, Amazon, Walmart, etc.</p>
			<p class='peebreak'>We take the hassle out of online shopping to save you time and money.
				SpanShop can help you find new products on websites you may not normally visit and prevent you from paying more than you have to.</p>
			<p class='peebreak'>Click inside the search bar, or choose a category below, to start your shopping exploration.</p>
			<p id='peeadjust'>If you have any feedback or suggestions for us, let us know on our <a href="./contact.php">contact page</a>.</p>
			</div>
		<div id="content"><div id="results">
			<form method='get' action='' id="homeCat" autocomplete='off'>
				<select class='sortFilter' id='frontCat' name='c' onchange='this.form.submit()'>
					<option value="All">-- Search By Category --</option>
					<?php foreach ($categories as $category => $value) {
						echo "<option value=".rawurlencode($category).">{$category}</option>";
					} ?>
				<input type='hidden' name='s' value="Relevance">
				<input type='hidden' name='p' value="1">
				</select>
			</form>
			<?php $random_category = True; include 'pull/search.php';
			echo '<p id="homeHead">Top Results in '.$category_name.'</p>';
			foreach ($results as $result) {
				echo '<div class="result"><a class="prodLink" href="'.$result["link"].'">';
				echo '<div class="topres"><img src="'.$result["picture"].'"></div><div class="midres">';
				echo '<p class="prodTit">'.$result["title"].'</p><p class="prodCat">in '.$result["category"].' </p>';
                if ($result['price'] == 'Click for Price') {
                    echo '<p class="prodPrc">'.$result['price'].'</p></div>';
                } else {
				    echo '<p class="prodPrc">'.$result["condition"]." for USD ".$result['price'].$result["shipping"].'</p></div>';
                }
				echo '<div class="botres"><img src="./media/'.$result["market"].'.png"></div></a></div>';
			} ?>
		</div></div>
		<div id="footer">
			<div class="disclaimer">SpanShop is part of a product affiliate program which means that we might make money from purchases
				made through links found on this site.</div>
			<div class="disclaimer">All product and company names are trademarks &trade; or registered &reg; trademarks of their respective holders.
				Use of them does not imply any affiliation with or endorsement by them.</div>
			<div id="copyright">Copyright &copy; <?php
			$year = new DateTime(null, new DateTimeZone('America/New_York'));
			echo $year->format('Y');
			?> SpanShop.com. All rights reserved.</div>
		</div>
	<?php } ?>
</div>
</body>
</html>
