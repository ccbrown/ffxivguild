<?php
header('Content-type: text/html;charset=utf-8');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		
		<title>Final Fantasy | ファイナルファンタジ &lt;&lt;FFXIV&gt;&gt;</title>
		
		<link rel="stylesheet" type="text/css" href="style/jquery-ui.min.css" />
		<link rel="stylesheet" type="text/css" href="style/ruby.css" />
		<link rel="stylesheet" type="text/css" href="style/style.css" />

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/xivdb-tooltips.js"></script>
	</head>
	<body>
		<div id="bgcoloranimation">&nbsp;</div>
		<div id="bggradient">&nbsp;</div>

		<script>
			$(function() {				
				var loadingCounter = 0;
				
				var loadingAnimationCycle = (function cycle() { 
					if (loadingCounter > 0) {
						$('#loadingwrapper').animate({ opacity: 1.0 }, 1500, 'linear', function() {
							$('#loadingwrapper').animate({ opacity: 0.5 }, 1500, 'linear', cycle);
						});
					}
				});

				var showLoadingIndicator = function() {
					if (++loadingCounter == 1) {
						$('#loadingwrapper').show();
						loadingAnimationCycle();
					}
				}

				var hideLoadingIndicator = function() {
					if (--loadingCounter == 0) {
						$('#loadingwrapper').hide();
					}
				}

				var visiblePage = '';
				var previousHash = window.location.hash;
				var navigateToHash = function() {
					var hash = window.location.hash;
					var errorhash = previousHash;
					previousHash = hash;
					var parts = hash.split('/');
					var url = 'ajax/home.php';
					if (parts.length >= 1) {
						if (parts[0] == '#apply') {
							url = 'ajax/apply.php';
						} else if (parts[0] == '#groups') {
							url = 'ajax/groups.php';
						}
					}
					if (url == visiblePage) {
						return;
					}
					$('#content').animate({opacity: 0.0}, 300);
					showLoadingIndicator();
					$('#content').load(url, function(response, status, xhr) {
						hideLoadingIndicator();
						if (status == "error") {
							if (xhr.status != 0) {
								alert("Error (" + xhr.status + "): " + xhr.statusText);
								window.location.hash = errorhash;
							}
						} else {
							visiblePage = url;
						}
						$('#content').animate({opacity: 1.0}, 300);
					});						
				};

				$(window).on('hashchange', function() {
					navigateToHash();
				});
				navigateToHash();
			});
		</script>

		<div id="wrapper">
			<div id="header">
				<table id="navbar"><tr>
					<td><a href="#">Home</a></td>
					<td><a href="#groups">Raid Groups</a></td>
					<td><a href="forums">Forums</a></td>
					<td><a href="http://sim.ffxivguild.net" target="_blank">Combat Simulator</a></td>
				</tr></table>
			</div>

			<div id="content">
				&nbsp;
			</div>
			
			<div id="loadingwrapper">
				<center>
					<div id="loadingbox">
						<ruby>
							<rb>読み込み中</rb><rp>(</rp><rt>Loading</rt><rp>)</rp>
						</ruby>
					</div>
				</center>
			</div>
		</div>
		
		<script>
		$(function() {
			var colors = ['#000070', '#700070', '#700000', '#707000', '#007000', '#007070'];
			var next = Math.floor(Math.random() * colors.length);
			var doNextColorAnimation = function() {
				if (next >= colors.length) {
					next = 0;
				}
				$('#bgcoloranimation').animate({
					'backgroundColor': colors[next++]
				}, 5000, doNextColorAnimation);
			}
			doNextColorAnimation();
		});
		</script>
	</body>
</html>