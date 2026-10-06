fetch("student.json")
    .then(response => response.json())
    .then(students => {
        let output = "";

        students.forEach(student => {
            output += `
                <div class="label">
                    <h3>${student.name}</h3>
                    <p>Roll No: ${student.rollNo}</p>
                    <p>Course: ${student.course}</p>
                    <p>Semester: ${student.semester}</p>
                    <p>Marks: ${student.marks}</p>
                    <hr>
                </div>
            `;
        });

        document.getElementById("studentData").innerHTML = output;
    })
    .catch(error => {
        console.error("Error loading student data:", error);
    });