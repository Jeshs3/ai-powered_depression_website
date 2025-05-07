let user_id = null;
let userLoaded = false;

function loadUserData() {
    if (!userLoaded) {
        $("#loading-spinner").show();
        $.get('/BANOL6/Admin/get_user.php', function (data) {
            user_id = data;  // Make sure it's user_id (variable consistency)
            userLoaded = true;
            $("#loading-spinner").hide();
        }).fail(function () {
            $("#loading-spinner").hide();
            alert("Failed to fetch user ID. Please refresh the page.");
        });
    }
}

window.submitAnswers = function () {
    // Load user data before submission if not already loaded
    loadUserData();

    if (!userLoaded) {
        return; // Prevent submission if user is not loaded yet
    }

    if (window.scores.length !== window.totalQuestions) {
        alert("Please answer all questions before submitting.");
        return;
    }

    let score = window.scores.reduce((a, b) => a + b, 0);
    //const answers = window.scores.join(" - ");
    const mappedAnswer = window.scores.map((index, qIndex) => {
        const emotionObj = window.questions[qIndex]?.emotions?.[index];
        return emotionObj || { label: "❓ Unknown", value: "unknown" };
    });

    const emotionValues = mappedAnswer.map(obj => obj.value);

    console.log("[DEBUG] Submitting answers:", {
        user_id,
        emotionValues,
        mappedAnswer,
        status,
        date: new Date().toISOString().split('T')[0]
    });

    $("#loading-spinner").show();


    $.ajax({
        url: "/BANOL6/backend/predict_depression.php",  // Backend endpoint for prediction
        type: "POST",
        data: {
            user_id: user_id,
            answers: emotionValues,
        },
        success: function (response) {
            const prediction = response.prediction;
            const probability = response.probability;  // Probability of the depression prediction

            console.log("[DEBUG] Prediction response:", response);

            let status = (prediction === 1) ? "high" : "low"; 
            
            const chosenLabels = mappedAnswer.map(ans => ans.label).join(", ");
            alert(
                "✅ Submission Summary:\n\n" +
                "🧠 Emotions Selected:\n" + chosenLabels + "\n\n" +
                "📊 Prediction: " + status.toUpperCase() + "\n" +
                "🔢 Probability: " + (probability * 100).toFixed(2) + "%"
            );
            
            $.ajax({
                url: "/BANOL6/Admin/submit.php",
                type: "POST",
                data: {
                    date: new Date().toISOString().split('T')[0],
                    answers: mappedAnswer,
                    score: score,
                    status: status,
                    probability: probability,  // Include the probability value
                },
                success: function (response) {
                    $("#loading-spinner").hide();
                    alert('Test submitted successfully!');
                },
                error: function (xhr, status, error) {
                    $("#loading-spinner").hide();
                    console.log(xhr.responseText);
                    alert("There was an error submitting your test.");
                }
            });
        },
        error: function (xhr, status, error) {
            $("#loading-spinner").hide();
            console.log(xhr.responseText);
            alert("Error predicting depression.");
        }
    });
};
