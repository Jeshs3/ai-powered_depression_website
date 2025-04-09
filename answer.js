let userId = null;
let userLoaded = false;

// Show spinner while loading user
$("#loading-spinner").show();

$.get('../Admin/get_user.php', function (data) {
    userId = data;
    userLoaded = true;
    $("#loading-spinner").hide();
}).fail(function () {
    $("#loading-spinner").hide();
    alert("Failed to load user session. Please log in again.");
});

$("#next-btn").click(function () {
    if (window.currentQuestion < window.totalQuestions - 1) {
        window.currentQuestion++;
        window.loadQuestion();
    } else {
        if (!userLoaded) {
            $("#loading-spinner").show();
            $.get('../Admin/get_user.php', function (data) {
                userId = data;
                userLoaded = true;
                $("#loading-spinner").hide();
                $("#next-btn").click(); // Retry submission after loading
            }).fail(function () {
                $("#loading-spinner").hide();
                alert("Failed to fetch user ID. Please refresh the page.");
            });
            return;
        }

        if (window.scores.length !== window.totalQuestions) {
            alert("Please answer all questions before submitting.");
            return;
        }

        let totalScore = window.scores.reduce((a, b) => a + b, 0);
        const answersString = window.scores.join(" - ");

        // Optionally show spinner for submission
        $("#loading-spinner").show();

        $.ajax({
            url: "../Admin/submit.php",
            type: "POST",
            data: {
                userId: userId,
                date: new Date().toISOString().split('T')[0],
                answers: answersString,
                totalScore: totalScore,
                status: 1
            },
            success: function (response) {
                $("#loading-spinner").hide();
                alert('Test submitted successfully!');
            },
            error: function (xhr, status, error) {
                $("#loading-spinner").hide();
                console.error("Submission failed:", error);
                alert("There was an error submitting your test.");
            }
        });
    }
});
