<?php
defined("INDEX")or die(header("location:/"));
header('Content-Type: text/html; charset=utf-8');

	$arch = array();
	$image = array();		
	$i = 1 ;
	$count = count(glob('downloads/*'));
	foreach(glob('downloads/*') as $do) {
		$figcap = explode(".tar", basename($do));
		$imgname = explode("-", basename($do));
		if($i === 1 ) {
			$prev = $count ;
			$next = ($i+1) ;
		}
		elseif( $i === $count) {
			$prev = ($i - 1) ;
			$next = 1 ;
		}
		else {
			$prev = ($i - 1) ;
			$next = ($i+1) ;
		}	
			
			$varcontent .= '
					<figure>
					<a href="#img'.$i.'">
    				<img src="images/screen/prev/'.$imgname[0].'.png" title="'.$figcap[0].'">
    				<figcaption style="text-transform:capitalize">'.$figcap[0].'</figcaption>
					</a>
  					</figure>
  					<!-- lightbox container hidden with CSS -->
    				<div class="lightbox" id="img'.$i.'">
    			   	<a href="#img'.$prev.'" class="light-btn btn-prev">
    			   		<img src="images/prev.png" alt="<">
    			   	</a>
         			<a href="#_" class="btn-close"> 
         				<img src="images/close.png" alt="X">
         			</a>
         			<img src="images/screen/'.$imgname[0].'.png">
        				<a href="#img'.$next.'" class="light-btn btn-next">
        					<img src="images/next.png" alt=">">
        				</a>
        				<a href="'.$do.'" class="btn-download"><img src="images/download.png" alt="↧">
        				</a>
        			</div>';
		$i++;
	}	

?>
