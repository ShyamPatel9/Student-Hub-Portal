let btn = document.querySelector(".loginbutton");

if(btn)
{
    btn.addEventListener("click", function()
    {
        alert("Welcome to Login Page!!!");
    });
}


let btn1 = document.querySelector(".logbutton");

if(btn1)
{
    btn1.addEventListener("click", function()
    {
        alert("Logged in successfullyyy!!! \n\nWelcome to Dashboard..");
    });
}


/* Registration Validation */

let registrationForm = document.getElementById("registrationForm");

if(registrationForm)
{
    registrationForm.addEventListener("submit", function(event)
    {
        let password = document.getElementById("pass").value;
        let confirmPassword = document.getElementById("confirmPass").value;
        let mobile = document.getElementById("mobile").value;


        // Password Regex
        let passwordRegex =
            /^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[@$!%*?&]).{8,}$/;


        // Mobile Regex
        let mobileRegex =
            /^[6-9][0-9]{9}$/;


        let valid = true;


        // Password validation
        if(!passwordRegex.test(password))
        {
            document.getElementById("passError").textContent =
                "Password must be at least 8 characters with a letter, number and special character.";

            valid = false;
        }
        else
        {
            document.getElementById("passError").textContent = "";
        }


        // Confirm Password validation
        if(password !== confirmPassword)
        {
            document.getElementById("confirmPassError").textContent =
                "Passwords do not match.";

            valid = false;
        }
        else
        {
            document.getElementById("confirmPassError").textContent = "";
        }


        // Mobile validation
        if(!mobileRegex.test(mobile))
        {
            document.getElementById("mobileError").textContent =
                "Enter a valid 10 digit mobile number.";

            valid = false;
        }
        else
        {
            document.getElementById("mobileError").textContent = "";
        }


        // If validation fails
        if(!valid)
        {
            event.preventDefault();
        }


        // If validation is successful
        else
        {
            alert("Registration Done successfullyyy!!!");
        }

    });
}
