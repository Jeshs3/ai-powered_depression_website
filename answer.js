let user_id = null;
let userLoaded = false;

function loadUserData() {
    if (!userLoaded) {
        $("#loading-spinner").show();
        $.get('/BANOL6/Admin/get_user.php', function (data) {
            userId = data;
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
    const answers = window.scores.join(" - ");

    console.log("[DEBUG] Submitting answers:", {
        user_id,
        answers,
        score,
        status,
        date: new Date().toISOString().split('T')[0]
    });

    $("#loading-spinner").show();

    $.ajax({
        url: "/BANOL6/Admin/submit.php",
        type: "POST",
        data: {
            date: new Date().toISOString().split('T')[0],
            answers: answers,
            score: score,
            status: "pending-analysis"
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
};
