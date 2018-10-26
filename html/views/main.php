	<div class="jumbotron">
		<?php if (isset($loaded_tweet) && mb_strlen($loaded_tweet->getContent()) > 0) { ?>
			<div class="alert alert-success">Be on the lookout! Your tweet has been queued and will go out pretty soon.</div>
		<?php } ?>
		<h1 class="text-center">Tweet anonymously</h1>
		<p class="text-center">Send anonymous text tweets through the <a href="https://twitter.com/<?=TWITTER_SCREEN_NAME?>">@<?=TWITTER_SCREEN_NAME?></a> Twitter account. <span class="hidden-xs">Perfect for when you don't want to create a burner account to detach yourself from a high-profile government leak, or for when you just want to complain about your boss.</span></p>
		<form class="form-horizontal" method="post" action="/controllers/save-tweet.php">
			<fieldset>
				<div class="form-group <?php if (isset($_GET['e'])) { echo 'has-error'; } ?>">
					<div class="col-md-8 col-md-offset-2">  
						<label class="control-label error-label" for="content"><?php if (isset($_GET['e'])) { ?><?=htmlspecialchars(urldecode($_GET['e']))?><?php } ?> </label>
						<textarea rows="3" class="form-control" id="content" name="content" placeholder="Your super secret anonymous tweet..." autofocus><?php if (isset($_GET['c'])) { ?><?=htmlspecialchars(urldecode($_GET['c']))?><?php } ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-8 col-md-offset-2">
						<button id="submit" name="submit" class="btn btn-primary btn-block">TWEET IT OUT</button>
					</div>
				</div>
				<p class="text-center"><small>Tip: use @ mentions or hashtags to get some attention</small></p>
			</fieldset>
		</form>
		<p class="text-center"><small>Questions? The DMs are open: <a href="http://www.twitter.com/<?=TWITTER_SCREEN_NAME?>">@<?=TWITTER_SCREEN_NAME?></a></small></p>
	</div>
