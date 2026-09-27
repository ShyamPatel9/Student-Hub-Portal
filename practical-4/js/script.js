
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
        alert("Logged in successfullyyy!!! \n\nWelocme to Dashboard..");
    });
}

let btn2 = document.querySelector(".regbutton");
if(btn2)
{
    btn2.addEventListener("click", function() 
    {
        alert("Registration Done successfullyyy!!!");
    });
}

const themeButton = document.getElementById("themeButton");

if (themeButton)
{
    themeButton.addEventListener("click", function()
    {
        document.body.classList.toggle("dark");

        if (document.body.classList.contains("dark"))
        {
            themeButton.textContent = "Light Mode";
            localStorage.setItem("theme", "dark");
        }
        else
        {
            themeButton.textContent = "Dark Mode";
            localStorage.setItem("theme", "light");
        }
    });

    if (localStorage.getItem("theme") === "dark")
    {
        document.body.classList.add("dark");
        themeButton.textContent = "Light Mode";
    }
}


