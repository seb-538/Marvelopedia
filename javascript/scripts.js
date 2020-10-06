
$(document).ready(function(){

$("#upload").click(function() { //a redirection to file input, because i don't find nice solution to change the original style
  $("#secret_upload").click();
  });

$("#secret_upload").change(function() {
  $("#upload").val($("#secret_upload").val().split("\\").pop());
})

$("#upload").keydown(function() {
  return false;
})

$("#form").submit(function(e){
    e.preventDefault();
       var name = $('#name').val();
       var file_data = $('#secret_upload').prop('files')[0];   
       var form_data = new FormData();                  
       form_data.append('file', file_data);                           
       $.ajax({                 //sending data to upload
           url: 'upload.php', 
           dataType: 'text',  
           cache: false,
           contentType: false,
           processData: false,
           data: form_data,                         
           type: 'post',
           success: function(msg){
             if (msg.includes("upload OK")){
               var path = msg.split("|").pop();
              $.post("insert.php", {name: name, image_url: path},  // sending data to insert in database
              function(msg){
                if (msg == "OK")
                {
                  $('#form_response').text(name + " à bien été ajouté.");
                  $('#form_response').css({'color':'green'});
                  $("<div class=item><img class=zoom src='"+path+"' alt='"+name+"'><p>"+name+"</p></div>").insertBefore( ".modal" );
                  info();
                }
                else
                {
                  $('#form_response').text(name + " existe déja.");
                  $('#form_response').css({'color':'red'});
                  $.post("delete_file.php", {path: path});              
                }
              }); 
           }
           else
              $('#form_response').text(msg);
            $("#name").val('');
            $("#upload").val('');
            $("#secret_upload").val('');
          }
        });
})

$("#submit").click(function(){      //input verification
    
    var file = $("#secret_upload").val().split("\\").pop();
    var extensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i; 
    if (file && !extensions.exec(file))
    { 
      alert(file+" : Format de fichier non valide");
      return false;     
    }
});

function info(){                     //function to make a modal window to display character details
$(".zoom").toArray().forEach(function(item) {
   item.addEventListener("click", function(e) {
    $(".modal").css("display", "block");
    $(".modalImage").attr("src", e.target.src);
    $(".modalName").text(e.target.alt);
    $.post("get_details.php", {name: e.target.alt.replace(/ /g, "%20")},
     function(msg){
      $(".modalDescription").text(msg);
    })
    $(window).scrollTop(0); 
   });
});
}

info();

$(".close, .modal").click(function(){       //action when modal window is closed
  $(".modal").css("display", "none");
  $(".modalDescription").text("");
  $(".modalImage").attr("src", "");
});

});


