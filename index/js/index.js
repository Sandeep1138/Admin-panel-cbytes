firebase.auth().onAuthStateChanged(function(user) {
  if (user) {
    // User is signed in.

 document.getElementById("user_div").style.display = "block";
 
 document.getElementById("Login_div").style.display = "none";


  var user = firebase.auth().currentUser;

  if(user != null){

   var email_id = user.email;

   document.getElementById("user_para").innerHTML = "welcome user : " + email;


  }



  } else {
    // No user is signed in.
  }
});


 document.getElementById("user_div").style.display = "none";
 
 document.getElementById("Login_div").style.display = "block";





function login[]{

    var userEmail = document.getElementById("email_field").value;
    var userPass = document.getElementById("pass_field").value;

    firebase.auth().signInWithEmailAndPassword(userEmail, userPass).catch(function(error) {
        // Handle Errors here.
        var errorCode = error.code;
        var errorMessage = error.message;

        window.alert("Error :"+ errorMessage);
        // ...
      });

    }

    function logout(){
      firebase.auth().signOut();


    }