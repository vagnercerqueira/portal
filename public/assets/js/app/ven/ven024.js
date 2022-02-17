teste();
function teste(){

document.addEventListener('DOMContentLoaded', (event) => {

    var dragSrcEl = null;
    
    function handleDragStart(e) {
      //this.style.opacity = '0.4';
	  this.style.cursor = 'move';
      
      dragSrcEl = this;
  
      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/html', this.innerHTML);
    }
  
    function handleDragOver(e) {
      if (e.preventDefault) {
        e.preventDefault();
      }
  
      e.dataTransfer.dropEffect = 'move';
      
      return false;
    }
  
    function handleDragEnter(e) {
      this.classList.add('over');
    }
  
    function handleDragLeave(e) {
      this.classList.remove('over');
    }
  
    function handleDrop(e) {
      if (e.stopPropagation) {
        e.stopPropagation(); // stops the browser from redirecting.
      }
      
      if (dragSrcEl != this) {
        dragSrcEl.innerHTML = this.innerHTML;
        this.innerHTML = e.dataTransfer.getData('text/html');
      }
      
      return false;
    }
  
    function handleDragEnd(e) {
      this.style.opacity = '1';
      let formulario = this.closest("form");
      salva_alteracao(formulario);
    }
	function handleEditText(e) {
		let ele = this.childNodes[0];
		ele.innerHTML = "<input data-old='"+(ele.innerHTML)+"' autofocus value='"+(ele.innerHTML)+"' type='text' onfocusout='remove_focusEditavel(this)'>";
		ele.childNodes[0].focus();
	}
	
	function loopDrags(items){
		items.forEach(function(item) {
		  item.addEventListener('dragstart', handleDragStart, false);
		  item.addEventListener('dragenter', handleDragEnter, false);
		  item.addEventListener('dragover', handleDragOver, false);
		  item.addEventListener('dragleave', handleDragLeave, false);
		  item.addEventListener('drop', handleDrop, false);
		  item.addEventListener('dragend', handleDragEnd, false);		  
		  item.addEventListener('dblclick', handleEditText, false)
		});	
	}	
	
	loopDrags(document.querySelectorAll('.box'));
	
  });
}

function remove_focusEditavel(ele){		
	let texto_sem_tags = ((ele.value).replace(/(<([^>]+)>)/gi, "")).trim();
	let span = ele.closest('span');
	let formulario = span.closest("form");
	
	if( texto_sem_tags == ""){
		error_alert("form[name="+formName+"]", false, "Erro ao alterar a ordem ", 1000);
		ele.focus();
	}else{
		
		span.innerHTML = texto_sem_tags;		
		
		salva_alteracao(formulario);
	}
	
}

  
  function salva_alteracao(formulario){
        let formName = formulario.getAttribute("name");
		let elementos = document.querySelectorAll("form[name="+formName+"] .box");
			   
		let box = {};
        [...elementos].forEach((v,i)=>{ 
			let key = v.childNodes[0].dataset.value;
			let valor = v.childNodes[0].innerHTML;
			box[key] = valor;
		});
	    
		if(Object.keys(box).length > 0 ){
			//box = box.join(",");
			box = JSON.stringify(box)
			let data = new FormData();
			data.append('ordem', box);
			data.append('campo', formName);
			
			fetch(pag_url + 'salva_ordem_cabecalhos', {
				method: 'POST',
				credentials: 'same-origin',
				body: data
			})
			.then((json) => json.json())
			.then(function (resp) {
				if(resp.retorno == "ok") {
					error_alert("form[name="+formName+"]", true, 'Coluna movida!!!', 500);
					toastr.clear();
				}
			})
			.catch(function (error) {
				error_alert("form[name="+formName+"]", false, "Erro ao alterar a ordem ", 1000);
				toastr.clear();
			}).finally(function () {
				
			});  			
		}
  }
  