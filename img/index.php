<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <title>Instagram with Hashtag <?php echo "#".$_GET['tag']; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Animated Responsive Image Grid - Cycling through a set of images in a responsive grid." />
        <meta name="keywords" content="grid, images, thumbnails, responsive, css3, jquery, javascript, random, transition, 3d, perspective" />
        <meta name="author" content="Codrops" />
        <meta http-equiv="refresh" content="60;URL=index.php?tag=<?php echo $_GET['tag']; ?>" />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script type="text/javascript" src="js/modernizr.custom.26633.js"></script>
		<noscript>
			<link rel="stylesheet" type="text/css" href="css/fallback.css" />
		</noscript>
		<!--[if lt IE 9]>
			<link rel="stylesheet" type="text/css" href="css/fallback.css" />
		<![endif]-->
    </head>
    <body>
		<div class="container">
			
			<!-- Codrops top bar -->
            <!--
            <div class="codrops-top">
                <a href="http://tympanus.net/Tutorials/PhotoBoothStripsLightbox/">
                    <strong>&laquo; Previous Demo: </strong>Photo Booth Strips with Lightbox
                </a>
                <span class="right">
					<a href="http://www.flickr.com/people/smanography/">Images by Sherman Geronimo-Tan</a>
                    <a href="http://tympanus.net/codrops/2012/08/02/animated-responsive-image-grid/">
                        <strong>Back to the Codrops Article</strong>
                    </a>
                </span>
                <div class="clr"></div>
            </div>
            -->
            <!--/ Codrops top bar -->
			
			<section class="main">

				<div id="ri-grid" class="ri-grid ri-grid-size-3">
					<img class="ri-loading-image" src="images/loading.gif"/>
					<ul>
                    	<?php
						
						$tag = $_GET['tag'];
						
						# Check hashtag
						if($tag == "") {
							echo "Not Found\n";
							exit;
						}

						//$img_dir = "../igprint/tag/".$tag."";
						$img_dir = "../newigprint/tag/".$tag."";
						//$img_dir = "images/medium";

						# Check Dir
						if(is_dir($img_dir)) {
						}
						else {
							echo "Not Found\n";
							exit;
						}
						if ($handle = opendir($img_dir)) {
							$i = 0;
    						while (false !== ($entry = readdir($handle))) {	
        						if ($entry != "." && $entry != ".." && $entry != "user_profile" && $entry != "img_cover" && $entry != ".DS_Store") {
									//echo "<li><a href=\"#\"><img src=\"$img_dir"."/"."$entry\"/></a></li>\n";
									//echo "$entry <br>\n";
									$i++;
        						}
								
    						}
    						closedir($handle);
							//echo "Total $i <br>\n";
						}
						
						// Show Images
						for ($j=$i;$j>=1;$j--) {
							//echo "$j <br>\n";
							echo "<li><a href=\"#\"><img src=\"$img_dir"."/"."$j.jpg\"/></a></li>\n";
						}
						
						?>
                      
					</ul>
				</div>
				
				<header class="codrops-header-special">

			
					<h1><img src="images/instagram_logo.png" border="0" width="100" high="100"> <?php echo "#$tag"; ?></h1><br> 
					<h2>Powered by: http://www.WeLoveEvent.com</h2>
					
					
				</header>

			</section>
			
        </div>
        <script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.gridrotator.js"></script>
		<script type="text/javascript">	
			$(function() {
			
				$( '#ri-grid' ).gridrotator( {
					//rows : 4,
					//columns : 8,
					rows : 4,
					columns : 7,
					maxStep : 2,
					interval : 2000,
					w1024 : {
						rows : 5,
						columns : 6
					},
					w768 : {
						rows : 5,
						columns : 5
					},
					w480 : {
						rows : 6,
						columns : 4
					},
					w320 : {
						rows : 7,
						columns : 4
					},
					w240 : {
						rows : 7,
						columns : 3
					},
				} );
			
			});
		</script>
    </body>
</html>
