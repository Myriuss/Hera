
$(document).ready( () => {
 
    $(" .flexsignup  form  ").submit(e => {
        e.preventDefault();
 
        $.post(
            'server/login/index.php' , // Un script PHP que l'on va créer juste après
            {
                
               
                email : $("#email").val(),
                pass : $("#pass").val()
            },
 
            data => {
 
                if(data == 'Success'){
                    window.location.replace("home ");

                }
                else{

                    $(".errorMessage").html('   ')
                     $(".errorMessage").html("<p class= 'alert alert-danger '> "+ data +"   </p>");
                     
                    
                    
                }
         
            },
            'text'
         );
    });

});