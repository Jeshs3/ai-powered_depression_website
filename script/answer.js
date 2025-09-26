let user_id = null;
let userLoaded = false;

function showLoading() {
    $("#loading-overlay").show();
}

function hideLoading() {
    $("#loading-overlay").hide();
}

function loadUserData() {
    return new Promise((resolve, reject) => {
        if (userLoaded) {
            resolve(user_id);
            return;
        }

        // Show loading spinner
        $("#loading-spinner").show();

        $.get('/BANOL6/database/action.php?action=check_session')
            .done(function(data, textStatus, jqXHR) {
                user_id = data;
                userLoaded = true;
                $("#loading-spinner").hide();
                resolve(user_id);
            })
            .fail(function(jqXHR) {
                $("#loading-spinner").hide();

                if (jqXHR.status === 401) {
                    // User not logged in
                    alert("You are not logged in. Please login to continue.");
                } else {
                    // Other errors
                    alert("Failed to fetch user session. Please refresh the page.");
                }

                reject(jqXHR.statusText);
            });
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

        showLoading();

        //FastAPI request
        const response = await fetch("http://127.0.0.1:8000/prediction", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ answers: emotionValues })
        });

        if (!response.ok) {
            throw new Error("FastAPI request failed: " + (await response.text()));
        }

        const result = await response.json();
        const prediction = result.status;
        const probability = result.probability;
        let status = prediction === 1 ? "high" : "low";

        // Submit results to your DB via PHP
        await $.ajax({
            url: "/BANOL6/database/submit.php",
            type: "POST",
            data: {
                date: new Date().toISOString().split('T')[0],
                answers: mappedAnswer,
                score: score,
                status: status,
                probability: probability,
                user_id: user_id
            }
        });

        hideLoading();
        let advice = "";
        if (status === "high" && probability >= 0.7) {
        advice = "⚠️ Please seek help from a mental health professional.";
        }

        Swal.fire({
        title: "Prediction Result",
        html: `
            <p><b>Status:</b> ${status === "high" ? "Depressed" : "Not Depressed"}</p>
            <p><b>Probability Score:</b> ${probability}</p>
            ${advice ? `<p style="color:red;">${advice}</p>` : ""}
        `,
        icon: status === "high" ? "warning" : "info",
        confirmButtonText: "OK"
        });


    } catch (error) {
        hideLoading();
        console.error("Submission error details:", error);
        Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            text: 'Error occurred during submission. Please try again.',
            confirmButtonColor: '#d33'
        });
    }
}
