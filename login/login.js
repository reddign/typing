function loginForm(event) {
    event.preventDefault();

    let loginform = document.getElementById("login_form");
    let user = loginform.elements["username"].value;
    let password = loginform.elements["password"].value;

    if(user=="" ||  password==""){
        alert(`You forgot to type a username or password`);
    }else{
        loginform.submit();
    }
}