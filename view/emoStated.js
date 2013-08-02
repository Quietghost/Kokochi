function emoStated(selectedName, selectedEmoId){
	$('#div-'+selectedEmoId).empty();
	newhtml = '<input type="button" value="'+selectedName+'" id="button" emoid="'+selectedEmoId+'" data-theme="e" /></div>';
	$('#div-'+selectedEmoId).append(newhtml);
	$('#div-'+selectedEmoId).trigger("create");
	
}