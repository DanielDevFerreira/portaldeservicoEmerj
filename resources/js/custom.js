function upload_file(e) {
    e.preventDefault();
    ajax_file_upload(e.dataTransfer.files);
}

function file_explorer() {
    document.getElementById('selectfile').click();
    document.getElementById('selectfile').onchange = function() {
        files = document.getElementById('selectfile').files;
        ajax_file_upload(files);
    };
}


function file_remove(val_id) {
  console.log(val_id);
  $.ajax({
        url: 'removeuploadajax',
        type: 'POST',
        data: {id_arquivo: +val_id},
        success: function (j) {
          console.log('msn='+j);
          $('#upload_arquivo_'+val_id+'').remove();
        },
        error: function () {
            alert("Error " + xhttp.status + " ocorreu um erro durante a exclusão do arquivo.");
        }
    });
}

function ajax_file_upload(files_obj) {
    if(files_obj != undefined) {
        var form_data = new FormData();
        for(i=0; i<files_obj.length; i++) {
            form_data.append('arquivo[]', files_obj[i]);
        }
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "uploadajax", true);
        xhttp.onload = function(event) {
            if (xhttp.status == 200) {
              var div = document.getElementById('arquivos_de_envio');
              div.innerHTML += this.responseText;
            } else {
                alert("Error " + xhttp.status + " ocorreu um erro durante o opload do arquivo.");
            }
        }

        xhttp.send(form_data);
    }
}
