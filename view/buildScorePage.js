function buildScorePage(currentscore, number) {
	
		$("#ranking").empty();
		$("#ranking").append('<h4>' + textGlobalScore + '</h4>');
		$("#ranking").trigger( "create" );
		
		var newhtml = buildCherryBlossomTree(currentscore);
		var flowerhtml = buildCollFlowers(number);
		
		$("#scoreSlider").empty();
		$("#scoreSlider").append('<input type="range" name="slider" id="slider" value="'+currentscore+'" min="0" max="'+maxGlobalScore+'"  />');
		$("#scoreSlider").trigger( "create" );
		
		$('#slider').slider('disable');	
	
		if (newhtml != ""){
			$("#cherrytree").empty();
			$("#cherrytree").append(newhtml);
			$("#cherrytree").append(flowerhtml);
			$('#cherrytree').trigger("create");
		}else{
			$("#cherrytree").empty();
			$("#cherrytree").append('<img src="view/img/iphone/3g/cherry_blossom_1.jpg" width="290" alt="start_cherry_tree" class="cherry-tree" id="tree-credits">');
			$("#cherrytree").append(flowerhtml);
			$('#cherrytree').trigger("create");
		}
		
		$('#slider').val(currentscore);
		$('#slider').slider('refresh');
		
		//$("#toast-message").remove();
		
		//$.mobile.fixedToolbars.show(true);
}