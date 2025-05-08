let user_id = null;
let userLoaded = false;
function loadUserData() {
    return new Promise((resolve, reject) => {
        if (userLoaded) {
            resolve(user_id);
        } else {
            $("#loading-spinner").show();
            $.get('/BANOL6/Admin/get_user.php', function (data) {
                user_id = data;
                userLoaded = true;
                $("#loading-spinner").hide();
                resolve(user_id);
            }).fail(function () {
                $("#loading-spinner").hide();
                alert("Failed to fetch user ID. Please refresh the page.");
                reject("User fetch failed");
            });
        }
    });
}


window.submitAnswers = async function () {
    try {
        await loadUserData(); // wait until user_id is loaded

        if (window.scores.length !== window.totalQuestions) {
            alert("Please answer all questions before submitting.");
            return;
        }

        let score = window.scores.reduce((a, b) => a + b, 0);
        const mappedAnswer = window.scores.map((index, qIndex) => {
            const emotionObj = window.questions[qIndex]?.emotions?.[index];
            return emotionObj || { label: "❓ Unknown", value: "unknown" };
        });

        const emotionValues = mappedAnswer.map(obj => obj.value);

        $("#loading-spinner").show();

        const response = await $.ajax({
            url: "/BANOL6/backend/predict_depression.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                user_id: user_id,
                answers: emotionValues
            })
        });

        const prediction = response.status;
        const probability = response.probability;
        let status = prediction === 1 ? "high" : "low";

        // Submit to DB
        await $.ajax({
            url: "/BANOL6/Admin/submit.php",
            type: "POST",
            data: {
                date: new Date().toISOString().split('T')[0],
                answers: mappedAnswer,
                score: score,
                status: status,
                probability: probability,
            }
        });

        $("#loading-spinner").hide();
        document.getElementById("popupStatus").textContent = "Status: " + (status === "high" ? "Depressed" : "Not Depressed");
        document.getElementById("popupProbability").textContent = "Probability Score: " + probability;
        if (status === "high" && probability >= 0.7) {
            document.getElementById("popupAdvice").textContent = "Please seek help from a mental health professional.";
        } else {
            document.getElementById("popupAdvice").textContent = "";
        }
        document.getElementById("resultPopup").style.display = "block";

    } catch (error) {
        $("#loading-spinner").hide();
        console.error("Submission error details:", error.responseText || error);
        alert("Error occurred during submission.");
    }
}

