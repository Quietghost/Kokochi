function buildCredits(){
	
	var html = '<div data-role="collapsible" data-theme="e" data-content-theme="d">' +
   	'<h3>Credits</h3>' +
   	'<span class="credits-text">Game Design: Sebastian Matyas</span><p>' +
	'<span class="credits-text">Art Design: Mariana Irigaray</span></p>' +
	'<span class="credits-text">Icon Design: <br />http://mapicons.nicolasmollet.com/<br />http://glyphicons.com/</span></p>' +
	'<span class="credits-text">Programming: Sebastian Matyas</span></p>' +
	'<span class="credits-text">Technical/Design Support:<br /> Shime-san/Kato-san </span></p>' +
	'<span class="credits-text">Powered by:<br /> http://jquerymobile.com/ </span></p>' +
	'</div>'
	http://mapicons.nicolasmollet.com/
	$("#credits").empty();
	$("#credits").append(html);
	$("#credits").trigger("create");
	
}