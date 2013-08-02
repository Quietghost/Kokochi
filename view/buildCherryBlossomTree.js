function buildCherryBlossomTree(currentscore){
	
	var newhtml ="";
	
	var firstLevel = Math.floor(maxGlobalScore/3)+1;
	var secondLevel = (firstLevel*2);
	var thirdLevel = maxGlobalScore;
	
	if (currentscore >= 0 && currentscore<=firstLevel){
		
	  	newhtml = '<center><img src="view/img/iphone/3g/cherry_blossom_1.jpg" width="290" alt="start_cherry_tree" class="cherry-tree" id="tree-credits"></center>'+
					'<div class="cherry-tree-text">'+textCherryBlossomTreeLevel1+' '+(parseInt(firstLevel)+1)+' '+textCherryBlossomTreeLevel2+'</div>';
	  	
		}else{
			if(currentscore>firstLevel && currentscore<=secondLevel){
				
				newhtml = '<center><img src="view/img/iphone/3g/cherry_blossom_2.jpg" width="290" alt="start_cherry_tree" class="cherry-tree" id="tree-credits"></center>'+
				'<div class="cherry-tree-text">'+textCherryBlossomTreeLevel1+' '+(parseInt(secondLevel)+1)+' '+textCherryBlossomTreeLevel2+'</div>';
				
			}else {
				if(currentscore>secondLevel && currentscore<maxGlobalScore){
					
					newhtml = '<center><img src="view/img/iphone/3g/cherry_blossom_3.jpg" width="290" alt="start_cherry_tree" class="cherry-tree" id="tree-credits"></center>'+
					'<div class="cherry-tree-text">'+textCherryBlossomTreeLevelLast1+' '+(parseInt(thirdLevel)+1)+' '+textCherryBlossomTreeLevelLast2+'</div>';
				
				}else{
					if(currentscore>=maxGlobalScore){
						
						newhtml = '<center><img src="view/img/iphone/3g/cherry_blossom_4.jpg" width="290" alt="start_cherry_tree" class="cherry-tree" id="tree-credits"></center>';
						
					}
				}
			}
		}
	
	return newhtml;
}