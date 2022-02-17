var tableBasicas = initDataTable({ idTab: 'tableBasicas' });
var posix = 0;

$('textarea').keyup(function () {
	var id = this.id;
	contaChar(id);
});

function contaChar(idd){
    var id = idd;
    var characterCount = $("#"+id).val().length;
    if (id == "ZAP_AGENDAMENTO1") {
        var current = $('#current_agendamento');
    }
    if (id == "ZAP_REAGENDAMENTO_1") {
        var current = $('#current_reagendamento1');
    }
    current.text(characterCount);	
}

function getCaretCharOffset(element) {
    var caretOffset = 0;
  
    if (window.getSelection) {
      var range = window.getSelection().getRangeAt(0);
      var preCaretRange = range.cloneRange();
      preCaretRange.selectNodeContents(element);
      preCaretRange.setEnd(range.endContainer, range.endOffset);
      caretOffset = preCaretRange.toString().length;
    } 
  
    else if (document.selection && document.selection.type != "Control") {
      var textRange = document.selection.createRange();
      var preCaretTextRange = document.body.createTextRange();
      preCaretTextRange.moveToElementText(element);
      preCaretTextRange.setEndPoint("EndToEnd", textRange);
      caretOffset = preCaretTextRange.text.length;
    }
  
    return caretOffset;
  }

  const listaParam = document.querySelectorAll(".list-group-item");			
  var textAreas_focus = false;					
  [...listaParam].forEach((v,i)=>{
      v.addEventListener('mouseover', (e)=>{
          var target = document.activeElement.id;
          if ( target == 'ZAP_AGENDAMENTO1' || target == 'ZAP_REAGENDAMENTO_1') {
            textAreas_focus = document.activeElement;
              return							  
          }							
      });
      v.addEventListener('click', (e)=>{
          if(textAreas_focus !== false){
              let campo = (e.target).dataset.campo;
              let tam_campo = (campo.length)
              alteraTxt(campo, tam_campo);
          }
          return;
      });						
  });					
  
  function alteraTxt(campo, tam_campo){ 
      let start = textAreas_focus.selectionStart;
      let end =  textAreas_focus.selectionEnd;
      let v = textAreas_focus.value;
      let textBefore = v.substring(0,  start );
      let textAfter  = v.substring( start, v.length );
      textAreas_focus.value = ( textBefore+ campo +textAfter );
      textAreas_focus.focus();
      textAreas_focus.selectionEnd= end + tam_campo;
  }
  
  var apos_editar = function (formu, dados) {
		contaChar("ZAP_AGENDAMENTO1");
		contaChar("ZAP_REAGENDAMENTO_1");
	};