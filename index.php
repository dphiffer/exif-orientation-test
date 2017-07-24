<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>EXIF orientation test</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style>

		html {
			box-sizing: border-box;
		}
		*, *:before, *:after {
			box-sizing: inherit;
		}

		body {
			margin: 0;
			font-family: sans-serif;
		}

		h2 {
			margin-top: 1.5em;
		}

		img {
			width: 100%;
			height: auto;
		}

		pre {
			font-size: 11px;
			word-wrap: break-word;
		}

		#container {
			width: 100%;
			max-width: 500px;
			margin: 2em auto;
		}

		.background-image {
			background-size: contain;
			background-repeat: no-repeat;
			width: 100%;
			height: 0;
			padding-bottom: 75%;
		}

		@media screen and (max-width: 500px) {

			#container {
				padding: 0 10px;
			}

		}

		</style>
	</head>
	<body>
		<div id="container">
			<h1>EXIF orientation test</h1>
			<p>This is a test of <a href="https://www.daveperrett.com/articles/2012/07/28/exif-orientation-handling-is-a-ghetto/">EXIF orientation modes</a> to check how browser and OS implementations work. I took the photo horizontally, with the volume buttons facing up (EXIF orientation mode 3). The photo was then resized by iOS Mail.app with the "Medium" setting. No other edits were made to the photo. You can see the <a href="https://github.com/dphiffer/exif-orientation-test">source code</a> on GitHub.</p>
			<p><a href="#" onclick="document.getElementById('all-tags').style.display = 'block'; this.style.display = 'none'; return false;">Show all EXIF tags</a></p>
			<pre id="all-tags" style="display: none;"><?php

			$exif = exif_read_data('exif.jpg');
			print_r($exif);

			?></pre>
			<?php

			$file = 'exif.jpg';
			$container = array('img tag', 'background');
			$encoding = array('normal', 'data uri');

			foreach ($container as $c) {
				$esc_c = htmlspecialchars($c);
				foreach ($encoding as $e) {
					echo "<h2>$esc_c / $e</h2>";
					if ($e == 'normal') {
						$url = $file;
					} else if ($e == 'data uri') {
						$url = file_get_contents($file);
						$url = base64_encode($url);
						$url = "data:image/jpeg;base64,$url";
					}
					$short_url = $url;
					if (strlen($short_url) > 32) {
						$short_url = substr($short_url, 0, 32) . '...';
					}
					if ($c == 'img tag') {
						echo "<pre>&lt;img src=\"$short_url\"&gt;</pre>";
						echo "<img src=\"$url\">";
					} else if ($c == 'background') {
						echo "<pre>&lt;div style=\"background-image: url('$short_url')\"&gt;&lt;/div&gt;</pre>";
						echo "<div class=\"background-image\" style=\"background-image: url('$url')\"></div>";
					}
				}
			}

			?>
			<h2 id="findings">Preliminary findings</h2>
			<ul>
				<li>On iOS 10.3.2 image tags (but not background images) are rotated to correct for EXIF orientation mode (photo is shown right-side-up, with "1" and "2" on top), in both Mobile Safari and Chrome.</li>
				<li>No other browser/OS attempts to rotate to correct for EXIF orientation mode (photo is shown upside down, with "4" and "3" on top), for both image tags and background images. Tested macOS 10.12.6 with Firefox 54.0.1, Safari 10.1.2, Chrome 59.0.3071.115 and Chrome on Android 7.0.</li>
				<li>Image URI vs. data URI has no impact on EXIF orientation rotation.</li>
			</ul>
			<p>Please <a href="mailto:dan@phiffer.org">contact me</a> if you discover a different behavior.</p>
		</div>
	</body>
</html>
