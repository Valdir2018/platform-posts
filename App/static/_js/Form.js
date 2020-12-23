// "use strict"

function addPergunta(value) {
    document.getElementById('pergunta').removeAttribute("disabled");
}

function addAlternativa(value) {
    let button_x = document.getElementById('saveanswer');
    let button_y = document.getElementById('save');
    let atribute = document.createAttribute("disabled");

    document.getElementById('resposta').removeAttribute("disabled");
    document.getElementById('div-group').style.display = 'block';
    button_x.style.margin = '0 27%';
    button_x.style.display = 'inline-block';
    button_x.style.position = 'absolute';

    atribute.value = "disabled";
    button_y.setAttributeNode(atribute);  

}

function removeAlternativa() {
   let field = document.getElementById('resposta');
   field.setAttribute("disabled", "disabled");
   console.log('Field hidden');
} 

function handleAddInputText(value) {
    document.getElementById('add_input_text').removeAttribute("disabled"); 
    addAlternativa(value); 
} 

function Save() {
	let post_id = document.getElementById('post_id');
	let pergunta = document.getElementById('pergunta');
	var dados = {
		post_id: post_id.value,
		pergunta:  pergunta.value		 
	};

	var data = JSON.stringify(dados);
			  
	let ajax = new XMLHttpRequest();
	ajax.open('POST','index.php?class=PostForm&method=addFormPost', true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.onreadystatechange = () => {
      if (ajax.readyState === 4) {
         if (ajax.status === 200) {
             message("Pergunta adicionada com sucesso !");
             document.getElementById('pergunta').value = '';
             console.log(ajax.responseText);
         }
      }
	};
  ajax.send(data);
}


function SaveAnswer() {
	let question_id = document.getElementById('id_question');
	let resposta = document.getElementById('resposta');
  let add_input_text = document.getElementById('add_input_text');
  
	var obj = {
        id: question_id.value,
        alternativa: resposta.value,
        add_input_text: add_input_text.value
	};

	var object_data_json = JSON.stringify(obj);

	var ajax = new XMLHttpRequest();
	ajax.open('POST','index.php?class=PostForm&method=addAlternative', true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.onreadystatechange = () => {
        if (ajax.readyState === 4) {
          if(ajax.status === 200) {
          	 message("Alternativa adicionada com sucesso !");
             document.getElementById('resposta').value = '';
             console.log(ajax.responseText);
          }	
        }
	};
  ajax.send(object_data_json);
}

function message(value) {
   var x = document.getElementById("add_message");
    document.getElementById("add_message").innerHTML = value;
    x.className = "show";
    setTimeout(function() {
     x.className = x.className.replace("show", ""); 
   }, 3000);
}

function hideOptionLevel() {
    document.getElementById('default').style.display = 'none';
    console.log("activate 1");
}

function hideOptionProduct() {
    document.getElementById('prod').style.display = 'none';
    console.log("activate 2");
}

function hideOptionCompany() {
    document.getElementById('empresa').style.display = 'none';
    console.log("activate 3");
}

function isFileValid(val) {
    var ext = '';
    var arr = ['mp4','m4v','ogg','webm','jpg','jpeg','gif','png','bmp'];
    for(var i =0; i <=  arr.length; i++) {
      if (arr[i] === val) {
          return ext = arr[i] == val;
      }
    }
    return false;
}

$(document).on("change", "#file", function() {
   var part_ext = this.value;
   var ext  = part_ext.split(".", 2);
   var attr = document.createAttribute("disabled");


   if ( isFileValid(ext[1]) === false ) {
        document.getElementById('msg_error').style.display = 'block';
        document.getElementById('btncad').setAttributeNode(attr);
   } else {
        document.getElementById('msg_error').style.display = 'none';
        document.getElementById('btncad').removeAttribute("disabled");
   }
});

function saveComment() {
  let comment = document.getElementById('comments').value;
  const usrid   = document.getElementById('userid').innerText;
  const postID  = document.getElementById('postID').value;

  let obj = { comment: comment, userid: usrid, postid: postID };

  var object_data_json = JSON.stringify(obj);

  var ajax = new XMLHttpRequest();
  ajax.open('POST','index.php?class=PostForm&method=addComent', true);
  ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  ajax.onreadystatechange = () => {
        if (ajax.readyState === 4) {
          if(ajax.status === 200) {
             console.log(ajax.responseText);
             document.getElementById('comments').value = '';
          } 
        }
  };
  ajax.send(object_data_json);

  
}

function handleClickOpenField(value) {
  const valor = $(value).attr('value');

  $('#button_'+valor).show("slow");
  $('#pergunta_'+valor).show("slow");

}

function handleClickButton(value) {
  const field = $(value).attr('value');
  $('#pergunta_'+field).val('');
  
  $('#button_'+field).hide("slow"); 
  $('#pergunta_'+field).hide("slow"); 

}


 var previewFile = function(event) {
 var input = event.target;
 var str   = input.value;
 var part  = str.split(".", 3);
 var capa  = document.getElementById('preview');
 var output = document.getElementById('output');
 var reader = new FileReader();
 reader.onload = function(){
   if (part[1] !== 'mp4') {
      var dataURL  = reader.result;
      output.src   = dataURL;
      capa.style.display  = 'none';
      // input.style.display = 'none';

   } 
 };
 reader.readAsDataURL(input.files[0]);};


// function saveField(value) {

//     const postID = $('#postID').val();
    
//     const questionID = $('#get').attr('name');
//     const id = $(value).attr('value');
//     const valores  = document.querySelectorAll('[data-id=perguntaid]');

//     for( var i = 0; i < valores.length; i++ ) {
//          console.log(valores[i]);
//     }

//     // alert(questionID);
//     // const field_question_id = $('#pergunta_'+value).attr('value');
//     const usuarioid   = document.getElementById('userid').innerText;
//     var data = { postID,  questionID, field_question_id,  usuarioid }
//     var object_data_json = JSON.stringify(data);
//     var ajax = new XMLHttpRequest();
//     ajax.open('POST', 'index.php?class=PostForm&method=addFieldComment', true);
//     ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//     ajax.onreadystatechange = () => {
//         if (ajax.readyState === 4) {
//           if (ajax.status === 200) {
//               console.log(ajax.responseText);   
//           }
//         }
//     };
   


