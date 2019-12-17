var app_fireBase = {};
(function(){
  // Your web app's Firebase configuration
  var firebaseConfig = {
    apiKey: "AIzaSyBYjrPplBw4oYZj6JuX2txbns0epliVHUQ",
    authDomain: "cbytes-2ba41.firebaseapp.com",
    databaseURL: "https://cbytes-2ba41.firebaseio.com",
    projectId: "cbytes-2ba41",
    storageBucket: "cbytes-2ba41.appspot.com",
    messagingSenderId: "28516490740",
    appId: "1:28516490740:web:3bac65669b26a3a4adada1",
    measurementId: "G-L7080NF5VM"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();

app_fireBase = firebase;


})()