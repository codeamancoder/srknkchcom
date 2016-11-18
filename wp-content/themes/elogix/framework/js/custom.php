<?php
header("Content-type: application/x-javascript");

if(file_exists('../../../../wp-load.php')) :
	include '../../../../wp-load.php';
else:
	include '../../../../../wp-load.php';
endif; 

$twitter_account = of_get_option('twitter_url');

?>

$(document).ready(function() {
  
    // set your twitter id
    var user = '<?php echo $twitter_account; ?>';
      
    // using jquery built in get json method with twitter api, return only one result
    $.getJSON('http://twitter.com/statuses/user_timeline.json?screen_name=' + user + '&count=1&callback=?', function(data)      {
          
        // result returned
        var tweet = data[0].text;
      
        // process links and reply
        tweet = tweet.replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, function(url) {
            return '<a href="'+url+'" target="_blank">'+url+'</a>';
        }).replace(/B@([_a-z0-9]+)/ig, function(reply) {
            return  reply.charAt(0)+'<a href="http://twitter.com/%27+reply.substring%281%29+%27">'+reply.substring(1)+'</a>';
        });
      
        // output the result
        $("#lasttweet").html(tweet);
    });
      
});