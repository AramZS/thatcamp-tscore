<html>
<head>
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script> -->
<script src="jquery.js" ></script>
<link rel='stylesheet' href='style.css' type='text/css' media='all' />
<script type="text/javascript">

var score = array(reputation:0,interest:0,correct:0,speed:0);

<?php

$source_names = array (
'Marty Hendrickson',

'Petey Calhoun',

'James Henley', 

'Cassie Thorpe',

'Dwight Valdez',

'Derrick Henson',

'Edwina Blackburn',

'Elmer Trujillo',

'Monique Sizemore',

'Sandy Houston',

'Rachel Kline',

'Artis Benton',

'Stuart Gonzales',

'Irwin Fox',

'Georgia Oneal',

'Collin Suggs',

'Faye Contreras',

'Myrna Page',

'Lakeisha Neff',

'Jasmine Brewster'

);

$sources = array();

$source_max = 19;

$source_count = rand(10, $source_max);

$reliability = 100;
$s = 0;
while ($s <= $source_max) {

	$rScore = rand(1, $reliability);
	$sources[] = array(
		'name' => $source_names[$s],
		'reliability' => $rScore
	);
	
	if ($rScore > ($reliability/2)){
		$reliability = $reliability-1;
	}
	
	$s++;

}

$tweet_max = 80;

$tweets = array();
$t = 0;
while ($t < $tweet_max){
	$correct = rand(0,1);
	$interest = rand(1, 100);
	$sourceC = rand(0, ($source_max-1));
	$tweets[] = array(
		'correct'	=> $correct,
		'interest' 	=> $interest,
		'name'	=> $sources[$sourceC]['name'],
		'reliability' => $sources[$sourceC]['reliability']
	);
	$t++;
}

?>

<?php 
	echo 'var tArray = [';
	$c = 1;
	foreach ($tweets as $tweet){
		echo '{id:'.$c.',correctness:'.$tweet['correct'].',interest:'.$tweet['interest'].',reliability:'.$tweet['reliability'].',name:"'.$tweet['name'].'"}';
		if ($c != count($tweets)){
			echo ',';
		}
		$c++;
	}
	echo '];';
?>

	var timer;
	
	function tweepBuilder(element) {
			var echoer = '<div class="tweet active" id="'+element.id+'">'
					+'<div class="source_meta"><h4>'+element.name+'</h4></div>'
					+'<div class="tweet_meta" correct="'+element.correctness+'">'
						+'Interest Level: <span class="interest data-span">'+element.interest+'</span> | Reliability: <span class="reliability data-span">'+element.reliability+'</span>'
					+'</div>'
				+'</div>'
			return echoer;
	}
	
	jQuery(document).ready(function () {
			var count = 0;
			for( var i = 0; i < 5; i++){
				var element = tArray[i];
				var echoer = tweepBuilder(element);
				jQuery('#sources').append(echoer);
			}
	});
	
	function beginIt(){
		timer = setInterval(tweep,1000);
	}
	
	var c = 6;
	// Every 9 seconds
	function tweep() {
		
			$c = 0;
			jQuery('#sources .tweet').last().remove();
			jQuery('#sources .tweet').each( function (i, v) {
				var thisE = jQuery(v);
				var correct = thisE.find('.tweet_meta').attr('correct');
				var reliability = parseInt(thisE.find('.tweet_meta .reliability').text());
				var flip = Math.floor((Math.random()*100))+1;
				var reliabilityP;
				var reliabilityN;
				if (correct == 0){
					reliabilityP = ((100-reliability)/100);
					reliabilityN = ((100-reliability)*2/100);
				} else {
					reliabilityP = ((100-reliability)*2/100);
					reliabilityN = ((100-reliability)/100);				
				}
				var resultP = (reliability+(reliability*reliabilityP));
				var resultN = (reliability-(reliability*reliabilityN));
				if (flip >= 50 && resultP < 100 && resultP > 1){
					reliability = (reliability+(reliability*reliabilityP));
					
				} else if (resultN < 100 && resultN > 1) {
					reliability = (reliability-(reliability*reliabilityN));
				} 
				reliability = Math.floor(reliability);
				thisE.find('.tweet_meta .reliability').text(reliability);
			});
			
			var theTweet = tweepBuilder(tArray[c]);
			jQuery('#sources').prepend(theTweet);
			c++;
		
	}
	
	function tweepSelect(tweep){
		var timed = parseInt(jQuery('#countdown').text());
		jQuery('#org').prepend(tweep);
	}
	
	
</script>
</head>
<body>
<button onclick="beginIt();">Start</button>

<div id="counter">
Reporting Window: 24 hours.
Deadline in: <span id="countdown">240000</span>
</div>

<div id="container">
	<div id="org">
		<div id="scoreboard"></div>
		<div id="tweetboard"></div>
	</div>
	<div id="sources">

	</div>
</div>

</body>
</html>