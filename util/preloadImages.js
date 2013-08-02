function preloadImages(){// Array dient zur Auflistung der Bilder

	imageArray = new Array();
	
	//intro-page
	imageArray[0] = "view/introPages/intro_strip_1.jpg";
	imageArray[1] = "view/introPages/intro_strip_2.jpg";
	imageArray[2] = "view/introPages/intro_strip_3.jpg";
	imageArray[3] = "view/introPages/intro_strip_4.jpg";
	imageArray[4] = "view/introPages/intro_strip_5.jpg";
	imageArray[5] = "view/introPages/intro_strip_6.jpg";
	imageArray[6] = "view/introPages/intro_strip_end.jpg";
	
	//home-page
	imageArray[7] = "view/img/emocount-sprite.png";
	imageArray[8] = "view/img/hugscount-sprite.png";
	imageArray[9] = "view/img/emotion-sprite.png";
	imageArray[10] = "view/img/flower-points-sprite.png";
	imageArray[11] = "view/img/hugs-sprite.png";
	imageArray[29] = "view/img/collaboration-sprite.png";
	
	//actions
	imageArray[12] = "view/actions/hugging.png";
	imageArray[13] = "view/actions/emotion.png";
	imageArray[14] = "view/actions/collaboration.png";
	
	//cherry blossom tree
	imageArray[15] = "view/img/iphone/3g/cherry_blossom_1.jpg";
	imageArray[16] = "view/img/iphone/3g/cherry_blossom_2.jpg";
	imageArray[17] = "view/img/iphone/3g/cherry_blossom_3.jpg";
	imageArray[18] = "view/img/iphone/3g/cherry_blossom_4.jpg";
	
	//navigation bar
	imageArray[19] = "view/myIcons/home.png";
	imageArray[20] = "view/myIcons/timeline.png";
	imageArray[21] = "view/myIcons/collaboration.png";
	imageArray[22] = "view/myIcons/score.png";
	
	//map icons
	imageArray[23] = "view/myIcons/letter_h.png";
	imageArray[24] = "view/myIcons/regroup.png";
	imageArray[25] = "view/myIcons/comment-map-icon.png";
	imageArray[26] = "view/myIcons/star-3.png";
	
	//actions
	imageArray[27] = "view/actions/cherry_tree.png";
	
	//flowers
	imageArray[28] = "view/img/flower_small.png";
	
	images = new Array()
	
	loadImages(imageArray)

}

function loadImages(imageArray)
{
  for (i=0; i < imageArray.length; i++) {
    images[i] = new Image();
    images[i].src = imageArray[i];
  }
}
